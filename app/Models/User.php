<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Cmgmyr\Messenger\Traits\Messagable;
use App\Notifications\VerifyEmail;
use App\Notifications\ResetPassword;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Cviebrock\EloquentSluggable\Sluggable;
use Overtrue\LaravelFollow\Traits\CanFollow;
use Overtrue\LaravelFollow\Traits\CanBeFollowed;
use \Znck\Eloquent\Traits\BelongsToThrough;
use Illuminate\Support\Facades\DB;

class User extends \TCG\Voyager\Models\User implements JWTSubject, MustVerifyEmail
{
    use Notifiable, Messagable, Sluggable, CanFollow, CanBeFollowed, BelongsToThrough;

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
        'settings' => 'array'
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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at'];

    /**
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => \App\Events\UserCreatedEvent::class,
        'deleting' => \App\Events\UserDeletingEvent::class,
    ];

    /**
     * @var array
     */
    public static $roleList = [
        1 => 'admin',
        2 => 'user',
        3 => 'streamer',
        4 => 'advertiser'
    ];

    /**
     * @param $value
     */
    public function setSettingsAttribute($value)
    {
        $this->attributes['settings'] = $value && is_array($value) ? json_encode($value) : $value;  //$value->toJson();
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        $name = "{$this->name} {$this->middle_name} {$this->last_name}";
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
     * Get the oauth providers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function advCampaigns()
    {
        return $this->hasMany(AdvCampaign::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function account()
    {
        return $this->hasOne(Account::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function channel()
    {
        return $this->hasOne(Channel::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function streams()
    {
        return $this->hasManyThrough('App\Models\Stream', 'App\Models\Channel');
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

    public function clearFakeData()
    {
        DB::beginTransaction();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        $this->account->reset();

        $tsIds = [];
        if($this->channel)
        {
            $stIds = $this->channel->streams->pluck('id')->toArray();
            $tsIds = Task::whereIn('stream_id', $stIds)->pluck('id')->toArray();
        }

        $tsIds = array_merge($tsIds, $this->tasks()->pluck('id')->toArray());

        Transaction::whereIn('task_id', $tsIds)->delete();
        Vote::whereIn('task_id', $tsIds)->delete();
        Task::whereIn('id', $tsIds)->delete();

        //user_roles
        //followables
        Message::where('user_id', $this->id)->forceDelete();
        Participant::where('user_id', $this->id)->forceDelete();

        $this->streams()->delete();
        $this->getTransactions()->delete();
        $this->notifications()->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        DB::commit();
    }

    /**
     * @param $data
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function updateThrowOauth($data)
    {
        try {
            $user = DB::transaction(function () use ($data) {
                $data['fake'] = false;
                return $this->update($data);
            });
        } catch (\Exception $e) {
            return response('An Error with updating user info from oauth', 422);
        }

        return $user;
    }

    /**
     * @param $channel_id
     * @return bool
     */
    public function ownerOfChannel($channel_id)
    {
        return isset($this->channel) && $channel_id==$this->channel->id;
    }

    public function isUser()
    {
        return $this->role_id===1;
    }

    public function isAdmin()
    {
        return $this->role_id===2;
    }

    public function isStreamer()
    {
        return $this->role_id===3;
    }

    public function isAdvertiser()
    {
        return $this->role_id===4;
    }

    public static function getRoleSlug($role_id)
    {
        return isset(self::$roleList[$role_id]) ? self::$roleList[$role_id] : null;
    }

    public static function getRoleIdBySlug($role)
    {
        return array_search($role, self::$roleList)!==false ? array_search($role, self::$roleList) : null;
    }
}
