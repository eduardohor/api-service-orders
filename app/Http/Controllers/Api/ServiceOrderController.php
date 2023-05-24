<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *   title="API Test Jump",
 *   version="1.0.0",
 *   contact={
 *     "email": "eduardo.hor@outlook.com"
 *   }
 * )
 */

class ServiceOrderController extends Controller
{
    protected $serviceOrder;

    public function __construct(ServiceOrder $serviceOrder)
    {
        $this->serviceOrder = $serviceOrder;
    }

    /**
     * @OA\Get(
     *     tags={"Ordens de Serviço"},
     *     summary="Obtenha todas as ordens de serviço",
     *     description="Este endpoint retorna todas as ordens de serviço listando 5 ordens por página, com opção de filtro para buscar veículo por placa passando a placa do veículo no campo 'vehicle_plate'",
     *     path="/api/service-orders",
     *     @OA\Parameter(
     *          name="page",
     *          description="Escolher qual página deseja listar",
     *          required=false,
     *          in="query"
     *      ),
     *     @OA\Parameter(
     *          name="vehicle_plate",
     *          description="Filtrar ordem de serviço pela placa do veículo",
     *          required=false,
     *          in="query"
     *      ),  
     *     @OA\Response(
     *          response=200,
     *          description="Dados da ordem de serviço",
     *          @OA\JsonContent(
     *              @OA\Property(property="vehiclePlate", type="string", example="PDF1234", description="Placa do veículo"),
     *              @OA\Property(property="entryDateTime", type="date", example="2023-05-22", description="Data da entrada do veículo"),
     *              @OA\Property(property="exitDateTime", type="date", example="2023-05-23", description="Data da saída do veículo"),
     *              @OA\Property(property="priceType", type="string", example="Cartão de Crédito", description="Tipo de Pagamento"),
     *              @OA\Property(property="price", type="double", example="50.00", description="Preço cobrado"),
     *              @OA\Property(property="userId", type="integer", example="1" , description="Id do usuário do veículo"),
     *              @OA\Property(
     *                  property="user",
     *                  type="object",
     *                  nullable=false,
     *                  description="Informações do usuário",
     *              @OA\Property(
     *                  property="id",
     *                  type="integer",
     *                  nullable=false,
     *                  description="Id do usuário",
     *                  ),
     *              @OA\Property(
     *                  property="name",
     *                  type="string",
     *                  nullable=false,
     *                  description="Nome do usuário",
     *                  ),
     *              )
     * 
     *          )
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Não encontrado",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Não encontrado"),
     *          )
     *     )
     * )
     */

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

    /**
     * @OA\POST(
     *      tags={"Ordens de Serviço"},
     *      summary="Criar nova ordem de serviço",
     *      description="Este endpoint cria uma nova ordem de serviço",
     *      path="/api/service-orders",
     *      @OA\RequestBody(
     *      @OA\MediaType(
     *          mediaType="application/x-www-form-urlencoded",
     *          @OA\Schema(
     *             required={"vehicle_plate", "entry_date_time", "exit_date_time", "price_type", "price", "user_id"},
     *              @OA\Property(property="vehicle_plate", type="string", example="PDF8932", description="Placa do veículo"),
     *              @OA\Property(property="entry_date_time", type="date", example="2023-05-22", description="Data da entrada do veículo"),
     *              @OA\Property(property="exit_date_time", type="date", example="2023-05-23", description="Data da saída do veículo"),
     *              @OA\Property(property="price_type", type="string", example="Cartão de Crédito", description="Tipo de Pagamento"),
     *              @OA\Property(property="price", type="double", example="50.00", description="Preço cobrado"),
     *              @OA\Property(property="user_id", type="integer", example="1" , description="Id do usuário do veículo")
     *           
     *          )
     *      ),
     *  ),
     *  @OA\Response(
     *      response=201,
     *      description="Service Order created",
     *      @OA\JsonContent(
     *       @OA\Property(property="vehicle_plate", type="string", example="PDF1234", description="Placa do veículo"),
     *             @OA\Property(property="entry_date_time", type="date", example="2023-04-10", description="Data da entrada do veículo"),
     *             @OA\Property(property="exit_date_time", type="date", example="2023-04-12", description="Data da saída do veículo"),
     *             @OA\Property(property="price_type", type="double", example="Cartão de Crédito", description="Tipo de Pagamento"),
     *             @OA\Property(property="price", type="double", example="100.00" , description="Preço cobrado"),
     *             @OA\Property(property="user_id", type="integer", example="1", description="Id do usuário do veículo"),
     *    )
     *  ),
     *  @OA\Response(
     *      response=422,
     *      description="Error: Unprocessable Content",
     *      @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Placa de veículo já cadastrada . (and 2 more errors)"),
     *       @OA\Property(property="errors", type="string", example="..."),
     *    )
     *  )
     * )
     */


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
