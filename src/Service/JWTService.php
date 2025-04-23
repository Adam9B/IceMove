<?php

namespace App\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService
{
    /**
     * Génère un token JWT.
     *
     * @param array $data Les données à inclure dans le token (par exemple, user_id).
     * @param string $secretKey La clé secrète pour signer le token.
     * @param int $expirationTime Le temps d'expiration en secondes (par ex., 3600 pour 1 heure).
     * @return string Le token JWT généré.
     */
    public function generateToken(array $data, string $secretKey, int $expirationTime): string
    {
        $payload = [
            'iat' => time(), // Timestamp de création
            'exp' => time() + $expirationTime, // Timestamp d'expiration
            'data' => $data, // Données personnalisées
        ];

        return JWT::encode($payload, $secretKey, 'HS256');
    }

    /**
     * Valide un token JWT.
     *
     * @param string $token Le token JWT à valider.
     * @param string $secretKey La clé secrète utilisée pour signer le token.
     * @return object|array Les données décodées du token.
     * @throws \Exception Si le token est invalide ou expiré.
     */
    public function validateToken(string $token, string $secretKey)
    {
        return JWT::decode($token, new Key($secretKey, 'HS256'));
    }
}

