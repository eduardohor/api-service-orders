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

    public function index(Request $request)
    {
        $serviceOrders = [];

        if ($request->has('filter')) {
            $conditions = (explode(':', $request->filter));
            $serviceOrders = $this->serviceOrder->with('user')
                ->where($conditions[0], $conditions[1], $conditions[2])
                ->get();
        } else {
            $serviceOrders =  $this->serviceOrder->with('user')
                ->paginate(5);
        }

        return response()->json($serviceOrders, 200);
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
