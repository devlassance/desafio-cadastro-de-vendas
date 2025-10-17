<?php

namespace App\Http\Controllers;

use App\Http\Requests\Seller\CreateSellerRequest;
use App\Services\Seller\CreateSellersService;
use App\Services\Seller\GetSellersService;
use App\Services\Seller\GetSellersWithSalesService;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SellerController extends Controller
{
    public function __construct(
        private GetSellersService $getSellersService,
        private CreateSellersService $createSellersService,
        private GetSellersWithSalesService $getSellersWithSalesService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json($this->getSellersService->execute());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CreateSellerRequest $request
     * @return JsonResponse
     *
     * @throws ValidationException
     * @throws Exception
     */
    public function store(CreateSellerRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->createSellersService->execute($data);
        return response()->json(['message' => 'Seller created successfully'], ResponseAlias::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return response()->json($this->getSellersWithSalesService->execute($id));
    }

}
