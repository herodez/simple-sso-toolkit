<?php


namespace App\Controller;


use App\Utils\DataAccessor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController
{
    /**
     * @param Request $request
     * @param DataAccessor $db
     * @return Response
     */
    public function index(Request $request, DataAccessor $db)
    {
        $data = $db->getConfigData()['user_data'];
        $template = str_replace('{user_data}', $data, file_get_contents(__DIR__ . '/../Templates/index.html'));
        return new Response($template);
    }
}