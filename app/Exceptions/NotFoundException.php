<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    protected $message = "The search bore no results";
}
