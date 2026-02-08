<?php

namespace Core;

use OTPHP\TOTP;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;

class TwoFactor
{
    private const ISSUER = 'Securo';  // Muda para o nome da tua app

    /**
     * Gera um secret novo (Base32 seguro)
     */
    public function generateSecret(): string
    {
        return TOTP::create()->getSecret();  // gera ~160 bits → ~32 chars Base32
    }

    /**
     * Gera QR code como data URI (base64) para <img src="...">
     * Usa Endroid localmente → mais fiável e privado
     */
   
    public function getQrCodeDataUri(string $email, string $secret): string
    {
        // Parte TOTP - usa ::create se disponível, senão new
        $totp = TOTP::create($secret);  // se der erro, muda para: new TOTP($secret);
        $totp->setLabel($email);
        $totp->setIssuer(self::ISSUER);

        $uri = $totp->getProvisioningUri();

        // Parte QR - construtor direto (evita Builder::create se o IDE reclamar)
        $builder = new \Endroid\QrCode\Builder\Builder(
            writer: new \Endroid\QrCode\Writer\PngWriter(),
            writerOptions: [],
            data: $uri,
            encoding: new \Endroid\QrCode\Encoding\Encoding('UTF-8'),
            errorCorrectionLevel: \Endroid\QrCode\ErrorCorrectionLevel::High,
            size: 450,
            margin: 10,
            roundBlockSizeMode: \Endroid\QrCode\RoundBlockSizeMode::Margin
            // Adiciona mais opções se quiseres (ex: foregroundColor, backgroundColor)
        );

        $result = $builder->build();

        return 'data:image/png;base64,' . base64_encode($result->getString());
    }

    // Alternativa simples se não quiseres Endroid (usa qrserver)
    public function getQrCodeImage(string $email, string $secret): string
    {
        $totp = TOTP::create($secret);
        $totp->setLabel($email);
        $totp->setIssuer(self::ISSUER);

        $uri = $totp->getProvisioningUri();

        return 'https://api.qrserver.com/v1/create-qr-code/?size=260x260&data=' . urlencode($uri);
    }

    /**
     * Verifica código com janela de tolerância recomendada (±1 = 90 segundos)
     */
    public static function verify(string $secret, string $code): bool
    {
        $code = trim($code);           // remove espaços acidentais
        if (!preg_match('/^\d{6}$/', $code)) {
            return false;
        }

        $totp = TOTP::create($secret);
        return $totp->verify($code, null, 1);  // ±1 intervalo (recomendado)
    }

    /**
     * Gera o código atual (útil para debug)
     */
    public static function getCurrentCode(string $secret): string
    {
        return TOTP::create($secret)->now();
    }
}