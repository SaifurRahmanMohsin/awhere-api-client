<?php

namespace aWhere;

/**
 * Exception thrown if the token is null or expired.
 *
 * @package aWhere
 */
class InvalidTokenException extends \Exception
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct("The token is invalid");
    }
}
