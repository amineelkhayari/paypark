<?php

namespace App\Http\Controllers;

use App\ParkingBooking;
use App\ParkingGuard;
use App\ParkingSpace;
use App\SpaceSlot;
use App\SpaceZone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ParkingSpaceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ParkingSpace::where('owner_id', Auth::user()->id)->get();
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    public function adminChangeVerify($id)
    {
        $data =   ParkingSpace::findOrFail($id);
        $data->verified = $data->verified == 1 ? 0  : 1;
        $data->update();
        return redirect()->back()->withStatus(__('Status Is changed.'));
    }
    public function adminSpaceView($id)
    {
        $data =   ParkingSpace::with(['guards', 'reviews.user'])->findOrFail($id)->setAppends(['facilitiesData', 'vehicleTypeData', 'avg_rating']);
        return view('parkingOwner.singleSpace', ['data' => $data]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'bail|required',
            'address' => 'bail|required',
            'lat' => 'bail|required',
            'lng' => 'bail|required',
            'price_par_hour' => 'bail|required|',
            // 'open_time' => 'bail|required|date_format:H:i:s',
            // 'close_time' => 'bail|required|date_format:H:i:s',
            'available_all_day' => 'bail|required|numeric',
        ]);
        $reqData = $request->all();
        $reqData['open_time'] = Carbon::parse($request->open_time)->format('H:i:s');
        $reqData['close_time'] = Carbon::parse($request->close_time)->format('H:i:s');
        $reqData['owner_id'] =  Auth::user()->id;
        $reqData['status'] =  1;
        $data =  ParkingSpace::create($reqData);
        if ($reqData['parkingZone']) {
            foreach ($reqData['parkingZone'] as $key => $value) {
                $zoneData = SpaceZone::create([
                    'space_id' => $data['id'],
                    'owner_id' => $reqData['owner_id'],
                    'name' => $value['name']
                ]);
                $slot = [];
                for ($i = 1; $i < (int) $value['size']; $i++) {
                    $slot[] = [
                        'zone_id' => $zoneData->id,
                        'space_id' => $data['id'],
                        'name' => $value['name'] . ' ' . $i,
                        'position' => $i,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ];
                }

                SpaceSlot::insert($slot);
            }
        }
        if (isset($reqData['guardList'])) {
            ParkingGuard::whereIn('id', $reqData['guardList'])->update([
                'space_id' => $data->id,
            ]);
        }
        return response()->json(['msg' => 'Parking Space Added secesuufuly', 'data' => null, 'success' => true], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ParkingSpace  $parkingSpace
     * @return \Illuminate\Http\Response
     */
   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ParkingSpace  $parkingSpace
     * @return \Illuminate\Http\Response
     */
    public function edit(ParkingSpace $parkingSpace)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ParkingSpace  $parkingSpace
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'bail|required',
            'address' => 'bail|required',
            'lat' => 'bail|required',
            'lng' => 'bail|required',
            'price_par_hour' => 'bail|required',

            'available_all_day' => 'bail|required|numeric',
        ]);
        $reqData = $request->all();
        $reqData['open_time'] = Carbon::parse($request->open_time)->format('H:i:s');
        $reqData['close_time'] = Carbon::parse($request->close_time)->format('H:i:s');
        $parkingSpace = ParkingSpace::findOrFail($id);
        $parkingSpace->update($reqData);
        ParkingGuard::whereIn('id', $reqData['oldGuardList'])->update([
            'space_id' => null
        ]);

        ParkingGuard::whereIn('id', $reqData['guardList'])->update([
            'space_id' => $id
        ]);
        return response()->json(['msg' => 'Parking Space Updated secesuufuly', 'data' => null, 'success' => true], 200);
    }
    public function show($id)
    {
        $data['space'] = ParkingSpace::with(['guards', 'zones.slots'])->findOrFail($id)->setAppends(['facilitiesData', 'vehicleTypeData']);
        $data['booking'] = ParkingBooking::with(['user', 'vehicle:id,model,vehicle_no'])->where([['space_id', $id]])->get();//whereDate('arriving_time', Carbon::today())->get();
        return response()->json(['msg' => null, 'data' => $data, 'success' => true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ParkingSpace  $parkingSpace
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $parkingSpace = ParkingSpace::findOrFail($id);
        $parkingSpace->delete();
        return response()->json(['msg' => 'Parking Space Deleted secesuufuly', 'data' => null, 'success' => true], 200);
    }

   
    

   
}