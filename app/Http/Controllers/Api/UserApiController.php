<?php

namespace App\Http\Controllers\Api;

use App\AdminSetting;
use App\AppUsers;
use App\Facilities;
use App\Http\Controllers\AppHelper;
use App\Http\Controllers\Controller;
use App\Notifications\SendPassword;
use App\NotificationTemplate;
use App\ParkingBooking;
use App\ParkingGuard;
use App\ParkingOwner;
use App\ParkingOwnerSetting;
use App\ParkingSpace;
use App\Review;
use App\SpaceZone;
use App\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Client;

class UserApiController extends Controller
{
    public function displayVehicleType()
    {
        //
        return response()->json(['msg' => null, 'data' => VehicleType::where('status', 1)->get(), 'success' => true], 200);
    }
    public function storeParkingBooking(Request $request)
    {
        $request->validate([
            'owner_id' => 'bail|required|exists:parking_owner,id',
            'space_id' => 'bail|required|exists:parking_space,id',
            'slot_id' => 'bail|required|exists:space_slot,id',
            'vehicle_id' => 'bail|required|exists:user_vehicle,id',
            'arriving_time' => 'bail|required|date',
            'leaving_time' => 'bail|required|after:arriving_time',
            'total_amount' => 'bail|required|min:1',
        ]);
        $reqData = $request->all();
        $reqData['arriving_time'] = Carbon::parse($request->arriving_time)->format('Y-m-d H:i:s');
        $reqData['leaving_time'] = Carbon::parse($request->leaving_time)->format('Y-m-d H:i:s');
        $reqData['user_id'] = Auth::user()->id;
        $reqData['order_no'] = uniqid();
        $data = ParkingBooking::create($reqData);
        if ($reqData['payment_type'] == 'stripe') {
            $pq = ParkingOwner::find($reqData['owner_id']);
            $reqData['payment_token'] = $this->paymentByStripe($pq->stripe_sk, $reqData['payment_token'], $reqData['total_amount']);
            $reqData['payment_status'] = 1;
        }

        $spaceData = ParkingSpace::find($reqData['space_id']);
        try {
            $app = AdminSetting::get(['id', 'notification','app_id','rest_api_key','user_auth_key','owner_app_id',
            'owner_rest_api_key','owner_auth_key','guard_app_id','guard_rest_api_key','guard_auth_key'])->first();
            $notification_template1 = NotificationTemplate::where('title','create appointment')->first();
            $msg_content = $notification_template1->msg_content;
            $mail_content = $notification_template1->mail_content;
            $detail1['User_Name'] = auth()->user()->name;
            $detail1['StartTime'] = $data->arriving_time;
            $detail1['EndTime'] = $data->leaving_time;
            $detail1['Payment_Method'] = $data->payment_type;
            $detail1['App_Name'] = AdminSetting::first()->name;
            $content = ["{User_Name}","{StartTime}","{EndTime}","{Payment_Method}","{App_Name}"];
            $mail = str_replace($content, $detail1, $mail_content);
            $message = str_replace($content, $detail1, $msg_content);
            $user = AppUsers::find($reqData['user_id']);
            $owner=  ParkingOwner::where('id',$request->owner_id)->first()->device_token;
            $userId = $user['device_token'];
           
            $ownerheader=  'New user booked  Your space';
            $ownermsg='Space is ' .$spaceData['title'];
            $guardheader=  'New user booked  Your space';
            $guardmsg='Space is ' .$spaceData['title'];
            $guard=ParkingGuard::where('owner_id',$request->owner_id)->where('space_id',$request->space_id)->first()->device_token;
            if (isset($userId) && $app->notification == 1) {
                try {
                    $content1 = array(
                        "en" => $message
                        );
                    
                    $fields1 = array(
                        'app_id' => $app->app_id,
                        'include_player_ids' => array($userId),
                        'data' => null,
                        'contents' => $content1
                    );
                    
                    $fields1 = json_encode($fields1);
    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            
                    $response = curl_exec($ch);
                    curl_close($ch);
                } catch (\Throwable $th) {
                }
            }
            if (isset($guard) && $app->notification == 1) {
                try {
                    $content1 = array(
                        "en" => $guardmsg
                        );
                    
                    $fields1 = array(
                        'app_id' => $app->guard_app_id,
                        'include_player_ids' => array($guard),
                        'data' => null,
                        'contents' => $content1
                    );
                    
                    $fields1 = json_encode($fields1);
    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            
                    $response = curl_exec($ch);
                    curl_close($ch);
                } catch (\Throwable $th) {
                }               
            }
            if (isset($owner) && $app->notification == 1) {
                try {
                    $content1 = array(
                        "en" => $ownermsg
                        );
                    
                    $fields1 = array(
                        'app_id' => $app->owner_app_id,
                        'include_player_ids' => array($owner),
                        'data' => null,
                        'contents' => $content1
                    );
                    
                    $fields1 = json_encode($fields1);
    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($ch, CURLOPT_HEADER, FALSE);
                    curl_setopt($ch, CURLOPT_POST, TRUE);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields1);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            
                    $response = curl_exec($ch);
                    curl_close($ch);
                } catch (\Throwable $th) {
                }       
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        $header = "Reminder from  " . $spaceData['title'];
        $text = 'Your parking with ' . $spaceData['title'] . ' is schedule at :- ' . $reqData['arriving_time'];
       
        $data['header'] = $header;
        $data['text'] = $text;
        return response()->json(['msg' => 'Thanks', 'data' => $data, 'success' => true], 200);
    }
    
    public function bookingCancel($id)
    {
        $data = ParkingBooking::find($id);
        $data->status = 3;
        $data->save();
        $data = ParkingBooking::with(['user', 'space:id,title,address'])->where([['id', $id]])->get()->first();
        $appuser = AppUsers::find($data->user_id)->name;
        $owner = ParkingOwner::find($data->owner_id)->device_token;
        try {
            $notification_template1 = NotificationTemplate::where('title', 'cancel appointment')->first();
            $msg_content = $notification_template1->msg_content;
            $detail1['spaceName'] = $data['space']['title'];
            $detail1['user']=$appuser ;
            $content = ["{spaceName}","{user}"];
            $message = str_replace($content, $detail1, $msg_content);
            $header =  'Sorry for inconvenience';
            $app = AdminSetting::get(['id', 'notification'])->first();
            $userId = $data['user']['device_token'];
            $app_id = AdminSetting::first()->owner_app_id;
            if ($owner && $app->notification == 1) {
                $content1 = array(
                    "en" => $message
                );
                $fields1 = array(
                    'app_id' => $app_id,
                    'include_player_ids' => array($owner),
                    'data' => null,
                    'contents' => $content1
                );
                $fields1 = json_encode($fields1);
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                $response = curl_exec($ch);
                curl_close($ch);
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return response()->json(['msg' => 'Booking Is Cancel', 'data' => null, 'success' => true], 200);
    }
    public function storeReview(Request $request)
    {
        //
        $reqData = $request->all();
        $reqData['user_id'] = Auth::user()->id;
        Review::create($reqData);
        return response()->json(['msg' => 'Thanks', 'data' =>  null, 'success' => true], 200);
    }
    public function showParkingBooking($id)
    {
        $data = ParkingBooking::with(['space:id,title,address,lat,lng'])->where('id', $id)->first();
        return response()->json(['msg' => 'Thanks', 'data' => $data, 'success' => true], 200);
    }
    public function displayParkingBooking()
    {
        $data = array();
        $dataCurrant = ParkingBooking::with(['space:id,title,address'])->where([['user_id', Auth::user()->id]])->whereIn('status', [0, 1])->get();
        $dataOld = ParkingBooking::with(['space:id,title,address'])->where([['user_id', Auth::user()->id]])->whereIn('status', [2, 3])->get();
        $data['currant'] = $dataCurrant;
        $data['old'] = $dataOld;
        return response()->json(['msg' => 'Thanks', 'data' => $data, 'success' => true], 200);
    }
    public function profileUpdate(Request $request)
    {
        $request->validate([

            'name' => 'bail|required',
            'phone_no' => 'bail|required',
        ]);
        auth()->user()->update($request->all());
        return response()->json(['msg' => 'Profile Updated', 'data' => null, 'success' => true], 200);
    }
    public function profilePictureUpdate(Request $request)
    {
        $name = (new AppHelper)->saveBase64($request->image);
        auth()->user()->update([
            'image' => $name,
        ]);
        return response()->json(['msg' => 'Profile Updated', 'data' => null, 'success' => true], 200);
    }
    public function deleteAccount()
    {
        $user = auth()->user();
        $booking = ParkingBooking::where('user_id',$user->id)->where('payment_status',0)->first();
        if(isset($booking) && $user->email == 'demouser@saasmonks.in')
        {
            return response()->json(['success' => false,'message' => 'Account Cant\'t Delete']);
        }
        else{
            $timezone = AdminSetting::first()->timezone;
            $user->name = 'Deleted User';
            $user->email = ' deleteduser_'.Carbon::now($timezone)->format('Y_m_d_H_i_s').'@saasmonks.in';
            $user->phone_no = '0000000000';
            $user->verified = 0;
            $user->status = 0;
            $user->save();
            Auth::user()->tokens->each(function ($token,$key) {
                $token->delete();
            });
        }
        return response()->json(['success' => true,'message' => 'Account Delete Successfully!']);
    }
    public function settingShow($id)
    {
        $settings = ParkingOwnerSetting::where('owner_id',$id)->first();
        return response()->json(['msg' => 'Setting Updated', 'data' => $settings, 'success' => true], 200);
    }
    public function allSetting()
    {
        $data = AdminSetting::first(['currency','currency_symbol','app_id']);
        $data['country_code'] = $data['country_code'];
        $data['verification'] = $data['verification'];
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
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
    public function storeUser(Request $request)
    {
        //
    
        $request->validate([
            'email' => 'bail|required|email|unique:app_users,email',
            'name' => 'bail|required',
            // 'password' => 'bail|required|confirmed|min:6',
        ]);
        $reqData = $request->all();

        $app = AdminSetting::get(['id', 'verification'])->first();
        $flow =    $app->verification == 1 ? 'verification' : 'home';
        if ($app->verification != 1) {
            $reqData['verified'] = 1;
        }
        $data = AppUsers::create($reqData);
        $token = $data->createToken('ParingAppUser')->accessToken;
        $data['token'] = $token;
        return response()->json(['nextStep' => $flow, 'msg' => 'Registered Successfully', 'data' => $data, 'success' => true], 200);
    }
    public function reqForOTP(Request $request)
    {
        $request->validate([

            'email' => 'bail|required',
            'phone_no' => 'bail|required',
        ]);
        $userData = AppUsers::Where([['email', $request->email]])->first();

        if ($userData && $userData['verified'] === 1) {
            return response()->json(['msg' => 'You already verify ', 'data' => null, 'success' => false, 'redirect' => 'login'], 200);
        } else if ($userData && $userData['verified'] !== 1) {
            $string = '0123456789';
            $string_shuffled = str_shuffle($string);
            $password = substr($string_shuffled, 1, 4);
            $userData->phone_no = $request->phone_no;
            $message = $password . ' your verification code.';
            try {
                $account_sid = env("TWILIO_SID");
                $auth_token = env("TWILIO_AUTH_TOKEN");
                $twilio_number = env("TWILIO_NUMBER");
                $client = new Client($account_sid, $auth_token);
                $client->messages->create(
                    $request->phone_no,
                    ['from' => $twilio_number, 'body' => $message]
                );
            } catch (\Exception $e) {
            }

            $userData->OTP = $password;
            $userData->verified = 0;
            $userData->save();
            return response()->json(['msg' => 'Verification code send you in Number', 'data' => null, 'success' => true], 200);
        } else {
            return response()->json(['msg' => 'We cant find you in our system', 'data' => null, 'success' => false, 'redirect' => 'login'], 200);
        }
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|required|min:6',
        ]);
        $user = AppUsers::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            if ($user['verified'] != 1) {
                return response()->json(['msg' => 'Please Verify your email', 'data' => null, 'success' => false], 200);
            }
            $token = $user->createToken('PayParkOwner')->accessToken;
            
            $user['device_token'] = $request->device_token;

            $user->save();
            $user['token'] = $token;

            return response()->json(['msg' => 'lOGIN ', 'data' => $user, 'success' => true], 200);
        } else {
            return response()->json(['message' => 'Your email and password not match with record'], 401);
        }
    }
    
    public function verifyMe(Request $request)
    {
        $request->validate([

            'email' => 'bail|required',
            'phone_no' => 'bail|required',

        ]);
        $userData = AppUsers::Where([['email', $request->email], ['phone_no', $request->phone_no]])->first();
        if ($userData && $userData['verified'] === 1) {
            return response()->json(['msg' => 'You already verify ', 'data' => null, 'success' => false, 'redirect' => 'login'], 200);
        } else if ($userData && $userData['verified'] !== 1) {

            if ($userData['OTP'] === $request->OTP) {
                $userData->OTP = '';
                $userData->verified = 1;
                $userData->save();
                $token = $userData->createToken('ParingAppUser')->accessToken;
                $userData['token'] = $token;
                return response()->json(['msg' => 'Thanks For being With us', 'data' => $userData, 'success' => true], 200);
            }
            return response()->json(['msg' => 'OTP is Wrong', 'data' => null, 'success' => false], 200);
        } else {
            return response()->json(['msg' => 'Email and number are attached with different account', 'data' => null, 'success' => false, 'redirect' => 'login'], 200);
        }
    }
    public function getNearByParking(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'lng' => 'required',
        ]);
        $apo = ParkingOwner::where([['status', 1],['subscription_status',1]])->get(['id']);

        $array = array();
        foreach ($apo as $value) {
            array_push($array, $value['id']);
        }
        $temp = [];
        $data = ParkingSpace::withCount(['reviews'])->where('verified',1)->where('status', 1)->whereIn('owner_id', $array)->get()->each->setAppends(['avg_rating']);
        foreach ($data as  $ps) {
            $ps['distance'] = (new AppHelper)->distance($ps->lat, $ps->lng, $request->lat, $request->lng, 'K');
            if ($ps['distance'] > 10) {
            }
        }
        $data = $data->sortBy('distance')->toArray();       
        $data = array_values($data);
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }
    public function getNearByParkingSingle(Request $request, $id)
    {
        $request->validate([
            'lat' => 'required',
            'lng' => 'required',
        ]);
        $ps = ParkingSpace::withCount(['reviews'])->with(['reviews.user', 'images'])->findOrFail($id)->setAppends(['facilitiesData', 'avg_rating']);
        $ps['distance'] = (new AppHelper)->distance($ps->lat, $ps->lng, $request->lat, $request->lng, 'K');
        return response()->json(['msg' => null, 'data' => $ps, 'success' => true], 200);
    }

    public function getParkingZone(request $request, $id)
    {
        $start =  Carbon::parse($request->startTime)->format('Y-m-d H:i:s');
        $end =  Carbon::parse($request->endTime)->format('Y-m-d H:i:s');

        $ps = SpaceZone::with(['slots'])->where('space_id', $id)->get();
      
        foreach ($ps as  $value) {
            foreach ($value['slots'] as &$slot) {
                $booking = ParkingBooking::where('slot_id', $slot['id'])->whereIn('status', [0, 1])->get();
                if (count($booking) > 0) {
                    foreach ($booking as &$b) {
                        $a_date = carbon::parse($b->arriving_time)->format('Y-m-d H:i:s');
                        $l_date = carbon::parse($b->leaving_time)->format('Y-m-d H:i:s');
                        $st = carbon::parse($start);
                        $et = Carbon::parse($end);
                        if ($st->between($a_date, $l_date)) {
                            $slot['available'] = false;
                        } else {
                            $slot['available'] = true;                            
                        }
                    }
                } else {
                    $slot['available'] = true;
                }
            }
        }
        return response()->json(['msg' => null, 'data' => $ps, 'success' => true], 200);
    }
    public function displayFacilities()
    {
        return response()->json(['msg' => null, 'data' => Facilities::all(), 'success' => true], 200);
    }
    public function ppApi()
    {
        $pp = AdminSetting::get()->first();
        return response()->json(['msg' => null, 'data' => $pp, 'success' => true], 200);
    } 
}
