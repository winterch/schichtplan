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

class Plan extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract

{
    use \Illuminate\Auth\Authenticatable, Authorizable, CanResetPassword, HasFactory;

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
     * We will generate a uniqueLink on initial save
     * @inheritDoc
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        // add a unique_link for newly created plans
        if(empty($this->unique_link)) {
            // seed random generator
            srand(self::make_seed());
            $randomVal = rand();
            $this->unique_link = md5($randomVal);
        }
        return parent::save($options);
    }

    /**
     * Get the associated shifts for the plan
     * @return Shift[]
     */
    public function shifts()
    {
        // order by group, type and start date
        return $this->hasMany(Shift::class)->orderBy('group')->orderBy('type')->orderBy('start');
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
     * Generate seed for random generator
     * @return float
     */
    private static function make_seed()
    {
        list($usec, $sec) = explode(' ', microtime());
        return $sec + $usec * 1000000;
    }
}
