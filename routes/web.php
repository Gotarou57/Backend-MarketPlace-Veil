<?php

use Illuminate\Support\Facades\Route;
use Spatie\Browsershot\Browsershot;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-pdf', function () {
    $html = '<h1>Test PDF</h1><p>Hello World!</p>';
    
    $pdf = Browsershot::html($html)
        ->format('A4')
        ->showBackground()
        ->pdf();
    
    return response($pdf)
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'attachment; filename="test.pdf"');
});