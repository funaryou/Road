@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'w-full h-auto p-2 border-2 border-gray-300 rounded-lg']) !!}>
