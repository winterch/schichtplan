<?php

namespace App\Models;

use App\Notifications\SendUnsubscribeConfirmation;
use App\Notifications\SendShiftReminder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class Subscription extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'comment',
        'notification',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'phone',
        'email',
        'comment',
        'confirmation'
    ];

    /**
     * Subscription belongs to a shift
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shift() {
        return $this->belongsTo(Shift::class);
    }

    public function sendUnsubscribeConfirmation() {
        $this->confirmation = Str::random(24);
        $this->save();
        $link = route('plan.subscription.confirmRemove', [
            'plan' => $this->shift()->get()[0]->plan()->get()[0],
            'shift' => $this->shift()->get()[0],
            'confirmation' => $this->confirmation]);
        $this->notify(new SendUnsubscribeConfirmation($link));
    }

    public function sendReminder() {
        $plan = $this->shift()->get()[0]->plan()->get()[0];
        $viewLink = route('plan.show', ['plan' => $plan->view_id]);
        $summary = array();
        foreach ($plan->shifts()->get() as $shift) {
            foreach ($shift->subscriptions()->get() as $sub) {
                if ($sub->email == $this->email) {
                    $summary[] = $shift->title . ': '. 
                        explode(' ', $shift->start)[1].' - '.
                        explode(' ',$shift->end)[1];
                    break;
                }
            }
        }
        $this->notify(new SendShiftReminder($viewLink, join(', ', $summary)));
    }

}
