<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Api\v1\User;
use App\Models\Backend\ReportedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index(){
        $totalUsers = User::count();
        $totalReportedUsers = ReportedUser::count();
        $totalMans = User::where(['tiGender' => 1])->count();
        $totalWoman = User::where(['tiGender' => 2])->count();
        $totalPendingUsers = User::where(['tiIsActive' => 1 ,'tiIsAdminApproved'=> 0])->count();
        $totalInactiveUsers = User::where(['tiIsActive' => 0 ])->count();
        $totalActiveUsers = User::where(['tiIsActive' => 1 ])->count();
        $totalPremiumUsers = User::where(['tiIsPremiumUser' => 1])->count();
        $totalBasicUsers = User::where(['tiIsPremiumUser' => 0])->count();
        $data = [
            'totalUsers' => $totalUsers,
            'totalReportedUsers' => $totalReportedUsers,
            'totalMans' => $totalMans,
            'totalWomans' => $totalWoman,
            'totalInactiveUsers' => $totalInactiveUsers,
            'totalActiveUsers' => $totalActiveUsers,
            'totalPendingUsers' => $totalPendingUsers,
            'totalBasicUsers' => $totalBasicUsers,
            'totalPremiumUsers' => $totalPremiumUsers

        ];
        return view('backend.home.index', ['data' => $data]);
    }
}
