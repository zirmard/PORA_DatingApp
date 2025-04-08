<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\ContactReason;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ContactReasonController extends Controller
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
                $contactReasons = ContactReason::where(['tiIsActive' => 1]);

                if($request->order ==null){
                    $contactReasons->orderBy('tsCreatedAt', 'DESC');
                }

                $data = DataTables::of($contactReasons)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        return '<a href="' . route("backend.contact-reasons.show", $row->vContactReasonUuid) . '" class="badge badge-warning" title="View"><i class="fa fa-eye p-1"></i></a>
                                <a href="javascript:void(0)" data-href="' . route("backend.contact-reasons.destroy", $row->vContactReasonUuid) . '" class="badge badge-danger delete-record delete-contact-reason" title="Delete"><i class="fa fa-trash p-1"></i></a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
                return $data;

            }
            return view('backend.contact-reasons.index');
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
            return view('backend.contact-reasons.create');
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
            "vContactReason" => "required|max:255",
            ],
            [
            'vContactReason.required' => 'Contact Reason cannot be blank.',
            'vContactReason.max' => 'User should not enter more then 255 characters from keyboard.',
            ]
        );
        try {
            $addContactReason = new ContactReason();
            $addContactReason->fill($request->all());
            $addContactReason->vContactReasonUuid = Str::uuid()->toString();
            if($addContactReason->save()) {
                return redirect()->route('backend.contact-reasons.index')->with('success','Contact Reason Added Successfully.');
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
                $contactReason = ContactReason::where(['vContactReasonUuid' => $id])->first();
                return view('backend.contact-reasons.show', ['model' => $contactReason]);
            } else {
                return redirect()->back()->with('error','Contact Reason not found.');
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
            $contactReason = ContactReason::where('vContactReasonUuid', '=', $id)->first();
            if ($contactReason->delete()) {
                $contactReason->tiIsActive = 0;
                $contactReason->save();
                return redirect()->back()->with('success', 'Contact Reason Deleted.');
            }
        } catch(Exception $ex) {
            return $ex->getMessage();
        }
    }
}
