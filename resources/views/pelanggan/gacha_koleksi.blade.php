@extends('books.layout')

@section('content')
<div class="h-full flex flex-col p-6 lg:p-10 relative overflow-y-auto">
    <div class="flex items-center gap-4 mb-8 sticky top-0 bg-white/90 backdrop-blur-md py-4 z-20 border-b border-gray-50 -mx-6 px-6 lg:-mx-10 lg:px-10">
        <a href="{{ route('gacha.index') }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:text-black hover:border-black shadow-sm transition-all">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl lg:text-3xl font-black text-gray-900 tracking-tight">Koleksi Gacha</h1>
            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">Daftar item fisik yang siap diklaim</p>
        </div>
    </div>

    @if($rewards->isEmpty())
        <div class="flex-1 flex flex-col items-center justify-center bg-gray-50 rounded-[2.5rem] border border-dashed border-gray-200 p-10 text-center min-h-[50vh]">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mb-6 shadow-sm border border-gray-100 relative">
                <i class="fas fa-box-open text-4xl text-gray-300"></i>
                <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-black rounded-full flex items-center justify-center text-white border-2 border-white">
                    <i class="fas fa-question text-xs"></i>
                </div>
            </div>
            <h3 class="text-xl font-black text-gray-900 tracking-tight">Belum Ada Item</h3>
            <p class="text-sm text-gray-500 mt-2 max-w-sm font-medium leading-relaxed">Kamu belum memenangkan hadiah fisik dari kotak Gacha. Kumpulkan lebih banyak EXP dan putar roletnya!</p>
            <a href="{{ route('gacha.index') }}" class="mt-8 inline-block bg-black text-white px-8 py-3.5 rounded-xl font-bold text-sm tracking-wide hover:shadow-xl hover:shadow-black/20 hover:-translate-y-0.5 transition-all">
                Mulai Putar Rolet
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($rewards as $reward)
                @php
                    $isDoorprize = str_contains($reward->prize_name, 'Sepeda');
                    $isEpic = str_contains($reward->prize_name, '100k') || str_contains($reward->prize_name, 'Kaos');
                @endphp
                <div class="bg-white p-6 rounded-3xl border {{ $isDoorprize ? 'border-yellow-300 bg-gradient-to-br from-yellow-50 to-amber-50 shadow-lg scale-[1.02]' : ($isEpic ? 'border-indigo-200 bg-gradient-to-br from-indigo-50 to-purple-50 shadow-md' : 'border-gray-100 hover:shadow-xl hover:-translate-y-1 transition-all') }} relative group flex flex-col h-full">
                    
                    @if($isDoorprize)
                    <div class="absolute -top-3 -right-3 w-10 h-10 bg-gradient-to-tr from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white shadow-md animate-bounce z-20">
                        <i class="fas fa-star text-sm"></i>
                    </div>
                    @elseif($isEpic)
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-full flex items-center justify-center text-white shadow-sm z-20">
                        <i class="fas fa-gem text-[10px]"></i>
                    </div>
                    @endif

                    <div class="flex items-start justify-between mb-5">
                        <div class="w-14 h-14 rounded-[1.2rem] flex items-center justify-center {{ $isDoorprize ? 'bg-gradient-to-tr from-yellow-400 to-orange-500 text-white shadow-inner' : ($isEpic ? 'bg-gradient-to-tr from-indigo-500 to-purple-600 text-white shadow-inner' : 'bg-gray-50 text-gray-800 border border-gray-100') }}">
                            <i class="fas {{ $isDoorprize ? 'fa-bicycle' : (str_contains($reward->prize_name, '100k') ? 'fa-ticket-alt' : ($isEpic ? 'fa-tshirt' : 'fa-gift')) }} text-2xl"></i>
                        </div>
                        <span class="px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest {{ $reward->status == 'claimed' ? 'bg-green-100 text-green-700' : 'bg-blue-50 text-blue-700 border border-blue-100' }}">
                            {{ $reward->status == 'claimed' ? 'Terambil' : 'Siap Klaim' }}
                        </span>
                    </div>

                    <h3 class="font-black {{ $isEpic ? 'text-indigo-900' : 'text-gray-900' }} tracking-tight leading-tight {{ $isDoorprize ? 'text-xl' : 'text-lg' }}">
                        {{ $reward->prize_name }}
                    </h3>
                    <p class="text-[10px] text-gray-400 font-bold tracking-wider mt-2 uppercase">Menang pada: {{ $reward->created_at->format('d M Y') }}</p>

                    <div class="mt-auto pt-6"> <!-- Push to bottom to align grids -->
                        @if($reward->status == 'pending')
                            <div class="pt-4 border-t {{ $isDoorprize ? 'border-yellow-200' : 'border-gray-100' }}">
                                <p class="text-[10px] text-gray-500 mb-3 font-medium leading-relaxed">Bawa HP-mu dan tunjukkan halaman ini ke meja Admin Perpustakaan untuk ditukar hadiah ini.</p>
                                <div class="flex flex-col gap-2">
                                    <button onclick="Swal.fire({
                                        title: 'Cara Klaim Hadiah',
                                        html: '<div class=\'text-sm text-gray-600 mt-2\'><ul class=\'text-left space-y-3\'><li class=\'flex gap-2\'><i class=\'fas fa-check-circle text-green-500 mt-0.5\'></i> Temui Admin Perpustakaan saat ini juga.</li><li class=\'flex gap-2\'><i class=\'fas fa-check-circle text-green-500 mt-0.5\'></i> Tunjukkan layar halaman <b>Koleksi Gacha</b> ini untuk diverifikasi Admin.</li><li class=\'flex gap-2\'><i class=\'fas fa-check-circle text-green-500 mt-0.5\'></i> Klik tombol Sudah Diklaim setelah Admin menyerahkan hadiah.</li></ul></div>',
                                        icon: 'info',
                                        confirmButtonColor: '#000',
                                        confirmButtonText: 'Mengerti',
                                        customClass: { popup: 'rounded-3xl border border-gray-100 shadow-2xl', confirmButton: 'rounded-xl px-8 font-bold' }
                                    })" class="w-full py-3 bg-black text-white text-[11px] font-black tracking-widest uppercase rounded-xl hover:bg-gray-800 transition-colors shadow-md">
                                        Cara Mengambil
                                    </button>
                                    
                                    <form id="claim-form-{{ $reward->id }}" action="{{ route('gacha.claim', $reward->id) }}" method="POST">
                                        @csrf
                                        <button type="button" class="w-full py-2.5 bg-gray-50 text-gray-700 text-[10px] font-black tracking-widest uppercase rounded-xl hover:bg-gray-200 transition-colors border border-gray-200" onclick="confirmClaim('claim-form-{{ $reward->id }}')">
                                            Sudah Diklaim
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="pt-4 border-t border-gray-100 text-center">
                                <span class="text-xs font-bold text-green-600 flex items-center justify-center gap-2">
                                    <i class="fas fa-check"></i> Telah Diserahkan
                                </span>
                            </div>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    @endif
</div>

<script>
    function confirmClaim(formId) {
        Swal.fire({
            title: 'Sudah Menerima Hadiah?',
            text: 'Tandai item ini jika admin Perpustakaan benar-benar sudah menyerahkan hadiah ini ke tanganmu.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#000',
            cancelButtonColor: '#f3f4f6',
            cancelButtonText: '<span style="color: #6b7280; font-weight: bold;">Batal</span>',
            confirmButtonText: 'Ya, Sudah Diterima',
            customClass: {
                popup: 'rounded-3xl border border-gray-100 shadow-2xl',
                confirmButton: 'rounded-xl px-6 py-3 font-bold',
                cancelButton: 'rounded-xl px-6 py-3 font-bold shadow-sm'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endsection
