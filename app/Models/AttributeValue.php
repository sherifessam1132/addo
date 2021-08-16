<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;
    use Translatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attribute_id',
        'image',
        'value_id'
    ];


    public $translatedAttributes = ['value'];

    protected $appends=['image_path'];

    public function getImagePathAttribute(): string
    {
        return asset('storage/uploads/attribute_values_images/'.($this->image));
    }
}
