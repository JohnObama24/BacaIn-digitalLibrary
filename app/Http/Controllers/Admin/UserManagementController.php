<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(){
        $users = User::all();
        return view("admin.ManagementUser.index",compact("users"));
    }


    public function create()
    {
        $users = User::all();
        return view("admin.ManagementUser.create", compact("users"));
    }

    public function store(Request $request){
         $request->validate([
            "name"=>"required|string|max:255",
            "email"=>"required|email|unique:users,email,",
            "password"=> "Required|string|min:8|confirmed",
            "role"=>"required|in:admin,user,librarian",
         ]);

         User::create([
            "name" =>$request->name,
            "email" => $request->email,
            "password"=> bcrypt($request->password),
            "role"=> $request->role,
         ]);

          return redirect()->route('user.index')->with('success', 'user berhasil ditambahkan');
    }

    public function edit ($id){
        $user = User::findOrFail($id);
        return view("admin.ManagementUser.edit",compact("user"));
    }

    public function update (Request $request, $id ){

        dd($request->all());

        $request->validate([
            "name"=>"required|string|max:255",
            "email"=>"required|email|unique:users,email,".$id,
            "password"=> "Required|string|min:8|confirmed",
            "role"=>"required|in:admin,user,librarian",
            "status"=>"required|in:Aktif,Nonaktif",
        ]);
        $user = User::findOrFail($id);

        $user->update([
            "name"=>$request->name,
            "email"=>$request->email,
            "role"=>$request->role,
            "status"=>$request->status,
            "password"=> bcrypt($request->password),
        ]);

        return redirect()->route("user.index")->with("success","User berhasil diubah");
    }

    public function destroy (Request $request, $id ){
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route("user.index'")->with("success","User deleted successfully");
    }
}
