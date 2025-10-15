<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\View\Factory as ViewFactory;

class EmailService
{
    private $mailer;
    private $viewFactory;

    public function __construct(Mail $mailer, ViewFactory $viewFactory)
    {
        $this->mailer       = $mailer;
        $this->viewFactory  = $viewFactory;
    }

    public function sendEmailRegisterUser($user)
    {
        $toEmail    = $user->email;
        $subject    = "Confirmación de registro en " . env("APP_NAME");

        $data = [
            'user'              => $user->name,
            'subject'           => $subject,
            'confirmation_code' => $user->confirmation_code,
        ];

        Mail::send('email.register', $data, function ($msj) use ($subject, $toEmail) {
            $msj->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
            $msj->subject($subject);
            $msj->to($toEmail);
        });
    }

    public function sendPasswordResetEmail($user)
    {
        $toEmail    = $user->email;
        $subject    = "Recuperación de contraseña en " . env("APP_NAME");

        $data = [
            'user'              => $user->name,
            'subject'           => $subject,
            'verification_link' => route('auth.recovery_password', [
                'email' => $user->email,
                'token' => $user->remember_token
            ]),
        ];

        Mail::send('email.recover_password', $data, function ($msj) use ($subject, $toEmail) {
            $msj->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
            $msj->subject($subject);
            $msj->to($toEmail);
        });
    }

    /**
     * Método genérico para enviar correos dinámicos
     *
     * @param string|array $toEmail   Destinatario(s)
     * @param string $subject         Asunto del correo
     * @param string $message         Contenido del mensaje (HTML o texto plano)
     * @param string|null $view       Vista Blade opcional (si se requiere diseño)
     * @param array $extraData        Datos extra a pasar a la vista
     * @return void
     */
    public function sendGenericEmail($toEmail, string $subject, string $message, ?string $view = null, array $extraData = [])
    {
        // Construcción de datos para la vista
        $data = array_merge([
            'subject' => $subject,
            'message' => $message,
        ], $extraData);

        if ($view) {
            // Usa una vista blade si está definida
            Mail::send($view, $data, function ($msj) use ($subject, $toEmail) {
                $msj->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
                $msj->subject($subject);
                $msj->to($toEmail);
            });
        } else {
            // Envío sin vista (texto plano o HTML directo)
            Mail::raw($message, function ($msj) use ($subject, $toEmail) {
                $msj->from(env("MAIL_FROM_ADDRESS"), env("MAIL_FROM_NAME"));
                $msj->subject($subject);
                $msj->to($toEmail);
            });
        }
    }
}
