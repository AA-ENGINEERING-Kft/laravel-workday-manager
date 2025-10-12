<?php

declare(strict_types=1);

namespace AAEngineering\WorkdayManager;

use AAEngineering\WorkdayManager\Models\MovedWorkday;
use Carbon\Carbon;

final class WorkdayManager
{
    /**
     * Get the modification type for a given date.
     *
     * @return string|null Returns 'holiday', 'workday', or null if not modified
     */
    public static function getModificationType(Carbon $date): ?string
    {
        $moved = MovedWorkday::query()
            ->where('day', $date->format('Y-m-d'))
            ->first();

        return $moved?->type;
    }

    /**
     * Check if the given date has been changed to a holiday.
     */
    public static function isChangedToHoliday(Carbon $date): bool
    {
        return self::getModificationType($date) === 'holiday';
    }

    /**
     * Check if the given date has been changed to a workday.
     */
    public static function isChangedToWorkday(Carbon $date): bool
    {
        return self::getModificationType($date) === 'workday';
    }
}
