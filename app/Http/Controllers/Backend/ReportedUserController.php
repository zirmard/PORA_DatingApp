<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\ReportedUser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Session;

class ReportedUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $reportedUsers = ReportedUser::select(DB::raw("CONCAT(users.vFirstName,' ',users.vLastName) as vFullname"),DB::raw("CONCAT(reportedBy.vFirstName,' ',reportedBy.vLastName) as vReportedFullname"), 'reported_users.*', 'report_reasons.vReportReason')
                ->leftjoin('users', function($join){
                    $join->on('users.iUserId','=', 'reported_users.iReportedUserId');
                })
                ->leftjoin('users as reportedBy', function($join){
                    $join->on('reportedBy.iUserId','=', 'reported_users.iUserId');
                })
                ->leftjoin('report_reasons', function($join){
                    $join->on('report_reasons.iReportReasonId','=', 'reported_users.iReportReasonId');
                });

                if($request->order ==null){
                    $reportedUsers->orderBy('tsCreatedAt', 'DESC');
                }

                $data = DataTables::of($reportedUsers)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        return '<a href="' . route("backend.reported-users.show", $row->vUserReportUuid) . '" class="badge badge-warning" title="View"><i class="fa fa-eye p-1"></i></a>';
                    })
                    ->filterColumn('vReportReason', function ($query, $keyword) {
                        $sql = "report_reasons.vReportReason like ?";
                        $query->whereRaw($sql, ["%{$keyword}%"]);
                    })
                    ->filterColumn('vFullname', function ($query, $keyword) {
                        $sql = "concat(users.vFirstName, ' ', users.vLastName) like ?";
                        $query->whereRaw($sql, ["%{$keyword}%"]);
                    })
                    ->filterColumn('vReportedFullname', function ($query, $keyword) {
                        $sql = "concat(reportedBy.vFirstName, ' ', reportedBy.vLastName) like ?";
                        $query->whereRaw($sql, ["%{$keyword}%"]);
                    })
                    ->rawColumns(['action'])
                    ->make(true);
                return $data;
            }
            return view('backend.reported-users.index');
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            if($id) {
                $reportedUser = ReportedUser::select(DB::raw("CONCAT(users.vFirstName,' ',users.vLastName) as vFullname"),DB::raw("CONCAT(reportedBy.vFirstName,' ',reportedBy.vLastName) as vReportedFullname"), 'reported_users.*', 'report_reasons.vReportReason')
                ->leftjoin('users', function($join){
                    $join->on('users.iUserId','=', 'reported_users.iReportedUserId');
                })
                ->leftjoin('users as reportedBy', function($join){
                    $join->on('reportedBy.iUserId','=', 'reported_users.iUserId');
                })
                ->leftjoin('report_reasons', function($join){
                    $join->on('report_reasons.iReportReasonId','=', 'reported_users.iReportReasonId');
                })
                ->where(['vUserReportUuid' => $id])->first();
                return view('backend.reported-users.show', ['model' => $reportedUser]);
            } else {
                return redirect()->back()->with('error','Reported User not found.');
            }
        } catch(Exception $ex) {
            return $ex->getMessage();
        }
    }

    public function destroy($id) {
        try {
            $deactivateUser = ReportedUser::where(['vUserReportUuid' => $id])->first();
            if(!empty($deactivateUser)) {
                $deactivateUser->delete();
                Session::flash('success', 'User has been De-Activated successfully');
                return redirect()->route('backend.reported-users.index');
            }
        } catch(Exception $ex) {
            return $ex->getMessage();
        }
    }
}
