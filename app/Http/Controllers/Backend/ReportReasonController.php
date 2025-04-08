<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\ReportReason;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ReportReasonController extends Controller
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
                $reportReasons = ReportReason::where(['tiIsActive' => 1]);

                if($request->order ==null){
                    $reportReasons->orderBy('tsCreatedAt', 'DESC');
                }

                $data = DataTables::of($reportReasons)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        return '<a href="' . route("backend.report-reasons.show", $row->vReportReasonUuid) . '" class="badge badge-warning" title="View"><i class="fa fa-eye p-1"></i></a>
                                <a href="javascript:void(0)" data-href="' . route("backend.report-reasons.destroy", $row->vReportReasonUuid) . '" class="badge badge-danger delete-record delete-report-reason" title="Delete"><i class="fa fa-trash p-1"></i></a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
                return $data;
            }
            return view('backend.report-reasons.index');
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('backend.report-reasons.create');
        } catch (Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "vReportReason" => "required|max:255",
            ],
            [
            'vReportReason.required' => 'Report Reason cannot be blank.',
            'vReportReason.max' => 'User should not enter more then 255 characters from keyboard.',
            ]
        );
        try {
            $addReportReason = new ReportReason();
            $addReportReason->fill($request->all());
            $addReportReason->vReportReasonUuid = Str::uuid()->toString();
            if($addReportReason->save()) {
                return redirect()->route('backend.report-reasons.index')->with('success','Report Reason Added Successfully.');
            }
        } catch(Exception $ex) {
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
                $reportReason = ReportReason::where(['vReportReasonUuid' => $id])->first();
                return view('backend.report-reasons.show', ['model' => $reportReason]);
            } else {
                return redirect()->back()->with('error','Report Reason not found.');
            }
        } catch(Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $reportReason = ReportReason::where('vReportReasonUuid', '=', $id)->first();
            if ($reportReason->delete()) {
                $reportReason->tiIsActive = 0;
                $reportReason->save();
                return redirect()->back()->with('success', 'Report Reason Deleted Successfully.');
            }
        } catch(Exception $ex) {
            return $ex->getMessage();
        }
    }
}
