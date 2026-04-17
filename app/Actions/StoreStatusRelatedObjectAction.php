<?php

namespace App\Actions;

use App\Models\Status;
use Illuminate\Database\Eloquent\Model;

class StoreStatusRelatedObjectAction
{
    public function execute(Model $statusable, Status $status, int $userId, ?string $notes = null): void
    {
        $statusable->statusRelatedObjects()->create([
            'status_id' => $status->id,
            'user_id' => $userId,
            'notes' => $notes,
        ]);
    }
}
