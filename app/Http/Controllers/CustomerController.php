<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

 
class customerController extends Controller
{
    //
        /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    public $user;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    
    /**
     * @OA\GET(
     *     path="/api/customer",
     *     tags={"Customer"},
     *     summary="Get Customer List",
     *     description="Get Customer List as Array",
     *     operationId="index Customer",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200,description="Get Customer List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index()
    {
        try {
            $customer  = Customer::orderBy('id', 'DESC')->paginate(10);
            return $this->responseSuccess($customer, 'Customer List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
    /**
     * @OA\GET(
     *     path="/api/customer/show/{id}",
     *     tags={"Customer"},
     *     summary="Show Customer Details",
     *     description="Show Customer Details",
     *     operationId="Show Customer",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Show Customer Details"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show($id): JsonResponse
    {
        try {
            
            $data  = Customer::where('id',$id)->first();
            if (is_null($data)) {
                return $this->responseError(null, 'Customer Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Customer Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * 
     * @OA\POST(
     *     path="/api/customer/create",
     *     tags={"Customer"},
     *     summary="Create New Customer",
     *     description="Create New Customer",
     *     operationId="Create Customer",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="integer", example="Jauhari"),
     *              @OA\Property(property="phone", type="text", example="0857 8155 0337"),
     *              @OA\Property(property="address", type="text", example="Semarang"),
     *              @OA\Property(property="email", type="text", example="jauharimalikupil@gmail.com")
     *              
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="Create New Customer" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

    public function create(Request $request){
        return $this->save($request);
    }
 
    
    public function save(Request $request)
    {
        try {
            $data = Customer::where('email',$request->email)->first();
            if($data){
                $data->update($request->all());
            }else{
                $data = Customer::create($request->all());
            }
            return $this->responseSuccess($data, 'Customer Data Update Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * 
     * @OA\POST(
     *     path="/api/customer/edit",
     *     tags={"Customer"},
     *     summary="Edit Customer",
     *     description="Edit Customer",
     *     operationId="Edit Customer",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="integer", example="Jauhari"),
     *              @OA\Property(property="phone", type="text", example="0857 8155 0337"),
     *              @OA\Property(property="address", type="text", example="Semarang"),
     *              @OA\Property(property="email", type="text", example="jauharimalikupil@gmail.com")
     *              
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="Update Data Berhasil" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */

    public function edit(Request $request){
        return $this->save($request);
    }

    
    /**
     * @OA\DELETE(
     *     path="/api/customer/{id}",
     *     tags={"Customer"},
     *     summary="Delete Customer",
     *     description="Delete Customer",
     *     operationId="destroy Customer",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Delete Customer"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function destroy($id): JsonResponse
    {
        try {
            $customer = Customer::where('id',$id)->first();
            if($customer){
                $customer->forceDelete();
            }else{
                return $this->responseError(null, 'Customer Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($customer, 'Customer Deleted Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
