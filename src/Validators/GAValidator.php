<?php

namespace Febrianrz\Micromidlleware\Validators;

use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;

class GAValidator
{
   
    public function validate(
        $attribute, 
        $value, 
        $parameters, 
        $validator)
    {
        $state = ($this->check($value))->state;
        return $state;
    }

    private function check($value){
        try {
            $auth_url = config('micro')['url']['auth'];
            $client = new Client([
                'base_uri'  => $auth_url,
                'timeout'   => (isset(config('micro')['timeout'])?config('micro')['timeout']:10)
            ]);
            $uri = '/api/v1/security-check';
            $response = $client->request('POST', $uri, [
                'headers' => [
                    'Authorization' => request()->header('Authorization'),
                    'Accept'     => 'application/json',
                ],
                'form_params' => [
                    'token' => $value,
                ]
            ]);
            
            if($response->getStatusCode() != 200){
                
            } 
            return (json_decode($response->getBody()->getContents()));
        } catch(\Exception $e){
            return (object)['state'=>false,'message'=>'Invalid Authenticator'];
        }
        
    }
}
