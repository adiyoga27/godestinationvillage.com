<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $orders = DB::table('orders')
            ->whereNull('uuid')
            ->orWhere('uuid', '')
            ->select('id')
            ->get();

        foreach ($orders as $order) {
            DB::table('orders')->where('id', $order->id)->update(['uuid' => (string) Str::uuid()]);
        }
    }

    public function down(): void
    {
        // tidak bisa dikembalikan (uuid tidak dapat direkonstruksi)
    }
};