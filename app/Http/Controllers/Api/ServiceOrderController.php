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

        if ($request->has('vehicle_plate')) {
            $condition = $request->vehicle_plate;
            $serviceOrders = $this->serviceOrder->with('user')
                ->where('vehiclePlate', $condition)
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
            'vehicle_plate' => 'required|unique:service_orders,vehiclePlate|string|max:7',
            'entry_date_time' => 'required|date',
            'exit_date_time' => 'required|date',
            'price_type' => 'required|string|max:55',
            'price' => 'required',
            'user_id' => 'required|exists:users,id',

        ];

        $feedback = [
            'required' => 'O campo :attribute é obrigatório',
            'vehicle_plate.unique' => 'Placa de veículo já cadastrada',
            'vehicle_plate.max' => 'O campo :attribute aceita no máximo 7 caracteres',
            'price_type.max' => 'O campo :attribute aceita no máximo 55 caracteres',
            'user_id.exists' => 'O id de usuário selecionado é inválido'

        ];

        $request->validate($rules, $feedback);

        $serviceOrder = $this->serviceOrder->create([
            'vehiclePlate' => $request->vehicle_plate,
            'entryDateTime' => $request->entry_date_time,
            'exitDateTime' => $request->exit_date_time,
            'priceType' => $request->price_type,
            'price' => $request->price,
            'userId' => $request->user_id
        ]);

        return response()->json($serviceOrder, 201);
    }
}
