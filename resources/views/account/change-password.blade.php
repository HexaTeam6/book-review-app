@extends('layouts.app')

@section('main')
<div class="container">

    <div class="container">
        <h2 style="margin-top: 40px; margin-bottom: 30px;">Change Password</h2>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @foreach ($errors->all() as $error)
        <div class="alert alert-danger">{{ $error }}</div>
    @endforeach

    <form action="{{ route('change.password.update') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" name="current_password" id="current_password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="new_password_confirmation">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
        </div>

        <div class="container">
            <button type="submit" class="btn btn-primary" style="margin-top: 40px;">Change Password</button>
        </div>
    </form>
</div>
@endsection
