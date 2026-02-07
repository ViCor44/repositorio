<?php

namespace Core;

use OTPHP\TOTP;

class TwoFactor {

    public static function generateSecret(): string {

        return TOTP::create()->getSecret();
    }

    public static function provisioningUri(
        string $email,
        string $secret
    ): string {

        $totp = TOTP::create($secret);
        $totp->setLabel($email);
        $totp->setIssuer('Repositorio Tecnico');

        return $totp->getProvisioningUri();
    }

    public static function verify(string $secret, string $code): bool {

        $totp = TOTP::create($secret);

        return $totp->verify($code);
    }
}
