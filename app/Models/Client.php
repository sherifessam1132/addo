<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Client extends Authenticatable
{
    use HasFactory,Notifiable,HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'gender',
        'phone',
        'social_id',
        'city_id',
        'longitude',
        'latitude',
        'fb_token'
    ];

    const SEARCHFIELDS=[
        'name',
        'email',
        'phone',
    ];

    protected $appends=['action','image_path'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'action'
    ];

    public function getImagePathAttribute(): string
    {
        return filter_var($this->image, FILTER_VALIDATE_URL) ?$this->image : asset('storage/uploads/client_images/'.$this->image );
    }

    public function advertisements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Advertisement::class,'client_id');
    }

    public function reactions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        if (request()->input('type') != null)
        {
            return $this->belongsToMany(\App\Models\Advertisement::class,'reactions')
                ->withPivot('type')
                ->wherePivot('type',request()->input('type'));
        }
        return $this->belongsToMany(\App\Models\Advertisement::class,'reactions')
            ->withPivot('type');
    }

    public function roomsAsBuyer(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Room::class,'buyer')->whereHas('messages')
//            ->with(['messages' => function ($query) {
//               return $query->limit(1);
//            }])
            ;
    }
    public function roomsAsSeller(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Room::class,'seller')->whereHas('messages')
//            ->with(['messages' => function ( $query) {
//                return $query->limit(1);
//            }])
            ;
    }
    public function rooms(){
        return $this->roomsAsBuyer->merge($this->roomsAsSeller)->load(['buyer','seller']);
    }

    public function city(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\City::class,'city_id');
    }

}
