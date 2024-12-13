<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CookieController extends Controller
{

    public function setCookie(Request $request): JsonResponse
    {
        if ($request->filled('value') && $request->filled('name')) {
            Session::put($request->input('name'), (int)$request->input('value'));
        }

        return response()->json(['ok'], 200);
    }

    public function resetVisualOptions(Request $request): JsonResponse
    {
        Session::put(['vo_font_percent' => 100, 'vo_high_contrast' => 0]);

        return response()->json(['ok'], 200);
    }
}
