<?php

namespace App\Http\Controllers;

use stdClass;
use App\ParkingSpace;
use App\Review;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;

// use App\User;

class AppHelper extends Controller
{
    public function deleteFile($fileName)
    {
        if ($fileName != "default.jpg") {
            $image_path = "upload/" . $fileName;
            if (unlink("upload/" . $fileName)) {
                return true;
            } else {
                echo "No someone reach First:)";
            }
        }
    }
    public function saveImage($request)
    {
        $image = $request->file('image');
        $input['imagename'] = uniqid() . '.' . $image->getClientOriginalExtension();
        $destinationPath = public_path('/upload');
        $image->move($destinationPath, $input['imagename']);

        return $input['imagename'];
    }

    public function getManagerBeach()
    {
        $beach = Beach::where('status', 0)->get();
        $master = array();
        foreach ($beach as $value) {
            if (in_array(Auth::user()->id, $value->manager_id)) {
                array_push($master, $value);
            }
        }
        return $master;
    }

    public function saveBase64($baseString)
    {
        $uploadpath  = public_path() . '/upload/';
        $parts       = explode(";base64,", $baseString);
        $imageparts  = explode("image/", @$parts[0]);
        $imagetype   = $imageparts[1];
        $imagebase64 = base64_decode($parts[1]);
        $imgName = uniqid();
        $file = $uploadpath . $imgName . '.png';
        file_put_contents($file, $imagebase64);
        return $imgName . '.png';
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return number_format(($miles * 1.609344), 2, '.', '');
            } else if ($unit == "N") {
                return number_format(($miles * 0.8684), 2, '.', '');
            } else {
                return number_format($miles, 2, '.', '');
            }
        }
    }

    
    public function All_Reviews()
    {
        $parkingspace = ParkingSpace::all()->pluck('id');
        $review = Review::all();
        $total_count_review = count($review);
        $total_sum = $review->sum('star');
    
        $object = new stdClass();
        $object->total_review = $total_count_review;
    
        if ($total_count_review != 0) {
            $object->per_total_review = number_format(($total_sum / $total_count_review), 2);
        } else {
            $object->per_total_review = 0;
        }
    
        $starValues = [5, 4, 3, 2, 1];
        foreach ($starValues as $starValue) {
            $starCount = Review::whereIn('space_id', $parkingspace)
                ->where('star', $starValue)
                ->count();   
            if ($total_count_review != 0) {
                $percentage = number_format(($starCount * 100 / $total_count_review), 2);
            } else {
                $percentage = 0;
            }
    
            $object->{"per_{$starValue}_star"} = $percentage;
        }
    
        return $object;
    }


    public function getPreviewUrl($url)
    {
        $previewUrl = url($url);
        header('Location: ' . $previewUrl);
        exit();
    }
}
