<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:owners'],
            // unique:ownersでowners変数で定義されたownerDBの中でメールアドレスを被らないようにしている
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        // バリデーションが成功したら、送信されたデータをownersテーブルに登録する
        Owner::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // 登録し終えるとadmin.owners.indexに戻り登録した情報が追記されているとOK!
        return redirect()->route('admin.owners.index')->with("message","オーナー登録を実施しました");
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
