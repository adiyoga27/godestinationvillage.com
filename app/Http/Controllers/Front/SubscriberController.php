<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Support\Seo;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'nullable|string|max:255',
        ]);

        $subscriber = Subscriber::firstOrNew(['email' => $validated['email']]);
        $subscriber->name = $validated['name'] ?? $subscriber->name;
        $subscriber->is_active = true;
        $subscriber->save();

        return redirect()->back()->with('status', 'Berhasil berlangganan! Cek email Anda untuk update terbaru dari GODEVI.');
    }

    public function unsubscribe($token)
    {
        $subscriber = Subscriber::where('unsubscribe_token', $token)->first();

        if (!$subscriber) {
            abort(404);
        }

        $data['subscriber'] = $subscriber;
        $data['seo'] = Seo::make()
            ->title('Unsubscribe Newsletter')
            ->description('Berhenti berlangganan newsletter GODEVI.')
            ->canonical('/unsubscribe/' . $token)
            ->noindex()
            ->toArray();

        return view('customer/unsubscribe', $data);
    }

    public function unsubscribeConfirm($token)
    {
        $subscriber = Subscriber::where('unsubscribe_token', $token)->first();

        if (!$subscriber) {
            abort(404);
        }

        $subscriber->update(['is_active' => false]);

        return redirect()->back()->with('status', 'Email Anda telah dihapus dari daftar berlangganan newsletter GODEVI.');
    }
}