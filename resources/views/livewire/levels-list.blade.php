<div id="levels=list" class="max-w-xl mx-auto text-gray-200 mb-10 overflow-auto">
    <div class="font-bold">levels</div>
    <ul class="levels list-none">
        @foreach($levels as $level)
        <li><a class="level" href="">{{$level->order}}</a></li>
        @endforeach
        <li><livewire:create-level /></li>
    </ul>
</div>