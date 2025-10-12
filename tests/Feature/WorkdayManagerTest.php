<?php

declare(strict_types=1);

use AAEngineering\WorkdayManager\Models\MovedWorkday;
use AAEngineering\WorkdayManager\WorkdayManager;
use Carbon\Carbon;

it('can create a moved workday', function (): void {
    $movedWorkday = MovedWorkday::create([
        'day' => '2025-12-14',
        'type' => 'workday',
    ]);

    expect($movedWorkday)->toBeInstanceOf(MovedWorkday::class)
        ->and($movedWorkday->day->format('Y-m-d'))->toBe('2025-12-14')
        ->and($movedWorkday->type)->toBe('workday');
});

it('can create a moved holiday', function (): void {
    $movedWorkday = MovedWorkday::create([
        'day' => '2025-12-24',
        'type' => 'holiday',
    ]);

    expect($movedWorkday)->toBeInstanceOf(MovedWorkday::class)
        ->and($movedWorkday->day->format('Y-m-d'))->toBe('2025-12-24')
        ->and($movedWorkday->type)->toBe('holiday');
});

it('detects when a date is changed to a holiday', function (): void {
    $date = Carbon::parse('2025-12-24');

    expect(WorkdayManager::isChangedToHoliday($date))->toBeFalse();

    MovedWorkday::create([
        'day' => $date->format('Y-m-d'),
        'type' => 'holiday',
    ]);

    expect(WorkdayManager::isChangedToHoliday($date))->toBeTrue();
});

it('detects when a date is changed to a workday', function (): void {
    $date = Carbon::parse('2025-12-14');

    expect(WorkdayManager::isChangedToWorkday($date))->toBeFalse();

    MovedWorkday::create([
        'day' => $date->format('Y-m-d'),
        'type' => 'workday',
    ]);

    expect(WorkdayManager::isChangedToWorkday($date))->toBeTrue();
});

it('returns null modification type for regular days', function (): void {
    $date = Carbon::parse('2025-06-15');

    expect(WorkdayManager::getModificationType($date))->toBeNull();
});

it('returns correct modification type for moved workday', function (): void {
    $date = Carbon::parse('2025-12-14');

    MovedWorkday::create([
        'day' => $date->format('Y-m-d'),
        'type' => 'workday',
    ]);

    expect(WorkdayManager::getModificationType($date))->toBe('workday');
});

it('returns correct modification type for moved holiday', function (): void {
    $date = Carbon::parse('2025-12-24');

    MovedWorkday::create([
        'day' => $date->format('Y-m-d'),
        'type' => 'holiday',
    ]);

    expect(WorkdayManager::getModificationType($date))->toBe('holiday');
});

it('can use factory to create moved workdays', function (): void {
    $movedWorkday = MovedWorkday::factory()->create();

    expect($movedWorkday)->toBeInstanceOf(MovedWorkday::class)
        ->and($movedWorkday->day)->toBeInstanceOf(Carbon::class)
        ->and($movedWorkday->type)->toBeIn(['holiday', 'workday']);
});

it('can use factory with workday state', function (): void {
    $movedWorkday = MovedWorkday::factory()->workday()->create();

    expect($movedWorkday->type)->toBe('workday');
});

it('can use factory with holiday state', function (): void {
    $movedWorkday = MovedWorkday::factory()->holiday()->create();

    expect($movedWorkday->type)->toBe('holiday');
});

it('does not detect holiday when date is changed to workday', function (): void {
    $date = Carbon::parse('2025-12-14');

    MovedWorkday::create([
        'day' => $date->format('Y-m-d'),
        'type' => 'workday',
    ]);

    expect(WorkdayManager::isChangedToHoliday($date))->toBeFalse()
        ->and(WorkdayManager::isChangedToWorkday($date))->toBeTrue();
});

it('does not detect workday when date is changed to holiday', function (): void {
    $date = Carbon::parse('2025-12-24');

    MovedWorkday::create([
        'day' => $date->format('Y-m-d'),
        'type' => 'holiday',
    ]);

    expect(WorkdayManager::isChangedToWorkday($date))->toBeFalse()
        ->and(WorkdayManager::isChangedToHoliday($date))->toBeTrue();
});

it('handles multiple moved workdays', function (): void {
    $date1 = Carbon::parse('2025-12-14');
    $date2 = Carbon::parse('2025-12-24');

    MovedWorkday::create([
        'day' => $date1->format('Y-m-d'),
        'type' => 'workday',
    ]);

    MovedWorkday::create([
        'day' => $date2->format('Y-m-d'),
        'type' => 'holiday',
    ]);

    expect(WorkdayManager::isChangedToWorkday($date1))->toBeTrue()
        ->and(WorkdayManager::isChangedToHoliday($date1))->toBeFalse()
        ->and(WorkdayManager::isChangedToHoliday($date2))->toBeTrue()
        ->and(WorkdayManager::isChangedToWorkday($date2))->toBeFalse();
});
