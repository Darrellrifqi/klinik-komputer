<?php

use App\Http\Controllers\AirtableWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::post('/webhook/airtable', [AirtableWebhookController::class, 'handle'])->name('api.airtable.webhook');
