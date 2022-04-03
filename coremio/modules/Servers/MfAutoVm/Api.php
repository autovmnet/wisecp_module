<?php

class Api
{

    private \GuzzleHttp\Client $client;
    private $token;
    private $baseUrl;

    public function __construct($baseUrl, $token)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => $baseUrl
        ]);
    }

    public function get($path, $headers = [], $params = [])
    {
        $params = $this->setHeader($params);

        return $this->request('get', $path, $params);
    }

    public function post($path, $params = [], $headers = [])
    {
        $params['form_params'] = $params;
        $params = $this->setHeader($params);

        return $this->request('post', $path, $params);
    }

    private function request(string $method, $path, array $params)
    {
        try {
            $response = $this->client->request($method, $path, $params);
            $content = $response->getBody()->getContents();

            return json_decode($content, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $error = $e->getResponse()->getBody()->getContents();
            $response = json_decode($error, true);

            return [
                'status' => 'error',
                'errors' => (isset($response['data'])) ? $response['data'] : $response
            ];
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {

            return [
                'status' => 'error',
                'errors' => $e->getMessage()
            ];
        }

    }

    private function setHeader(array $params)
    {
        $params['headers']['token'] = $this->token;

        return $params;
    }
}