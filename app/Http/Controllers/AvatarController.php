<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    public function __invoke(string $hash)
    {
        $path = "avatars/$hash.svg";

        if (! Storage::exists($path)) {
            // Download from source
            Storage::put($path,
                file_get_contents("https://hostedboringavatars.vercel.app/api/beam?colors=FF6467,E63B3F,B71C1C,FF2A2E,7F0F12&name={$hash}")
            );
        }

        return Storage::download($path);
    }
}
