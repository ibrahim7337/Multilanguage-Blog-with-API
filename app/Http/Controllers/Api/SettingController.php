<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(Request $request){
        $settings = SettingResource::make(Setting::checkSettings());
        return response()->json(['data'=>$settings , 'error'=>''] , 200);
    }
}