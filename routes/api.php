<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\DrinkSqlController;
use App\Http\Controllers\api\DrinkBuilderController;
use App\Http\Controllers\api\DrinkController;
use App\Http\Controllers\api\PackageController;
use App\Http\Controllers\api\TypeController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AdminController;
use App\Http\Controllers\api\ProfileController;
use App\Models\User;

//User
Route::post( "/register", [ UserController::class, "register" ]);
Route::post( "/login", [ UserController::class, "login" ]);

Route::middleware([ "auth:sanctum" ])->group( function() {
    
    //User
    Route::post( "/logout", [ UserController::class, "logout" ]);

    //Profile
    Route::get( "/profile/{id}", [ ProfileController::class, "getProfile" ]);

    Route::middleware([ "role" ])->group( function() {

        //Drink
        Route::get( "/drinks", [ DrinkController::class, "getDrinks" ]);
        Route::get( "/drink/{drink}", [ DrinkController::class, "getDrink" ]);
        Route::post( "/newdrink", [ DrinkController::class, "create" ]);
        Route::put( "/updatedrink/{drink}", [ DrinkController::class, "update" ]);
        
        //Package
        Route::get( "/packages", [ PackageController::class, "getPackages" ]);
        Route::post( "newpackage", [ PackageController::class, "create" ]);
        Route::put( "/updatepackage/{package}", [ PackageController::class, "update" ]);
        
        //Type
        Route::get( "/types", [ TypeController::class, "getTypes" ]);
        Route::post( "/newtype", [ TypeController::class, "create" ])->middleware( "role" );
        Route::put( "/updatetype/{type}", [ TypeController::class, "update" ]);
        
        //User management
        Route::get( "/users", [ AdminController::class, "getUsers" ]);
        Route::get( "/addadmin/{user}", [ AdminController::class, "setAdminRole" ]);
        Route::get( "/deladmin/{user}", [ AdminController::class, "delAdminRole" ]);
        Route::post( "/newuser", [ AdminController::class, "createUser" ]);
        Route::put( "/setpassword/{user}", [ AdminController::class, "setPassword" ]);
        Route::get( "/token", [ AdminController::class, "getTokens" ]);

        Route::middleware([ "abilities:drinks:delete", ])->group( function() {

                Route::delete( "/deletedrink/{drink}", [ DrinkController::class, "destroy" ]);
                Route::delete( "deletepackage/{package}", [ PackageController::class, "destroy" ]);
                Route::delete( "/deletetype/{type}", [ TypeController::class, "destroy" ]);
            });
    });
});

Route::get("/verify_email/{id}/{hash}", function( Request $request, $id, $hash ) {

    $user = User::findOrFail($request->id);

    if( !$user->hasVerifiedEmail() ) {

        return Response()->json([ "message" => "Ez az email már meg van erősítve" ]);
    }

    $user->markEmailAsVerified();

    return Response()->json([ "message" => "Az email erősítve" ]);

} )->name("verification.verify")->middleware( "signed" );
