<?php

namespace CurveGame\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use CurveGame\ApiBundle\Exception\ApiException;

class BaseController extends Controller {

    /**
     * Shorthand method for retrieving the entity manager.
     *
     * @param null $manager
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function getEm($manager = null) {

        return $this->getDoctrine()->getManager($manager);
    }

    /**
     * Checks if the received json is valid and extracts it.
     *
     * @param null $json
     * @return Object
     * @throws ApiException
     */
    protected function extractJson($json = null) {

        if (empty($json)) return false;

        $obj = json_decode($json);

        // If the JSON is valid, return it.
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
    protected function jsonResponse($json = null, $statusCode = 200, $charset = 'UTF-8', Array $headers = null) {

        $response = new Response();

        // JSON cannot be a boolean, throw exception.
        if(is_bool($json)) {

            if ($json === true) {

                $json = 'TRUE given';
            } else {

                $json = 'FALSE given';
            }

            throw new ApiException(ApiException::HTTP_INTERNAL_SERVER_ERROR, 'Response cannot be a boolean: ' . $json);
        }

        // Encode if json is an array.
        if (!empty($json) && is_array($json)) {

            $response->setContent(json_encode($json));
        }

        // We've already got a JSON string, set it.
        else if (!is_array($json)) {

            $response->setContent($json);
        }

        // Populate response with correct HTTP headers.
        $response
            ->setCharset($charset)
            ->setStatusCode($statusCode);

        // If extra headers are passed, add it to the response.
        if (isset($headers) && is_array($headers)) {

            foreach ($headers as $name => $value) {

                $response->headers->set($name, $value);
            }
        }

        // Make sure that there is always the 'application/json' header (as we're working with JSON only anyways).
        if (!$response->headers->has(strtolower('content-type'))) {

            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }
}