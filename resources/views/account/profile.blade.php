@extends('layouts.app')

@section('main')
<div class="card-header  text-white">
	Welcome, {{ Auth::user()->name }}
</div>
<div class="card-body">
	<div class="text-center mb-3">
		<img src="images/profile-img-1.jpg" class="img-fluid rounded-circle" alt="Luna John">
	</div>
	<div class="h5 text-center">
		<strong>{{ Auth::user()->name }} </strong>
		<p class="h6 mt-2 text-muted">5 Reviews</p>
	</div>
<li class="nav-item">
	<a href="{{ route('account.logout') }}">Logout</a>
</li>
<h1>Profile Page</h1>
@endsection
