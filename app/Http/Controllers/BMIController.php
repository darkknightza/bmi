<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BMIController extends Controller
{
    public function BMICriterion($value)
    {
        $data = ['น้ำหนักต่ำกว่าเกณฑ์', 'สมส่วน', 'น้ำหนักเกิน', 'โรคอ้วน', 'โรคอ้วนอันตราย'];
        $result = '';
        if ($value < 18.5) {
            $result = $data[0];
        } else if ($value >= 18.5 && $value <= 22.9) {
            $result = $data[1];
        } else if ($value >= 23 && $value <= 24.9) {
            $result = $data[2];
        } else if ($value >= 25 && $value <= 29.9) {
            $result = $data[3];
        } else if ($value >= 30) {
            $result = $data[4];
        }
        return $result;
    }
    public function getProfile(){
        $user = Auth::User()->user_id;
        $result = DB::table('user')
            ->where('user_id',$user)
            ->first();
        return response([
            'success' => true,
            'profile' => $result
        ]);
    }
    public function saveProfile(Request $request){
        DB::table('user')
            ->where('user_id', $request->get('user_id'))
            ->update([
                'name' => $request->get('name'),
                'lastname' => $request->get('lastname'),
                'student_id' => $request->get('student_id'),
                'gender' => $request->get('gender'),
                'card_id' => $request->get('card_id'),
            ]);
        return response([
            'success' => true,
            'data' => []
        ]);
    }
    public function getReport()
    {
        $student_report = DB::table('bmi')
            ->where('user_type_id', 2)
            ->join('rfid', 'rfid.rf_id', '=', 'bmi.rf_id')
            ->join('user', 'user.user_id', '=', 'rfid.user_id')
            ->get(['weight', 'height', 'bmi']);
        $student_list_bmi = [
            'bmi_1' => 0,
            'bmi_2' => 0,
            'bmi_3' => 0,
            'bmi_4' => 0,
            'bmi_5' => 0,
            'count' => count($student_report)
        ];
        foreach ($student_report as $key => $row) {
            $value = $row->bmi;
            if ($value < 18.5) {
                $student_list_bmi['bmi_1']++;
            } else if ($value >= 18.5 && $value <= 22.9) {
                $student_list_bmi['bmi_2']++;
            } else if ($value >= 23 && $value <= 24.9) {
                $student_list_bmi['bmi_3']++;
            } else if ($value >= 25 && $value <= 29.9) {
                $student_list_bmi['bmi_4']++;
            } else if ($value >= 30) {
                $student_list_bmi['bmi_5']++;
            }
        }

        $personnel_report = DB::table('bmi')
            ->where('user_type_id', 3)
            ->join('rfid', 'rfid.rf_id', '=', 'bmi.rf_id')
            ->join('user', 'user.user_id', '=', 'rfid.user_id')
            ->get(['weight', 'height', 'bmi']);
        $personnel_list_bmi = [
            'bmi_1' => 0,
            'bmi_2' => 0,
            'bmi_3' => 0,
            'bmi_4' => 0,
            'bmi_5' => 0,
            'count' => count($personnel_report)
        ];
        foreach ($personnel_report as $key => $row) {
            $value = $row->bmi;
            if ($value < 18.5) {
                $student_list_bmi['bmi_1']++;
            } else if ($value >= 18.5 && $value <= 22.9) {
                $student_list_bmi['bmi_2']++;
            } else if ($value >= 23 && $value <= 24.9) {
                $student_list_bmi['bmi_3']++;
            } else if ($value >= 25 && $value <= 29.9) {
                $student_list_bmi['bmi_4']++;
            } else if ($value >= 30) {
                $student_list_bmi['bmi_5']++;
            }
        }
        $report_3 = DB::table('bmi')
            ->selectRaw('hw_name,count(hw_name) as count')
            ->join('hardware', 'hardware.hw_id', '=', 'bmi.hw_id')
            ->groupBy(['hw_name'])
            ->orderBy('count','desc')
            ->limit(5)
            ->get();
        $report_4 = [
          'student' => $student_list_bmi['count'],
          'personnel' => $personnel_list_bmi['count'],
          'count' => $student_list_bmi['count']+$personnel_list_bmi['count'],
        ];
        $time_00 = DB::table('bmi')
            ->selectRaw('count(bmi) as count')
            ->whereRaw('CAST(time_update as time) >= "00:00:00" AND CAST(time_update as time) <= "12:00:00"')
            ->groupBy(['bmi'])
            ->get();
        $time_00 = count($time_00);
        $time_12 = DB::table('bmi')
            ->selectRaw('count(bmi) as count')
            ->whereRaw('CAST(time_update as time) > "12:00:00" AND CAST(time_update as time) <= "24:00:00"')
            ->groupBy(['bmi'])
            ->get();
        $time_12 = count($time_12);
        $report_5 = [
            'time_00' => $time_00,
            'time_12' => $time_12,
        ];
        return response([
            'success' => true,
            'student' => $student_list_bmi,
            'personnel' => $personnel_list_bmi,
            'report_3' => $report_3,
            'report_4' => $report_4,
            'report_5' => $report_5,
        ]);
    }

    public function getAllSite()
    {
        $result = DB::table('site')->get();
        return response([
            'success' => true,
            'site' => $result
        ]);
    }

    public function getStatus()
    {
        $result = DB::table('status')->get();
        return response([
            'success' => true,
            'data' => $result
        ]);
    }

    public function getAllLocation()
    {
        $result = DB::table('location')->get();
        return response([
            'success' => true,
            'locations' => $result
        ]);
    }

    public function SaveHW(Request $request)
    {
        if ($request->get('hw_id')) {
            DB::table('hardware')
                ->where('hw_id', $request->get('hw_id'))
                ->update([
                    'status_id' => $request->get('status_id'),
                    'board_id' => $request->get('board_id'),
                    'hw_name' => $request->get('hw_name'),
                    'time_update' => Carbon::now()

                ]);
        } else {
            DB::table('hardware')
                ->insert([
                    'status_id' => $request->get('status_id'),
                    'board_id' => $request->get('board_id'),
                    'hw_name' => $request->get('hw_name'),
                    'time_update' => Carbon::now()
                ]);
        }

        return response(['success' => true, 'data' => []]);
    }

    public function SaveBoard(Request $request)
    {
        if ($request->get('board_id')) {
            DB::table('board')
                ->where('board_id', $request->get('board_id'))
                ->update([
                    'board_name' => $request->get('board_name'),
                    'site_id' => $request->get('site_id'),
                    'time_update' => Carbon::now()

                ]);
        } else {
            DB::table('board')
                ->insert([
                    'board_name' => $request->get('board_name'),
                    'site_id' => $request->get('site_id'),
                    'time_update' => Carbon::now()
                ]);
        }

        return response(['success' => true, 'data' => []]);
    }

    public function SaveSite(Request $request)
    {
        if ($request->get('site_id')) {
            DB::table('site')
                ->where('site_id', $request->get('site_id'))
                ->update([
                    'site_name' => $request->get('site_name'),
                    'location_id' => $request->get('location_id'),
                    'time_update' => Carbon::now()
                ]);
        } else {
            DB::table('site')
                ->insert([
                    'site_name' => $request->get('site_name'),
                    'location_id' => $request->get('location_id'),
                    'time_update' => Carbon::now()
                ]);
        }
        return response(['success' => true, 'data' => []]);
    }

    public function SaveLocation(Request $request)
    {
        if ($request->get('location_id')) {
            DB::table('location')
                ->where('location_id', $request->get('location_id'))
                ->update([
                    'location_name' => $request->get('location_name'),
                    'time_update' => Carbon::now()
                ]);
        } else {
            DB::table('location')
                ->insert([
                    'location_name' => $request->get('location_name'),
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

    public function getLocationUser()
    {
        $result = DB::table('site')
            ->join('board', 'board.site_id', '=', 'site.site_id')
            ->join('location', 'location.location_id', '=', 'site.location_id')
            ->join('hardware', 'hardware.board_id', '=', 'board.board_id')
            ->join('status', 'hardware.status_id', '=', 'status.status_id')
            ->get();
        return response([
            'success' => true,
            'data' => $result
        ]);
    }

    public function getBoardAdmin()
    {
        $result = DB::table('board')
            ->join('site', 'site.site_id', '=', 'board.site_id')
            ->get();
        return response(['success' => true, 'data' => $result]);
    }

    public function getBoardById($id)
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

    public function getSiteAdmin()
    {
        $result = DB::table('site')
            ->join('location', 'location.location_id', '=', 'site.location_id')
            ->get(['site_id', 'site.time_update', 'site_name', 'location_name']);
        return response(['success' => true, 'data' => $result]);
    }

    public function getSiteById($id)
    {
        $result = DB::table('site')
            ->join('location', 'location.location_id', '=', 'site.location_id')
            ->where('site_id', $id)
            ->first();
        return response([
            'success' => true,
            'data' => $result
        ]);
    }

    public function getLocationById($id)
    {
        $result = DB::table('location')
            ->where('location_id', $id)
            ->first();
        return response([
            'success' => true,
            'data' => $result
        ]);
    }

    public function getHwById($id)
    {
        $result = DB::table('hardware')
            ->where('hw_id', $id)
            ->first();
        return response([
            'success' => true,
            'data' => $result
        ]);
    }


    public function getStaticOverview()
    {

        $user_list = DB::table('user')
            ->join('user_type', 'user_type.user_type_id', '=', 'user.user_type_id')
            ->get();
        $data = [];
        foreach ($user_list as $row) {
            $result = DB::table('bmi')
                ->select('bmi', 'bmi.time_update', 'board_name', 'site_name', 'hw_name')
                ->join('rfid', 'rfid.rf_id', '=', 'bmi.rf_id')
                ->join('hardware', 'hardware.hw_id', '=', 'bmi.hw_id')
                ->join('board', 'board.board_id', '=', 'hardware.board_id')
                ->join('site', 'site.site_id', '=', 'board.site_id')
                ->where('rfid.user_id', $row->user_id)
                ->orderBy('bmi.time_update', 'desc')
                ->first();
            if ($result) {
                $data [] = [
                    'user_id' => $row->user_id,
                    'user_type' => $row->user_type_name,
                    'bmi' => $result->bmi,
                    'time_update' => $result->time_update,
                    'hw_name' => $result->hw_name,
                    'board_name' => $result->board_name,
                    'site_name' => $result->site_name,
                    'criterion' => $this->BMICriterion($result->bmi)
                ];
            }
        }
        return response([
            'success' => true,
            'data' => $data
        ]);
    }
}
