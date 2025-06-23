<?php

declare(strict_types = 1);

namespace App\Models;

use Database\Factories\TimeEntryFactory;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property User $user_id
 * @property DateTime $recorded_at
 *
 * @method static TimeEntryFactory factory(...$parameters)
 */
class TimeEntry extends Model
{
    /** @phpstan-use HasFactory<TimeEntryFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'recorded_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<User, TimeEntry>
     */
    public function user(): BelongsTo
    {
        /** @var BelongsTo<User, TimeEntry> */
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeTodayForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId)
            ->whereDate('recorded_at', Carbon::today());
    }

    public function scopeInDateRange(Builder $query, Carbon $startDate, Carbon $endDate): Builder
    {
        return $query->whereBetween('recorded_at', [$startDate, $endDate]);
    }
}
