<?php

namespace Febrianrz\Micromidlleware;

use Closure;
use GuzzleHttp\Client;

class MicroAuthenticate
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
        $user = $this->getUser($request);
        if($user){
            $request->merge([
                'user'  => $user
            ]);
            return $next($request);
        } 
        // return response()->json([
        //     'message'   => 'Cannot access this resource or unauthenticated'
        // ]);
        return abort(401,'Cannot access this resource or unauthenticate server');
        
    }

    private function getUser($request){
        try {
            $auth_url = config('micro')['url']['auth'];
            $client = new Client([
                'base_uri'  => $auth_url,
                'timeout'   => (isset(config('micro')['timeout'])?config('micro')['timeout']:10)
            ]);
            if(config('micro')['app']['auth_path']){
                $uri = config('micro')['app']['auth_path'] . config('micro')['endpoint']['profile'];
            } else {
                $uri = config('micro')['endpoint']['profile'];
            }
            $response = $client->request('GET', $uri, [
                'headers' => [
                    'Authorization' => $request->header('Authorization'),
                    'Accept'     => 'application/json',
                ]
            ]);
            if($response->getStatusCode() == 200){
                return json_decode($response->getBody()->getContents());
            } 
            return null;
        } catch(\Exception $e){
            return abort(500,$e->getMessage());
        }
    }
}
