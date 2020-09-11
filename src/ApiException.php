<?php

namespace aWhere;

/**
 * Exception thrown when there is an API error.
 * https://docs.awhere.com/knowledge-base-docs/api-design-conventions#9-error-messages
 *
 * @package aWhere
 */
class ApiException extends \Exception
{
    /**
     * @var string HTTP status code
     */
    protected $statusCode;

    /**
     * @var string HTTP status name
     */
    protected $statusName;

    /**
     * @var string Internal error ID by aWhere API
     */
    protected $errorId;

    /**
     * @var string A more detailed error message
     * designed for developers.
     */
    protected $detailedMessage;

    /**
     * Factory method to create a new exception with a normalized error message
     *
     * @param response $response JSON response received
     */
    public static function create($response): self
    {
        $exception = new self;
        $exception->statusCode = $response->statusCode;
        $exception->statusName = $response->statusName;
        $exception->errorId = $response->errorId;
        $exception->message = $response->simpleMessage;
        $exception->detailedMessage = $response->detailedMessage;
        return $exception;
    }

    public function getDetailedMessage()
    {
        return $this->detailedMessage;
    }

    public function __toString()
    {
        return __CLASS__ . ": [$this->statusCode]: $this->simpleMessage\n";
    }
}
