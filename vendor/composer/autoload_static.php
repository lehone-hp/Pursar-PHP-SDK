<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit710e83dc75f1efe18df895c58021d30b
{
    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Pursar' => 
            array (
                0 => __DIR__ . '/../..' . '/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit710e83dc75f1efe18df895c58021d30b::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
