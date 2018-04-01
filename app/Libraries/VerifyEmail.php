<?php 

namespace App\Libraries;

/**
 * Based on http://www.webtrafficexchange.com/smtp-email-address-validation
 */
class VerifyEmail { 

    private $options = array(
            "port" => 25,
            "timeout" => 1,  // Connection timeout to remote mail server.
            "sender" => "hello@destiyadian.com",
            "short_response" => true,
            'mx_validation_only' => false
    );
     
    /**
     *  Override the options for those specified.
     */
    function __construct($options = null) {
        if (!empty($options)) {
            if (is_array($options)) {
                foreach ($options as $key => $value) {
                    $this->options[$key] = $value;
                }
            }
        }
    }
     
    /**
     *  Validate the email address via SMTP.
     *  If 'short_response' is true, the method will return true or false;
     *  Otherwise, the entire array of useful information will be provided.
     */
    public function validate($email) 
    {
        $result = array("valid" => false);
        $errors = array();
        $mxhosts = [];
         
        // Email address (format) validation
        if (empty($email)) {
            $errors = array("Email address is required.\n");
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors = array("Invalid email address.\n");
        } else {
            list($username, $hostname) = explode('@', $email);
            if (function_exists('getmxrr')) {
                if (getmxrr($hostname, $mxhosts, $mxweights)) {
                    $result['mx_records'] = array_combine($mxhosts, $mxweights);
                    asort($result['mx_records']);
                } else {
                    $errors = "No MX record found.";
                }
            }

            if ($this->options['mx_validation_only']) {
                if (count($mxhosts) > 0) {
                    $result['valid'] = true;
                }
            } else {

                foreach ($mxhosts as $host) {
                    $fp = @fsockopen($host, $this->options['port'], $errno, $errstr, 
                                           $this->options['timeout']);
                    if ($fp) {
                        $data = fgets($fp);
                        $code = substr($data, 0, 3);
                        if ($code == '220') {
                            $sender_domain = explode('@', $this->options['sender']);
                            fwrite($fp, "HELO {$sender_domain[0]}\r\n");
                            fread($fp, 4096);
                            fwrite($fp, "MAIL FROM: <{$this->options['sender']}>\r\n");
                            fgets($fp);
                            fwrite($fp, "RCPT TO:<{$email}>\r\n");
                            $data = fgets($fp);
                            $code = substr($data, 0, 3);
                            $result['response'] = array("code" => $code, "data" => $data);
                            fwrite($fp, "quit\r\n");
                            fclose($fp);
                            switch ($code) {
                                case "250":  // We're good, so exit out of foreach loop
                                case "421":  // Too many SMTP connections
                                case "450":
                                case "451":  // Graylisted
                                case "452":
                                    $result['valid'] = true;
                                    break 2;  // Assume 4xx return code is valid.
                                default:
                                    $errors[] = "({$host}) RCPT TO: {$code}: {$data}\n";
                            }
                        } else {
                            $errors[] = "MTA Error: (Stream: {$data})\n";
                        }
                    } else {
                        $errors[] = "{$errno}: $errstr\n";
                    }
                }
            }
     
        }

        if (!empty($errors)) {
            $result['errors'] = $errors;
        }

        return ($this->options['short_response']) ? $result['valid'] : $result;
    }
}