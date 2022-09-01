<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Notifications\ResetPasswordNotification;

class Plan extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract

{
    use \Illuminate\Auth\Authenticatable, Authorizable, CanResetPassword, HasFactory, Notifiable, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'contact',
        'owner_email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'owner_email',
        'remember_token',
    ];

    /**
     * @inheritDoc
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        // add a unique_link for newly created plans
        if(empty($this->edit_id)) {
            $this->edit_id = Str::random(24);
        }
        if(empty($this->view_id)) {
            $this->view_id = Str::random(32);
        }
        return parent::save($options);
    }

    /**
     * Get the associated shifts for the plan
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shifts()
    {
        // order by group, type and start date
        return $this->hasMany(Shift::class)->orderBy('group')->orderBy('type')->orderBy('start');
    }

    public function anyType()
    {
        foreach ($this->shifts as $shift) {
            if ($shift->type !== '')
                return true;
        }
        return false;
    }

    /**
     * Get the e-mail address where password reset links are sent.
     * Used for reset password email
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->owner_email;
    }

    /**
     * Route notifications for the mail channel.
     * This is a fix for a laravel problem with the reset mail
     *
     * @return string
     */
    public function routeNotificationForMail()
    {
        return $this->owner_email;
    }

    /**
     * Send custom mail to reset the password of a plan
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        // Build the route to reset the PW. This has to include the plan unique_link
        $url = route('password.reset', ['token' => $token, 'plan' => $this]);
        $this->notify(new ResetPasswordNotification($url));
    }

    /**
     * Generate seed for random generator
     * @return float
     */
    private static function make_seed()
    {
        list($usec, $sec) = explode(' ', microtime());
        return $sec + $usec * 1000000;
    }
}
