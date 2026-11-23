<?php

namespace App\Http\Controllers;

use App\Models\Tfe;

class SitemapController extends Controller
{
    public function index()
    {
        $tfes = Tfe::where('status', 1)->orderByDesc('updated_at')->limit(45000)->get(['id', 'theme', 'updated_at']);

        // En-tête ajouté ici : dans la vue, « <? » serait lu comme du PHP si short_open_tag est actif.
        $xml = '<' . '?xml version="1.0" encoding="UTF-8"?' . ">
" . view('sitemap', compact('tfes'))->render();

        return response($xml)->header('Content-Type', 'application/xml');
    }
}
