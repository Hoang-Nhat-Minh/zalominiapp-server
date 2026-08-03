<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PartyDocument;

class DocumentController extends Controller
{
    public function index()
    {
        $stats = (object)[
            'total' => PartyDocument::count(),
            'published' => PartyDocument::where('status', 'published')->count(),
            'draft' => PartyDocument::where('status', 'draft')->count(),
            'archived' => PartyDocument::where('status', 'archived')->count(),
        ];

        $documents = PartyDocument::with('author')
            ->latest()
            ->paginate(10);

        return view('frontend.documents.index', compact(
            'stats',
            'documents'
        ));
    }
}