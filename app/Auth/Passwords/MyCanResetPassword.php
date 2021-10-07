<?php

namespace App\Auth\Passwords;

use Illuminate\Http\Request;
use App\Notifications\User\MyResetPassword as ResetPasswordNotification;
use GuzzleHttp\Client;

trait MyCanResetPassword
{
    private Client $http;

    public function __construct()
    {
        $this->http = new Client([
            'base_uri' => config('mail.sendgrid.base_url'),
        ]);
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
          return $this->mail_address;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $mail = new ResetPasswordNotification($token);
        $option = [
            'headers' => [
                'Accept'     => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . config('mail.sendgrid.api_key')
            ],
            'json' => [
                "personalizations" => [
                    [
                        "to" => [
                            [
                                "email" => $this->mail_address
                            ]
                        ]
                    ]
                ],
                "from" => [
                    "email" => config('mail.from.password_reset')
                ],
                "subject" => "【顧客管理システム】パスワードの再発行URLのお知らせ",
                "content" => [
                    [
                        "type" => "text/plain",
                        "value" => $mail->toMail($this)
                    ]
                ]

            ],
        ];
        return $this->http->request('POST', '/v3/mail/send', $option);
    }
}
