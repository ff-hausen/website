<?php

namespace App\Http\Controllers;

use App\Models\Passkey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Webauthn\AttestationStatement\AttestationStatementSupportManager;
use Webauthn\AuthenticatorAttestationResponse;
use Webauthn\AuthenticatorAttestationResponseValidator;
use Webauthn\AuthenticatorSelectionCriteria;
use Webauthn\CeremonyStep\CeremonyStepManagerFactory;
use Webauthn\Denormalizer\WebauthnSerializerFactory;
use Webauthn\PublicKeyCredential;
use Webauthn\PublicKeyCredentialCreationOptions;
use Webauthn\PublicKeyCredentialRpEntity;
use Webauthn\PublicKeyCredentialUserEntity;

class PasskeyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $options = new PublicKeyCredentialCreationOptions(
            rp: new PublicKeyCredentialRpEntity(
                name: config('app.name'),
                id: parse_url(config('app.url'), PHP_URL_HOST),
            ),
            user: new PublicKeyCredentialUserEntity(
                name: $request->user()->email,
                id: $request->user()->id,
                displayName: $request->user()->full_name,
            ),
            challenge: Str::random(),
            authenticatorSelection: new AuthenticatorSelectionCriteria(
                authenticatorAttachment: AuthenticatorSelectionCriteria::AUTHENTICATOR_ATTACHMENT_NO_PREFERENCE,
                residentKey: AuthenticatorSelectionCriteria::RESIDENT_KEY_REQUIREMENT_REQUIRED,
            )
        );

        Session::flash('passkey-registration-options', $options);

        return response()->json($options);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'passkey' => ['required', 'json'],
        ]);

        /** @var PublicKeyCredential $publicKeyCredential */
        $publicKeyCredential = (new WebauthnSerializerFactory(AttestationStatementSupportManager::create()))
            ->create()
            ->deserialize($data['passkey'], PublicKeyCredential::class, 'json');

        if (! $publicKeyCredential->response instanceof AuthenticatorAttestationResponse) {
            return to_route('login');
        }

        /** @var PublicKeyCredentialCreationOptions $publicKeyCredentialCreationOptions */
        $publicKeyCredentialCreationOptions = Session::get('passkey-registration-options');
        $publicKeyCredentialCreationOptions->challenge = base64_decode($publicKeyCredentialCreationOptions->challenge);

        try {
            $publicKeyCredentialSource = AuthenticatorAttestationResponseValidator::create(
                (new CeremonyStepManagerFactory)->creationCeremony()
            )->check(
                authenticatorAttestationResponse: $publicKeyCredential->response,
                publicKeyCredentialCreationOptions: $publicKeyCredentialCreationOptions,
                host: $request->getHost(),
            );
        } catch (\Throwable $e) {
            Log::error('Passkey validation failed.', ['error' => $e]);

            throw ValidationException::withMessages([
                'name' => 'Der angegebene Passkey ist ungültig.',
            ]);
        }

        $request->user()->passkeys()->create([
            'name' => $data['name'],
            'credential_id' => $publicKeyCredentialSource->publicKeyCredentialId,
            'data' => (new WebauthnSerializerFactory(AttestationStatementSupportManager::create()))
                ->create()
                ->serialize($publicKeyCredentialSource, 'json'),
        ]);

        return to_route('profile.edit')->withFragment('managePasskey');
    }

    /**
     * Display the specified resource.
     */
    public function show(Passkey $passkey)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Passkey $passkey)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Passkey $passkey)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Passkey $passkey)
    {
        $passkey->delete();
    }
}
