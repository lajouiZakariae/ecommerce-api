<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'platform',
        'settings_value',
        'settings_default'
    ];

    protected $hidden = ['id', 'settings_default'];

    protected $casts = ['settings_value' => 'object', 'settings_default' => 'object'];

    public $timestamps = false;

    public function getRouteKeyName()
    {
        return "platform";
    }
}
