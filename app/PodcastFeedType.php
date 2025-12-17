<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastFeedType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'logo_sponsor',
    ];
}
