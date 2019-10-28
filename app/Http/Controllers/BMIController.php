<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class BMIController extends Controller
{
    public function getAllLocation()
    {
        $result = DB::table('site')->get();
        $status = DB::table('status')->get();
        $data = [];
        foreach ($status as $row) {
            $data[$row->status_id] = $row;
        }
        return response([
            'success' => true,
            'status' => $data,
            'site' => $result
        ]);
    }

    public function SaveBoard(Request $request)
    {
        if ($request->get('board_id')) {
            DB::table('board')
                ->where('board_id', $request->get('board_id'))
                ->update([
                    'board_name' => $request->get('board_name'),
                    'site_id' => $request->get('site_id'),
                    'board_status' => $request->get('board_status'),
                    'time_update' => Carbon::now()

                ]);
        } else {
            DB::table('board')
                ->insert([
                    'board_name' => $request->get('board_name'),
                    'site_id' => $request->get('site_id'),
                    'board_status' => $request->get('board_status'),
                    'time_update' => Carbon::now()
                ]);
        }

        return response(['success' => true, 'data' => []]);
    }

    public function getChartBMI()
    {
        $user = Auth::User()->user_id;
        $rf_id = DB::table('rfid')
            ->where('user_id', $user)
            ->pluck('rf_id');
        $result = DB::table('bmi')
            ->whereIn('rf_id', $rf_id)
            ->orderBy('time_update', 'asc')
            ->get();
        $data = [];
        if (count($result) !== 0 && $result !== null) {
            $label = [];
            $height = [];
            $weight = [];
            $bmi = [];
            foreach ($result as $row) {
                $label [] = $row->time_update;
                $height [] = $row->height;
                $weight [] = $row->weight;
                $bmi [] = $row->bmi;
            }
            $data = [
                'label' => $label,
                'height' => $height,
                'weight' => $weight,
                'bmi' => $bmi
            ];
        }
        $status = false;
        if ($result) {
            $status = true;
        }
        return response([
            'success' => $status,
            'data' => $data
        ]);
    }

    public function getLocation()
    {
        $result = DB::table('site')
            ->join('board', 'board.site_id', '=', 'site.site_id')
            ->join('status', 'board.board_status', '=', 'status.status_id')
            ->get();
        return response([
            'success' => true,
            'data' => $result
        ]);
    }

    public function getLocationById($id)
    {
        $result = DB::table('site')
            ->join('board', 'board.site_id', '=', 'site.site_id')
            ->where('board_id', $id)
            ->first();
        return response([
            'success' => true,
            'data' => $result
        ]);
    }

    public function getBmiUser(Request $request)
    {
        $user = Auth::User()->user_id;
        $rf_id = DB::table('rfid')
            ->where('user_id', $user)
            ->pluck('rf_id');
        $result = DB::table('bmi')
            ->whereIn('rf_id', $rf_id)
            ->orderBy('time_update', 'desc')
            ->get();
        $status = false;
        if ($result) {
            $status = true;
        }
        return response([
            'success' => $status,
            'data' => $result
        ]);
    }

    public function getStaticOverview()
    {

        $user_list = DB::table('user')->get();
        $data = [];
        foreach ($user_list as $row) {
            $result = DB::table('bmi')
                ->select('bmi', 'bmi.time_update', 'board_name', 'site_name')
                ->join('rfid', 'rfid.rf_id', '=', 'bmi.rf_id')
                ->join('board', 'board.board_id', '=', 'bmi.board_id')
                ->join('site', 'site.site_id', '=', 'board.site_id')
                ->where('user_id', $row->user_id)
                ->orderBy('bmi.time_update', 'desc')
                ->first();
            if ($result) {
                $data [] = [
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
