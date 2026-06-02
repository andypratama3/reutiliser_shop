<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Handle contact form submission.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // For now, just log the submission. This can be replaced by mail, DB, or dispatching a job.
        Log::info('Contact form submission', $data);

        return back()->with('status', 'Thank you — your message has been received. We will reply within 48 hours.');
    }
}
