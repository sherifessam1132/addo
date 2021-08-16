<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementImage extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'advertisement_id',
    ];

    protected $appends=['image_path'];

    public function getImagePathAttribute(): string
    {
        return asset('storage/uploads/advertisement_images/'.($this->image));
    }

    public function advertisement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Advertisement::class,'advertisement_id');
    }
}
