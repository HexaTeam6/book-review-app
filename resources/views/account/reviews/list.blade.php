@extends('layouts.app')

@section('main')
<div class="container">
    <div class="row my-5">
        <div class="col-md-3">
            @include('layouts.sidebar')               
        </div>
        <div class="col-md-9">
            @include('layouts.message')
            <div class="card border-0 shadow">
                <div class="card-header text-white">
                    Reviews
                </div>
                <div class="card-body pb-0">
                    <div class="d-flex justify-content-end">
                        <form action="" method="GET">
                            <div class="d-flex">
                                <input type="text" class="form-control" value="{{ Request::get('keyword') }}" name="keyword" placeholder="Keyword">
                                <button type="submit" class="btn btn-primary ms-2">Search</button>
                                <a href="{{ route('account.reviews') }}" class="btn btn-secondary ms-2">Clear</a>
                            </div>
                        </form>
                    </div>          
                    <table class="table table-striped mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th>Book</th>
                                <th>Review</th>
                                <th>Rating</th>
                                <th>Created At</th>
                                <th>Status</th>                                  
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reviews as $review)
                            <tr>
                                <td>{{ $review->book->title }}</td>
                                <td>{{ $review->review }} <br>by <strong>{{ $review->user->name }}</strong></td>                                        
                                <td>{{ $review->rating }}</td>
                                <td>{{ \Carbon\Carbon::parse($review->created_at)->format('d M, Y') }}</td>
                                <td>
                                    <span class="{{ $review->status == 1 ? 'text-success' : 'text-danger' }}">
                                        {{ $review->status == 1 ? 'Active' : 'Block' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('account.reviews.edit', $review->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <a href="#" onclick="didDeleteReviewButtonTapped({{ $review->id }})" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </td>
                            </tr> 
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No reviews found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $reviews->links() }}              
                </div>
            </div>                
        </div>
    </div>       
</div>
@section('script')
<script>
    function didDeleteReviewButtonTapped(id) {
        if (confirm("Are you sure you want to delete this review?")) {
            $.ajax({
                url: '{{ route("account.reviews.deleteReview") }}',
                data: {id: id},
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status) {
                        window.location.href = '{{ route("account.reviews") }}';
                    } else {
                        alert("Failed to delete the review. Please try again.");
                    }
                }
            });
        }
    }
</script>
@endsection
@endsection
