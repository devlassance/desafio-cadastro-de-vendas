<?php

namespace App\Http\Controllers;

use App\Exceptions\SellerNotFoundException;
use App\Http\Requests\Seller\CreateSellerRequest;
use App\Services\Seller\CreateSellersService;
use App\Services\Seller\GetSellersForSelect;
use App\Services\Seller\GetSellersService;
use App\Services\Seller\GetSellersWithSalesService;
use App\Services\Seller\ResendEmailSales;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class SellerController extends Controller
{
    public function __construct(
        private GetSellersService $getSellersService,
        private CreateSellersService $createSellersService,
        private GetSellersWithSalesService $getSellersWithSalesService,
        private GetSellersForSelect $getSellersForSelect,
        private ResendEmailSales $resendEmailSales
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
     *
     * @param int $id
     * @return JsonResponse
     *
     * @throws SellerNotFoundException
     * @throws Exception
     */
    public function show(int $id): JsonResponse
    {
        return response()->json($this->getSellersWithSalesService->execute($id));
    }

    /**
     * Display a listing of sellers for select input.
     *
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function showForSelect(): JsonResponse
    {
        return response()->json($this->getSellersForSelect->execute());
    }

    /**
     * Resend sales email to the seller.
     *
     * @param int $id
     * @return JsonResponse
     *
     * @throws Exception
     */
    public function resendEmailSales(int $id): JsonResponse
    {
        $this->resendEmailSales->execute($id);
        return response()->json(['message' => 'E-mail resent successfully'], ResponseAlias::HTTP_OK);
    }

}
