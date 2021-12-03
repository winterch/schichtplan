<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'title',
        'group',
        'description',
        'start',
        'end',
        'team_size',
    ];

    public function plan() {
        return $this->belongsTo(Plan::class);
    }
}
