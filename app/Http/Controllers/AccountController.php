<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function register() {
        return view('account.register');
    }

    public function processRegister(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
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
        return view('account.profile');
    }

    public function logout() {
        Auth::logout();
        return view('account.login');
    }

    public function myReviews(Request $request) {
        $reviews = Review::with('book')->orderBy('created_at', 'DESC');
        if (!empty($request->keyword)){
            $reviews = $reviews->where('review', 'like', '%'.$request->keyword.'%');
        }
        $reviews = $reviews->paginate(10);

        return view('account.my-reviews', [
            'reviews' => $reviews
        ]);
    }
}
