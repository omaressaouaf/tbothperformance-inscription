<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalendlyWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        info($request->all());
        return response("Webhook handled", 200);
    }
}
