<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementAttribute extends Model
{
    use HasFactory;
    protected $fillable=[
        'advertisement_id',
        'attribute_id',
        'value'
    ];
    protected $append=['value_name'];
    protected $table='advertisement_attribute';

    public function attribute(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Attribute::class,'attribute_id');
    }
    public function getValueNameAttribute()
    {
        return AttributeValue::query()->find($this->value);
    }

    public function attributeValue(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\AttributeValue::class,'value');
    }
}
