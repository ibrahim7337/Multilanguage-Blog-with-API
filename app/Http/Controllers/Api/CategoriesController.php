<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index(){
        $categories = Category::paginate(15);
        return CategoryResource::collection($categories);
    }
    public function navbarCategories(){
        $categories = Category::with('children')->where('parent' , 0)->orWhere('parent' , null)->get();
        return CategoryCollection::make($categories);
    }
    public function indexPageCategoriesWithPosts(){
        $categories_with_posts = Category::with(['posts'=>function ($q){
            // $q->with('user')->limit(5);
            $q->with('category')->limit(5);
            // $q->limit(5);
        }])->get();
        return CategoryCollection::make($categories_with_posts);
    }
    public function show($id){
        $category = Category::where('id' , $id)->firstOrFail();
        return CategoryResource::make($category);
    }
}
