# Subscriber Manager

This repo is technical test material for backend developer position in MailerLite


## Installation

* Download or clone this repository
* Make sure your machine meets Laravel requirements as described [here](https://laravel.com/docs/5.6/installation#server-requirements)
* CD to the root folder of this application
* run `composer install`
* run `php artisan key:generate`
* configure your database access in `.env` file
* run `php artisan migrate`
* run `php artisan db:seed`
* run `php artisan serve`
* and access the application at `http://127.0.0.1:8000`


## Using the Application

* After you open the application, click on Login link in navbar
* Login using email : `john.doe@gmail.com` and password : `secret`
* You can now start managing Fields and Subscriber in the dashboard.


## Run Unit Test

* CD to the root folder of this application
* run `./vendor/bin/phpunit`


## API usage

### API Authentication
API call (except login) requires Basic Authorization. The Authorization token can be received by call login API first. From login API you will get `api_token` response that will be used as Authorization header.

Example Authorization Header: `Authorization: Bearer randomstringtoken`

### Notes on Using Postman
Make sure to specify header `X-Requested-With` with value `XMLHttpRequest` to force Laravel return json instead of redirect when running validation.

### login
URL : `/api/auth/login`

Type : **POST**

Params : 

Name | Type | Required
---- | ---- | --------
email | String | yes
password | String | yes

Response :
```
{
    email: 'john.doe@gmail.com',
    api_token: 'randomstringtoken'
}
```


### List Fields
URL: `api/field/list`

Type: **GET**

Response:
```
{
    fields: [
        {
            id: 4, // int
            user_id: 1 // int or null
            title: 'Name',
            type: 'String', // String, Date, Boolean, Number
            name: 'name',
            placeholder: '{name}'
        },
        etc...
    ]
}
```


### Create Field
URL : `/api/field/create`

Type : **POST**

Params : 

Name | Type | Required
---- | ---- | --------
title | String | yes
type | String (options: string, data, number, boolean) | yes

Response :
```
{
    field: // identical to single field in field list API
}
```


### Update Field
URL : `/api/field/update/{field_id}`

Type : **PUT**

Params : 

Name | Type | Required
---- | ---- | --------
title | String | yes

Response :
```
{
    field: // identical to single field in field list API
}
```


### Remove Field
URL : `/api/field/remove/{field_id}`

Type : **DELETE**

Response :
```
{
    status: true
}
```
Notes: Only field with user_id that can be removed


### List Subscriber

URL: `api/subscriber/list`

Type: **GET**

Response:
```
{
    subscribers: [
        {
            id: 4, // int
            email: 'lucas@gmail.com',
            name: 'Lucas',
            state: 'active' // active, unsubscribed, junk, bounced, unconfirmed
        },
        etc...
    ]
}
```


### Get Subscriber
URL: `api/subscriber/show/{subscriber_id}`

Type: **GET**

Response:
```
{
    subscriber: ,// identical to single subscriber list
    fields: [
        {
            id: 12,
            field_id: 4, // id or user field
            title: 'Birth Day', // field title
            name: 'birth_day', // field name
            type: 'String', // field type
            value: '1988-09-02', // the actual value
        },
        etc...
    ]
}
```


### Create Subscriber
URL : `/api/subscriber/create`

Type : **POST**

Params : 

Name | Type | Required
---- | ---- | --------
email | String | yes
name | String | yes
other fields name | String | No

Response :
```
{
    field: // identical to single subscriber in subscriber list API
}
```


### Update Subscriber
URL : `/api/subscriber/update/{subscriber_id}`

Type : **PUT**

Params : 

Name | Type | Required
---- | ---- | --------
email | String | yes
name | String | yes
other fields name | String | No

Response :
```
{
    field: // identical to single subscriber in subscriber list API
}
```


### Remove Subscriber
URL : `/api/subscriber/remove/{subscriber_id}`

Type : **DELETE**

Response :
```
{
    status: true
}
```


## About Verifying Email Address

Unfortunately I can't make email verifier work. I failed when I try to connect to the email server. Even I tried from 2 different ubuntu server, but it always timed out. Still not sure how to configure it properly.

So, the email verifier will just check if the host has MX records at this moment. 

