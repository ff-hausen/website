<?php

namespace App\Support;

use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Output\QROutputInterface;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class EpcPaymentQrCode
{
    public static function buildPayload(
        string $recipient,
        string $iban,
        float $amount,
        string $bic = '',
        string $remittanceInformation = ''
    ): string {
        $normalizedRecipient = self::normalize($recipient, 70);
        $normalizedIban = strtoupper(str_replace(' ', '', $iban));
        $normalizedBic = strtoupper(trim($bic));
        $normalizedAmount = number_format($amount, 2, '.', '');
        $normalizedRemittance = self::normalize($remittanceInformation, 140);

        return implode("\n", [
            'BCD',
            '002',
            '1',
            'SCT',
            $normalizedBic,
            $normalizedRecipient,
            $normalizedIban,
            'EUR'.$normalizedAmount,
            '',
            '',
            $normalizedRemittance,
        ]);
    }

    public static function makeDataUri(
        string $recipient,
        string $iban,
        float $amount,
        string $bic = '',
        string $remittanceInformation = ''
    ): string {
        $payload = self::buildPayload($recipient, $iban, $amount, $bic, $remittanceInformation);

        return (new QRCode(new QROptions([
            'outputType' => QROutputInterface::MARKUP_SVG,
            'outputBase64' => true,
            'svgAddXmlHeader' => false,
            'eccLevel' => EccLevel::M,
        ])))->render($payload);
    }

    private static function normalize(string $value, int $maxLength): string
    {
        $normalized = preg_replace('/\s+/', ' ', trim($value)) ?? '';

        return mb_substr($normalized, 0, $maxLength);
    }
}
