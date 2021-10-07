<?php

namespace App\Helpers;

use Illuminate\Http\Response;

/**
*
* API Response
*
*/

class Responses {

    /**
    *
    * Response Formatter
    *
    * @var array
    *
    */

    protected static $responses = [
        'meta' => [
            'code' => null,
            'status' => null,
            'message' => null,
        ],
        'data' => null
    ];

    /**
    *
    * Success Response
    *
    */

    public static function success($data = null, $message = null, $code = Response::HTTP_OK, $status = "success")
    {
        self::$responses['meta']['code'] = $code;
        self::$responses['meta']['status'] = $status;
        self::$responses['meta']['message'] = $message;
        self::$responses['data'] = $data;

        return response()->json(self::$responses, self::$responses['meta']['code']);
    }

    /**
    *
    * Error Response
    *
    */

    public static function error($data = null, $message = null, $code = Response::HTTP_INTERNAL_SERVER_ERROR, $status = "error")
    {
        self::$responses['meta']['code'] = $code;
        self::$responses['meta']['status'] = $status;
        self::$responses['meta']['message'] = $message;
        self::$responses['data'] = $data;

        return response()->json(self::$responses, self::$responses['meta']['code']);
    }
}
