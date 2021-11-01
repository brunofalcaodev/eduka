<?php

use Illuminate\Support\Facades\Auth;

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
Route::get('/ppp', function () {
    session()->forget('website-checkout');
    session()->forget('website-paylink');

    return redirect(route('welcome', ['ppp' => 1]));
})->name('welcome.ppp');

if (env('EDUKA_LAUNCHED') == 1) {
    Auth::routes(['register' => false,
        'confirm' => false,
        'verify' => false, ]);
}

Route::redirect('/home', '/videos');

/*
 * Extremely important to have a throttle for the paylink since
 * hackers might try to charge cards in a bulk way.
 * This way it will block the user from trying again.
 * 3 requests per minute should be enough.
 */
Route::get('/paylink', function () {
    return redirect(WebsiteCheckout::make()->payLink());
})->name('checkout.paylink')
->middleware('throttle:3,1');

Route::get('/paylink-half', function () {
    session()->forget('website-checkout');
    session()->forget('website-paylink');

    return redirect(WebsiteCheckout::make()->payLinkHalf());
})->name('checkout.paylink.half')
->middleware('throttle:3,1');

Route::get('/webhook/download', function () {
    return response('Purchase successful. Thank you for buying the Mastering Nova course!. Hope you enjoy it as much as I did making it for you. Let me know if any issues!', 200);
});
