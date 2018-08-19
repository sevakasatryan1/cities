<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UpdateConfig extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'update_config';

    protected $fillable = [
        'started',
        'last_modified',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


}
