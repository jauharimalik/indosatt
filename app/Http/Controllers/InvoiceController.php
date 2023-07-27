<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Invoice_detail;
use PDF;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


/**
 * @OA\Info(
 *     description="API Documentation Test Indosat",
 *     version="1.0.0",
 *     title="Basic CRUD Laravel API Documentation",
 *     @OA\Contact(
 *         email="jauharimalikupil@gmail.com"
 *     ),
 *     @OA\License(
 *         name="GPL2",
 *         url="https://jauharimalik.github.io"
 *     )
 * )
 */

 
class InvoiceController extends Controller
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
        $this->user = Auth::guard()->user();
    }

    
    /**
     * @OA\GET(
     *     path="/api/invoice",
     *     tags={"Invoice"},
     *     summary="Get invoice List",
     *     description="Get invoice List as Array",
     *     operationId="index invoice",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200,description="Get invoice List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index()
    {
        try {
            $invoice  = Invoice::with(['customer', 'detail','detail.product'])->orderBy('created_at', 'DESC')->paginate(10);
            return $this->responseSuccess($invoice, 'Invoice List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    
    /**
     * @OA\GET(
     *     path="/api/invoice/show/{id}",
     *     tags={"Invoice"},
     *     summary="Show Invoice Details",
     *     description="Show Invoice Details",
     *     operationId="Show Invoice",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Show Invoice Details"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show($id): JsonResponse
    {
        try {
            
            $data  = Invoice::with(['customer', 'detail','detail.product'])->where('nomorbukti',$id)->orderBy('created_at', 'DESC')->first();
            if (is_null($data)) {
                return $this->responseError(null, 'Invoice Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Product Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * 
     * @OA\POST(
     *     path="/api/invoice/create",
     *     tags={"Invoice"},
     *     summary="Create New Invoice",
     *     description="Create New Invoice",
     *     operationId="Create Invoice",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="customer_id", type="integer", example=104),
     *              @OA\Property(property="duedate", type="date", example="27-07-2023"),
     *              @OA\Property(property="details", type="object", example={{
     *                  "id":1,"harga":15000,"qty":3
     *              },{
     *                  "id":2,"harga":35000,"qty":5
     *              },{
     *                  "id":3,"harga":38000,"qty":9
     *              }})
     *              
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="Create New Invoice" ),
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
            $buktibaru = Invoice::orderBy('nomorbukti','desc')->take(1)->first();
            $nomorbukti = ($request->nomorbukti) ? ($request->nomorbukti) : (($buktibaru) ? ($buktibaru->nomorbukti + 1) : 1);

            $subtotal = 0;
            $produkarr = [];
            foreach($request->details as $idk => $inv){
                $produkc = Product::where('id',$inv['id'])->first();
                if($produkc){
                    $produk['product_id'] = $inv['id'];
                    $produk['qty'] = $inv['qty'];
                    $produk['price'] = $inv['harga'];
                    $produk['subtotal'] = ($inv['harga'] * $inv['qty']); 
                    array_push($produkarr,$produk);
                    $subtotal += $produk['subtotal']; 
                }  
            }

            $header_invoice = [];
            $header_invoice['nomorbukti'] = $nomorbukti;
            $header_invoice['customer_id'] = $request->customer_id;
            $header_invoice['duedate'] = date("Y-m-d",strtotime($request->duedate));
            $header_invoice['total'] = $subtotal;
            $header_invoice['user_id']=$this->user->id;
            
            
            $invd = Invoice::where('nomorbukti',$nomorbukti)->first();
            if($invd){
                $invid = $invd['id'];
                Invoice::where('nomorbukti',$nomorbukti)->update($header_invoice);
                Invoice_detail::where('invoice_id',$invid)->delete();
            }else{
                $invn = Invoice::create($header_invoice);
                $invid = $invn['id'];
                Invoice_detail::where('invoice_id',$invid)->delete();
            }

            $header_invoice['details'] = [];
            foreach($produkarr as $indk => $indv){
                $indv['invoice_id'] = $invid;
                Invoice_detail::create($indv);
                array_push($header_invoice['details'],$indv);
            }

            return $this->responseSuccess($header_invoice, 'New Data Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * 
     * @OA\POST(
     *     path="/api/invoice/edit",
     *     tags={"Invoice"},
     *     summary="Edit Invoice",
     *     description="Edit Invoice",
     *     operationId="Edit Invoice",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     * 
     *              @OA\Property(property="nomorbukti", type="integer", example=2),
     *              @OA\Property(property="customer_id", type="integer", example=104),
     *              @OA\Property(property="duedate", type="date", example="27-07-2023"),
     *              @OA\Property(property="details", type="object", example={{
     *                  "id":1,"harga":15000,"qty":3
     *              },{
     *                  "id":2,"harga":35000,"qty":5
     *              }})
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
     *     path="/api/invoice/{id}",
     *     tags={"Invoice"},
     *     summary="Delete Invoice",
     *     description="Delete Invoice",
     *     operationId="destroy invoice",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Delete Invoice"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function destroy($id): JsonResponse
    {
        try {
            $invoice = Invoice::where('nomorbukti',$id)->first();
            if($invoice){
                $invoiced = Invoice_detail::where('invoice_id',$invoice->id)->forceDelete();
                $invoice->forceDelete();
            }else{
                return $this->responseError(null, 'Invoice Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($invoice, 'Invoice Deleted Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
