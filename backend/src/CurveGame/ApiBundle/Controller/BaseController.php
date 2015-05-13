<?php

namespace CurveGame\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use CurveGame\ApiBundle\Exception\ApiException;

class BaseController extends Controller {

    /**
     * Checks if the received json is valid and extracts it.
     *
     * @param null $json
     * @return Object
     * @throws ApiException
     */
    public function extractJson($json = null) {

        if (empty($json)) return false;

        $obj = json_decode($json);

        if (json_last_error() === JSON_ERROR_NONE) {

            return $obj;
        } else {

            throw new ApiException(ApiException::HTTP_BAD_REQUEST, "The JSON was invalid");
        }
    }

    /**
     * Creates and returns a Response object with provided data.
     * Content-Type and charset headers are already automatically set, but can be overridden.
     *
     * @param array $json
     * @param int $statusCode
     * @param string $charset
     * @param array $headers
     * @return Response
     * @throws ApiException
     */
    public function jsonResponse($json = null, $statusCode = 200, $charset = 'UTF-8', Array $headers = null) {

        $response = new Response();

        if(is_bool($json)) {

            if ($json === true) {

                $json = 'TRUE given';
            } else {

                $json = 'FALSE given';
            }

            throw new ApiException(ApiException::HTTP_INTERNAL_SERVER_ERROR, 'Response cannot be a boolean: ' . $json);
        }

        if (!empty($json) && is_array($json)) {

            $response->setContent(json_encode($json));
        }
        else if (!is_array($json)) {

            $response->setContent($json);
        }

        $response
            ->setCharset($charset)
            ->setStatusCode($statusCode);

        if (isset($headers) && is_array($headers)) {

            foreach ($headers as $name => $value) {

                $response->headers->set($name, $value);
            }
        }

        if (!$response->headers->has(strtolower('content-type'))) {

            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }
}