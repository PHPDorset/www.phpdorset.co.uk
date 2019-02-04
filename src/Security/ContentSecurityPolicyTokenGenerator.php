<?php

declare(strict_types=1);

namespace App\Security;

final class ContentSecurityPolicyTokenGenerator
{
    public function generateToken(): string
    {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }
}
