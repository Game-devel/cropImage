<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CropImage extends Model
{
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_img', 'full_img', 'croped_img'
    ];
}
