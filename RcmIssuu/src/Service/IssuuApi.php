<?php

namespace RcmIssuu\Service;

use Guzzle\Http\Client;

class IssuuApi
{
    public function getEmbed($userName, $docTitle)
    {
        $endPoint = 'https://issuu.com/oembed';

        $url = 'http://issuu.com/'.$userName.'/docs/'.$docTitle;

        $send = array(
            'url' => $url,
            'format' => 'json'
        );

        $client = new Client();

        $request = $client->get($endPoint, array(), array(
            'query' => $send
        ));

        $response = $request->send();

        $statusCode = $response->getStatusCode();

        if ($statusCode != 200) {
            throw new \Exception('Unable to get document from Issuu');
        }

        $jsonData = $response->json();

        if (!$jsonData['html']) {
            throw new \Exception('Invalid Format for response');
        }

        return $jsonData;
    }
}
