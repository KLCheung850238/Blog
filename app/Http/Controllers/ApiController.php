<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp\Client;

class ApiController extends BaseController
{
    private $apiUrl;

    public function getAutoPassowrd()
    {
        $this->apiUrl = 'https://v.api.aa1.cn/api/api-mima/mima.php?msg=10';
        $re = file_get_contents($this->apiUrl);
        if (mb_strlen($re) == '10') {
            $arr['code'] = 200;
            $arr['msg'] = 'success';
            $arr['data'] = $re;
        } else {
            $arr['code'] = 400;
            $arr['msg'] = 'failed';
        }
        return response()->json($arr);
    }

    public function getNews()
    {
        $client = new Client(['timeout' => 30]);
        $request_header = array('Accept' => 'application/json');
        $url = 'https://newsapi.org/v2/top-headlines?country=us&category=business&apiKey=ea38e1e9b8c043929331d87f142d8719';
        $response = $client->request('GET', $url, ['headers' => $request_header]);
        if ($response->getStatusCode() == 200) {
            $arr['code'] = 200;
            $arr['msg'] = 'success';
            $arr['data'] = json_decode($response->getBody()->__toString());
        } else {
            $arr['code'] = 400;
            $arr['msg'] = 'failed';
            $arr['data'] = json_encode($response->getBody());
        }
        // return $arr['data'];
        return response()->json($arr);
    }


}