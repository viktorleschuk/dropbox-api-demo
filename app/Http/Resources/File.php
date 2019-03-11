<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Arr;

/**
 * Class File
 * @package App\Http\Resources
 */
class File extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'path'      =>  Arr::get($this, 'path'),
            'timestamp' =>  Arr::get($this, 'timestamp'),
            'size'      =>  Arr::get($this, 'size'),
            'type'      =>  Arr::get($this, 'type'),
            'dirname'   =>  Arr::get($this, 'dirname'),
            'basename'  =>  Arr::get($this, 'basename'),
            'extension' =>  Arr::get($this, 'extension'),
            'filename'  =>  Arr::get($this, 'filename'),
        ];
//  ]
    }
}