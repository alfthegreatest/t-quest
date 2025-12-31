@extends('layouts.app')

@section('content')
	@auth
		<x-page-heading>Welcome page</x-page-heading>
	@endauth	

	<livewire:game-manager />
@endsection