<?php

namespace App\Exceptions;

use Exception;

class SummaryException extends Exception
{
    protected $message = "There was a problem calculating the summary";
}
