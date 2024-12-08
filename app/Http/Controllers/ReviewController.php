<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index(Request $request) {
        $reviews = Review::with('book', 'user')->orderBy('created_at', 'DESC');
        if (!empty($request->keyword)){
            $reviews = $reviews->where('review', 'like', '%'.$request->keyword.'%');
        }
        $reviews = $reviews->paginate(10);

        return view('account.reviews.list', [
            'reviews' => $reviews
        ]);
    }

    public function edit($id) {
        $review = Review::findOrFail($id);
        return view('account.reviews.edit', [
            'review' => $review
        ]);
    }

    public function updateReview($id, Request $request) {
        $review = Review::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'status' => 'required'
        ]);

        if($validator->fails()) {
            return redirect()->route('account.reviews.edit', $id)->withInput()->withErrors();
        }

        $review->status = $request->status;
        $review->save();

        session()->flash('success', 'Review update successfully.');
        return redirect()->route('account.reviews');
    }
}
