@props(['name', 'title', 'maxWidth' => 'xl'])

<div x-data="{ show: false }" x-on:open-modal.window="$event.detail === '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="show = false" x-on:keydown.escape.window="show = false" x-show="show"
    class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">

    <!-- Overlay -->
    <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false">
    </div>

    <!-- Modal Content -->
    <div class="flex min-h-screen items-center justify-center p-4">
        <div x-show="show" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="w-full max-w-{{ $maxWidth }} bg-white rounded-xl shadow-2xl transform transition-all relative"
            @click.stop>

            <!-- Modal Header -->
            <div
                class="flex items-center justify-between px-6 py-4 border-b bg-primary-blue rounded-t-xl">
                <h3 class="text-xl font-bold text-white">{{ $title }}</h3>
                <button type="button" @click="show = false"
                    class="text-white hover:text-gray-200 transition focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>