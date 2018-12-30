<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitaccc5b4b0af84eb160de08d4ce5cf1a2
{
    public static $files = array (
        'cfa7953bc0f465812a856d6f3af20147' => __DIR__ . '/..' . '/sandfoxme/monsterid/src/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SandFoxMe\\MonsterID\\' => 20,
        ),
        'R' => 
        array (
            'RandomLocalAvatars\\' => 19,
        ),
        'J' => 
        array (
            'Jdenticon\\' => 10,
        ),
        'I' => 
        array (
            'Identicon\\' => 10,
        ),
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SandFoxMe\\MonsterID\\' => 
        array (
            0 => __DIR__ . '/..' . '/sandfoxme/monsterid/src/classes',
        ),
        'RandomLocalAvatars\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'Jdenticon\\' => 
        array (
            0 => __DIR__ . '/..' . '/jdenticon/jdenticon/src',
        ),
        'Identicon\\' => 
        array (
            0 => __DIR__ . '/..' . '/yzalis/identicon/src/Identicon',
        ),
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitaccc5b4b0af84eb160de08d4ce5cf1a2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitaccc5b4b0af84eb160de08d4ce5cf1a2::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}