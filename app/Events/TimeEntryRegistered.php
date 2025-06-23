<?php

declare(strict_types = 1);

namespace App\Events;

use App\Models\TimeEntry;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TimeEntryRegistered
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Cria uma nova instância do evento.
     */
    public function __construct(
        public readonly TimeEntry $timeEntry
    ) {
    }
}
