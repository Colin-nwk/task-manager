<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Member extends Pivot
{
    protected $hidden = [
        'email_verified_at',
        'created_at',
        'updated_at'
    ];
}
