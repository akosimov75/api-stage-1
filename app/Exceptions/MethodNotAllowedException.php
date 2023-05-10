<?php
namespace App\Exceptions;

class MethodNotAllowedException extends \Exception
{
    protected $message = 'error => Method Not Allowed';
    protected $code = 405;
}
