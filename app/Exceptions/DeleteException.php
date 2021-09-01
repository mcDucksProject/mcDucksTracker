<?php

namespace App\Exceptions;

use Exception;

class DeleteException extends Exception
{
    protected $message = "There was a problem deleting";
}
