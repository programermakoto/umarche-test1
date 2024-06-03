<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LifeCycleTestController extends Controller
{
    public function showServiceContainer(){
        // return view("tests.service-container");
        app()->bind("life-cycle-test",function(){
            return "ライフサイクルテスト";
        });
        $test = app()->make("life-cycle-test");
        dd($test,app());
    }
}
