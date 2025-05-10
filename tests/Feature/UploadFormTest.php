<?php

test('upload form renders correctly', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('Upload CSV File')
        ->assertSee('Only CSV files accepted')
        ->assertSee('Select CSV');
});

test('shows drag and drop interface by default', function () {
    $this->get('/')
        ->assertSee('Drop CSV here', false)
        ->assertSee('Click to browse');
});

test('validates CSV file type client-side', function () {
    $this->get('/')->assertSee('accept=".csv,text/csv,application/vnd.ms-excel"', false);
});

test('validates file size client-side', function () {
    $this->get('/')->assertSee('80 * 1024 * 1024', false);
});

test('shows error for invalid file types', function () {
    $this->get('/')->assertSee('Only CSV files are allowed', false);
});

test('shows error for oversized files', function () {
    $this->get('/')->assertSee('File size must be less than 80MB', false);
});

test('has file preview functionality', function () {
    $this->get('/')
        ->assertSee('Preview First 5 Rows')
        ->assertSee('FileReader', false);
});

test('has file reset functionality', function () {
    $this->get('/')
        ->assertSee('resetSelection')
        ->assertSee('Change CSV');
});

test('shows upload progress during upload', function () {
    $this->get('/')
        ->assertSee('Uploading...')
        ->assertSee('uploadProgress');
});

test('has close button functionality', function () {
    $this->get('/')->assertSee('$dispatch(\'close-uploader\')', false);
});
