<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1ec418c2babfa3f72de9503e600132ed
{
    public static $files = array (
        '6a4eeeb18eeefb24087f78ebb2211b1e' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/HttpConstants.php',
    );

    public static $classMap = array (
        'CertificateSigningRequest' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/CertificateSingingRequest.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Http\\Curl' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/Curl.php',
        'Http\\CurlConfiguration' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/CurlConfiguration.php',
        'Http\\Http' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/Http.php',
        'Http\\HttpHeader' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/HttpHeader.php',
        'Http\\HttpHeaderCollection' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/HttpHeaderCollection.php',
        'Http\\HttpMessage' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/HttpMessage.php',
        'Http\\HttpRedirect' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/HttpRedirect.php',
        'Http\\HttpRequest' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/HttpRequest.php',
        'Http\\HttpResponse' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/HttpResponse.php',
        'Http\\IHttpCache' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/IHttpCache.php',
        'Http\\Parameter' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/Signature/Parameter.php',
        'Http\\SignatureParameter' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/Signature/SignatureParameter.php',
        'Http\\SignatureParameterBag' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/Signature/SignatureParameterBag.php',
        'Http\\SigningKey' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/SigningKey.php',
        'Http\\SigningRequest' => __DIR__ . '/..' . '/ocdladefense/lib-http/src/SigningRequest.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit1ec418c2babfa3f72de9503e600132ed::$classMap;

        }, null, ClassLoader::class);
    }
}
