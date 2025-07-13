<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require 'vendor/autoload.php';
use Firebase\JWT\JWT as FirebaseJWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

class Jwt {

    private $key;
    private $alg;

    public function __construct()
    {
        // Configuración de la clave secreta
        $this->key = K_JWT;
        $this->alg = 'HS256';
    }

    /**
     * Genera un token JWT
     * @param array $data Datos que quieres incluir en el token (ej. id, email)
     * @param int $exp_minutes Tiempo en minutos para que expire (ej. 60)
     * @return string Token JWT
     */
    public function generate(array $data, int $exp_minutes = 60)
    {
        $issuedAt = time();
        $expireAt = $issuedAt + ($exp_minutes * 60);

        $payload = array_merge($data, [
            'iat' => $issuedAt,
            'exp' => $expireAt
        ]);

        return FirebaseJWT::encode($payload, $this->key, $this->alg);
    }

    /**
     * Valida y decodifica un token JWT
     * @param string $token
     * @return object|false Retorna el payload si es válido, o false si no
     */
    public function validate(string $token)
    {
        try {
            return FirebaseJWT::decode($token, new Key($this->key, $this->alg));
        } catch (ExpiredException $e) {
            log_message('error', 'JWT expirado: ' . $e->getMessage());
        } catch (SignatureInvalidException $e) {
            log_message('error', 'JWT firma inválida: ' . $e->getMessage());
        } catch (Exception $e) {
            log_message('error', 'JWT error: ' . $e->getMessage());
        }

        return null;
    }
}
