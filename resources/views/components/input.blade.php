@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 
'bg-gray-50 trans border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 
focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 
dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500']) !!}>
