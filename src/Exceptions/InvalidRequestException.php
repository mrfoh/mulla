<?php

namespace Mrfoh\Mulla\Exceptions;

use Exception;

class InvalidRequestException extends Exception {
    /**
     * @var array
     */
    private $messages;

    /**
     * InvalidRequestException constructor.
     * @param string|null $message
     * @param array $messages
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(string $message = null, array $messages, int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->messages = $messages;
    }

    /**
     * Get validation messages
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}
