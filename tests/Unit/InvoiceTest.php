<?php

namespace Tests\Unit;

use App\Models\Invoice;
use App\Models\Invoice_detail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;


class InvoiceTest extends TestCase
{
    /**
     * Check if public profile api is accessible or not.
     *
     * @return void
     */
    public function test_cant_access_public_product_api()
    {
        $response = $this->get('/api/invoice');
        $response->assertStatus(401);
    }

    /**
     * Test if customer is creatable.
     *
     * @return void
     */
    public function test_can_create_data(){
        // Login the user first.
        Auth::login(User::where('email', 'jauharimalikupil@gmail.com')->first());
        $input = new Invoice();

        // First count total number of products
        $total = Invoice::get('id')->count();
        $inputan['nomorbukti'] = "123";
        $inputan['customer_id'] = 1;
        $inputan['duedate'] = date("Y-m-d");
        $inputan['total'] = 60000;
        $inputan['user_id']= 1;

        $input->create($inputan);
        $this->assertDatabaseCount('invoices', $total + 1);

        // Delete the product as need to keep it in database for other tests
        $data = Invoice::where('nomorbukti', '123')->first();
        $data->delete();
    }
    public function test_can_delete_data(){
        // Login the user first.
        Auth::login(User::where('email', 'jauharimalikupil@gmail.com')->first());
        $input = new Invoice();

        // First count total number of products
        $total = Invoice::get('id')->count();
        $inputan['nomorbukti'] = "123";
        $inputan['customer_id'] = 1;
        $inputan['duedate'] = date("Y-m-d");
        $inputan['total'] = 60000;
        $inputan['user_id']= 1;

        $input->create($inputan);
        $this->assertDatabaseCount('invoices', $total + 1);

        // Delete the product as need to keep it in database for other tests
        $data = Invoice::where('nomorbukti', '123')->first();
        $data->delete();
    }
}
