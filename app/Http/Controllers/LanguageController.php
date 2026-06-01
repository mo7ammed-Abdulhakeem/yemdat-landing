<?php

namespace App\Http\Controllers;

class LanguageController extends Controller
{
    /**
     * Switch the session locale (English/Arabic) and return to the previous page.
     */
    public function switch(string $locale)
    {
        if (in_array($locale, ['en', 'ar'], true)) {
            session(['locale' => $locale]);
        }

        return back();
    }
}
