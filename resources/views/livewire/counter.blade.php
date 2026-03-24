<div class="p-6 bg-white rounded-xl shadow-lg flex flex-col items-center gap-4">
    <h3 class="text-lg font-semibold text-gray-700">Livewire Counter Test</h3>
    <div class="text-4xl font-bold text-primary">{{ $count }}</div>
    <button wire:click="increment" 
            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-opacity-90 transition transform active:scale-95">
        Increment
    </button>
</div>
