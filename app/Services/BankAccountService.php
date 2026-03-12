<?php

namespace App\Services;

use App\Models\BankAccount;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BankAccountService
{

	public static function all()
    {
        DB::statement(DB::raw('set @rownum=0'));
        return BankAccount::query()->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('bank_accounts.*')
        ])->where('bank_accounts.deleted_at', NULL);
    }

    public static function find($id)
    {
        return BankAccount::find($id);
    }

    public static function create($payload)
    {
        $model = BankAccount::create($payload);
        return $model;
    }

    public static function update($id, $payload)
    {
        $model = BankAccount::find($id);
        return $model->update($payload);
    }

    public static function destroy($id)
    {
        $model = BankAccount::find($id);
        return $model->destroy($id);
    }

}