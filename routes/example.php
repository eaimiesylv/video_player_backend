<?php

use App\Http\Controllers\Api\CommunicationEmailController;
use App\Http\Controllers\Api\CommunicationSmsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Application gateway response for health check
Route::get('/v1/test', function () {
    return response()->json([
        'app'     => 'mds',
        'version' => env('APP_VERSION') ?? null,
        'env'     => app()->environment(),
    ]);
});

// Azure Communication Services
Route::resource('/v1/communication/email', CommunicationEmailController::class);
Route::resource('/v1/communication/sms', CommunicationSmsController::class);

/********************************************
 * API Version 1
 ********************************************/

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('v1')->group(function () {
        // Universal routes used for select lists or card elements. Ensure the query is filtered by the user's company id first.
        Route::resources('list/locations', App\Http\Controllers\Users\ListController::class)->only('index');
        Route::resources('list/customers', App\Http\Controllers\Users\ListController::class)->only('index');
        Route::resources('list/team', App\Http\Controllers\Users\ListController::class)->only('index');
        Route::resources('list/searches', App\Http\Controllers\Users\ListController::class)->only('index');
        Route::resources('list/services', App\Http\Controllers\Users\ProductsController::class)->only('index');
        Route::resources('list/products', App\Http\Controllers\Users\ProductsController::class)->only('index');

        // Begin: Refactor these routes
        Route::resource('invitations', App\Http\Controllers\Users\InvitationController::class); // Takes: type=franchisee|agent
        Route::resource('customers', App\Http\Controllers\Customers\CustomerController::class); // ok
        Route::resource('retail-customer/location', App\Http\Controllers\Franchisee\FranchiseeLocationController::class); // only shows up for the franchise-operator
        // End: Refactor these routes

        // Example routes. The franchisee can query ALL data for itself.
        Route::resource('franchisee/dashboards', App\Http\Controllers\Franchisee\FranchiseeAppointmentsController::class);
        Route::resource('franchisee/appointments', App\Http\Controllers\Franchisee\FranchiseeAppointmentsController::class);
        Route::resource('franchisee/product-services', App\Http\Controllers\Franchisee\FranchiseeProductServicesContactController::class);
        Route::resource('franchisee/orders', App\Http\Controllers\Franchisee\FranchiseeProductServicesContactController::class);
        Route::resource('franchisee/inventory', App\Http\Controllers\Franchisee\FranchiseeProductServicesContactController::class);
        Route::resource('franchisee/customers', App\Http\Controllers\Franchisee\FranchiseeContactController::class);
        Route::resource('franchisee/contacts', App\Http\Controllers\Franchisee\FranchiseeContactController::class);
        Route::resource('franchisee/reports', App\Http\Controllers\Franchisee\FranchiseeContactController::class);
        Route::resource('franchisee/integrations', App\Http\Controllers\Franchisee\FranchiseeContactController::class);
        Route::resource('franchisee/teams', App\Http\Controllers\Franchisee\FranchiseeTeamController::class);
        Route::resource('franchisee/marketing', App\Http\Controllers\Franchisee\FranchiseeTeamController::class);
        Route::resource('franchisee/profile', App\Http\Controllers\Franchisee\FranchiseeTeamController::class);
        Route::resource('franchisee/learning-center', App\Http\Controllers\Franchisee\FranchiseeTeamController::class);

        // Franchise Operator routes. THe operator can query ALL data for the franchisee and itself.
        // Table filters should be put in place to sort by All | Example | Operator
        Route::resource('operator/dashboards', App\Http\Controllers\Franchisee\FranchiseeAppointmentsController::class);
        Route::resource('operator/appointments', App\Http\Controllers\Franchisee\FranchiseeAppointmentsController::class);
        Route::resource('operator/product-services', App\Http\Controllers\Franchise\FranchiseProductServicesContactController::class);
        Route::resource('franchisee/orders', App\Http\Controllers\Franchisee\FranchiseeProductServicesContactController::class);
        Route::resource('franchisee/inventory', App\Http\Controllers\Franchisee\FranchiseeProductServicesContactController::class);
        Route::resource('operator/customers', App\Http\Controllers\Franchisee\FranchiseeContactController::class);
        Route::resource('operator/contacts', App\Http\Controllers\Franchisee\FranchiseeContactController::class);
        Route::resource('operator/reports', App\Http\Controllers\Franchisee\FranchiseeContactController::class);
        Route::resource('operator/integrations', App\Http\Controllers\Franchisee\FranchiseeContactController::class);
        Route::resource('operator/teams', App\Http\Controllers\Franchisee\FranchiseeTeamController::class);
        Route::resource('operator/marketing', App\Http\Controllers\Franchisee\FranchiseeTeamController::class);
        Route::resource('operator/profile', App\Http\Controllers\Franchisee\FranchiseeTeamController::class);
        Route::resource('operator/franchisees', App\Http\Controllers\Franchisee\FranchiseeTeamController::class);
        Route::resource('operator/learning-center', App\Http\Controllers\Franchisee\FranchiseeTeamController::class);

        // Retail Customer routes
        Route::resource('retail/search', App\Http\Controllers\Franchisee\FranchiseeTeamController::class); // This can be the "list" route
        Route::resource('retail/appointments', App\Http\Controllers\Franchisee\FranchiseeTeamController::class);
        Route::resource('retail/profiles', App\Http\Controllers\Franchisee\FranchiseeTeamController::class);
        Route::resource('retail/payments', App\Http\Controllers\Franchisee\FranchiseeTeamController::class);
    });

});








/********************************************
 * API Version 2
 ********************************************/
// Reserved for future use
