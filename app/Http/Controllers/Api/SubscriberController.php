<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use App\Subscriber;
use App\UserField;
use App\SubscriberFieldValue;
use App\Transformers\SubscriberTransformer;
use App\Transformers\SubscriberFieldTransformer;
use App\Http\Requests\CreateSubscriberRequest;
use App\Http\Requests\ShowSubscriberRequest;
use App\Http\Requests\UpdateSubscriberRequest;

class SubscriberController extends Controller
{
    public function list(Request $request)
    {
        $user = Auth::guard('api')->user();

        $subscribers = Subscriber::where('user_id', $user->id)
                    ->get();

        $subscribers = fractal($subscribers, new SubscriberTransformer())->toArray();

        return response()->json(['subscribers' => $subscribers['data']]);
    }

    public function show(ShowSubscriberRequest $request, $subscriberId)
    {
        $subscriber = Subscriber::find($subscriberId);

        $subscriberFields = SubscriberFieldValue::with('field')
                                ->where('subscriber_id', $subscriberId)
                                ->get();

        $subscriber = fractal($subscriber, new SubscriberTransformer())->toArray();
        $subscriberFields = fractal($subscriberFields, new SubscriberFieldTransformer())->toArray();

        return response()->json([
            'subscriber'    => $subscriber['data'], 
            'fields'        => $subscriberFields['data']
        ]);
    }

    public function create(CreateSubscriberRequest $request)
    {
        $user = Auth::guard('api')->user();
        $validated = $request->validated();

        DB::beginTransaction();

        // save subscriber
        $subscriber = new Subscriber;
        $subscriber->user_id = $user->id;
        $subscriber->email = $validated['email'];
        $subscriber->name = $validated['name'];
        $subscriber->state = Subscriber::STATE_ACTIVE;
        $subscriber->save();

        // save fields
        $userFields = UserField::whereNull('user_id')
                        ->orWhere('user_id', $user->id)
                        ->orderBy('user_id')
                        ->get();

        foreach ($userFields as $userField) {
            $fieldValue = new SubscriberFieldValue;
            $fieldValue->subscriber_id = $subscriber->id;
            $fieldValue->user_field_id = $userField->id;
            $fieldValue->value = isset($validated[$userField->name]) ? 
                $validated[$userField->name] : '';
            $fieldValue->save();
        }

        DB::commit();

        $subscriber = fractal($subscriber, new SubscriberTransformer())->toArray();

        return response()->json(['subscriber' => $subscriber['data']]);
    }

    public function update(UpdateSubscriberRequest $request, $subscriberId)
    {
        $validated = $request->validated();
        $user = Auth::guard('api')->user();

        $subscriber = Subscriber::find($subscriberId);

        DB::beginTransaction();

        $subscriber->user_id = $user->id;
        $subscriber->email = $validated['email'];
        $subscriber->name = $validated['name'];
        $subscriber->save();

        $subscriberFields = SubscriberFieldValue::with('field')
                                ->where('subscriber_id', $subscriber->id)
                                ->get();

        foreach ($subscriberFields as $subscriberField) {
            if (isset($validated[$subscriberField->field->name])) {
                $subscriberField->value = $validated[$subscriberField->field->name];
                $subscriberField->save();
            }
        }

        DB::commit();

        $subscriber = fractal($subscriber, new SubscriberTransformer())->toArray();
        $subscriberFields = fractal($subscriberFields, new SubscriberFieldTransformer())->toArray();

        return response()->json([
            'subscriber'    => $subscriber['data'], 
            'fields'        => $subscriberFields['data']
        ]);
    }

    public function remove(ShowSubscriberRequest $request, $subscriberId)
    {
        DB::beginTransaction();

        // remove field value first
        SubscriberFieldValue::where('subscriber_id', $subscriberId)
            ->delete();

        // remove subscriber
        Subscriber::where('id', $subscriberId)
            ->delete();

        DB::commit();

        return response()->json(['status' => true]);
    }

    public function unsubscribe(ShowSubscriberRequest $request, $subscriberId)
    {
        $subscriber = Subscriber::find($subscriberId);

        $subscriber->state = Subscriber::STATE_UNSUBSCRIBED;
        $subscriber->save();

        $subscriber = fractal($subscriber, new SubscriberTransformer())->toArray();

        return response()->json(['subscriber' => $subscriber['data']]);
    }
}
