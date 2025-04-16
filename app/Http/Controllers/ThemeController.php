<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    public function saveThemePreference(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark',
        ]);

        $chief = Auth::user();
        $chief->theme = $request->theme;
        $chief->save();

        return response()->json(['message' => 'Theme preference saved successfully!']);
    }
}
