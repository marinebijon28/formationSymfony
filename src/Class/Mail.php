<?php

namespace App\Class;

use Mailjet\Client;
use Mailjet\Resources;

class Mail 
{
    private $apiKey = "5d2d24678434d3bf572ef01f4e7a573b";
    private $apiKeySecret = "f1e2aae033a35383535f325515abf4a4";

    public function send($toEmail, $toName, $subject, $content) 
    {
        $mj = new Client($this->apiKey, $this->apiKeySecret, true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => 
                    [
                        'Email' => "marine.b.58@hotmail.fr",
                        'Name' => "site ecommerce"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toName
                        ]
                    ],
                    'TemplateID' => 3375813,
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

?>