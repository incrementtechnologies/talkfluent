<?php

namespace App;
class ActiveCampaign
{
    public $params = array(
      'api_action' => 'contact_add',
      'api_key'    => null,
      'api_output' => 'json'
    );

    public $post;

    public $api;

    public $response;


    public function __construct($data){
      $this->post = $data;
      $this->params['api_key'] = env('ACTIVECAMPAIGN_API_KEY', null);
      $this->init();
      return $this->response;
    }

    public function init(){
      $query = "";
      foreach( $this->params as $key => $value ) $query .= urlencode($key) . '=' . urlencode($value) . '&';
      $query = rtrim($query, '& ');

      $data = "";
      foreach( $this->post as $key => $value ) $data .= urlencode($key) . '=' . urlencode($value) . '&';
      $data = rtrim($data, '& ');

      if ( !function_exists('curl_init') ) die('CURL not supported. (introduced in PHP 4.0.2)');

      if ( $this->params['api_output'] == 'json' && !function_exists('json_decode') ) {
          die('JSON not supported. (introduced in PHP 5.2.0)');
      }

      $url = env('ACTIVECAMPAIGN_API_URL', null);

      $url = rtrim($url, '/ ');

      $this->api = $url.'/admin/api.php?'.$query;
      $this->apiRequest($data);
    }

    public function apiRequest($data){
      $request = curl_init($this->api);
      curl_setopt($request, CURLOPT_HEADER, 0);
      curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($request, CURLOPT_POSTFIELDS, $data);
      curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);

      $this->response = (string)curl_exec($request);
      curl_close($request);
    }
}
