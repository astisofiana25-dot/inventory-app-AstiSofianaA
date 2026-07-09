<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        try {
            // Register middleware alias 'role' to ensure routes using ->middleware('role:...') work
            $router = $this->app->make(\Illuminate\Routing\Router::class);
            $router->aliasMiddleware('role', \App\Http\Middleware\CheckRole::class);

            if (\Schema::hasTable('mail_settings')) {
                $mailSetting = \App\Models\MailSetting::first();
                if ($mailSetting) {
                    config([
                        'mail.default' => $mailSetting->mailer,
                        'mail.mailers.smtp.host' => $mailSetting->host,
                        'mail.mailers.smtp.port' => $mailSetting->port,
                        'mail.mailers.smtp.username' => $mailSetting->username,
                        'mail.mailers.smtp.password' => $mailSetting->password,
                        'mail.mailers.smtp.encryption' => $mailSetting->encryption ?: 'tls',
                        'mail.from.address' => $mailSetting->from_address,
                        'mail.from.name' => $mailSetting->from_name,
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Biarkan kosong agar ketika Railway melakukan build/cache tanpa database,
            // prosesnya tidak akan memicu error dan build bisa sukses berjalan.
        }
    }
}