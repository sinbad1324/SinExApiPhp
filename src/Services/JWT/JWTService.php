<?php

namespace Services\JWT;

use Jose\Component\Core\JWK;
use Jose\Component\KeyManagement\JWKFactory;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Signature\Algorithm\HS256;
use Jose\Component\Signature\JWSBuilder;
use Jose\Component\Signature\Serializer\CompactSerializer;
use Jose\Component\Signature\JWSVerifier;

final class JWTService
{
    public static AlgorithmManager $algo;
    public static JWSBuilder $jwsBuilder;
    public static JWK $jwk;
    public static CompactSerializer $serializer;
    public static JWSVerifier $jwsVerifier;
    public static bool $isInit = false;
    public static function init()
    {
        if (static::$isInit == true) return;
        static::$isInit = true;
        static::$jwk = JWKFactory::createFromSecret($_ENV["SECRET_JWT_KEY"],  [
            'alg' => 'HS256',
            'use' => 'sig',
        ]);
        static::$algo = new AlgorithmManager([new HS256()]);
        static::$jwsBuilder = new JWSBuilder(static::$algo);
        static::$jwsVerifier = new JWSVerifier(static::$algo);
        static::$serializer = new CompactSerializer();
    }
    /**
     * exemple: 
     * ['name' => 'John Doe',
     *  'sub' => '1234567890',  
     *  'iat' => time()]
     *@param array $payload ne pas donner des donnée sensible
     *@return string 
     */
    public static function CreateNewJWT(array $payload): string
    {
        $jws = static::$jwsBuilder
            ->create()
            ->withPayload(json_encode($payload))
            ->addSignature(static::$jwk, [
                'alg' => 'HS256',
                'typ' => 'JWT',
            ])
            ->build();
        return static::$serializer->serialize($jws, 0); // tansformer en text
    }
    /**
     * Si le JWT est valide on donne le payload
     * sinon null
     *@param string $JWT
     *@return array
     */
    public static function  VerifieJWT(string $JWT): array | null
    {
        $jws = static::$serializer->unserialize($JWT); // transformet en instance jsw
        $isValid = static::$jwsVerifier->verifyWithKey($jws, static::$jwk, 0);
        if ($isValid) {
            return json_decode($jws->getPayload(), true);
        }
        return null;
    }

    /** 
     * Cette verification verifi si JWT est valid et que le Token n'a pas encore expiré
     *@param string $JWT
     *@return array
     */
    public static function VerifieJWTTemps(string $JWT): array | null
    {
        $payload = static::VerifieJWT($JWT);
        if ($payload) 
            if (isset($payload["exp"])) 
                if ($payload["exp"] < time()) 
                    return $payload;
        return null;
    }
}
JWTService::init();
