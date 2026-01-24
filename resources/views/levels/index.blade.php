@extends('layouts.app')

@section('content')
    <x-close-link />

	<x-page-heading>{{ $title }}</x-page-heading>
    
    <div x-data="{ showAddCode: false }">
        <button 
            title="add code" 
            class="add-new-btn block" 
            @click="showAddCode = !showAddCode"
        >Add code</button>

        <div x-show="showAddCode">
            <form>
                <input type="text" class="input-base">
            </form>    
        </div>
    </div>
@endsection