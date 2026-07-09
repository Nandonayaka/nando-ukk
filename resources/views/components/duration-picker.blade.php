@props([
    'name' => 'durasi_tipe',
    'id' => 'durasiSelect',
    'placeholder' => 'Pilih Jangka Waktu...',
    'value' => '',
    'options' => [
        ['value' => '3', 'label' => '3 Hari (Kilat)'],
        ['value' => '7', 'label' => '7 Hari (Standar)'],
        ['value' => '12', 'label' => '12 Hari (Maksimal)'],
        ['value' => 'custom', 'label' => 'Custom (Pilih Sendiri...)']
    ]
])

<div class="duration-picker-container relative w-full" x-data="{ 
    open: false, 
    selectedLabel: '{{ $placeholder }}', 
    selectedValue: '{{ $value }}',
    select(val, label) {
        this.selectedValue = val;
        this.selectedLabel = label;
        this.open = false;
        $refs.hiddenInput.value = val;
        // Trigger change event for parent scripts
        $refs.hiddenInput.dispatchEvent(new Event('change'));
        if (typeof handleDurationChange === 'function') {
            handleDurationChange(val);
        }
    }
}" @click.outside="open = false">
    
    <!-- Hidden Input for Form -->
    <input type="hidden" name="{{ $name }}" id="{{ $id }}" x-ref="hiddenInput" value="{{ $value }}" required>

    <!-- Trigger Button -->
    <button type="button" 
        @click="open = !open"
        class="w-full bg-white border border-gray-100 rounded-2xl px-6 py-5 flex items-center justify-between group transition-all duration-300 shadow-sm hover:shadow-md hover:border-black focus:outline-none">
        
        <div class="flex items-center gap-4">
            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:text-black transition-colors">
                <i class="fas fa-history text-sm transition-transform duration-500" :class="open ? 'rotate-[-45deg]' : ''"></i>
            </div>
            <span class="text-sm font-bold text-gray-900 tracking-tight" x-text="selectedLabel"></span>
        </div>
        
        <i class="fas fa-chevron-down text-[10px] text-gray-300 transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-2 scale-95"
        class="absolute z-[110] left-0 right-0 mt-3 bg-white border border-gray-100 rounded-[1.5rem] shadow-2xl overflow-hidden"
        style="display: none;">
        
        <div class="px-5 py-3 bg-gray-50/50 border-b border-gray-50">
            <span class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">{{ $placeholder }}</span>
        </div>

        <div class="py-2 max-h-[240px] overflow-y-auto custom-scrollbar">
            @foreach($options as $opt)
                <button type="button" 
                    @click="select('{{ $opt['value'] }}', '{{ $opt['label'] }}')"
                    class="w-full text-left px-5 py-4 text-xs font-bold text-gray-800 hover:bg-gray-50 transition-colors flex items-center justify-between group">
                    <span>{{ $opt['label'] }}</span>
                    <i class="fas fa-check text-[8px] text-black opacity-0 transition-opacity" :class="selectedValue == '{{ $opt['value'] }}' ? 'opacity-100' : ''"></i>
                </button>
            @endforeach
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #d1d5db;
    }
</style>
