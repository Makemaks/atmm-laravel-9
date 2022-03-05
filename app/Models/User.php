<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function creditCard()
    {
        return $this->hasOne(CreditCard::class, 'credit_card_id', 'id');
    }

    public function subscriptionType()
    {
        return $this->hasOne(SubscriptionType::class, 'subscription_type_id', 'id');
    }

    public function trialLogs()
    {
        return $this->hasMany(InfusionsoftTrialLog::class, 'user_id', 'id')
                    ->orderBy('id', 'desc');

    }

    public function nmiPayments()
    {
        return $this->hasMany(Nmipayments::class, 'user_id', 'id')
                    ->orderBy('id', 'desc');

    }

    public function nmiTransactions()
    {
        return $this->hasMany(Nmitransactions::class, 'email', 'email')
                    ->orderBy('id', 'desc');

    }

}
