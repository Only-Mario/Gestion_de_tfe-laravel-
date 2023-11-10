<?php

namespace App\Http\Controllers;

use App\Models\Tfe;
use Illuminate\Http\Request;

class TfeListController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10; // Reports per page
        $tfes = Tfe::with('document')->paginate($perPage);

        return view('tfe.liste', compact('tfes'));
    }
}
