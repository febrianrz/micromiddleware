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
        
        return abort(401,'Cannot access this resource or unauthenticate server');
        
    }

    private function getUser($request){
        try {
            $type = isset(config('micro')['endpoint']['type']) ? config('micro')['endpoint']['type'] : 'v1';
            if($type == 'v2') return $this->v2($request);
            else return $this->v1($request);
        } catch(\Exception $e){
            return abort(401,$e->getMessage());
        }
    }

    private function v1($request){
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
        if($response->getStatusCode() != 200){
            throw new \Exception('Unauthorize');
        } 
        return json_decode($response->getBody()->getContents());
    }

    private function v2($request){
        $auth_url = config('micro')['url']['auth'];
        $client = new Client([
            'base_uri'  => $auth_url,
            'timeout'   => (isset(config('micro')['timeout'])?config('micro')['timeout']:10)
        ]);
        if(config('micro')['app']['auth_path']){
            $uri = config('micro')['app']['auth_path'] . config('micro')['endpoint']['profile'];
        } else {
            $uri = '/api/v2/profile';
        }
        $response = $client->request('GET', $uri, [
            'headers' => [
                'Authorization' => $request->header('Authorization'),
                'Accept'     => 'application/json',
            ],
            'query'     => [
                'app_id'    => config('micro')['app']['scope_app_id'],
                'with_attributes'    => true,
                'with_fields'    => true,
                'with_applications'    => true,
            ]
        ]);
        if($response->getStatusCode() != 200){
            throw new \Exception('Unauthorize');
        } 
        return json_decode($response->getBody()->getContents());
    }
}
