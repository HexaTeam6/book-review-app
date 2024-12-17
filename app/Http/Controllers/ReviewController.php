<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Display a listing of reviews.
     */
    public function index(Request $request)
    {
        $reviews = Review::with('book', 'user')->orderBy('created_at', 'DESC');

        // Filter by keyword
        if (!empty($request->keyword)) {
            $reviews = $reviews->where('review', 'like', '%' . $request->keyword . '%');
        }

        $reviews = $reviews->paginate(10);

        return view('account.reviews.list', [
            'reviews' => $reviews
        ]);
    }

    /**
     * Show the form for editing a specific review.
     */
    public function edit($id)
    {
        $review = Review::findOrFail($id);

        return view('account.reviews.edit', [
            'review' => $review
        ]);
    }

    /**
     * Update the specified review in the database.
     */
    public function updateReview($id, Request $request)
    {
        $review = Review::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:0,1', // Ensure status is either 0 or 1
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('account.reviews.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }

        // Update review status
        $review->status = $request->status;
        $review->save();

        // Flash success message
        session()->flash('success', 'Review updated successfully.');

        return redirect()->route('account.reviews');
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
