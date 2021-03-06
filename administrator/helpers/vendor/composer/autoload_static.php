<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1522c8ab3ccf85b45a5961d4de04b6f2
{
    public static $prefixLengthsPsr4 = array (
        'R' => 
        array (
            'RenanBr\\BibTexParser\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'RenanBr\\BibTexParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/renanbr/bibtex-parser/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1522c8ab3ccf85b45a5961d4de04b6f2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1522c8ab3ccf85b45a5961d4de04b6f2::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
