<?php

namespace RcmIssuu\Service;

use Guzzle\Http\Client;

class IssuuApi
{
    public function getEmbed($url, $width, $height)
    {
        $endPoint = 'https://issuu.com/oembed';

        $send = array(
            'url' => $url,
//            'maxwidth' => 1024,
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

        return $jsonData['html'];
    }
}
