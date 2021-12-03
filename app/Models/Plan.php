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
            $randval = rand();
            $this->unique_link = md5($randval);
        }
        return parent::save($options);
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

    /**
     * Get the shifts for the plan
     * @return Shift[]
     */
    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }
}
