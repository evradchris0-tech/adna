<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JWTModel extends Model
{
    use HasFactory;

    public static function base64url_encode($str)
    {
        return rtrim(strtr(base64_encode(base64_encode($str)), '+/', '-_'), '=');
    }

    public static function generate_jwt($payload, $secret = 'secret', $headers = array('alg' => 'SHA256', 'typ' => 'JWT'))
    {
        $headers_encoded = JWTModel::base64url_encode(json_encode($headers));

        $payload_encoded = JWTModel::base64url_encode(json_encode($payload));

        $signature = hash_hmac($headers['alg'], "$headers_encoded.$payload_encoded", $secret, true);
        $signature_encoded = JWTModel::base64url_encode($signature);
        $jwt = "$payload_encoded.$headers_encoded.$signature_encoded";

        return $jwt;
    }

    public static function is_jwt_valid($jwt, $secret = 'secret', $headers = array('alg' => 'SHA256', 'typ' => 'JWT'))
    {
        // split the jwt
        $tokenParts = explode('.', $jwt);
        $header = base64_decode(base64_decode($tokenParts[1]));
        $payload = base64_decode(base64_decode($tokenParts[0]));
        $signature_provided = $tokenParts[2];
        // check the expiration time
        try {
            $expiration = json_decode($payload)->exp;
            $is_token_expired = ($expiration - time()) < 0;
        } catch (\Throwable $th) {
            return FALSE;
        }

        // build a signature based on the header and payload using the secret
        $base64_url_header = JWTModel::base64url_encode($header);
        $base64_url_payload = JWTModel::base64url_encode($payload);
        $signature = hash_hmac($headers['alg'], "$base64_url_header.$base64_url_payload", $secret, true);
        $base64_url_signature = JWTModel::base64url_encode($signature);
        // verify it matches the signature provided in the jwt
        $is_signature_valid = ($base64_url_signature === $signature_provided);

        if ($is_token_expired || !$is_signature_valid) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
