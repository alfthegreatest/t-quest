@extends('layouts.app')

@section('content')
    <x-close-link />

	<x-page-heading>{{ $title }}</x-page-heading>
    
    <div class="mx-auto overflow-x-auto shadow rounded-lg dark:bg-gray-800 max-w-[800px]" x-data="{ showAddCode: false }">
        
    <div id="add-code-component">
        <livewire:add-code :levelId="$levelId" />

        <livewire:codes-list :levelId="$levelId" />
    </div>
@endsection