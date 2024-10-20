<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AccountController extends Controller
{
/*************  ✨ Codeium Command ⭐  *************/
    /**
     * Shows the registration form for a new user
     *
     * @return \Illuminate\Contracts\View\View
     */
/******  f2827ba5-1b79-40a0-a015-79434b36481a  *******/
    public function register() {
        return view('account.register');
    }

    public function processRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:5',
            'password_confirmation' => 'required'
        ]);
    
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('account.login')->with('success', 'You have registered successfully.');
    }

    public function login() {
        return view('account.login');
    }

    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        if ($validator->fails()) {
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }

        $isLoginValid = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        if ($isLoginValid) {
            return redirect()->route('account.profile');
        } else {
            return redirect()->route('account.login')->with('error', 'Either email/password is incorrect.');
        }
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
    
        $validator = Validator::make($request->all(), $rules);

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

        if (!empty($request->image)) {
            $image = $request->image;
            $imageExtension = $image->getClientOriginalExtension();
            $imageName = time().'.'.$imageExtension;
            $image->move(public_path('uploads/profile'), $imageName);
        
            $user->image = $imageName;
            $user->save();
        }

        if (!empty($request->image)) {
            $image = $request->image;
            $imageExtension = $image->getClientOriginalExtension();
            $imageName = time().'.'.$imageExtension;
            $image->move(public_path('uploads/profile'), $imageName);
        
            $user->image = $imageName;
            $user->save();
        
            $manager = new ImageManager(Driver::class);
            $image = $manager->read(public_path('uploads/profile/'.$imageName));
        
            $image->cover(150, 150);
            $image->save(public_path('uploads/profile/thumb/'.$imageName));
        }

        if (!empty($request->image)) {
            // delete old image
            File::delete(public_path('uploads/profile/'.$user->image));
            File::delete(public_path('uploads/profile/thumb/'.$user->image));

    
        return redirect()->route('account.profile')->with('success', 'Profile updated successfully.');
    }
}
}