@extends('layouts.app')

@section('content')
    <x-close-link />

	<x-page-heading>{{ $title }}</x-page-heading>
    
    <div class="mx-auto overflow-x-auto shadow rounded-lg dark:bg-gray-800 max-w-[800px]" x-data="{ showAddCode: false }">
        
    <div id="add-code-component">
            <button 
                title="add code" 
                class="add-new-btn block" 
                @click="showAddCode = !showAddCode"
            >Add code</button>

            <div x-show="showAddCode" class="px-1">
                <form>
                    <input type="text" class="input-base">
                </form>
            </div>
        </div>

        <livewire:codes-list />
    </div>
@endsection