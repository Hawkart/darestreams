<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquire extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inquires';

    protected $fillable = [
        'title', 'name', 'phone', 'email'
    ];
}