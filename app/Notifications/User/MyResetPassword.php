<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

class MyResetPassword extends ResetPassword
{
    use Queueable;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = config('app.url') . config('app.link.password_change') . "?token=" . $this->token . "&email=" . $notifiable->mail_address;
        $mail_text =
<<<MailText
パスワード再発行の申請を受け付けました。

パスワードの再発行をご希望の場合は、以下URLをクリックし、新しいパスワードをご登録ください。
※パスワードリセットの申請に心当たりがない場合は、以降の対応は不要となります。

▼パスワード再発行URL
{$url}

※上記のURLにアクセスするとパスワード再発行画面に遷移いたします。
※当メールの受信から24時間経過するとURLが開けなくなりますのでご注意ください。
URLが開けなくなった場合、Webサイトから再度お手続きをお願いいたします。

----------------------------------------------------
※本メールは自動配信メールです。
※配信専用となりますので、本メールにご返信していただきましても、
お問い合わせにはお答えできませんのでご了承ください。
----------------------------------------------------
MailText;
        return $mail_text;
    }
}
