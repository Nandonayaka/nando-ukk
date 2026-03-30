@extends('books.layout')

@section('content')
<div class="flex items-center justify-between mb-10">
    <div>
        <h1 class="text-4xl font-extrabold tracking-tight mb-2">My History</h1>
        <p class="text-gray-500 text-[13px] font-medium">Record of your purchased books.</p>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-3xl overflow-hidden shadow-sm">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-widest text-gray-400">Date</th>
                <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-widest text-gray-400">Book Details</th>
                <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-widest text-gray-400">Price</th>
                <th class="px-8 py-5 text-[11px] font-bold uppercase tracking-widest text-gray-400 text-center">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($transactions as $transaction)
                <tr class="hover:bg-gray-50 transition duration-300">
                    <td class="px-8 py-6 text-[13px] font-semibold text-gray-400 whitespace-nowrap">
                        {{ $transaction->created_at->format('d M Y, H:i') }}
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-20 bg-gray-50 border border-gray-100 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                                @if(isset($transaction->book->gambar) && $transaction->book->gambar)
                                    <img src="{{ Str::startsWith($transaction->book->gambar, 'http') ? $transaction->book->gambar : asset('img/' . $transaction->book->gambar) }}" class="w-full h-full object-cover" alt="Cover">
                                @else
                                    <img src="https://api.dicebear.com/9.x/open-peeps/svg?seed={{ urlencode($transaction->book->judul ?? 'N/A') }}&size=64&face=smile" class="w-12 h-12 object-contain opacity-80" alt="Cover">
                                @endif
                            </div>
                            <div>
                                <div class="font-bold text-black mb-1 text-sm">{{ $transaction->book->judul ?? 'Buku Dihapus' }}</div>
                                <div class="text-[13px] text-gray-500">
                                    <i class="fas fa-feather-alt text-[9px] mr-1"></i> {{ $transaction->book->penulis ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 font-semibold text-[13px] text-black">
                        {{ isset($transaction->book->harga) ? ($transaction->book->harga ? 'Rp ' . number_format($transaction->book->harga, 0, ',', '.') : 'Free') : '-' }}
                    </td>
                    <td class="px-8 py-6 text-center">
                        <span class="bg-green-50 text-green-600 border border-green-200 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest">
                            {{ $transaction->status }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-24 text-center text-gray-400">
                        <div class="mb-4">
                            <img src="https://api.dicebear.com/9.x/open-peeps/svg?seed=History&size=100&face=smile" class="w-32 mx-auto grayscale opacity-50">
                        </div>
                        <p class="font-bold text-sm tracking-widest uppercase">No purchased history yet.</p>
                        <a href="{{ route('katalog.index') }}" class="mt-4 inline-block bg-black text-white px-6 py-2.5 rounded-full font-semibold text-[13px] hover:bg-gray-800 transition">Start Browsing</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
