<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueUserEmail implements ValidationRule
{
    const ROLES = [1 => 'Admin', 2 => 'Village', 3 => 'Member'];

    public function __construct(private $ignoreId = null) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $user = User::query()
            ->where('email', $value)
            ->when($this->ignoreId, fn ($q) => $q->where('id', '!=', $this->ignoreId))
            ->orderBy('id')
            ->first();

        if (!$user) {
            return;
        }

        $role = self::ROLES[$user->role_id] ?? 'role #' . $user->role_id;
        $fail('Email sudah digunakan oleh akun ' . $role . ' (ID ' . $user->id . ', ' . $user->name . ').');
    }
}
