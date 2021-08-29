<?php

namespace App\Exceptions;

use Exception;

class SaveException extends Exception
{
    protected $message = "There was a problem saving";
}
