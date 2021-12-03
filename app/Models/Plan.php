<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

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
}
