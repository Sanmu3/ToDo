<?php

namespace App\Helpers;
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

    public static function success($data = null, $message = null, $code = 200,$status = "success")
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

    public static function error($data = null, $message = null, $code = 500, $status = "error")
    {
        self::$responses['meta']['code'] = $code;
        self::$responses['meta']['status'] = $status;
        self::$responses['meta']['message'] = $message;
        self::$responses['data'] = $data;

        return response()->json(self::$responses, self::$responses['meta']['code']);
    }
}
