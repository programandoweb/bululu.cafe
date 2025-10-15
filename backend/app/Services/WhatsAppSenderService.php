<?php

/**
 * ---------------------------------------------------
 *  Desarrollado por: Jorge Méndez - Programandoweb
 *  Correo: lic.jorgemendez@gmail.com
 *  Celular: 3115000926
 *  Website: Programandoweb.net
 *  Proyecto: Ivoolve
 * ---------------------------------------------------
 */

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

class WhatsAppSenderService
{
    protected string $baseUrl;
    protected string $sessionId;

    public function __construct()
    {
        $this->baseUrl      =   'https://whatsapp2025.ivoolve.cloud/api/';        
    }

    public function sendMessage(string $sessionId, string $to, string $text, ?string $imageUrl = null)
    {
        try {
            $url = $this->baseUrl . $sessionId . "/send";

            $payload = [
                'to' => $to,
                'text' => $text,
            ];

            $payload['imageUrl'] = env("LOGO_URL");

            if ($imageUrl) {
                $payload['imageUrl'] = $imageUrl;
            }

            $token = env('WHATSAPP_TOKEN');

            $response = Http::timeout(30)
                ->withToken($token)
                ->post($url, $payload);
            return true;
        } catch (\Throwable $th) {
            //throw $th;
        }
        
        /*
        if ($response->failed()) {
            //throw new \Exception($response->json('error') ?? 'Error al enviar el mensaje');
        }

        return $response->json();
        */
    }

}
