<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Traits\UploadImage;
use App\Models\Category;
use App\Models\Post;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PostsController extends Controller
{
    use UploadImage;
    protected $postmodel;

    public function __construct(Post $post) {
        $this->postmodel = $post;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.posts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        if (count($categories) > 0) {
            return view('dashboard.posts.add' , compact('categories'));
        }
        abort(404);
    }

    public function getPostsDatatable()
    {
        $data = Post::select('*')->with('category');
        return  DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {

                if(auth()->user()->can('update', $row)){
                // if (auth()->user()->can('update' , $this->postmodel)) {
                return $btn = '
                        <a href="' . Route('dashboard.posts.edit', $row->id) . '"  class="edit btn btn-success btn-sm" ><i class="fa fa-edit"></i></a>
                        <a id="deleteBtn" data-id="' . $row->id . '" class="edit btn btn-danger btn-sm"  data-toggle="modal" data-target="#deletemodal"><i class="fa fa-trash"></i></a>';
                }else{
                    return;
                }
            })

            ->addColumn('category_name', function ($row) {
                return  $row->category->translate(app()->getLocale())->title;
            })


            ->addColumn('title', function ($row) {
                return $row->translate(app()->getLocale())->title;
            })
            ->rawColumns(['action', 'title' , 'category_name'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create' , $this->postmodel);
        $post = Post::create($request->except('image','_token'));
        $post->update(['user_id' => auth()->user()->id]);
        if ($request->has('image')) {
           $post->update(['image'=>$this->upload($request->image)]);
        }
       return redirect()->route('dashboard.posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $this->authorize('update' , $post);
        $categories = Category::all();
        return view('dashboard.posts.edit' , compact('post' , 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update' , $post);
        $post->update($request->except('image' , '_token'));
        $post->update(['user_id' => auth()->user()->id]);
        if ($request->has('image')) {
            $post->update(['image'=>$this->upload($request->image)]);
         }
        return redirect()->route('dashboard.posts.edit' , $post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function delete(Request $request)
    {
        $this->authorize('delete' , $this->postmodel->find($request->id));
        if (is_numeric($request->id)) {
            Post::where('id' , $request->id)->delete();
        }
        return redirect()->route('dashboard.posts.index');
    }
}