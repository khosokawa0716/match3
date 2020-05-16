<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Lang;

class CustomResetPassword extends Notification
{

    /**
    * The password reset token.
    *
    /** @var string */
    public $token;

    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param string token
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
        $token = $this->token; // URL生成用に定義
        $email = '?email='.urlencode($notifiable->getEmailForPasswordReset()); // URL生成用に定義

        return (new MailMessage)
                    ->from('k.hosokawa0716@gmail.com', config('app.name'))
                    ->subject(Lang::getFromJson('パスワード再設定のご案内'))
                    ->line(Lang::getFromJson('パスワード再設定のお問い合わせをお受けしました。'))
                    ->line(Lang::getFromJson('下のボタンをクリックして、パスワードを再設定してください。'))

                    // デフォルトのコードだとEmailのURLに/api/が含まれてしまい、jsonの文字列が表示されてしまう。
                    // ->action(Lang::getFromJson('Reset Password'), url(config('app.url').route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
                    ->action(Lang::getFromJson('パスワード再設定'), url(config('app.url').'/password/reset/'.$token.$email))
                    ->line(Lang::getFromJson('このリンクの有効期限は :count 分です。', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
                    ->line(Lang::getFromJson('もし心当たりがない場合は、本メッセージは破棄してください。'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
