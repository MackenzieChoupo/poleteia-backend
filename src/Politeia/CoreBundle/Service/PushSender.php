<?php

namespace Politeia\CoreBundle\Service;

use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Recipient\Device;
use paragraph1\phpFCM\Notification;

class PushSender
{
    /**
     * Envoie une notification push
     *
     * @param string $text , string $title, array $tokens
     * @param string $title
     * @param array  $tokens
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function sendPush($title, $text, $tokens) {
        $apiKey = 'AIzaSyAYWJGc2Dt9MjbXnuHGNlY9rqqk3CrKoQI';
        
        $client = new Client();
        $client->setApiKey($apiKey);
        $client->injectHttpClient(new \GuzzleHttp\Client());
        
        $note = new Notification($title, $text);
        
        $message = new Message();
        
        foreach ($tokens as $token) {
            if ($token['deviceToken'] !== null) {
                $message->addRecipient(new Device($token['deviceToken']));
            }
        }
        
        $message->setNotification($note)->setData(
            array(
                'title'      => $title,
                'content'    => $text
            )
        );
        
        $response = $client->send($message);
        
        return $response;
    }
}