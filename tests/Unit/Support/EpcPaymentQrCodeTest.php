<?php

use App\Support\EpcPaymentQrCode;

test('it builds epc payload in sepa format', function () {
    $payload = EpcPaymentQrCode::buildPayload(
        recipient: 'Freiwillige Feuerwehr Frankfurt-Hausen e.V.',
        iban: 'DE51 5005 0201 0000 3191 29',
        amount: 123.4,
        bic: 'HELADEF1822',
        remittanceInformation: 'Vereinsausflug ABC-123',
    );

    $lines = explode("\n", $payload);

    expect($lines[0])->toBe('BCD')
        ->and($lines[1])->toBe('002')
        ->and($lines[3])->toBe('SCT')
        ->and($lines[4])->toBe('HELADEF1822')
        ->and($lines[6])->toBe('DE51500502010000319129')
        ->and($lines[7])->toBe('EUR123.40')
        ->and($lines[9])->toBe('')
        ->and($lines[10])->toBe('Vereinsausflug ABC-123');
});

test('it generates a qr code as svg data uri', function () {
    $dataUri = EpcPaymentQrCode::makeDataUri(
        recipient: 'Freiwillige Feuerwehr Frankfurt-Hausen e.V.',
        iban: 'DE51500502010000319129',
        amount: 42,
        bic: 'HELADEF1822',
        remittanceInformation: 'Vereinsausflug',
    );

    expect($dataUri)->toStartWith('data:image/svg+xml;base64,');
});
