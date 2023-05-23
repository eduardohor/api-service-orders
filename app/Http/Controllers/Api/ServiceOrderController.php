<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;

class ServiceOrderController extends Controller
{
    protected $serviceOrder;

    public function __construct(ServiceOrder $serviceOrder)
    {
        $this->serviceOrder = $serviceOrder;
    }

    public function create(Request $request)
    {
        $serviceOrder = $this->serviceOrder->create([
            'vehiclePlate' => $request->vehiclePlate,
            'entryDateTime' => $request->entryDateTime,
            'exitDateTime' => $request->exitDateTime,
            'priceType' => $request->priceType,
            'price' => $request->price,
            'userId' => $request->userId
        ]);

        return response()->json($serviceOrder, 201);
    }
}
