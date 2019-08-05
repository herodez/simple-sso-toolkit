<?php


namespace App\Utils;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ServerLogger
{
    public static function log(Request $request, Response $response)
    {
        $date = date('D M  j H:i:s Y');
        $messageFormat = "[{$date}] " . $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'] . ' [{statusCode}]: ' . $_SERVER['REQUEST_URI'] . ' {message}' . PHP_EOL;
        
        /** @var Response $response */
        if ($response->isOk() || $response->isRedirection()) {
            $message = str_replace(array('{statusCode}', '{message}'),
                array($response->getStatusCode(), ''), $messageFormat);
            file_put_contents('php://stdout', $message);
        } else {
            $message = str_replace(array('{statusCode}', '{message}'),
                array($response->getStatusCode(), ' - ' . $response->getContent()), $messageFormat);
            file_put_contents('php://stderr', $message);
        }
    }
}