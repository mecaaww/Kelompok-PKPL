<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $resetUrl = route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);

            return (new MailMessage)
                ->subject('Reset Kata Sandi - Apotek Alfina Rizqy')
                ->greeting('Halo, ' . ($notifiable->username ?? 'Pelanggan') . '!')
                ->line('Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi untuk akun Anda di Apotek Alfina Rizqy.')
                ->action('Atur Ulang Kata Sandi', $resetUrl)
                ->line('Tautan reset kata sandi ini akan kedaluwarsa dalam 60 menit.')
                ->line('Jika Anda tidak merasa melakukan permintaan ini, tidak ada tindakan lebih lanjut yang diperlukan.')
                ->salutation("Salam hangat,\nApotek Alfina Rizqy");
        });
    }
}
