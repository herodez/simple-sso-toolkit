<?php


namespace App;


use App\Controller\DefaultController;
use App\Controller\LoginController;

class Routing
{
    /**
     * @param $path
     * @return mixed
     */
    public function resolve($path)
    {
        return $this->getController($path);
    }
    
    private function getController($path)
    {
        $routes = [
            ['path' => '/', 'controller' => DefaultController::class, 'method' => 'index'],
            ['path' => '/simple-sso/verify', 'controller' => LoginController::class, 'method' => 'verify'],
            ['path' => '/simple-sso/login', 'controller' => LoginController::class, 'method' => 'login']
        ];
        
        foreach ($routes as $route) {
            if ($route['path'] === $path) {
                $route['controller'] = new $route['controller'];
                return $route;
            }
        }
        
        return null;
    }
    
    
}