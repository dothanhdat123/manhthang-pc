<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param Throwable $exception
     * @throws Throwable
     */
    public function report(Throwable $exception)
    {
        // emails.exception is the template of your email
        // it will have access to the $error that we are passing below
        if ($this->shouldReport($exception)) {
            if (hwa_setting('email_admin_report_enable') && hwa_setting('admin_email')) {
                $this->sendEmail($exception); // sends an email
            }
        }
        return parent::report($exception);

    }

    /**
     * @param Throwable $exception
     */
    public function sendEmail(Throwable $exception)
    {
        try {
            $e = FlattenException::create($exception);
            $handler = new HtmlErrorRenderer(true); // boolean, true raises debug flag...
            $css = $handler->getStylesheet();
            $content = $handler->getBody($e);

            Mail::send('emails.exception', compact('css', 'content'), function ($message) {
                $message->to(hwa_setting('admin_email'))
                    ->subject(hwa_app_name() . ' | Exception: ' . \Request::fullUrl());
            });
        } catch (Throwable $exception) {
            Log::error($exception);
        }
    }
}
