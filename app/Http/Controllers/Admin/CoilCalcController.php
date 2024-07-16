<?php

namespace App\Http\Controllers\Admin;

use App\Services\CoilCalcService;
use Illuminate\Http\Request;
use Exception;

class CoilCalcController
{
    protected $coilCalcService;

    public function __construct(CoilCalcService $coilCalcService)
    {
        $this->coilCalcService = $coilCalcService;
    }

    public function calculate(Request $request)
    {
        $reqStr = $request->input('reqStr', 'Your default request string');
        
        try {
            $result = $this->coilCalcService->calculate($reqStr);
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
