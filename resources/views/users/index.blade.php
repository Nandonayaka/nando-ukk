@extends('books.layout')

@section('content')
<div class="mb-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-6">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Kelola Anggota</h1>
            <p class="text-gray-500 text-sm">Manajemen akun administrator, petugas, dan peminjam perpustakaan.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <a href="{{ route('users.create') }}" class="w-full sm:w-auto bg-black text-white px-6 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-800 transition shadow-md flex items-center justify-center gap-2">
                <i class="fas fa-user-plus text-[10px]"></i> Tambah Anggota
            </a>
        </div>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Nama</th>
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Email</th>
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Role</th>
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400">Alamat</th>
                    <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-widest text-gray-400 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-6">
                            <div class="font-bold text-gray-900 text-sm">{{ $user->name }}</div>
                            <div class="text-[10px] text-gray-400 font-medium">{{ $user->nama_lengkap ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-6 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-6">
                            @if($user->role === 'administrator')
                                <span class="bg-red-50 text-red-600 px-3 py-1 rounded-full text-[10px] font-bold border border-red-100">Administrator</span>
                            @elseif($user->role === 'petugas')
                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-bold border border-blue-100">Petugas</span>
                            @else
                                <span class="bg-green-50 text-green-600 px-3 py-1 rounded-full text-[10px] font-bold border border-green-100">Peminjam</span>
                            @endif
                        </td>
                        <td class="px-6 py-6 text-xs text-gray-500 max-w-[200px] truncate">{{ $user->alamat ?? '-' }}</td>
                        <td class="px-6 py-6">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="w-8 h-8 flex items-center justify-center bg-gray-50 rounded-lg text-gray-400 hover:text-black transition">
                                    <i class="fas fa-pen text-[10px]"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="m-0 inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus anggota ini?')" class="w-8 h-8 flex items-center justify-center bg-red-50 rounded-lg text-red-400 hover:text-red-600 transition">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
