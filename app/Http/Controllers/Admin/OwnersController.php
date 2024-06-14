<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Illuminate\Support\Facades\Log;
use App\Models\Owner;
use App\Models\shop;

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

        $owners = Owner::select("id", "name", "email", "created_at")->paginate(3);

        return view("admin.owners.index", compact("owners"));
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
        try{
            DB::transaction(function() use($request){
                $owner = Owner::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                ]);
                shop::create([
                    "owner_id"=>$owner->id,
                    "name"=>"店舗名",
                    "information"=>"",
                    "filename"=>"",
                    "is_selling"=>true
                ]);
            });
        }
        catch(Throwable $e){
            Log::error($e);
            throw $e;
        }
        // バリデーションが成功したら、送信されたデータをownersテーブルに登録する
       
        // 登録し終えるとadmin.owners.indexに戻り登録した情報が追記されているとOK!
        return redirect()->route('admin.owners.index')->with(["message"=>"オーナー登録を実施しました","status"=>"info"]);
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $owner = Owner::findOrFail($id);
        // dd($owner);
        return view("admin.owners.edit", compact("owner"));

    }
    public function update(Request $request, $id)
    {
        $owner = Owner::findOrFail($id);
        $owner->name = $request->name;
        $owner->email = $request->email;
        $owner->password = $request->password;
        $owner->save();

        // 登録し終えるとadmin.owners.indexに戻り登録した情報が追記されているとOK!
        return redirect()->route('admin.owners.index')->with(["message"=>"オーナー登録を実施しました","status"=>"info"]);
    }


    public function destroy($id)
    {
        // dd("削除処理");
        Owner::findOrFail($id)->delete();

        return redirect()->route("admin.owners.index")->with(["message"=>"オーナー情報を削除しました","status"=>"alert"]);
    }
    public function expiredOwnerIndex(){

        $expiredOwners = Owner::onlyTrashed()->get();
        
        return view("admin.expired-owners",compact("expiredOwners"));
        
        }
        
        public function expiredOwnerDestroy($id){
        
        Owner::onlyTrashed()->findOrFail($id)->forceDelete();
        
        return redirect()->route("admin.expired-owners.index");
        
        }
}


