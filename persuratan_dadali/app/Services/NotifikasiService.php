<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\User;

class NotifikasiService
{
    public function notifyRolesAndUsers(array $roles, array $userIds, array $attributes): void
    {
        $roleUserIds = User::query()
            ->whereIn('role', $roles)
            ->pluck('id')
            ->all();

        $recipientIds = collect($roleUserIds)
            ->merge($userIds)
            ->filter()
            ->unique()
            ->values();

        foreach ($recipientIds as $userId) {
            Notifikasi::create(array_merge($attributes, ['user_id' => $userId]));
        }
    }
}