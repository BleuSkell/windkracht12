<button {{ $attributes->merge(['type' => 'submit', 'class' => 'bg-white text-[#0e1142] px-4 py-2 rounded-lg mr-4 ease-in-out duration-150 hover:bg-gray-200']) }}>
    {{ $slot }}
</button>
