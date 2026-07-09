<div class="relative custom-dropdown {{ $class ?? '' }}" id="{{ $id ?? $name }}-dropdown">
    <input type="hidden" name="{{ $name }}" id="{{ $id ?? $name }}-input" value="{{ $selected }}">
    <button type="button" 
            class="dropdown-trigger w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-[11px] font-black uppercase tracking-wider focus:outline-none focus:ring-4 focus:ring-black/5 focus:border-black transition-all shadow-sm flex items-center justify-between group">
        <span class="dropdown-label text-left truncate">
            @php
                $selectedOption = $options->firstWhere('id', $selected);
            @endphp
            {{ $selectedOption ? $selectedOption->nama_kategori : ($placeholder ?? 'PILIH OPSI') }}
        </span>
        <i class="fas fa-chevron-down text-[9px] arrow-rotate text-gray-400"></i>
    </button>
    
    <div class="dropdown-menu dropdown-hidden absolute {{ $align ?? 'right-0' }} mt-2 min-w-[200px] bg-white border border-gray-100 rounded-2xl shadow-2xl z-[150] overflow-hidden transform origin-top dropdown-animate">
        <div class="py-1">
            <button type="button" data-val="" data-label="{{ $placeholder ?? 'SEMUA' }}"
                    class="dropdown-item w-full text-left px-5 py-3.5 text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:bg-gray-50 border-b border-gray-50 transition-colors {{ !$selected ? ($activeBg ?? 'bg-black') . ' text-white' : '' }}">
                {{ $placeholder ?? 'SEMUA' }}
            </button>
            @foreach($options as $opt)
                <button type="button" data-val="{{ $opt->id }}" data-label="{{ $opt->nama_kategori }}"
                        class="dropdown-item w-full text-left px-5 py-3.5 text-[10px] font-bold uppercase tracking-widest text-gray-500 hover:bg-gray-50 border-b border-gray-50 last:border-0 transition-colors {{ $selected == $opt->id ? ($activeBg ?? 'bg-black') . ' text-white' : '' }}">
                    {{ $opt->nama_kategori }}
                </button>
            @endforeach
        </div>
    </div>
</div>
