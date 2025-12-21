@props(['field', 'fieldLabel'])

<label for="{{ $field }}" class="absolute left-3 top-1 text-gray-400 text-sm transition-all 
        peer-placeholder-shown:top-5 peer-placeholder-shown:text-gray-500 
        peer-placeholder-shown:text-base 
        peer-focus:top-0 peer-focus:text-sm peer-focus:text-blue-400">
    {{ $fieldLabel }} <x-field-notification field="{{ $field }}" />
</label>