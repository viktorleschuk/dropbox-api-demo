<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\File;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\Flysystem\Config;
use League\Flysystem\Util;

/**
 * CRUD for files resource
 *
 * Class FilesController
 * @package App\Http\Controllers\Api
 */
class FilesController extends ApiController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $files = Storage::listContents('', true);

        return File::collection(collect($files)->filter(function($item) { return Arr::get($item, 'type') !== 'dir'; }));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function preview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path'  =>  'required'
        ]);

        if ($validator->fails()) {
            return $this->returnValidationFailure('Validation failure', $validator->errors());
        }

        if (! Storage::has($request->get('path'))) {
            return $this->returnNotFoundFailure('File not found');
        }

        return response()->json(['path' => Storage::getAdapter()->getTemporaryLink($request->get('path'))]);
    }

    /**
     * @param Request $request
     * @return File|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file'  =>  'required'
        ]);

        if ($validator->fails()) {
            return $this->returnValidationFailure('Validation failure', $validator->errors());
        }

        $metadata = Storage::getMetadata(Storage::putFileAs('', $request->file('file'), $request->file('file')->getClientOriginalName()));

        $metadata += Util::pathinfo($metadata['path']);

        return new File($metadata);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'path'  =>  'required'
        ]);

        if ($validator->fails()) {
            return $this->returnValidationFailure('Validation failure', $validator->errors());
        }

        Storage::delete($request->get('path'));

        return response()->json();
    }
}
