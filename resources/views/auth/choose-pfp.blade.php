@extends('books.layout')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12">
    <div class="max-w-4xl w-full">
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-black tracking-tight mb-4 uppercase">Pilih Foto Profil Anda</h1>
            <p class="text-gray-400 text-sm font-medium tracking-[0.2em] mb-2">Tentukan foto profil Anda untuk memulai</p>
            <p class="text-black font-black text-xs tracking-[0.3em] uppercase">PustakaKU</p>
        </div>

        <form action="{{ route('pfp.update') }}" method="POST">
            @csrf
            <div class="relative h-[320px] md:h-[450px] flex items-center justify-center">
                <!-- Navigation Arrows -->
                <button type="button" onclick="changeAvatar(-1)" class="absolute left-0 md:left-10 w-14 h-14 bg-white rounded-full shadow-2xl flex items-center justify-center z-50 border border-gray-100 hover:scale-110 active:scale-95 transition-all text-black hover:bg-gray-50">
                    <i class="fas fa-arrow-left text-xs"></i>
                </button>
                <button type="button" onclick="changeAvatar(1)" class="absolute right-0 md:right-10 w-14 h-14 bg-white rounded-full shadow-2xl flex items-center justify-center z-50 border border-gray-100 hover:scale-110 active:scale-95 transition-all text-black hover:bg-gray-50">
                    <i class="fas fa-arrow-right text-xs"></i>
                </button>

                <!-- Fading Container -->
                <div class="relative w-64 h-64 md:w-80 md:h-80">
                    @php
                        $avatars = ['pfp-m-1.png', 'pfp-m-2.png', 'pfp-m-3.png', 'pfp-f-1.png', 'pfp-f-2.png', 'pfp-f-3.png'];
                    @endphp

                    @foreach($avatars as $index => $avatar)
                        <label class="avatar-slide absolute inset-0 transition-all duration-700 opacity-0 pointer-events-none scale-90" id="slide-{{ $index }}">
                            <input type="radio" name="pfp" value="{{ $avatar }}" class="hidden" {{ $index === 0 ? 'checked' : '' }} id="radio-{{ $index }}">
                            
                            <div class="w-full h-full rounded-full bg-white border-[3px] border-gray-200 p-2 shadow-[0_30px_60px_rgba(0,0,0,0.08)]">
                                <div class="w-full h-full rounded-full overflow-hidden border-8 border-white">
                                    <img src="{{ asset('img/pfp/' . $avatar) }}" class="w-full h-full object-cover">
                                </div>
                                
                                <!-- Selection Badge -->
                                <div class="absolute inset-x-0 -bottom-6 flex justify-center z-20">
                                    <div class="bg-black text-white px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl flex items-center gap-2">
                                        <i class="fas fa-star text-[8px] text-yellow-400"></i> Avatar {{ $index + 1 }}
                                    </div>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mt-20 flex flex-col items-center">
                <button type="submit" class="group relative px-20 py-7 bg-black text-white rounded-[2.5rem] font-black text-[12px] uppercase tracking-[0.4em] hover:bg-gray-800 transition-all active:scale-95 shadow-[0_20px_50px_rgba(0,0,0,0.2)] overflow-hidden">
                    <span class="relative z-10">SIMPAN PROFIL</span>
                    <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                </button>
                <div class="mt-8 flex gap-2">
                    @foreach($avatars as $index => $avatar)
                        <div class="indicator w-2 h-2 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-black w-6' : 'bg-gray-200' }}" id="indicator-{{ $index }}"></div>
                    @endforeach
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .avatar-slide.active {
        opacity: 1 !important;
        pointer-events: auto !important;
        transform: scale(1) !important;
    }

    @keyframes bounce-subtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    .animate-bounce-subtle {
        animation: bounce-subtle 3s ease-in-out infinite;
    }
</style>

<script>
    let currentIndex = 0;
    const totalAvatars = {{ count($avatars) }};

    function updateDisplay() {
        document.querySelectorAll('.avatar-slide').forEach((slide, index) => {
            if (index === currentIndex) {
                slide.classList.add('active');
                document.getElementById('radio-' + index).checked = true;
            } else {
                slide.classList.remove('active');
            }
        });

        document.querySelectorAll('.indicator').forEach((indicator, index) => {
            if (index === currentIndex) {
                indicator.classList.add('bg-black', 'w-6');
                indicator.classList.remove('bg-gray-200');
            } else {
                indicator.classList.remove('bg-black', 'w-6');
                indicator.classList.add('bg-gray-200');
            }
        });
    }

    function changeAvatar(step) {
        currentIndex = (currentIndex + step + totalAvatars) % totalAvatars;
        updateDisplay();
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', updateDisplay);
</script>
@endsection
