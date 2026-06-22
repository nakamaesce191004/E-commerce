<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * Display the homepage.
     */
    public function home()
    {
        return response()->html('
            <html>
                <head>
                    <title>Laravel Test</title>
                </head>
                <body style="font-family: Arial; text-align:center; padding:50px;">
                    <h1>Laravel OK</h1>
                    <p>Laravel berhasil berjalan di Vercel.</p>
                </body>
            </html>
        ');
    }

    /**
     * Display the about page.
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('contact');
    }
}