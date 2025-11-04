<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Set;
use Illuminate\Support\Str;

final class SetObserver
{
    public function creating(Set $set): string
    {
        return $set->uuid = (string) Str::uuid();
    }
}
