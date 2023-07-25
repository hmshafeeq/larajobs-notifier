<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    protected $fillable = [
        'title',
        'link',
        'pub_date',
        'creator',
        'location',
        'job_type',
        'salary',
        'company',
        'company_logo',
        'tags',
    ];
}
