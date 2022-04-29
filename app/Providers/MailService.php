<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class MailService extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function registe($email)
    {

        return $email;
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
