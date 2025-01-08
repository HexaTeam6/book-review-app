<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index(Request $request)
    {
        $reviews = Review::with('book', 'user')->orderBy('created_at', 'DESC');
        if (!empty($request->keyword)) {
            $reviews = $reviews->where('review', 'like', '%'.$request->keyword.'%');
        }
        $reviews = $reviews->paginate(10);
        
        if (Auth::check() && Auth::user()->role === 'admin') {
            return view('account.reviews.list', [
                'reviews' => $reviews
            ]);view('account.reviews.list');
        }

        return redirect()->route('account.myReviews');
    }

    public function myReviews(Request $request) {
        $userId = auth()->id();
    
        $reviews = Review::with('book')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC');
    
        if (!empty($request->keyword)) {
            $reviews = $reviews->where('review', 'like', '%' . $request->keyword . '%');
        }
    
        $reviews = $reviews->paginate(10);
    
        return view('account.my-reviews', [
            'reviews' => $reviews
        ]);
    }
    

    /**
     * Show the form for editing a specific review.
     */
    public function edit($id) {
        $review = Review::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    
        return view('account.reviews.edit', [
            'review' => $review,
        ]);
    }

    /**
     * Update the specified review in the database.
     */
public function updateReview($id, Request $request) {
    $review = Review::findOrFail($id);
    $validator = Validator::make($request->all(), [
        'status' => 'required'
    ]);

    if ($validator->fails()) {
        return redirect()->route('account.reviews.edit', $id)->withInput()->withErrors($validator);
    }

    $review->review = $request->review;
    $review->status = $request->status;
    $review->save();


    session()->flash('success', 'Review updated successfully.');
    return redirect()->route('account.myReviews');
}

    public function deleteReview(Request $request) {
        $review = Review::find($request->id);
    
        if ($review == null) {
            session()->flash('error', 'Review not found');
            return response()->json([
                'status' => false
            ]);
        } else {
            $review->delete();
            session()->flash('success', 'Review deleted successfully');
            return response()->json([
                'status' => true
            ]);
        }
    }
}
