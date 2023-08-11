<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryAdminController extends Controller
{
    protected $setting;
    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('viewAny' , $this->setting);
        $category =  Category::create($request->except('image', '_token'));
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = Str::uuid() . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $path = 'images/' . $filename;
            $category->update(['image' => $path]);
        }
        return CategoryResource::make($category);
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $categoryadmin)
    {
        // dd($categoryadmin);
        $this->authorize('viewAny' , $this->setting);
        $id = $categoryadmin->id;
        $categoryadmin->update($request->except('image' , '_token'));
        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = Str::uuid() . $file->getClientOriginalName();
            $file->move(public_path('images') , $filename);
            $path = 'images/' . $filename;
            $categoryadmin->update(['image' => $path]);
        }
        $category = Category::find($id);
        return CategoryResource::make($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $categoryadmin)
    {
        // if (is_numeric($categoryadmin->id)) {
        //     category::where('parent' , $categoryadmin->id)->delete();
            // category::where('id' , $categoryadmin->id)->delete();
        // }
        $this->authorize('viewAny' , $this->setting);
        $categoryadmin->delete();
        return response()->json('deleted successfully');
    }
}
