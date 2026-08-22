<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\CustomImage;
use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class SliderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request, Builder $htmlBuilder)
    {
        if (request()->ajax()) {
            \Illuminate\Support\Facades\DB::statement('set @rownum=0');
            return DataTables::of(
                Slider::query()->select([
                    \Illuminate\Support\Facades\DB::raw('@rownum := @rownum + 1 AS rownum'),
                    'id',
                    'title',
                    'title_id',
                    'desc',
                    'img',
                    'created_at',
                ])
            )
                ->addColumn('action', function ($slider) {
                    return view('datatable._action_dinamyc', [
                        'model'           => $slider,
                        'delete'          => route('slider.destroy', $slider->id),
                        'url'             => [
                            'Edit' => route('slider.edit', $slider->id),
                        ],
                        'confirm_message' => 'Anda yakin untuk menghapus data "' . $slider->title . '" ?',
                        'padding'         => '85px',
                    ]);
                })
                ->editColumn('img', function ($slider) {
                    $src = asset('storage/sliders/' . $slider->img);
                    return "<img src='{$src}' style='width:140px;height:70px;object-fit:cover;border-radius:8px;' onerror=\"this.style.display='none'\">";
                })
                ->editColumn('created_at', function ($slider) {
                    return date('Y-m-d', strtotime($slider->created_at));
                })
                ->rawColumns(['action', 'img'])
                ->toJson();
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => '', 'orderable' => false, 'searchable' => false])
            ->addColumn(['data' => 'rownum', 'name' => 'rownum', 'title' => 'No', 'searchable' => false])
            ->addColumn(['data' => 'img', 'name' => 'img', 'title' => 'Gambar', 'orderable' => false, 'searchable' => false])
            ->addColumn(['data' => 'title', 'name' => 'title', 'title' => 'Judul' ])
            ->addColumn(['data' => 'title_id', 'name' => 'title_id', 'title' => 'Judul (ID)' ])
            ->addColumn(['data' => 'desc', 'name' => 'desc', 'title' => 'Deskripsi' ])
            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Dibuat' ])
            ->parameters([
                'scrollX' => true,
                'order' => [5, 'asc'],
            ]);

        return view('backend.slider.index')->with(compact('html'));
    }

    public function create()
    {
        return view('backend.slider.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:50',
            'desc' => 'nullable',
            'title_id' => 'nullable|max:50',
            'desc_id' => 'nullable',
            'img' => 'required|image|max:10240',
        ]);

        $upload = CustomImage::storeFile($request->file('img'), 'sliders');
        $validated['img'] = $upload['name'];

        Slider::create($validated);

        return redirect(route('slider.index'))->with('status', 'Slider berhasil ditambahkan');
    }

    public function edit($id)
    {
        $slider = Slider::findOrFail($id);

        return view('backend.slider.edit')->with(compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|max:50',
            'desc' => 'nullable',
            'title_id' => 'nullable|max:50',
            'desc_id' => 'nullable',
            'img' => 'nullable|image|max:10240',
        ]);

        if ($request->hasFile('img')) {
            $upload = CustomImage::storeFile($request->file('img'), 'sliders');
            $validated['img'] = $upload['name'];
        }

        $slider->update($validated);

        return redirect(route('slider.index'))->with('status', 'Slider berhasil diperbarui');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->delete();

        return redirect(route('slider.index'))->with('status', 'Slider berhasil dihapus');
    }
}