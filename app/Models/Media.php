<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
//use Spatie\MediaLibrary\Models\Media as BaseMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia{
        getFirstMediaUrl as protected getFirstMediaUrlTrait;
    }

    protected $appends = [
        'url',
        'thumb',
        'icon',
        'formated_size'
    ];

    /**
     * to generate media url in case of fallback will
     * return the file type icon
     * @param string $conversion
     * @return string url
     */
    public function getFirstMediaUrl($collectionName = 'default',$conversion = '')
    {
        $url = $this->getFirstMediaUrlTrait($collectionName);
        $array = explode('.', $url);
        $extension = strtolower(end($array));
        if (in_array($extension,config('medialibrary.extensions_has_thumb'))) {
            return asset($this->getFirstMediaUrlTrait($collectionName,$conversion));
        }else{
            return asset(config('medialibrary.icons_folder').'/'.$extension.'.png');
        }
    }

    public function getUrlAttribute()
    {
        return $this->getFirstMediaUrlTrait();
    }

    public function getThumbAttribute()
    {
        return $this->getFirstMediaUrl('thumb');
    }

    public function getIconAttribute()
    {
        return $this->getFirstMediaUrl('icon');
    }

    public function getFormatedSizeAttribute()
    {
        return formatedSize($this->size);
    }   
}