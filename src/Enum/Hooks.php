<?php

namespace Buoy\Enum;

class Hooks
{
    public const BEFORE_SPINUP  = 'before_spinup';
    public const AFTER_SPINUP   = 'after_spinup';
    public const BEFORE_CLEANUP = 'before_cleanup';
    public const AFTER_CLEANUP  = 'after_cleanup';

    public static function getSupportedHooks()
    {
        return [
            self::BEFORE_SPINUP,
            self::AFTER_SPINUP,
            self::BEFORE_CLEANUP,
            self::AFTER_CLEANUP,
        ];
    }
}
