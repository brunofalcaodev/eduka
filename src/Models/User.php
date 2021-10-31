<?php

namespace Eduka\Models;

use Eduka\Mail\ResetYourPassword;
use Eduka\Mail\ThankYouForPurchasing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class User extends Authenticatable implements ShouldQueue
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',

        'is_subscribed' => 'boolean',
        'is_admin' => 'boolean',

        'properties' => 'array',
    ];

    public function videosCompleted()
    {
        return $this->belongsToMany(Video::class, 'videos_completed')
                    ->withTimestamps();
    }

    public function sendPasswordResetNotification($token)
    {
        Mail::to($this->email)
            ->send(new ResetYourPassword($this, $token));
    }

    public static function createFromPurchase(PaddleLog $checkout)
    {
        // Do we already have a user id with this email?
        if (User::where('email', $checkout->email)->exists()) {
            activity()
                ->withProperties(['checkout_data' => $checkout->get()])
                ->log('ERROR:: User made a payment but this email already exists on the database - '.$checkout->email);

            throw new \Exception('ERROR:: User made a payment but this email already exists on the database - '.$checkout->email);
        }

        // Instanciate a new user.
        $user = new User();

        // Populate the new user with the checkout fields.
        $user->email = $checkout->email;
        $user->invoice_link = $checkout->receipt_url;
        $user->name = $checkout->customer_name;
        $user->uuid = (string) Str::uuid();
        $user->mode = $checkout->mode;
        $user->password = bcrypt(Str::random(10));
        $user->save();

        $token = Password::broker()->createToken($user);

        // Send "thank you email".
        Mail::to($user->email)
            ->send(new ThankYouForPurchasing($user->email, $checkout->receipt_url, $token));

        return $user;
    }

    public function getFirstNameAttribute()
    {
        return Str::ucfirst(explode(' ', strtolower($this->name))[0]);
    }

    public function createPasswordResetTokenLinkFromPurchase()
    {
        $token = Str::random(60);

        // Delete any password reset (if requested before).
        DB::table('password_resets')->where('email', $this->email)->delete();

        DB::table('password_resets')->insert([
            'email' => $this->email,
            'token' => bcrypt($token),
            'created_at' => now(), ]);

        $link = url(route('password.reset', [
            'token' => $token,
            'email' => $this->email,
        ], false));

        return $link;
    }
}
