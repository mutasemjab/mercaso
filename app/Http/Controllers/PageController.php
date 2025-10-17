<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display the privacy policy page.
     */
    public function privacyPolicy()
    {
        $page = Page::privacyPolicy()->first();
        

        return view('privacy', compact('page'));
    }

    /**
     * Display the terms and conditions page.
     */
    public function termsConditions()
    {
        $page = Page::termsConditions()->first();
        

        return view('terms', compact('page'));
    }

   

}