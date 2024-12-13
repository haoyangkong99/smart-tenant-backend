<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    LeaseController,
    ContactController,
    ExpenseController,
    GeneralSettingController,
    InvoiceController,
    MaintainerController,
    PaymentController,
    PermissionController,
    PropertyController,
    PropertyUnitController,
    RoleController,
    RolePermissionsController,
    SubscriptionPackageController,
    SubscriptionTransactionController,
    UserRoleController,
    TenantController
};
use App\Models\MaintenanceRequest;

Route::post('/register-without-token', [AuthController::class, 'registerwithouttoken']);
Route::post('/login', [AuthController::class, 'login']);
// Protected Routes (Require authentication)
Route::prefix('auth')->group(function () {
Route::middleware('auth:api')->group(function(){
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/refresh', [AuthController::class, 'refresh']);
Route::get('/permissions', [PermissionController::class, 'index']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/user/{id}', [AuthController::class, 'show']);
    Route::put('/user/{id}', [AuthController::class, 'update']);       // Update user details
    Route::put('/user/{id}/change-password', [AuthController::class, 'changePassword']); // Change password
    Route::apiResources([
        'tenants' => TenantController::class,
        'leases' => LeaseController::class,
        'contacts' => ContactController::class,
        'properties' => PropertyController::class,
        'property-units'=>PropertyUnitController::class,
        'roles' => RoleController::class,
        'subscriptions' => SubscriptionPackageController::class,
        'subscription-transactions' => SubscriptionTransactionController::class,
        'expenses' => ExpenseController::class,
        'general-settings' => GeneralSettingController::class,
        'invoices' => InvoiceController::class,
        'maintainers' => MaintainerController::class,
        'payments' => PaymentController::class,
        'role-permissions' => RolePermissionsController::class,
        'user-roles' => UserRoleController::class,
        'maintenance-requests'=>MaintenanceRequest::class
    ]);
});

});
