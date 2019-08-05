<?php


namespace App\Controller;


use App\Utils\DataAccessor;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController
{
    /**
     * @param Request $request
     * @param DataAccessor $db
     * @return Response
     */
    public function verify(Request $request, DataAccessor $db)
    {
        $verify = $db->getVerify() ? '1' : '0';
        return new Response($verify);
    }
    
    /**
     * @param Request $request
     * @param DataAccessor $db
     * @return RedirectResponse|Response
     */
    public function login(Request $request, DataAccessor $db)
    {
        if ($request->isMethod('GET')) {
            if(!$request->query->has('_target')){
                return new Response('Parameter _target is required', Response::HTTP_BAD_REQUEST);
            }
            
            $path = $request->get('_target');
            $targetPathAndQueries = explode('?', $path);
            $otp = 'token';
            
            if(count($targetPathAndQueries) === 2){
                $target = $targetPathAndQueries[0];
                $queries = $targetPathAndQueries[1] . '&_sso_otp='.urlencode($otp);
                $path = $target . '?' . $queries;
            }
            else{
                $path .= '?_sso_otp='.urlencode($otp);
            }
            
            return new RedirectResponse($path);
        }
        
        $data = json_decode($db->getUserData(), true);
        $data = base64_encode(serialize($data));
        
        return new Response($data);
    }
}