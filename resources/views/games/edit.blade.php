@extends('layouts.app')

@section('content')
    <x-back-link :href="route('admin.games.index')" :text="'Games'"/>
    
    <x-page-heading>Game editing</x-page-heading>
    
    <livewire:levels-list :gameId="$game->id" />
    <livewire:edit-level />
    <livewire:game-editor :game="$game" />
@endsection