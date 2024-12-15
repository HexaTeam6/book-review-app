<?php
namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;

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
    
        if ($validator->fails()) {
            return redirect()->route('account.register')->withErrors($validator)->withInput();
        }

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

    public function updateProfile(Request $request) {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id.',id'
        ];
    
        if ($request->hasFile('image')) {
            $rules['image'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }
    
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageExtension = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $imageExtension;
        
            // Ensure directories exist
            $profilePath = public_path('uploads/profile');
            $thumbPath = public_path('uploads/profile/thumb');
            
            if (!File::exists($profilePath)) {
                File::makeDirectory($profilePath, 0755, true);
            }
            
            if (!File::exists($thumbPath)) {
                File::makeDirectory($thumbPath, 0755, true);
            }        
    
            // Delete old image if exists
            if (!empty($user->image)) {
                File::delete(public_path('uploads/profile/' . $user->image));
                File::delete(public_path('uploads/profile/thumb/' . $user->image));
            }
    
            // Move and save new image
            $image->move(public_path('uploads/profile'), $imageName);
            $user->image = $imageName;
    
            // Create thumbnail
            $manager = new ImageManager(Driver::class);
            $imageObj = $manager->read(public_path('uploads/profile/' . $imageName));
            $imageObj->cover(150, 150);
            $imageObj->save(public_path('uploads/profile/thumb/' . $imageName));
        }
    
        $user->save();
    
        return redirect()->route('account.profile')->with('success', 'Profile updated successfully.');
    }
    


    public function logout() {
        Auth::logout();
        return redirect()->route('account.login');  // Redirect after logout
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
