<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

trait ResolvesExportPeriod
{
    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    protected function resolvePeriod(Request $request): array
    {
        $from = $request->filled('from')
            ? Carbon::parse($request->input('from'))->startOfDay()
            : now()->startOfMonth();

        $to = $request->filled('to')
            ? Carbon::parse($request->input('to'))->endOfDay()
            : now()->endOfMonth();

        return [$from, $to];
    }
}
