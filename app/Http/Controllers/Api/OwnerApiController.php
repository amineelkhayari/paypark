<?php

namespace App\Http\Controllers\Api;

use App\AdminSetting;
use App\Facilities;
use App\Http\Controllers\AppHelper;
use App\Http\Controllers\Controller;
use App\Notifications\SendPassword;
use App\ParkingBooking;
use App\ParkingGuard;
use App\ParkingImage;
use App\ParkingOwner;
use App\ParkingOwnerSetting;
use App\Review;
use App\SpaceSlot;
use App\SpaceZone;
use App\Subscription;
use App\SubscriptionBuy;
use App\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Stripe\StripeClient;

class OwnerApiController extends Controller
{
    public function storeSpaceZone(Request $request)
    {
        # code...
        $zoneData = SpaceZone::create([
            'space_id' => $request->space_id,
            'owner_id' => Auth::user()->id,
            'name' => $request->name
        ]);
        $slot = [];
        for ($i = 0; $i < (int) $request->size; $i++) {
            # code...
            $slot[] = [
                'zone_id' => $zoneData->id,
                'space_id' => $request->space_id,
                'name' => $zoneData['name'] . ' ' . $i,
                'position' => $i,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        SpaceSlot::insert($slot);
        return response()->json(['msg' => 'Boom', 'data' =>  null, 'success' => true], 200);
    }
    public function updateSpaceZone(Request $request, $id)
    {
        $request->validate([
            'name' => 'bail|required',
        ]);
        SpaceZone::findOrFail($id)->update($request->all());
        return response()->json(['msg' => 'Updated', 'data' =>  null, 'success' => true], 200);
    }
    public function deleteSpaceZone($id)
    {
        $data = SpaceSlot::where('zone_id', $id)->get();
        foreach ($data as $key => $value) {
            try {
                //code...
                SpaceSlot::find($value->id)->forceDelete();
            } catch (\Throwable $th) {
                $value->zone_id = null;
                $value->update();
                $value->delete();
                //throw $th;
            }
        }
        SpaceZone::findOrFail($id)->delete();
        return response()->json(['msg' => 'Deleted', 'data' =>  null, 'success' => true], 200);
    }   
    public function parkingImages($id)
    {
        $data = ParkingImage::where('space_id', $id)->get();
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }
    public function storeParkingImage(Request $request)
    {
        if ($request->images) {
            foreach ($request->images as  $value) {

                $name = (new AppHelper)->saveBase64($value);
                ParkingImage::create([
                    'space_id' => $request->space_id,
                    'image' => $name
                ]);
            }
        }
        return response()->json(['msg' => 'Image Upload ', 'data' => null, 'success' => true], 200);
    }
    public function destroyParkingImage($id)
    {
        ParkingImage::find($id)->delete($id);
        return response()->json(['msg' => 'Image Deleted ', 'data' => null, 'success' => true], 200);
    }
    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name' => 'bail|required',
            'phone_no' => 'bail|required',
        ]);
        $data = $request->all();
        if ($request->has('image')) {
            $data['image'] = (new AppHelper)->saveBase64($request->image);
        }
        auth()->user()->update($data);
        return response()->json(['msg' => 'Profile Updated', 'data' => null, 'success' => true], 200);
    }
    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'password' => 'bail|required|confirmed|min:6',
        ]);
        auth()->user()->update($request->all());
        return response()->json(['msg' => 'Password is changed', 'data' => null, 'success' => true], 200);
    }
    public function settingUpdate(Request $request)
    {
        $paymentSetting = ParkingOwnerSetting::where('owner_id',auth()->user()->id)->first();
        $request->validate([
            'stripe_status' => 'bail|required',
            'razorpay_status' => 'bail|required',
            'cod' => 'bail|required',
            'paypal_status' => 'bail|required',
            'flutterwave_status' => 'bail|required',
            'stripe_secret' => 'bail|required_if:stripe_status,1',
            'stripe_public' => 'bail|required_if:stripe_status,1',
            'razorpay_key' => 'bail|required_if:razorpay_status,1',
            'paypal_client_key' => 'bail|required_if:paypal_status,1',
            'paypal_secret_key' => 'bail|required_if:paypal_status,1',
            'flutterwave_key' => 'bail|required_if:flutterwave_status,1',
        ]);
        $data = $request->all();
        $paymentSetting->update($data);
        return response()->json(['msg' => 'Setting Updated', 'data' => null, 'success' => true], 200);
    }
    public function availableGuard()
    {
        $available =  ParkingGuard::where('owner_id', Auth::user()->id)->Where('space_id', null)->get();
        return response()->json(['msg' => 'Guard  added ', 'data' => $available, 'success' => true], 200);
    }
    public function ownerShowScanner($order)
    {
        $data = ParkingBooking::with(['user', 'vehicle:id,model,vehicle_no'])->where('id', $order)->first();
        return response()->json(['msg' => 'Thanks', 'data' => $data, 'success' => true], 200);
    }
    public function spaceReview($id)
    {
        //
        $review =  Review::with(['user'])->where('space_id', $id)->get();
        return response()->json(['msg' => null, 'data' =>  $review, 'success' => true], 200);
    }
    public function allReview()
    {
        //
        $review =  Review::with(['user', 'space:id,title'])->get();
        return response()->json(['msg' => null, 'data' =>  $review, 'success' => true], 200);
    }
    public function zoneForSpace($id)
    {
        # code...
        $spaceZone =   SpaceZone::with('slots')->where('space_id', $id)->get();
        return response()->json(['msg' => null, 'data' => $spaceZone, 'success' => true], 200);
    }
    public function transactionAllInOne($id, $type)
    {
        $date = Carbon::today();
        if ($type === 'week') {
            $data = ParkingBooking::where('space_id', $id)
                ->whereBetween('arriving_time', [$date->startOfWeek()->format('Y-m-d'),  $date->endOfWeek()->format('Y-m-d')])->orderBy('user_id')
                ->get();
            $temp = array();
            foreach ($data as $value) {

                $userCount = ParkingBooking::with(['user'])->where([['space_id', $id], ['user_id', $value['user_id']]])
                    ->whereBetween('arriving_time', [$date->startOfWeek()->format('Y-m-d'),  $date->endOfWeek()->format('Y-m-d')])
                    ->get();
                array_push($temp, [
                    'user' => $userCount[0]['user'],
                    'user_id' => $userCount[0]['user_id'],
                    'total' => $userCount->sum('total_amount')
                ]);
            }
            $coll = collect($temp);
            $unique = $coll->unique('user_id');
            $tempData = array();
            $tempData['grandTotal'] = $unique->sum('total');
            $tempData['data'] = $unique;


            return response()->json(['msg' => null, 'data' => $tempData, 'success' => true], 200);
        } else if ($type === 'day') {
            $data = ParkingBooking::where('space_id', $id)
                ->whereDate('arriving_time', Carbon::today())->orderBy('user_id')
                ->get();
            $temp = array();
            foreach ($data as $value) {
                $userCount = ParkingBooking::with(['user'])->where([['space_id', $id], ['user_id', $value['user_id']]])
                    ->whereDate('arriving_time', Carbon::today())
                    ->get();
                array_push($temp, [
                    'user' => $userCount[0]['user'],
                    'user_id' => $userCount[0]['user_id'],
                    'total' => $userCount->sum('total_amount')
                ]);
            }
            $coll = collect($temp);
            $unique = $coll->unique('user_id');
            $tempData = array();
            $tempData['grandTotal'] = $unique->sum('total');
            $tempData['data'] = array_values($unique->toArray());
            return response()->json(['msg' => null, 'data' => $tempData, 'success' => true], 200);
        } else if ($type === 'month') {
            $data = ParkingBooking::where('space_id', $id)
                ->whereMonth('arriving_time', $date->month)->orderBy('user_id')
                ->get();
            $temp = array();
            foreach ($data as $value) {
                $userCount = ParkingBooking::with(['user'])->where([['space_id', $id], ['user_id', $value['user_id']]])
                    ->whereMonth('arriving_time', $date->month)->whereYear('arriving_time', $date->year)
                    ->get();
                array_push($temp, [
                    'user' => $userCount[0]['user'],
                    'user_id' => $userCount[0]['user_id'],
                    'total' => $userCount->sum('total_amount')
                ]);
            }
            $coll = collect($temp);
            $unique = $coll->unique('user_id');
            $tempData = array();
            $tempData['grandTotal'] = $unique->sum('total');
            $tempData['data'] = array_values($unique->toArray());
            return response()->json(['msg' => null, 'data' => $tempData, 'success' => true], 200);
        }
        ParkingBooking::where('space_id', $id);
    }
    public function transactionCustom(Request $request, $id)
    {
        $Sdate = Carbon::parse($request->start_date);
        $Edate = Carbon::parse($request->end_date);

        $data = ParkingBooking::where('space_id', $id)
            ->whereBetween('arriving_time', [$Sdate->format('Y-m-d'),  $Edate->format('Y-m-d')])->orderBy('user_id')
            ->get();
        $temp = array();
        foreach ($data as $value) {
            $userCount = ParkingBooking::with(['user'])->where([['space_id', $id], ['user_id', $value['user_id']]])
                ->whereBetween('arriving_time', [$Sdate->format('Y-m-d'),  $Edate->format('Y-m-d')])
                ->get();
            array_push($temp, [
                'user' => $userCount[0]['user'],
                'user_id' => $userCount[0]['user_id'],
                'total' => $userCount->sum('total_amount')
            ]);
        }
        $coll = collect($temp);
        $unique = $coll->unique('user_id');
        $tempData = array();
        $tempData['grandTotal'] = $unique->sum('total');
        $tempData['data'] = $unique;
        return response()->json(['msg' => null, 'data' => $tempData, 'success' => true], 200);
    }
    public function liveSlotLocation($id)
    {        
        $timezone = AdminSetting::first()->timezone;
        $start = Carbon::now($timezone)->format('Y-m-d H:i:s');
        $end = Carbon::now($timezone)->addMinute('60')->format('Y-m-d H:i:s');
        $ps = SpaceZone::with(['slots'])->where('space_id', $id)->get();
        foreach ($ps as  $value) {
            foreach ($value['slots'] as &$slot) {
                $booking = ParkingBooking::with('user:id,name,image','vehicle:id,model,vehicle_no')->where('slot_id', $slot['id'])->whereIn('status', [0, 1])->get();
                if (count($booking) > 0) {
                    foreach ($booking as &$b) 
                    {
                        $a_date = carbon::parse($b->arriving_time)->format('Y-m-d H:i:s');
                        $l_date = carbon::parse($b->leaving_time)->format('Y-m-d H:i:s');
                        $st = carbon::parse($start);
                        $et = Carbon::parse($end);
                     
                        if ($st->between($a_date, $l_date)) {            
                            $slot['available'] = true; 
                            $slot['booking'] = $b->makeHidden(['created_at','updated_at','discount','payment_type','payment_token','payment_status','status']);
                        } else {
                            $slot['available'] = false;
                        }
                    }
                } else {
                    $slot['available'] = false;
                }
            }
        }
        return response()->json(['msg' => null, 'data' => $ps, 'success' => true], 200);

    }
    public function updateSpaceSlot(Request $request, $id)
    {
        $request->validate([
            'name' => 'bail|required',

        ]);
        SpaceSlot::findOrFail($id)->update($request->all());
        return response()->json(['msg' => 'Updated', 'data' =>  null, 'success' => true], 200);
    }
    public function deleteSPaceSlot($id)
    {

        try {
            //code...
            SpaceSlot::findOrFail($id)->forceDelete();
        } catch (\Throwable $th) {
            SpaceSlot::findOrFail($id)->delete();
            //throw $th;
        }
        return response()->json(['msg' => 'Updated', 'data' =>  null, 'success' => true], 200);
    }
    public function updateParkingBooking(Request $request, $id)
    {
        $data = ParkingBooking::find($id);
        $data->payment_status = $request->payment_status;
        $data->status = $request->status;
        $data->save();
        $app = AdminSetting::get(['id', 'notification'])->first();
        $data = ParkingBooking::with(['user', 'vehicle:id,model,vehicle_no', 'space:id,title,address'])->where([['id', $id]])->get()->first();
        $temp = true;
        $header = '';
        $text = '';
        if ($request->status == '1') {
           
            $header = $data['space']['title'] . ' welcome`s you';
            $text = 'You are check in for ' . $data['space']['title'] . '   parking space';
        } else if ($request->status == '2') {
            $header = 'Parking with ' . $data['space']['title'] . ' complete';

            $text = 'Thanks for park with' . $data['space']['title'] . ' please visit again';
        } else if ($request->status == '2') {
            $header = 'Important notice by' . $data['space']['title'];

            $text = 'Your parking with' . $data['space']['title'] . ' is cancel please contact us for more info';
        } else if ($request->payment_status == '1') {
            $header = 'Payment received';

            $text = 'Payment complete for ' . $data['space']['title'] . ' parking space thanks for payment';
        } else {
            $temp = false;
        }
        try {
            //code...
            
            $userId = $data['user']['device_token'];
            if (isset($userId) && $temp === true  && $app->notification == 1) {
                OneSignal::sendNotificationToUser(
                    $text,
                    $userId,
                    $url = null,
                    $data = null,
                    $buttons = null,
                    $schedule = null,
                    $headings = $header

                );
            }

        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()->json(['msg' => 'Thanks', 'data' => $data, 'success' => true], 200);
    }
    public function profilePictureUpdate(Request $request)
    {
        $name = (new AppHelper)->saveBase64($request->image);
        auth()->user()->update([
            'image' => $name,
        ]);
        return response()->json(['msg' => 'Profile Updated', 'data' => null, 'success' => true], 200);
    }
    public function guardSpace(Request $request)
    {
        $request->validate([
            'guard_id' => 'required',
            'space_id' => 'required',
        ]);
        $data = $request->all();
        $sguard=ParkingGuard::find($request->guard_id);
        $sguard->update($data);
        return response()->json(['success' => true, 'message' => 'Update Successfully!']);
    }
    public function ownerSetting()
    {
        $ownerSetting= ParkingOwnerSetting::where('owner_id',auth()->user()->id)->first();
        return response()->json(['data' => $ownerSetting, 'success' => true]);
    }
  
    public function storeParkingOwner(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email|unique:parking_owner,email',
            'name' => 'bail|required',
            'password' => 'bail|required|min:6',
        ]);
        $data = $request->all();
        $data['subscription_status'] = 1;
        $data = ParkingOwner::create($data);
        $subscription = Subscription::where('subscription_name','Free')->first();
        $admin_setting = AdminSetting::first();
        SubscriptionBuy::create([
            'owner_id' => $data->id,
            'subscription_id' => $subscription['id'],
            'price' => 0,
            'status' => 1,
            'payment_status' => 1,
            'duration' => 1,
            'start_at' => Carbon::now()->format('Y-m-d'),
            'end_at' => Carbon::now(env('timezone'))->addDay($admin_setting->trial_days)->format('Y-m-d'),
        ]);
        ParkingOwnerSetting::create([
            'owner_id' => $data->id,
        ]);
        $token = $data->createToken('PayParkOwner')->accessToken;
        $data['token'] = $token;
        return response()->json(['msg' => 'Login Successfully.', 'data' => $data, 'success' => true], 200);
    }
    public function login(Request $request)
    {
        //
        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
        ]);
        if ($request->type === 'owner') {

            $user = ParkingOwner::where('email', $request->email)->first();
        } else {

            $user = ParkingGuard::where('email', $request->email)->first();
        }
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                if ($request->type === 'owner' && $user['verified'] != 1) {
                    return response()->json(['msg' => 'Please Verify your email', 'data' => null, 'success' => false], 200);
                }
                $subscription = SubscriptionBuy::where([['owner_id',$user->id],['status',1]])->first();
                $max_space_limit = Subscription::where('id',$subscription->subscription_id)->first()->max_space_limit;
                if ($subscription)
                {
                    $cDate = Carbon::now()->format('Y-m-d');
                    if($subscription->end_at > $cDate)
                    {
                        // subscription active
                        $user->update(['subscription_status' => 1]);
                    }
                    else
                    {
                        // subscription expire
                        $user->update(['subscription_status' => 0]);
                    }
                }
                else
                {
                    $user->update(['subscription_status' => 0]);
                }
                $token = $user->createToken('PayParkOwner')->accessToken;
                $user['device_token'] = $request->device_token;
                $user->save();
                $user['token'] = $token;
                $user['plan_expire_on'] = $subscription->end_at;
                $user['max_space_limit'] = $max_space_limit;
                return response()->json(['msg' => 'lOGIN ', 'data' => $user, 'success' => true], 200);
            } else {
                return response()->json(['message' => 'Password does not match!'], 401);
            }
        } else {
            return response()->json(['message' => 'User doesn\'t exist!'], 401);
        }
    }
    public function forgotPassword(Request $request)
    {
        $password = rand(100000, 999999);
        $setting = AdminSetting::first();
        $gard =  ParkingOwner::where('email', $request->email)->first();
        if ($gard) {

            $gard->password = $password;
            $gard->save();
      
            $config = array(
                'driver'     => $setting->mail_driver,
                'host'       => $setting->mail_host,
                'port'       => $setting->mail_port,
                'from'       => array('address' => $setting->mail_from_address, 'name' => $setting->mail_from_name),
                'encryption' => $setting->mail_encryption,
                'username'   => $setting->mail_username,
                'password'   => $setting->mail_password
            );
            Config::set('mail', $config);
            $gard->notify(new SendPassword($password));
            return response()->json(['msg' => 'Password send to your email address', 'data' => null, 'success' => true], 200);
        } 
        return response()->json(['msg' => 'No User Found ', 'data' => null, 'success' => false], 200);

    }
    public function displayFacilities()
    {
        return response()->json(['msg' => null, 'data' => Facilities::all(), 'success' => true], 200);
    }
    public function displayVehicleType()
    {
        //
        return response()->json(['msg' => null, 'data' => VehicleType::where('status', 1)->get(), 'success' => true], 200);

    }
    public function allSetting()
    {
        $data = AdminSetting::first()->makeHidden(['paypal_sandbox','paypal_production']);
        $data['country_code'] = $data['country_code'];
        $data['verification'] = $data['verification'];
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }
    public function ownerShow($id)
    {
        # code...
        $data = ParkingBooking::with(['user', 'vehicle:id,model,vehicle_no'])->where([['id', $id]])->first();
        return response()->json(['msg' => 'Thanks', 'data' => $data, 'success' => true], 200);
    }
    public function getSubscription()
    {
        $subscriptions = Subscription::where('status',1)->get();
        $purchaseSubscription = SubscriptionBuy::where([['owner_id',auth()->user()->id],['status',1]])->first();
        foreach ($subscriptions as $subscription) {
            $subscription['plan'] = json_decode($subscription['plan']);
            if($subscription->id == $purchaseSubscription->subscription_id){
                $subscription->isPurchase = 1;
            }
            else{
                $subscription['trial_days'] = AdminSetting::first()->trial_days;
                $subscription->isPurchase = 0;
            }
        }
        return response(['success' => true,'data' => $subscriptions]);
    }
    public function purchaseSubscription(Request $request)
    {
        $request->validate([
            'amount' => 'bail|required',
            'payment_token' => 'bail|required',
            'payment_type' => 'bail|required',
            'duration' => 'bail|required',
            'subscription_id' => 'bail|required',
        ]);
        $data = $request->all();
        $data['payment_type'] = strtoupper($data['payment_type']);
        $currency = AdminSetting::find(1)->currency;
        try 
        {
            if($request->payment_type == 'STRIPE')
            {
                $amount = $data['amount'] * 100;
                $stripe_sk = AdminSetting::first()->stripe_secret;
                $stripe = new StripeClient($stripe_sk);
                $charge = $stripe->charges->create([
                    "amount" => $amount,
                    "currency" => $currency,
                    "source" => $request->payment_token,
                ]);
                $data['payment_token'] = $charge->id;
            }
            auth()->user()->update(['subscription_status' => 1]);
            SubscriptionBuy::where('owner_id',auth()->user()->id)->get()->each->update(['status' => 0]);
            SubscriptionBuy::create([
                'owner_id' => auth()->user()->id,
                'subscription_id' => $data['subscription_id'],
                'price' => $data['amount'],
                'payment_status' => 1,
                'status' => 1,
                'payment_token' => $data['payment_token'],
                'duration' => $data['duration'],
                'start_at' => Carbon::now()->format('Y-m-d'),
                'end_at' => Carbon::now(env('timezone'))->addMonths($data['duration'])->format('Y-m-d'),
                'payment_type' => $data['payment_type'],
            ]);
        } catch (\Throwable $th) {
            return response(['success' => false,'message' => 'Something went wrong.']);
        }
        return response(['success' => true,'message' => 'Subscription purchased.']);
    }
    public function subscriptionHistory()
    {
        $subscriptions = SubscriptionBuy::where('owner_id',auth()->user()->id)->with('subscription:id,subscription_name')->get();
        return response(['success' => true,'data' => $subscriptions]);
    }

    public function showParkingSpace($id)
    {
        $data['space'] = ParkingSpace::with(['guards', 'zones.slots'])->findOrFail($id)->setAppends(['facilitiesData', 'vehicleTypeData']);
        $data['booking'] = ParkingBooking::with(['user', 'vehicle:id,model,vehicle_no'])->where([['space_id', $id]])->get();//whereDate('arriving_time', Carbon::today())->get();
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }
}
