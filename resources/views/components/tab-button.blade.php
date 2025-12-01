@props(['active' => false, 'label' => ''])

<button 
    class="px-6 py-2 rounded-full font-semibold transition 
    {{ $active ? 'bg-blue-600 text-white shadow' : 'bg-gray-200 text-gray-600 hover:bg-gray-300' }}">
    {{ $label }}
</button>
