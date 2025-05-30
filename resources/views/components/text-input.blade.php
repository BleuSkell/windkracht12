@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-[#5b9fe3] bg-white text-[#0e1142] focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>
