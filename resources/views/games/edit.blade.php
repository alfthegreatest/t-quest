@extends('layouts.app')

@section('content')
    <x-page-heading>Game editing</x-page-heading>
    
    <livewire:levels-list :gameId="$game->id" />    
    <livewire:game-editor :game="$game" />
@endsection