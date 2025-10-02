@extends('layouts.app')

@section('content')
	@auth
		<x-page-heading>Welcome page</x-page-heading>
	@endauth	

	@guest
		@include('partials.google-auth')
	@endauth

	<livewire:game-manager />
@endsection