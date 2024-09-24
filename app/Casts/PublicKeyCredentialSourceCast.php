<?php

namespace App\Casts;

use App\Support\JsonSerializer;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Webauthn\PublicKeyCredentialSource;

class PublicKeyCredentialSourceCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return JsonSerializer::deserialize($value, PublicKeyCredentialSource::class);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return [
            'credential_id' => $value->publicKeyCredentialId,
            'data' => JsonSerializer::serialize($value),
        ];
    }
}
