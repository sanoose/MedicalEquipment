<?php
 
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EquipmentsCardController;
use App\Http\Controllers\EquipmentsMaintenanceController;
use App\Http\Controllers\EquipmentsOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuppliesCardController;
use App\Http\Controllers\SuppliesOrderController;
use App\Http\Controllers\UserController;
use App\Models\EquipmentsCard;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
 
 
 
Route::middleware([
 
])->group(function () { 
 

Route::get('/ForgotPassword' , function () {
    return Inertia::render('Auth/ForgotPassword' ); 
} )->name('ForgotPassword') ;
 


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
   
])->group(function () {

    Route::get('/Profile/Profile', [ UserController::class, 'Profile'])->name('Profile.Profile');
    Route::post('/Profile/ProfileUpdate', [ UserController::class, 'ProfileUpdate'])->name('Profile.ProfileUpdate');

    Route::get('/', [ HomeController::class, 'Dashboard'])->name('Dashboard');
    Route::get('/test',  function () {
        return inertia('test' ) ;
    }) ;

 
 
 
    Route::middleware([
        'is_admin', 
        ])->group(function () {     
          Route::get('/Users/Index',   [UserController::class , 'Index' ]  )->name('Users.Index');
           Route::get('/Users/Create',   [UserController::class , 'Create' ]  )->name('Users.Create');
           Route::post('/Users/Store',   [UserController::class , 'Store' ]  )->name('Users.Store');
           Route::get('/Users/Edit/{id}',   [UserController::class , 'Edit' ]  )->name('Users.Edit');
           Route::post('/Users/Update',   [UserController::class , 'Update' ]  )->name('Users.Update');
           Route::delete('/Users/Delete/{id}',   [UserController::class , 'Delete' ]  )->name('Users.Delete');

        }); 




    Route::middleware([
        'is_admin', 
        ])->group(function () {   

         }); 
 


    Route::middleware([
        'is_super_admin', 
        ])->group(function () { 
       
        Route::post("Clients/StoreQuick" , [ClientController::class , "StoreQuick"])->name("Clients.StoreQuick") ;
        Route::resource("Clients" , ClientController::class)->names("Clients") ;
        Route::resource("EquipmentsCards" , EquipmentsCardController::class)->names("EquipmentsCards") ;
        Route::resource("SuppliesCards" , SuppliesCardController::class)->names("SuppliesCards") ;
        Route::get("EquipmentsMaintenances/ChangeStatus" , [ EquipmentsMaintenanceController::class , "ChangeStatus"] )->name("EquipmentsMaintenances.ChangeStatus") ;
        Route::resource("EquipmentsMaintenances" , EquipmentsMaintenanceController::class)->names("EquipmentsMaintenances") ;

        Route::get('EquipmentsOrders/ChangeStatus', [\App\Http\Controllers\EquipmentsOrderController::class, 'ChangeStatus'])
          ->name('EquipmentsOrders.ChangeStatus');
        Route::resource("EquipmentsOrders" , EquipmentsOrderController::class)->names("EquipmentsOrders") ;
       
        Route::get('SuppliesOrders/ChangeStatus', [\App\Http\Controllers\SuppliesOrderController::class, 'ChangeStatus'])->name("SuppliesOrders.ChangeStatus") ; 
        Route::resource("SuppliesOrders" , SuppliesOrderController::class)->names("SuppliesOrders") ;
           
         });

});



Route::middleware(['guest'])->group(function () { 
    Route::post('/Login', [AuthController::class, 'Login'])->name('Login');



});
 

} ) ;
