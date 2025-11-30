<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(){
        $users = User::all();
        return view("admin.users.index",compact("users"));
    }

  

    public function edit ($id){
        $user = User::findOrFail($id);
        return view("admin.users.edit",compact("user"));
    }

    public function update (Request $request, $id ){
        $request->validate([
            "name"=>"required|string|max:255",
            "email"=>"required|email|unique:users,email,".$id,
            "role"=>"required|in:admin,user,librarian",
        ]);
        $user = User::findOrFail($id);

        $user->update([
            "name"=>$request->name,
            "email"=>$request->email,
            "role"=>$request->role,
        ]);

        return redirect()->route("")->with("success","User updated successfully");
    }

    public function destroy (Request $request, $id ){
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route("admin.users.index")->with("success","User deleted successfully");
    }
}
