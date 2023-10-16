<?php

namespace Hani221b\Grace\Support;

class Response {
    public static function success($data = null, $message = '', $status = 200)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status
        ], $status);
    }

    public static function error($message = '', $status = 200)
    {
        return response()->json([
            'message' => $message,
        ], $status);
    }
}
