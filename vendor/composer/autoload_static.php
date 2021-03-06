<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb87514d55865b752662cc0c9a7b9d526
{
    public static $files = array (
        '5255c38a0faeba867671b61dfda6d864' => __DIR__ . '/..' . '/paragonie/random_compat/lib/random.php',
        '7f939cf3886f8168713c84dc1019984a' => __DIR__ . '/..' . '/lastguest/murmurhash/murmurhash3.php',
        '7cfce27594bbc1dd0dbf7e3eb5cd4911' => __DIR__ . '/..' . '/bitwasp/bitcoin/src/Script/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'p' => 
        array (
            'plainview\\sdk_mcc\\' => 18,
            'phpEther\\' => 9,
        ),
        'h' => 
        array (
            'harmonypay\\api\\' => 15,
            'harmonypay\\' => 11,
        ),
        'M' => 
        array (
            'Mdanter\\Ecc\\' => 12,
        ),
        'F' => 
        array (
            'FG\\' => 3,
        ),
        'C' => 
        array (
            'Composer\\Semver\\' => 16,
            'CashAddr\\' => 9,
        ),
        'B' => 
        array (
            'Btccom\\BitcoinCash\\' => 19,
            'BitWasp\\Buffertools\\' => 20,
            'BitWasp\\Bitcoin\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'plainview\\sdk_mcc\\' => 
        array (
            0 => __DIR__ . '/..' . '/plainview/sdk',
        ),
        'phpEther\\' => 
        array (
            0 => __DIR__ . '/..' . '/arizn/phpether',
        ),
        'harmonypay\\api\\' => 
        array (
            0 => __DIR__ . '/..' . '/harmonypay/api/src',
        ),
        'harmonypay\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Mdanter\\Ecc\\' => 
        array (
            0 => __DIR__ . '/..' . '/mdanter/ecc/src',
        ),
        'FG\\' => 
        array (
            0 => __DIR__ . '/..' . '/fgrosse/phpasn1/lib',
        ),
        'Composer\\Semver\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/semver/src',
        ),
        'CashAddr\\' => 
        array (
            0 => __DIR__ . '/..' . '/btccom/cashaddress/src',
        ),
        'Btccom\\BitcoinCash\\' => 
        array (
            0 => __DIR__ . '/..' . '/btccom/bitwasp-bitcoin-bch-addon/src',
        ),
        'BitWasp\\Buffertools\\' => 
        array (
            0 => __DIR__ . '/..' . '/bitwasp/buffertools/src/Buffertools',
        ),
        'BitWasp\\Bitcoin\\' => 
        array (
            0 => __DIR__ . '/..' . '/bitwasp/bitcoin/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Pleo' => 
            array (
                0 => __DIR__ . '/..' . '/pleonasm/merkle-tree/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb87514d55865b752662cc0c9a7b9d526::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb87514d55865b752662cc0c9a7b9d526::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitb87514d55865b752662cc0c9a7b9d526::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitb87514d55865b752662cc0c9a7b9d526::$classMap;

        }, null, ClassLoader::class);
    }
}
