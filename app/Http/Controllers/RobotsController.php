<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    /**
     * Generate robots.txt
     */
    public function index(): Response
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n\n";
        $content .= "# Sitemap\n";
        $content .= "Sitemap: " . url('/sitemap.xml') . "\n\n";
        $content .= "# Disallow private pages\n";
        $content .= "Disallow: /dashboard\n";
        $content .= "Disallow: /settings\n";
        $content .= "Disallow: /login\n";
        $content .= "Disallow: /register\n";
        $content .= "Disallow: /password\n";
        $content .= "Disallow: /two-factor\n";

        return response($content, 200)
            ->header('Content-Type', 'text/plain');
    }
}

