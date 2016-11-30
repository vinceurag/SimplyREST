<?php

class HTTP_Status {

    /**
     * Successful request
     * @var integer
     */
    const HTTP_OK = 200;

    /**
     * Successful creation
     * @var integer
     */
    const HTTP_CREATED = 201;

    /**
     * requested resource could not be found
     * @var integer
     */
    const HTTP_NOT_FOUND = 404;

    /**
     * when accessing a resource without/invalid authorization
     * @var integer
     */
    const HTTP_UNAUTHORIZED = 401;

    /**
     * invalid/malformed request
     * @var integer
     */
    const HTTP_BAD_REQUEST = 400;
}
