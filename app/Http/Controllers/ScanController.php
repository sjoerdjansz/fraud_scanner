<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use Inertia\Inertia;
use Inertia\Response;

class ScanController extends Controller
{
    public function show(Scan $scan): Response
    {
        return Inertia::render('FraudScanner', [
            'scan' => $scan->only('id', 'created_at'),
            'customers' => $scan->customers,
        ]);
    }

    public function index(): Response
    {
        return Inertia::render('Scans', [
            'scans' => Scan::query()->with('customers')->orderBy('created_at', 'desc')->get(),
        ]);
    }
}
