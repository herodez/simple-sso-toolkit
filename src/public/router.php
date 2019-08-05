<?php

use App\Kernel;
use App\Utils\ServerLogger;
use Symfony\Component\HttpFoundation\Request;

require 'vendor/autoload.php';

if (preg_match('/\.(?:png|jpg|jpeg|gif|css|svg|js)$/', $_SERVER['REQUEST_URI'])) {
    return false;
} else {
    // Create a instance of kernel.
    $request = Request::createFromGlobals();
    $kernel = new Kernel($request->getHost(), $request->getPort());
    
    // Handle request.
    $response = $kernel->handler($request);
    $response->send();
    ServerLogger::log($request, $response);
}
