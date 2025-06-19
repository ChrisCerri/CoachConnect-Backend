<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


Route::get('/{any}', function () {

    $indexPath = public_path('index.blade.php');

    if (File::exists($indexPath)) {
        return Response::make(File::get($indexPath), 200, [
            'Content-Type' => 'text/html',
        ]);
    }

    abort(404, 'Frontend SPA (index.blade.php) not found in public directory.');

})->where('any', '.*')->middleware('web');

