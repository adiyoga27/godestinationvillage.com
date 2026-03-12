<?php

namespace App\Services;

use App\Models\MemberDiscount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DiscountMemberService
{

	public static function all()
    {
        DB::statement(DB::raw('set @rownum=0'));
        return MemberDiscount::query()->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            DB::raw('member_discounts.*')
        ])->where('member_discounts.deleted_at', NULL);
    }

    public static function find($id)
    {
        return MemberDiscount::find($id);
    }

    public static function create($payload)
    {
        if($payload['is_active'] == 1)
            MemberDiscount::where('is_active', 1)->update(['is_active' => 0]);

        $model = MemberDiscount::create($payload);

        return $model;
    }

    public static function update($id, $payload)
    {
        if($payload['is_active'] == 1)
            MemberDiscount::where('is_active', 1)->update(['is_active' => 0]);

        $model = MemberDiscount::find($id);
        return $model->update($payload);
    }

    public static function destroy($id)
    {
        $model = MemberDiscount::find($id);
        if($model->is_active == 1)
            return false;
        else
            return $model->destroy($id);
    }

}