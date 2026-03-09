<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuoteRequest;
use App\Services\QuoteService;

class QuoteController extends Controller
{
    public function quote(QuoteRequest $request, QuoteService $service)
    {
        $data = $service->buildQuote($request->validated());
        return response()->json($data);
    }
}
