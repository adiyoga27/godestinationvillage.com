<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if (request()->ajax()) {
            return DataTables::of(ReviewService::all())
            ->addColumn('action', function($review){
                return view('datatable._action_dinamyc', [
                    'model'           => $review,
                    'delete'          => route('review.destroy', $review->id),
                    'url'             => [
                        'Edit'            => route('review.edit', $review->id),
                    ],
                    'confirm_message' =>  'Anda yakin untuk menghapus data "' . $review->name . '" ?',
                    'padding'         => '85px',
                ]);
            })
            ->editColumn('is_active', function($review){
                if($review->is_active == 0)
                    return "<label class='badge badge-gradient-danger'>Tidak Aktif</label>";
                else
                    return "<label class='badge badge-gradient-success'>Aktif</label>";
            })->editColumn('created_at', function($admin){
                return date('Y-m-d', strtotime($admin->created_at));
            })->rawColumns(['action', 'is_active'])->toJson();
        }

        $html = $htmlBuilder
              ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'reviewable'=>false, 'searchable'=>false])
              ->addColumn(['data' => 'rownum', 'name'=>'rownum', 'title'=>'No','searchable'=>false])
              ->addColumn(['data' => 'inv', 'name' => 'inv', 'title' => 'No. Invoice' ])
              ->addColumn(['data' => 'rating', 'name' => 'rating', 'title' => 'Rating' ])
              ->addColumn(['data' => 'job', 'name' => 'job', 'title' => 'Job' ])
              ->addColumn(['data' => 'name', 'name' => 'job', 'title' => 'Name' ])
              ->addColumn(['data' => 'comment', 'name' => 'job', 'title' => 'Comment' ])
              ->addColumn(['data' => 'avatar', 'name' => 'job', 'title' => 'Avatar' ])
              ->addColumn(['data' => 'is_active', 'name' => 'is_active', 'title' => 'Status' ])

          
              ->parameters([
                'scrollX' => true,
                'review' => [3, 'desc']
              ]);

        return view('backend.review.index')->with(compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.review.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = ReviewService::create($request->except('_token'));

        if ($result) 
            return redirect(route('review.index'))->with('status', 'Successfully created');
        else
            return redirect(route('review.create'))->with('error', 'Failed to create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $review = ReviewService::find($id);

        return view('backend.review.edit')->with(compact(
            'review'
        ));
    }

    public function update($id, Request $request)
    {
        $result = ReviewService::update($id, $request->except('_token'));
        
        if ($result) 
            return redirect(route('review.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error','Failed to update');
    }

    public function destroy($id)
    {  
        $result = ReviewService::destroy($id);

        if ($result)
            return redirect(route('review.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('review.index'))->with('error','Failed to delete');
    }

   
}
