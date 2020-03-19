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
        return response()->json([
            'message'   => 'Cannot access this resource or unauthenticated'
        ]);
        
    }

    private function getUser($request){
        try {
            $auth_url = config('micro')['url']['auth'];
            $client = new Client([
                'base_uri'  => $auth_url,
                'timeout'   => (isset(config('micro')['timeout'])?config('micro')['timeout']:10)
            ]);
            $response = $client->request('GET', config('micro')['endpoint']['profile'], [
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
