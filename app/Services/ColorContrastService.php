<?php

namespace App\Services;

use Tomloprod\Colority\Colors\HslColor;
use Tomloprod\Colority\Support\Algorithms\ContrastRatioScore;
use Tomloprod\Colority\Support\Facades\Colority;

class ColorContrastService
{
    const int ACCURACY = 5;

    public function findTextColor(string $backgroundColorHex, ContrastRatioScore $minimumScore = ContrastRatioScore::Good): string
    {
        $colorHsl = Colority::fromHex($backgroundColorHex)->toHsl();
        [, , $lightness] = $colorHsl->getArrayValueColor();

        $direction = ($lightness < 50) ? +self::ACCURACY : -self::ACCURACY;

        $color = $this->tryColorRatio($colorHsl, $direction, $minimumScore->getMinimumScore());

        if ($color) {
            return $color;
        }

        return ($lightness < 0.5) ? '#ffffff' : '#000000';
    }

    private function tryColorRatio(HslColor $backgroundColor, int $step, float $targetRatio = 7.0): ?string
    {
        [$backgroundHue, $backgroundSaturation, $backgroundLightness] = $backgroundColor->getArrayValueColor();
        for ($lightness = $backgroundLightness; $lightness >= 0 && $lightness <= 100; $lightness += $step) {
            $textColor = Colority::fromHsl([$backgroundHue, $backgroundSaturation, $lightness]);

            $ratio = $backgroundColor->getContrastRatio($textColor);

            if ($ratio >= $targetRatio) {
                return $textColor->toHex()->getValueColor();
            }
        }

        return null;
    }
}
