<?php

namespace App\Controllers;

class ErrorController
{
    /**
     * Error 404 function
     * 
     * @return void
     */

    public static function notFound($message = 'Oops Page Not Found')
    {
        http_response_code(404);

        loadView('error', ['status' => 404, 'message' => $message]);
    }

    /**
     * Error 403 function
     * 
     * @return void
     */

    public static function unauthorized($message = 'Oops, you are not authorized to view this page')
    {
        http_response_code(403);

        loadView('error', ['status' => 403, 'message' => $message]);
    }
}
