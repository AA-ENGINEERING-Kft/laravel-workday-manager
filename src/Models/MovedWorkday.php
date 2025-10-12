<?php

declare(strict_types=1);

namespace AAEngineering\WorkdayManager\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property \Illuminate\Support\Carbon $day
 * @property string $type
 * @property-read \Illuminate\Support\Carbon|null $created_at
 * @property-read \Illuminate\Support\Carbon|null $updated_at
 */
final class MovedWorkday extends Model
{
    /** @use HasFactory<\AAEngineering\WorkdayManager\Database\Factories\MovedWorkdayFactory> */
    use HasFactory;

    protected $fillable = [
        'day',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'day' => 'date:Y-m-d',
        ];
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): \AAEngineering\WorkdayManager\Database\Factories\MovedWorkdayFactory
    {
        return \AAEngineering\WorkdayManager\Database\Factories\MovedWorkdayFactory::new();
    }
}
