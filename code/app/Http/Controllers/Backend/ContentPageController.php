<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\ContentPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Str;



class ContentPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $cmspages = ContentPage::where(['tiIsActive' => 1]);

            $data = Datatables::of($cmspages)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<a href="' . route("backend.content_page.show", ['content_page' => $row->vPageUuid]) . '" class="badge badge-warning" title="View"><i class="fa fa-eye p-1"></i></a>
                <a href="' . route("backend.content_page.edit", ['content_page' => $row->vPageUuid]) . '" class="badge badge-info" title="Edit"><i class="fa fa-edit p-1"></i></a>';
                })
                ->rawColumns(['action'])
                ->make(true);
            return $data;

        }
        return view('backend.content_page.index');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.content_page.create');
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
            'vPageName' => 'required',
            'vSlug' => 'required|unique:content_pages',
            'txContent' => 'required',
        ],
            [
                'vPageName.required' => 'CMS page name can not be blank.',
                'vSlug.required' => 'Slug cannot be blank.',
                'vSlug.unique' => 'This page is already exists.',
                'txContent.required' => 'Description cannot be blank.',
            ]
        );
        try {
            $cmspage = new ContentPage();
            $cmspage->vPageUuid = Str::uuid()->toString();
            $cmspage->vPageName = $request->get('vPageName');
            $cmspage->vSlug = Str::slug($request->get('vPageName'), '-');
            $cmspage->txContent = $request->get('txContent');
            $cmspage->iCreatedAt = time();
            if($cmspage->save()) {
                $request->session()->flash('success', $request->vPageName.' content has been Added Successfully.');
                return redirect(route('backend.content_page.index'));
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', $ex->getMessage() . ' at line: ' . $ex->getLine());
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($uuid)
    {
        $model = $this->getPage($uuid);
        return view('backend.content_page.show',compact('model'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($uuid)
    {
        $model = $this->getPage($uuid);
        return view('backend.content_page.edit',compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $this->validate($request, [
            'vPageName' => 'required|string',
            'txContent' => 'required',
                ], [
            'vPageName.required' => 'The page name field is required.',
            'txContent.required' => 'The content field is required.',
        ]);
        try {
            $model = $this->getPage($uuid);
            $model->vPageName = $request->vPageName;
            $model->txContent = $request->txContent;
            $model->iUpdatedAt = time();
            if ($model->update()) {
                $request->session()->flash('success', $request->vPageName.' content has been Updated Successfully.');
            } else {
                $request->session()->flash('error', 'Fail to update '.$request->vTitle.' content.');
            }
            return redirect()->route('backend.content_page.show', ['content_page' => $uuid]);
        } catch (\Exception $ex) {
            $request->session()->flash('error', __('message.something_wrong'));
            return redirect()->route('backend.content_page.edit', ['content_page' => $uuid]);
        }
    }

    //Get page detail
    private function getPage($uuid) {
        $model = ContentPage::where(['vPageUuid' => $uuid])->first();
        if(empty($model)) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

}
