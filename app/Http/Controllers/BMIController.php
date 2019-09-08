<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BMIController extends Controller
{
    public function getAllLocation(){
        $result = DB::table('site')->get();
        $status = DB::table('status')->get();

        return response([
           'success' => true,
           'status' => $status,
           'site' => $result
        ]);
    }
    public function getLocation(){
        $result = DB::table('site')
            ->join('board','board.site_id','=','site.site_id')
            ->get();
        return response([
            'success' => true,
            'data' => $result
        ]);
    }
    public function getLocationById($id){
        $result = DB::table('site')
            ->join('board','board.site_id','=','site.site_id')
            ->where('board_id',$id)
            ->first();
        return response([
            'success' => true,
            'data' => $result
        ]);
    }
    public function getBmiUser(Request $request){
        $user = Auth::User();
        dd($user);
        $rf_id = DB::table('rfid')
            ->where('user_id',$user)
            ->pluck('rf_id');
        $result = DB::table('bmi')
            ->whereIn('rf_id',$rf_id)
            ->orderBy('time_update','desc')
            ->get();
        $status = false;
        if($result){
            $status = true;
        }
        return response([
            'success' => $status,
            'data' => $result
        ]);
    }
    public function getStaticOverview(){

        $user_list = DB::table('user')->get();
        $data = [];
        foreach ($user_list as $row){
            $result = DB::table('bmi')
                ->select('bmi','bmi.time_update','board_name','site_name')
                ->join('rfid','rfid.rf_id','=','bmi.rf_id')
                ->join('board','board.board_id','=','bmi.board_id')
                ->join('site','site.site_id','=','board.site_id')
                ->where('user_id',$row->user_id)
                ->orderBy('bmi.time_update','desc')
                ->first();
            if($result){
                $data []= [
                    'user_id' => $row->user_id,
                    'Fname' => $row->name,
                    'Lname' => $row->lastname,
                    'bmi' => $result->bmi,
                    'time_update' => $result->time_update,
                    'board_name' => $result->board_name,
                    'site_name' => $result->site_name
                ];
            }
        }
        return response([
            'success' => true,
            'data' => $data
        ]);
    }
}
