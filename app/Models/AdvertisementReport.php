<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementReport extends Model
{
    use HasFactory,Translatable;

    protected $fillable=[
        'advertisement_id'
    ];
    const SEARCHFIELDS=[
        'translation' => [
            'name'
        ]
    ];
    public $translatedAttributes = ['name'];

    public function advertisement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Advertisement::class,'advertisement_id');
    }
}
