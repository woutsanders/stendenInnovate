<?php

namespace CurveGame\ApiBundle\Exception;

/**
 * Class ApiException
 * @package CurveGame\ApiBundle\Exception
 */
class ApiException extends \Exception {

    // Create some human readable status codes to choose from.
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_NO_CONTENT = 204;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_METHOD_NOT_ALLOWED = 405;
    const HTTP_NOT_ACCEPTABLE = 406;
    const HTTP_CONFLICT = 409;
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_NOT_IMPLEMENTED = 501;

    // Map them with header strings.
    public static $messages = array(
        self::HTTP_OK			            => '200 OK',
        self::HTTP_CREATED		            => '201 Created',
        self::HTTP_NO_CONTENT	            => '204 No Content',
        self::HTTP_BAD_REQUEST	            => '400 Bad Request',
        self::HTTP_UNAUTHORIZED	            => '401 Unauthorized',
        self::HTTP_FORBIDDEN	            => '403 Forbidden',
        self::HTTP_NOT_FOUND	            => '404 Not Found',
        self::HTTP_METHOD_NOT_ALLOWED	    => '405 Method Not Allowed',
        self::HTTP_NOT_ACCEPTABLE           => '406 Not Acceptable',
        self::HTTP_CONFLICT		            => '409 Conflict',
        self::HTTP_INTERNAL_SERVER_ERROR    => '500 Internal Server Error',
        self::HTTP_NOT_IMPLEMENTED          => '501 Not Implemented',
    );

    /**
     * Throws the HTTP status code with a description (if given)
     * and exits.
     *
     * @param string $httpStatus
     * @param null $message
     */
    public function __construct($httpStatus, $message=null) {

        if (!array_key_exists($httpStatus, self::$messages)) {

            $httpStatus = self::HTTP_INTERNAL_SERVER_ERROR;
        }

        header('HTTP/1.1 ' . self::$messages[$httpStatus]);
        header('content-type: application/json');

        $arr = $message != null ? array('error'=>$message) : array();

        echo json_encode($arr);
        die;
    }
}