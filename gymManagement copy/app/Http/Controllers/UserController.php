<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function verifying (Request $req){
        $newFields = $req->validate([
            'name' => ['required', 'min:3' , 'max:12' , Rule::unique('users', 'name')],
            'email' => ['required', "email" , Rule::unique('users', 'email')],
            'password' => ['required', 'min:8 , max: 16']
                                     
        ]);
      
        $newFields['password'] =bcrypt($newFields['password']);
        $newUser = User::create($newFields);
        auth()->login($newUser);
        Log::info($newFields['name']);

        return redirect('/')->with('success', 'jimCore account created successfully.')->with('userName', $newFields['name']);
    }


    public function logIn(Request $req){
        $fields = $req->validate([
            'name' => ['required'],
            'password' => ['required']

        ]);
        if (auth()->attempt(['name'=>$fields['name'] , 'password'=>$fields['password']])) {
            $req -> session()->regenerate();
            return redirect('/')->with('success', 'Logged in successfully.')->with('userName', $fields['name']);
             
        }          
        else { 
          $userName = User::where('name', $fields['name'])->first();
          if (! $userName){
            return back()->withErrors([
                'name' => 'The provided name does not match our records.'
            ]);
          }
          else {
            return back()->withErrors([
                'password' => 'The password is incorrect.'
            ]);
          }
        }
    }



    public function logOut (){
        auth()-> logout();
        return redirect('/')->with('logOutSuccess', "You have logged out!");
    }



}