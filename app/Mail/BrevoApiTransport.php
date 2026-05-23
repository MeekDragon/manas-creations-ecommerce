<?php

namespace App\Mail;

use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class BrevoApiTransport extends AbstractTransport
{
    protected string $apiKey;

    public function __construct(string $apiKey)
    {
        parent::__construct();
        $this->apiKey = $apiKey;
    }

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $sender = $email->getFrom();
        $fromEmail = !empty($sender) ? $sender[0]->getAddress() : env('MAIL_FROM_ADDRESS', 'manascreationsofficial@gmail.com');
        $fromName = !empty($sender) ? $sender[0]->getName() : env('MAIL_FROM_NAME', 'Manas Creations');

        $toAddresses = [];
        foreach ($email->getTo() as $to) {
            $toAddresses[] = [
                'email' => $to->getAddress(),
                'name' => $to->getName() ?: $to->getAddress(),
            ];
        }

        $body = [
            'sender' => [
                'name' => $fromName ?: 'Manas Creations',
                'email' => $fromEmail ?: 'manascreationsofficial@gmail.com',
            ],
            'to' => $toAddresses,
            'subject' => $email->getSubject(),
        ];

        // Retrieve html or text body
        $html = $email->getHtmlBody();
        if ($html) {
            $body['htmlContent'] = is_resource($html) ? stream_get_contents($html) : $html;
        } else {
            $text = $email->getTextBody();
            if ($text) {
                $body['textContent'] = is_resource($text) ? stream_get_contents($text) : $text;
            } else {
                $body['textContent'] = 'No content';
            }
        }

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'api-key' => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', $body);

        if (!$response->successful()) {
            throw new \Exception('Brevo API send failed: ' . $response->body());
        }
    }

    public function __toString(): string
    {
        return 'brevo-api';
    }
}
