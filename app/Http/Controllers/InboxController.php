<?php

namespace App\Http\Controllers;

use App\Models\InboxMessage;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    /**
     * Halaman Inbox/Mailbox
     */
    public function index()
    {
        $messages = InboxMessage::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pelanggan.inbox', compact('messages'));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        $msg = InboxMessage::where('user_id', auth()->id())->findOrFail($id);
        $msg->update(['is_read' => true]);

        return back()->with('success', 'Pesan ditandai sudah dibaca.');
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllRead()
    {
        InboxMessage::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua pesan ditandai sudah dibaca.');
    }

    /**
     * Hapus satu notifikasi
     */
    public function destroy($id)
    {
        $msg = InboxMessage::where('user_id', auth()->id())->findOrFail($id);
        $msg->delete();

        return back()->with('success', 'Pesan berhasil dihapus.');
    }

    /**
     * Hapus semua notifikasi (Clear All)
     */
    public function clearAll()
    {
        InboxMessage::where('user_id', auth()->id())->delete();

        return back()->with('success', 'Semua pesan berhasil dihapus.');
    }

    /**
     * API: Ambil jumlah unread (untuk badge di topbar)
     */
    public function unreadCount()
    {
        $count = InboxMessage::where('user_id', auth()->id())
            ->where('is_read', false)
            ->count();

        return response()->json(['count' => $count]);
    }
}
