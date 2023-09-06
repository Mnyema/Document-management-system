<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    // protected $guarded=[];
    
protected $fillable=[
  'file',
];
    public function mediaItems()
    {
        return $this->hasMany(Media::class, 'model_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('docs');
             //->acceptsMimeTypes(['application/pdf', 'application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'text/plain']);
           //  ->singleFile();
    }
   
}
