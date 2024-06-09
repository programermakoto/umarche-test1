<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class OwnersController extends Controller
{
    //adminからしかアクセス不可
    public function __construct()
    {
        $this->middleware("auth:admin");
    }
    // admin/dashboardへ
    public function index()
    {
        // $data_now = Carbon::now();

        // echo $data_now;

        // $e_all = Owner::all(); 

        // $q_get = DB::table("owners")->select("name","created_at")->get();

        // $q_first = DB::table("owners")->select("name")->first();

        // $c_test = collect(["name" => "test"]);

        // dd($e_all,$q_get,$q_first,$c_test);

        // dd("オーナー登録一覧");

        $owners = Owner::select("name","email","created_at")->get(); 

        return view("admin.owners.index",compact("owners"));
    }

    public function create()
    {
        return view("admin.owners.create");
    }

    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }
    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
