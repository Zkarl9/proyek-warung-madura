<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;

class NotificationSettingController extends Controller
{
   public function index(Request $request)
{
    $setting = NotificationSetting::where('user_id', $request->user()->id)->first()
            ?? new NotificationSetting(['user_id' => $request->user()->id]);

    return view('owner.notifikasi.index', compact('setting'));
}

    public function update(Request $request)
    {
        $data = $request->validate([
            'wa_number' => ['nullable', 'string'],
            'fonnte_token' => ['nullable', 'string'],
            'telegram_chat_id' => ['nullable', 'string'],
            'is_active' => ['boolean'],
        ]);

        NotificationSetting::updateOrCreate(
            ['user_id' => $request->user()->id],
            $data
        );

        return back()->with('status', 'Pengaturan notifikasi berhasil diperbarui.');
    }
}
