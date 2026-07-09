<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GachaController extends Controller
{
    private function getPrizesList()
    {
        // Berdasarkan instruksi final (9 Item, hapus voucher)
        // Kita menggunakan perkalian x100 agar Doorprize 0.1% bisa jadi bilangan bulat 1
        return [
            ['name' => 'Doorprize Sepeda', 'label' => 'SEPEDA', 'chance' => 1, 'display' => '0.1'],
            ['name' => 'Voucher Belanja 100k', 'label' => 'Rp 100k', 'chance' => 20, 'display' => '2'],
            ['name' => 'Merchandise Kaos', 'label' => 'Kaos', 'chance' => 20, 'display' => '2'],
            ['name' => 'Kembalian 40 EXP', 'label' => '+40 EXP', 'chance' => 400, 'display' => '40'],
            ['name' => 'Stiker Keren', 'label' => 'Stiker', 'chance' => 300, 'display' => '30'],
            ['name' => 'Pulpen Eksklusif', 'label' => 'Pulpen', 'chance' => 150, 'display' => '15'],
            ['name' => 'Pin Perpustakaan', 'label' => 'Pin', 'chance' => 100, 'display' => '10'],
            ['name' => 'Gantungan Kunci', 'label' => 'Ganci', 'chance' => 100, 'display' => '10'],
            ['name' => 'Buku Catatan Premium', 'label' => 'Buku', 'chance' => 100, 'display' => '10'],
            ['name' => 'Coba Lagi (Zonk)', 'label' => 'ZONK', 'chance' => 980, 'display' => '98']
        ];
    }

    public function index()
    {
        $prizes = $this->getPrizesList();
        $totalWeight = array_sum(array_column($prizes, 'chance'));
        return view('pelanggan.gacha', compact('prizes', 'totalWeight'));
    }

    public function spin(Request $request)
    {
        $user = auth()->user();
        $cost = 5; // Biaya 1x spin (50 EXP)

        if ($user->exp < $cost) {
            return back()->withArray([
                'error' => 'EXP Anda tidak cukup untuk melakukan spin (Butuh ' . $cost . ' EXP)!'
            ])->with('error', 'EXP Anda tidak mencukupi.');
        }

        // Deduct EXP
        $user->decrement('exp', $cost);

        $prizes = $this->getPrizesList();

        // Sistem Acak Berdasarkan Total Bobot (Weighted Random)
        $totalWeight = array_sum(array_column($prizes, 'chance'));
        $rand = mt_rand(1, $totalWeight);
        $cumulative = 0;
        $prize = 'Coba Lagi (Zonk)'; // Default fallback

        foreach ($prizes as $p) {
            $cumulative += $p['chance'];
            if ($rand <= $cumulative) {
                $prize = $p['name'];
                break;
            }
        }

        $message = "Selamat! Anda mendapatkan hadiah: " . $prize;
        $showCollectionMessage = false;
        
        if ($prize === 'Coba Lagi (Zonk)') {
            $message = "Yah sayang sekali, Anda belum beruntung (Zonk). Jangan menyerah!";
        } elseif ($prize === 'Kembalian 40 EXP') {
            $user->increment('exp', 40);
            $message = "Wow! Kamu mendapatkan kembalian 40 EXP kembali!";
        } else {
            \App\Models\GachaReward::create([
                'user_id' => $user->id,
                'prize_name' => $prize,
                'status' => 'pending'
            ]);
            $showCollectionMessage = true;

            // Kirim notifikasi inbox untuk hadiah gacha
            \App\Models\InboxMessage::kirim(
                $user->id,
                'gacha',
                'Hadiah Gacha!',
                'Selamat! Anda memenangkan "' . $prize . '" dari Gacha Spin! Kunjungi halaman Koleksi Gacha untuk mengklaimnya.',
                'fas fa-gift',
                'purple'
            );
        }

        // Calculate visual probability (for controller if ever needed)
        // Since the user requested "chance => 80" etc, the visual % can be rendered dynamically in blade.
        
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'prize' => $prize,
                'message' => $message,
                'is_zonk' => $prize === 'Coba Lagi (Zonk)',
                'show_collection' => $showCollectionMessage,
                'new_exp' => $user->exp
            ]);
        }

        return back()->with('prize_won', $message)->with('success', 'Spin berhasil dilakukan!');
    }

    public function koleksi()
    {
        $rewards = \App\Models\GachaReward::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('pelanggan.gacha_koleksi', compact('rewards'));
    }

    public function claim($id)
    {
        $reward = \App\Models\GachaReward::where('user_id', auth()->id())->findOrFail($id);
        $reward->update(['status' => 'claimed']);
        return back()->with('success', 'Status item berhasil diperbarui menjadi Sudah Diklaim.');
    }
}
