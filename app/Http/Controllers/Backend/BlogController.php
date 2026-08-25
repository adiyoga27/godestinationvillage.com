<?php
namespace App\Http\Controllers\Backend;
use App\Models\Blog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Blog\BlogCreateRequest;
use App\Http\Requests\blog\BlogPostCreateRequest;
use App\Http\Requests\blog\BlogPostUpdateRequest;
use App\Services\BlogService;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Builder;
use Session;
use Yajra\DataTables\Facades\DataTables;
class BlogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, Builder $htmlBuilder)
    {
        if (request()->ajax()) {
            $query = BlogService::all();
            $role = Auth::user()->role_id ;
            if ($role == 2) {
                $query->where('post.post_author', Auth::user()->id);
            }
            return DataTables::of($query)
            ->addColumn('action', function($post){
                return view('datatable._action_dinamyc', [
                    'model'           => $post,
                    'delete'          => route('news.destroy', $post->id),
                    'url'             => [
                        'Edit'            => route('news.edit', $post->id),
                    ],
                    'confirm_message' =>  'Anda yakin untuk menghapus data "' . $post->post_title . '" ?',
                    'padding'         => '85px',
                ]);
            })
            ->editColumn('post_content', function($post){
                    return  \Illuminate\Support\Str::limit(strip_tags($post->post_content), 150, $end='...');
               
            }) 
        
            ->editColumn('isPublished', function($post){
                if($post->isPublished == 0)
                    return "<label class='badge badge-gradient-danger'>Tidak Aktif</label>";
                else
                    return "<label class='badge badge-gradient-success'>Aktif</label>";
            })
            ->editColumn('created_at', function($post){
                return $post->created_at ? date('Y-m-d H:i', strtotime($post->created_at)) : '-';
            })->rawColumns(['action', 'isPublished'])->toJson();
        }
        $html = $htmlBuilder
              ->addColumn(['data' => 'action', 'name'=>'action', 'title'=>'', 'orderable'=>false, 'searchable'=>false])
              ->addColumn(['data' => 'rownum', 'name'=>'rownum', 'title'=>'No','searchable'=>false])
              ->addColumn(['data' => 'post_title', 'name' => 'post_title', 'title' => 'Judul' ])
              ->addColumn(['data' => 'post_content', 'name' => 'post_content', 'title' => 'Isi' ])
              ->addColumn(['data' => 'isPublished', 'name' => 'isPublished', 'title' => 'Status' ])
              ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Dibuat' ])
              ->parameters([
                'scrollX' => true,
                'order' => [5, 'desc'],
              ]);
        return view('backend.blog.index')->with(compact('html'));
    }
    public function create()
    {
        return view('backend.blog.create');
    }
    public function store(BlogPostCreateRequest $request)
    {
        $result = BlogService::create($request->except('_token'));
        if ($result && !($result instanceof \Throwable))
            return redirect(route('news.index'))->with('status', 'Successfully created');
        else
            return redirect(route('news.create'))->with('error', $result instanceof \Throwable ? 'Gagal menyimpan: ' . $result->getMessage() : 'Failed to create');
    }
    public function edit($id)
    {
        $blog = BlogService::find($id);
        return view('backend.blog.edit')->with(compact(
            'blog'
        ));
    }
    public function update($id, BlogPostUpdateRequest $request)
    {
        $result = BlogService::update($id, $request->except('_token'));
        if ($result && !($result instanceof \Throwable))
            return redirect(route('news.index'))->with('status', 'Successfully updated');
        else
            return back()->with('error', $result instanceof \Throwable ? 'Gagal memperbarui: ' . $result->getMessage() : 'Failed to update');
    }
    public function destroy($id)
    {  
        $result = BlogService::destroy($id);
        if ($result)
            return redirect(route('news.index'))->with('status', 'Successfully deleted');
        else
            return redirect(route('news.index'))->with('error','Failed to delete');
    }

    public function uploadImage(Request $request)
    {
        $file = $request->file('file');
        if ($file) {
            $path = $file->store('blog/images', 'public');
            return response()->json(['location' => asset('storage/' . $path)]);
        }
        return response()->json(['error' => 'No file uploaded'], 400);
    }
}