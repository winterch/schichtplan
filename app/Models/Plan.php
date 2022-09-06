<?php

namespace App\Models;

use App\Notifications\SendLinksNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Plan extends Model
{
    use HasFactory, Notifiable;

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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'owner_email',
        'edit_id',
        'view_id'
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

    /**
     * Check if any of the associated shifts has a specific type
     * @return bool
     */
    public function anyType()
    {
        foreach ($this->shifts as $shift) {
            if ($shift->type !== '')
                return true;
        }
        return false;
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
     * Send a notification with the edit and view links to the owner of the plan
     */
    public function sendLinksNotification() {
        $adminLink = route('plan.admin', ['plan' => $this->edit_id]);
        $viewLink = route('plan.show', ['plan' => $this->view_id]);
        $this->notify(new SendLinksNotification($this->title, $adminLink, $viewLink));
    }
}
