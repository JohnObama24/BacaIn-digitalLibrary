<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function ShowLogin (){
      return view('auth.login');
    }

    public function showRegister (){
      return view('auth.register');
    }

    public function Login(Request $requset){
      $credentials = $requset->validate([
        "email"=>"required|string|email",
        "password"=>"required|string",
      ]);

      if (Auth::attempt($credentials)) {
        $requset->session()->regenerate();
       if(Auth::user()->role == "admin"){
          return redirect()->route('admin-dashboard');
        }else if(Auth::user()->role == "librarian"){
          return redirect()->route('librarian-dashboard');
       }else{
        return redirect()->route('user-dashboard');
      }
      };

      return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
      ])->onlyInput('email');
    }

    public function Register (Request $requset){

      $requset->validate([
        "name" => "required|string|max:255",
        "email" => "required|string|email|max:255|unique:users",
        "password" => "required|string|min:8|confirmed",
      ]);


      User::create([
        "name"=>$requset->name,
        "email"=>$requset->email,
        "password"=>bcrypt($requset->password),
        "role"=>"user",
      ]);

      return redirect()->route('login')->with('success','Register Success, Please Login');
    }

    public function Logout (Request $requset){
      auth::logout();
      $requset->session()->invalidate();
      $requset->session()->regenerateToken();
      return redirect("/");
    }


}
