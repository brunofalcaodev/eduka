<?php

namespace Eduka\Http\Controllers\Auth;

use Eduka\Http\Controllers\Controller;
use Eduka\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Honeypot\ProtectAgainstSpam;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $redirectTo = '/videos';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware(ProtectAgainstSpam::class)->only('login');
    }

    /**
     * Redirect the user to the Twitter authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @return null|Eloquent\Model
     */
    public function handleProviderCallback($provider, Request $request)
    {
        $user = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($user, $provider);

        if ($authUser) {
            Auth::login($authUser, true);

            return redirect($this->redirectTo);
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request->merge(['email' => $user->email]));
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     *
     * @param   $user Socialite user object
     * @param   $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        // Do we have this user already registered via oauth?
        $authUser = User::where('provider_id', $user->id)
                        ->where('provider', $provider)
                        ->first();

        if ($authUser) {
            return $authUser;
        }

        // Do we have this user registered but not by oauth?
        $authUser = User::firstWhere('email', $user->email);

        if ($authUser) {
            $authUser->update(['provider_id' => $user->id,
                               'provider'    => $provider,
                               'name'        => $user->name,
                               'nickname'    => $user->nickname,
                               'avatar'      => $user->avatar, ]);

            return $authUser;
        }

        /* Create user */
        return User::create([
            'name'        => $user->name,
            'nickname'    => $user->nickname,
            'password'    => Hash::make(env('OAUTH_DEFAULT_PASSWORD')),
            'email'       => $user->email,
            'avatar'      => $user->avatar,
            'provider'    => $provider,
            'provider_id' => $user->id,
        ]);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|email:rfc,dns',
            'password' => 'required|string',
        ]);
    }

    protected function redirectTo()
    {
        return '/videos';
    }
}
