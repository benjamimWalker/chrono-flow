<?php

use App\Models\WorkLog;

test('work logs route renders successfully', function () {
    $this->get(route('work-logs'))
        ->assertOk()
        ->assertViewIs('list_work_logs');
});

test('work logs page shows correct title', function () {
    $this->get(route('work-logs'))
        ->assertSee('Work Logs');
});

test('work logs page has import CSV button', function () {
    $this->get(route('work-logs'))
        ->assertSee('Import CSV')
        ->assertSee(route('home'));
});

test('work logs table shows correct headers', function () {
    $this->get(route('work-logs'))
        ->assertSeeInOrder([
            'Employee',
            'Date',
            'Hours',
            'Description'
        ]);
});

test('work logs table shows empty state when no logs exist', function () {
    $this->get(route('work-logs'))
        ->assertSee('No work logs found')
        ->assertSee('Import a CSV');
});

test('work logs table displays work log data', function () {
    $log = WorkLog::factory()->create([
        'employee_name' => 'John Doe',
        'date' => '2023-01-01',
        'hours' => 8.5,
        'description' => 'Worked on project'
    ]);

    $this->get(route('work-logs'))
        ->assertSee($log->employee_name)
        ->assertSee('Jan 01, 2023')
        ->assertSee('8.5')
        ->assertSee($log->description);
});

test('work logs table paginates results', function () {
    WorkLog::factory()->count(25)->create();

    $this->get(route('work-logs'))
        ->assertSee('Showing 1 to 20 of 25 results');
});

test('work logs pagination controls work correctly', function () {
    WorkLog::factory()->count(30)->create();

    $response = $this->get(route('work-logs'));

    $response->assertSee('href="' . route('work-logs', ['page' => 2]) . '"', false);
});

test('work logs page has dark mode classes', function () {
    $this->get(route('work-logs'))
        ->assertSee('dark:bg-gray-900')
        ->assertSee('dark:text-white');
});

test('description is truncated with line-clamp', function () {
    $longDescription = str_repeat('This is a long description. ', 20);
    WorkLog::factory()->create(['description' => $longDescription]);

    $this->get(route('work-logs'))
        ->assertSee('line-clamp-2');
});

test('pagination shows ellipsis when many pages exist', function () {
    WorkLog::factory()->count(200)->create();

    $this->get(route('work-logs', ['page' => 10]))
        ->assertSee('...');
});
