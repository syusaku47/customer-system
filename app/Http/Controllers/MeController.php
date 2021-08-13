<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class MeController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
//        dd($user    );

        return new JsonResponse([
            'id' => $user->id,
            'name' => $user->name,
            'mail_address' => $user->mail_address,
        ]);
    }
}
