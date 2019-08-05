<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Cmgmyr\Messenger\Traits\Messagable;
use App\Notifications\VerifyEmail;
use App\Notifications\ResetPassword;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use TCG\Voyager\Traits\VoyagerUser;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable, Messagable, Sluggable, VoyagerUser, CanFollow, CanBeFollowed;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

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
    ];

    /**
     * @return array
     */
    public function sluggable()
    {
        return [
            'nickname' => [
                'source' => $this->nickname ? 'nickname' : 'name'
            ],
        ];
    }

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => \App\Events\UserCreatedEvent::class,
    ];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        $name = "{$this->first_name} {$this->middle_name} {$this->last_name}";
        return preg_match('/\S/', $name) ? $name : "{$this->nickname}";
    }

    /**
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function oauthProviders()
    {
        return $this->hasMany(OAuthProvider::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function account()
    {
        return $this->hasOne(Account::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function streams()
    {
        return $this->hasMany(Stream::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function votes()
    {
        return $this->hasMany(Vote::class, 'user_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * @return int
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @param bool $type
     * @return mixed
     */
    public function getTransactions($type = false)
    {
        switch($type)
        {
            case 'sent':
                return Transaction::where('account_sender_id', $this->account->id);
            break;
            case 'received':
                return Transaction::where('account_receiver_id', $this->account->id);
            break;
            default:
                return Transaction::where('account_sender_id', $this->account->id)
                    ->orWhere('account_receiver_id', $this->account->id);
            break;
        }
    }
}
