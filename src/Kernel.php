<?php

namespace App;

use App\Utils\DataAccessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Kernel
{
    /**
     * @var DataAccessor
     */
    private $db;
    
    public function __construct($interface, $port)
    {
        // Initialize data base
        $this->db = new DataAccessor("{$interface}:$port");
        $this->db->initDataBase();
    }
    
    /**
     * @param Request $request
     * @return Response
     */
    public function handler(Request $request)
    {
        $routing = new Routing();
        $routing = $routing->resolve($request->getPathInfo());
        
        if ($routing === null) {
            return new Response('Page not found', Response::HTTP_NOT_FOUND);
        }
        
        return $routing['controller']->{$routing['method']}($request, $this->db);
    }
}