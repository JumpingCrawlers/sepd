<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PodcastFeadType extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'logo_sponsor',
        'image',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'logo_sponsor_src',
    ];

    public function getNombreAttribute ()
    {
        return $this->title;
    }

    public function getLogoSponsorSrcAttribute ()
    {
        return url(config('app.url_back')).'storage/'.$this->image;
    }
}
