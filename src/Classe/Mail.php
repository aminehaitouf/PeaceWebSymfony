<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{


    private $api_key = '80b5d84efab46d9724b67aaea7ebe8ec';
    private $api_key_secret = '4136cf962df2ae44c141c74b8f91b4af';

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);


        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "benoit.barone@peace.sc",
                        'Name' => "Peace"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => 3751240,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,

                    ]

                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}
