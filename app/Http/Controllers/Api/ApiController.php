<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $statusCode = Response::HTTP_OK;

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnNotFoundFailure($message)
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)->formatRespond($message ?: 'Not Found');
    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function returnValidationFailure($message = 'Validation failure', $data = null)
    {
        return $this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)->formatRespond($message, $data);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $message
     * @param null $errors
     * @return \Illuminate\Http\JsonResponse
     */
    private function formatRespond($message, $errors = null) {
        if ($errors) {
            return response()->json([
                'message'   =>  $message,
                'meta'      =>  ['code' => $this->getStatusCode(), 'status' => 'failure'],
                'data'      =>  $errors
            ], $this->getStatusCode());
        } else {
            return response()->json([
                'message'   =>  $message,
                'meta'      =>  ['code' => $this->getStatusCode(), 'status' => 'failure']
            ], $this->getStatusCode());
        }
    }
}
