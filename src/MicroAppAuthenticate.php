<?php

namespace Febrianrz\Micromidlleware;

use Closure;
use GuzzleHttp\Client;

class MicroAppAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $check = $this->checkApp($request);
        if($check) {
            return $next($request);
        }
        return response()->json([
            'message'   => 'Cannot access this resource or unauthenticat server'
        ]);
        
    }

    private function checkApp($request){
        try {
            $auth_url = config('micro')['url']['auth'];
            $client = new Client([
                'base_uri'  => $auth_url,
                'timeout'   => 2.0
            ]);
            $response = $client->request('GET', config('micro')['endpoint']['app_check'], [
                'headers'   => [
                    'Accept'     => 'application/json',
                ],
                'query'     => [
                    'app_id'    => config('micro')['app']['id'],
                    'app_secret'    => config('micro')['app']['secret'],
                ]
            ]);
            if($response->getStatusCode() == 200){
                return true;
            } 
            return false;
        } catch(\Exception $e){
            return false;
        }
    }
}
