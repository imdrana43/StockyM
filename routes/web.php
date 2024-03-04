<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\SalesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

//------------------------------------------------------------------\\

Route::middleware('auth')->get('/test/{id}', [SalesController::class, 'SaleInv']);

Route::post('/login', [
    'uses' => 'Auth\LoginController@login',
    'middleware' => 'Is_Active',
]);

Route::get('password/find/{token}', 'PasswordResetController@find');

//------------------------------------------------------------------\\

$installed = Storage::disk('public')->exists('installed');

if ($installed === false) {
    Route::get('/setup', [
        'uses' => 'SetupController@viewCheck',
    ])->name('setup');

    Route::get('/setup/step-1', [
        'uses' => 'SetupController@viewStep1',
    ]);

    Route::post('/setup/step-2', [
        'as' => 'setupStep1', 'uses' => 'SetupController@setupStep1',
    ]);

    Route::post('/setup/testDB', [
        'as' => 'testDB', 'uses' => 'TestDbController@testDB',
    ]);

    Route::get('/setup/step-2', [
        'uses' => 'SetupController@viewStep2',
    ]);

    Route::get('/setup/step-3', [
        'uses' => 'SetupController@viewStep3',
    ]);

    Route::get('/setup/finish', function () {

        return view('setup.finishedSetup');
    });

    Route::get('/setup/getNewAppKey', [
        'as' => 'getNewAppKey', 'uses' => 'SetupController@getNewAppKey',
    ]);

    Route::get('/setup/getPassport', [
        'as' => 'getPassport', 'uses' => 'SetupController@getPassport',
    ]);

    Route::get('/setup/getMegrate', [
        'as' => 'getMegrate', 'uses' => 'SetupController@getMegrate',
    ]);

    Route::post('/setup/step-3', [
        'as' => 'setupStep2', 'uses' => 'SetupController@setupStep2',
    ]);

    Route::post('/setup/step-4', [
        'as' => 'setupStep3', 'uses' => 'SetupController@setupStep3',
    ]);

    Route::post('/setup/step-5', [
        'as' => 'setupStep4', 'uses' => 'SetupController@setupStep4',
    ]);

    Route::post('/setup/lastStep', [
        'as' => 'lastStep', 'uses' => 'SetupController@lastStep',
    ]);

    Route::get('setup/lastStep', function () {
        return redirect('/setup', 301);
    });

} else {
    Route::any('/setup/{vue}', function () {
        abort(403);
    });
}

//------------------------------------------------------------------\\

Route::group(['middleware' => ['auth', 'Is_Active']], function () {
    Route::get('/check-ref', function (Request $request) {
        $imeiNumber = $request->input('imei');

        $result = DB::table('purchase_details')
            ->select('purchases.Ref')
            ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->join(DB::raw('(SELECT 0 as digit UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3) as n'), function($join) use ($imeiNumber) {
                $join->on(DB::raw('LENGTH(REPLACE(TRIM(imei_number), ",", ""))'), '<=', DB::raw('LENGTH(TRIM(imei_number)) - n.digit'));
            })
            ->whereRaw('TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(imei_number), ",", n.digit+1), ",", -1)) = ?', [$imeiNumber])
            ->first();

        if ($result) {
            return response()->json(['Ref' => $result->Ref]);
        } else {
            return response()->json(['error' => 'Not found'], 404);
        }
    });

    Route::get('/login', function () {
        $installed = Storage::disk('public')->exists('installed');
        if ($installed === false) {
            return redirect('/setup');
        } else {
            return redirect('/login');
        }
    });


    Route::get('/{vue?}',
        function () {
            $installed = Storage::disk('public')->exists('installed');

            if ($installed === false) {
                return redirect('/setup');
            } else {
                return view('layouts.master');
            }
        })->where('vue', '^(?!api|setup|update|password).*$');


    });

    Auth::routes([
        'register' => false,
    ]);


//------------------------------------------------------------------\\

Route::group(['middleware' => ['auth', 'Is_Active']], function () {

    Route::get('/update', 'UpdateController@viewStep1');

    Route::get('/update/finish', function () {

        return view('update.finishedUpdate');
    });

    Route::post('/update/lastStep', [
        'as' => 'update_lastStep', 'uses' => 'UpdateController@lastStep',
    ]);

});


