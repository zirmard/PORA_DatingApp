<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Faq;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class FaqController extends Controller
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
                $faqs = Faq::where(['tiIsActive' => 1]);

                if($request->order ==null){
                    $faqs->orderBy('tsCreatedAt', 'DESC');
                }

                $data = DataTables::of($faqs)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        return '<a href="' . route("backend.faqs.show", $row->vFaqUuid) . '" class="badge badge-warning" title="View"><i class="fa fa-eye p-1"></i></a>
                                <a href="' . route("backend.faqs.edit", $row->vFaqUuid) . '" class="badge badge-info" title="Edit"><i class="fa fa-edit p-1"></i></a></a>
                                <a href="javascript:void(0)" data-href="' . route("backend.faqs.destroy", $row->vFaqUuid) . '" class="badge badge-danger delete-record delete-faq" title="Delete"><i class="fa fa-trash p-1"></i></a>';
                    })
                    ->filter(function ($instance) use ($request) {

                        if ($request->get('question_category_filter') != "") {
                            $instance->where('vQuestionCategory', $request->get('question_category_filter'));
                        }
                    })
                    ->rawColumns(['action','txAnswer'])
                    ->make(true);
                return $data;

            }
            $getQuestionCategories = Faq::select('vQuestionCategory')->where(['tiIsActive' => 1])->get()->unique('vQuestionCategory');
            return view('backend.faqs.index', compact('getQuestionCategories'));
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
            return view('backend.faqs.create');
        } catch(Exception $ex) {
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
            "vQuestionCategory" => "required|max:255",
            "vQuestion" => "required|max:255",
            "txAnswer" => "required",
            ],
            [
            'vQuestionCategory.required' => 'Question Category cannot be blank.',
            'vQuestionCategory.max' => 'User should not enter more then 255 characters from keyboard.',
            'vQuestion.required' => 'Question cannot be blank.',
            'vQuestion.max' => 'User should not enter more then 255 characters from keyboard.',
            'txAnswer.required' => 'Answer cannot be blank.',
            ]
        );
        try {
            $addFaq = new Faq();
            $addFaq->fill($request->all());
            $addFaq->vFaqUuid = Str::uuid()->toString();
            if($addFaq->save()) {
                return redirect()->route('backend.faqs.index')->with('success','FAQ added successfully.');
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
                $faq = Faq::where(['vFaqUuid' => $id])->first();
                return view('backend.faqs.show', ['model' => $faq]);
            } else {
                return redirect()->back()->with('error','Faq Page not found.');
            }
        } catch(Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
           $faq = Faq::where(['vFaqUuid' => $id])->first();
            return view('backend.faqs.edit', ['model' => $faq]);
        } catch(Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "vQuestionCategory" => "required|max:255",
            "vQuestion" => "required|max:255",
            "txAnswer" => "required",
            ],
            [
            'vQuestionCategory.required' => 'Question Category cannot be blank.',
            'vQuestionCategory.max' => 'User should not enter more then 255 characters from keyboard.',
            'vQuestion.required' => 'Question cannot be blank.',
            'vQuestion.max' => 'User should not enter more then 255 characters from keyboard.',
            'txAnswer.required' => 'Answer cannot be blank.',
            ]
        );
        try {
            $updateFaq = Faq::where(['vFaqUuid' => $id])->first();
            $updateFaq->fill($request->all());
            if($updateFaq->save()) {
                return redirect()->route('backend.faqs.index')->with('success','FAQ updated successfully.');
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
            $faq = Faq::where('vFaqUuid', '=', $id)->first();
            if ($faq->delete()) {
                $faq->tiIsActive = 0;
                $faq->save();
                return redirect()->back()->with('success', 'Faq Deleted.');
            }
        } catch(Exception $ex) {
            return $ex->getMessage();
        }
    }
}
