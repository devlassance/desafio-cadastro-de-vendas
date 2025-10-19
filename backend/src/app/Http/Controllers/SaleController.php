<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sale\CreateSaleRequest;
use App\Services\Sale\CreateSaleService;
use App\Services\Sale\GetSalesService;
use Exception;
use Illuminate\Http\JsonResponse;
use Nette\Schema\ValidationException;

class SaleController extends Controller
{
    public function __construct(
        private readonly GetSalesService $getSalesService,
        private readonly CreateSaleService $createSaleService,
    )
    {
        //
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function index(): JsonResponse
    {
        return response()->json($this->getSalesService->execute());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateSaleRequest $request
     * @return JsonResponse
     *
     * @throws ValidationException
     * @throws Exception
     */
    public function store(CreateSaleRequest $request): JsonResponse
    {
        $this->createSaleService->execute($request->validated());
        return response()->json(['message' => 'Sale created successfully'], 201);
    }
}
