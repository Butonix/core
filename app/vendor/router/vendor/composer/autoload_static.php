<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9209f2a5c7c944e38a26ab6e6ab204f8
{
    public static $files = array (
        'ce30818c35dfce0b74f3d34a4027ae2a' => __DIR__ . '/..' . '/http-interop/http-server-middleware/src/alias.php',
        'cf97c57bfe0f23854afd2f3818abb7a0' => __DIR__ . '/..' . '/zendframework/zend-diactoros/src/functions/create_uploaded_file.php',
        '9bf37a3d0dad93e29cb4e1b1bfab04e9' => __DIR__ . '/..' . '/zendframework/zend-diactoros/src/functions/marshal_headers_from_sapi.php',
        'ce70dccb4bcc2efc6e94d2ee526e6972' => __DIR__ . '/..' . '/zendframework/zend-diactoros/src/functions/marshal_method_from_sapi.php',
        'f86420df471f14d568bfcb71e271b523' => __DIR__ . '/..' . '/zendframework/zend-diactoros/src/functions/marshal_protocol_version_from_sapi.php',
        'b87481e008a3700344428ae089e7f9e5' => __DIR__ . '/..' . '/zendframework/zend-diactoros/src/functions/marshal_uri_from_sapi.php',
        '0b0974a5566a1077e4f2e111341112c1' => __DIR__ . '/..' . '/zendframework/zend-diactoros/src/functions/normalize_server.php',
        '1ca3bc274755662169f9629d5412a1da' => __DIR__ . '/..' . '/zendframework/zend-diactoros/src/functions/normalize_uploaded_files.php',
        '40360c0b9b437e69bcbb7f1349ce029e' => __DIR__ . '/..' . '/zendframework/zend-diactoros/src/functions/parse_cookie_header.php',
    );

    public static $prefixLengthsPsr4 = array (
        'm' => 
        array (
            'mindplay\\middleman\\' => 19,
        ),
        'Z' => 
        array (
            'Zend\\Diactoros\\' => 15,
        ),
        'S' => 
        array (
            'Spatie\\Macroable\\' => 17,
        ),
        'R' => 
        array (
            'Rareloop\\Router\\' => 16,
        ),
        'P' => 
        array (
            'Psr\\Http\\Server\\' => 16,
            'Psr\\Http\\Message\\' => 17,
            'Psr\\Container\\' => 14,
        ),
        'I' => 
        array (
            'Invoker\\' => 8,
            'Interop\\Http\\Server\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'mindplay\\middleman\\' => 
        array (
            0 => __DIR__ . '/..' . '/mindplay/middleman/src',
        ),
        'Zend\\Diactoros\\' => 
        array (
            0 => __DIR__ . '/..' . '/zendframework/zend-diactoros/src',
        ),
        'Spatie\\Macroable\\' => 
        array (
            0 => __DIR__ . '/..' . '/spatie/macroable/src',
        ),
        'Rareloop\\Router\\' => 
        array (
            0 => __DIR__ . '/..' . '/rareloop/router/src',
        ),
        'Psr\\Http\\Server\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-server-handler/src',
            1 => __DIR__ . '/..' . '/psr/http-server-middleware/src',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'Invoker\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-di/invoker/src',
        ),
        'Interop\\Http\\Server\\' => 
        array (
            0 => __DIR__ . '/..' . '/http-interop/http-server-middleware/src',
        ),
    );

    public static $classMap = array (
        'AltoRouter' => __DIR__ . '/..' . '/altorouter/altorouter/AltoRouter.php',
        'mindplay\\readable' => __DIR__ . '/..' . '/mindplay/readable/src/readable.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9209f2a5c7c944e38a26ab6e6ab204f8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9209f2a5c7c944e38a26ab6e6ab204f8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9209f2a5c7c944e38a26ab6e6ab204f8::$classMap;

        }, null, ClassLoader::class);
    }
}
