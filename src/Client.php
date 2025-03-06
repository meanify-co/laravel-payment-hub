<?php

namespace Meanify\LaravelPaymentHub;

use Illuminate\Support\Facades\Log;

trait Client
{
    use HandleResult;

    private $apiRequest;

    /**
     * @param $request
     * @return void
     */
    private function setApiRequest($request)
    {
        $this->apiRequest = $request;
    }

    /**
     * @return array
     */
    public function send($formatResult = true)
    {
        $success = true;
        $message = '';
        $has_exception = false;

        $arguments = [];

        if(isset($this->apiRequest->headers))
        {
            $arguments['headers'] = $this->apiRequest->headers;
        }
        if(isset($this->apiRequest->body))
        {
            $arguments['body'] = $this->apiRequest->body;
        }
        if(isset($this->apiRequest->formParams))
        {
            $arguments['form_params'] = $this->apiRequest->formParams;
        }
        if(isset($this->apiRequest->queryParams))
        {
            $arguments['query_params'] = $this->apiRequest->queryParams;
        }

        try
        {
            $client = new \GuzzleHttp\Client();
            $result = $client->request($this->apiRequest->method, $this->apiRequest->endpoint, $arguments);
            $result = json_decode($result->getBody(), false);
        }
        catch (\GuzzleHttp\Exception\RequestException $exception)
        {
            Log::info('***********************************************');
            Log::error((string) $exception->getResponse()->getBody());
            Log::info($this->apiRequest->method);
            Log::info($this->apiRequest->endpoint);
            Log::info(json_encode($arguments,256));
            Log::info('***********************************************');

            $has_exception = true;

            $success    = false;
            $message    = 'Error on request: '.((string) $exception->getResponse()->getBody());
            $full_error = json_decode((string) $exception->getResponse()->getBody());
            $result     = null;
        }

        if($formatResult and isset($result))
        {
            $result = $this->format($result);
        }

        $response['success'] = $success;
        $response['message'] = isset($message) ? $message : null;

        if($has_exception)
        {
            $response['gateway_response'] = $full_error ?? null;
        }

        $response['result']  = isset($result) ? $result : null;
        return $response;
    }
}
