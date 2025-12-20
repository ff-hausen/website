<?php

namespace App\Facades;

use App\Services\ColorContrastService;
use Illuminate\Support\Facades\Facade;

/**
 * @see ColorContrastService
 */
class ColorContrast extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ColorContrastService::class;
    }
}
