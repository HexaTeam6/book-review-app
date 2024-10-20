@extends('layouts.app')

@section('main')
<div class="col-md-9">
    @include('layouts.message') <!-- Include the message alerts -->
    <div class="card border-0 shadow">
        <div class="card-header text-white">
            Profile
        </div>
        <div class="card-body">
            <!-- Display the logged-in user's profile picture -->
            <div class="text-center mb-3">
                @if (Auth::user()->image != "")
                    <img src="{{ asset('uploads/profile/thumb/'.Auth::user()->image) }}" class="img-fluid rounded-circle" alt="{{ Auth::user()->name }}">
                @endif
            </div>
            <div class="h5 text-center">
                <strong>{{ Auth::user()->name }}</strong>
                <p class="h6 mt-2 text-muted">5 Reviews</p>
            </div>

            <!-- Update Profile Form -->
            <form action="{{ route('account.updateProfile') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Name" />
                    @error('name')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" />
                    @error('email')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Profile Picture</label>
                    <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror">
                    @error('image')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                    @if (Auth::user()->image != "")
                        <img src="{{ asset('uploads/profile/thumb/'.Auth::user()->image) }}" class="img-fluid mt-4" alt="{{ Auth::user()->name }}">
                    @endif
                </div>

                <button type="submit" class="btn btn-primary mt-2">Update</button>
            </form>

            <!-- Logout Button -->
            <div class="mt-3">
                <a href="{{ route('account.logout') }}" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>

    <!-- Sidebar Navigation -->
    <div class="card border-0 shadow-lg mt-3">
        <div class="card-header text-white">
            Navigation
        </div>
        <div class="card-body sidebar">
            @include('layouts.sidebar') <!-- Include the sidebar for user navigation -->
        </div>
    </div>
</div>
@endsection
