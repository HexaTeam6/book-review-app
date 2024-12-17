@extends('layouts.app')

@section('main')
<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')               
        </div>
        <div class="col-md-9">
            <div class="card border-0 shadow">
                <div class="card-header  text-white">
                    Reviews
                </div>
                <div class="card-body"> 
                    <form action="{{ route('account.reviews.updateReview', $review->id) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="review" class="form-label">Review</label>
                            <textarea name="review" id="review" class="form-control" disabled>{{ old('review', $review->review) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="1" {{ ($review->status == 1) ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ ($review->status == 0) ? 'selected' : '' }}>Block</option>
                            </select>
                            @error('status')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="btn btn-primary mt-2">Update</button>
                    </form>         
                </div>
            </div>                
        </div>
    </div>       
</div>
@endsection