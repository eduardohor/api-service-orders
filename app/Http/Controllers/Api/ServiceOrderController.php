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
        $rules = [
            'vehiclePlate' => 'required|string|max:7',
            'entryDateTime' => 'required|date',
            'exitDateTime' => 'required|date',
            'priceType' => 'required|string|max:55',
            'price' => 'required',
            'userId' => 'required|exists:users,id',

        ];

        $feedback = [
            'required' => 'O campo :attribute é obrigatório',
            'vehiclePlate.max' => 'O campo :attribute aceita no máximo 7 caracteres',
            'priceType.max' => 'O campo :attribute aceita no máximo 55 caracteres',
            'userId.exists' => 'O id de usuário selecionado é inválido'

        ];

        $request->validate($rules, $feedback);

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
