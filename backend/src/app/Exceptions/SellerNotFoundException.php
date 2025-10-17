<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SellerNotFoundException extends Exception
{
    public function __construct(string $message = "Seller not found.", int $code = ResponseAlias::HTTP_NOT_FOUND, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}
