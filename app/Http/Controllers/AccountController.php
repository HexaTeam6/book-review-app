<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function register(){
        return view('account.register');
    }

    public function processRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed\min:5',
            'confirm_password' => 'required'
        ]);
        
        if ($validator->fails()) {
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
    }

    public function login(){
        return view('account.login');
    }

    public function profile() {
        $user = User::find(Auth::user()->id);
        return view('account.profile', [
            'user' => $user
        ]);

    }

    public function logout() {
        Auth::logout();
        return view('account.login');
    }

    public function updateProfile(Request $request) {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id.',id'
        ];
    
        if (!empty($request->image)) {
            $rules['image'] = 'image';
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id.',id'
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }
    
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
    
        return redirect()->route('account.profile')->with('success', 'Profile updated successfully.');
    }
}

