<?php

namespace App\Http\Controllers;

use App\Services\Sale\GetSalesService;
use Exception;
use Illuminate\Http\JsonResponse;

class SaleController extends Controller
{
    public function __construct(private GetSalesService $getSalesService)
    {
        //
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function __invoke(): JsonResponse
    {
        return response()->json($this->getSalesService->execute());
    }
}
