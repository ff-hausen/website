<?php

namespace App\Services;

use Hracik\ColorConverter\ColorConverter;

class ColorContrastService
{
    const WCAG_AAA_RATIO = 7.0;

    const WCAG_AA_RATIO = 4.5;

    const ACCURACY = 0.05;

    public function findTextColor(string $backgroundColor): string
    {
        [$hue, $saturation, $lightness] = ColorConverter::hex2hsl($backgroundColor);

        $direction = ($lightness < 0.5) ? +self::ACCURACY : -self::ACCURACY;

        $parameters = [
            [$backgroundColor, $direction, self::WCAG_AAA_RATIO],
            [$backgroundColor, -$direction, self::WCAG_AAA_RATIO],
            [$backgroundColor, +$direction, self::WCAG_AA_RATIO],
            [$backgroundColor, -$direction, self::WCAG_AA_RATIO],
        ];

        foreach ($parameters as $i => $parameter) {
            $color = $this->tryColorRatio(...$parameter);

            if ($color) {
                return $color;
            }
        }

        return ($lightness < 0.5) ? '#ffffff' : '#000000';
    }

    private function tryColorRatio(string $startColor, float $step, float $targetRatio = 7.0): ?string
    {
        [$hue, $saturation, $lightness] = ColorConverter::hex2hsl($startColor);
        for ($i = $lightness; $i >= 0 && $i <= 1; $i += $step) {
            $color = ColorConverter::hsl2hex([$hue, $saturation, $i]);
            $ratio = $this->calculateLuminosityRatio($startColor, $color);

            if ($ratio >= $targetRatio) {
                return $color;
            }
        }

        return null;
    }

    private function calculateLuminosityRatio($color1, $color2): float
    {
        $l1 = $this->calculateLuminosity($color1);
        $l2 = $this->calculateLuminosity($color2);

        if ($l1 > $l2) {
            $ratio = (($l1 + 0.05) / ($l2 + 0.05));
        } else {
            $ratio = (($l2 + 0.05) / ($l1 + 0.05));
        }

        return $ratio;
    }

    private function calculateLuminosity($color): float
    {
        $color = ltrim($color, '#');

        $r = hexdec(substr($color, 0, 2)) / 255; // red value
        $g = hexdec(substr($color, 2, 2)) / 255; // green value
        $b = hexdec(substr($color, 4, 2)) / 255; // blue value
        if ($r <= 0.03928) {
            $r = $r / 12.92;
        } else {
            $r = pow((($r + 0.055) / 1.055), 2.4);
        }

        if ($g <= 0.03928) {
            $g = $g / 12.92;
        } else {
            $g = pow((($g + 0.055) / 1.055), 2.4);
        }

        if ($b <= 0.03928) {
            $b = $b / 12.92;
        } else {
            $b = pow((($b + 0.055) / 1.055), 2.4);
        }

        $luminosity = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;

        return $luminosity;
    }
}
