<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

 

class CustomerTest extends TestCase
{
    /**
     * Check if public profile api is accessible or not.
     *
     * @return void
     */
    public function test_cant_access_public_product_api()
    {
        $response = $this->get('/api/customer');
        $response->assertStatus(401);
    }

    /**
     * Test if customer is creatable.
     *
     * @return void
     */
    public function test_can_create_customer(){
        // Login the user first.
        Auth::login(User::where('email', 'jauharimalikupil@gmail.com')->first());
        $customer = new Customer();

        // First count total number of products
        $total = Customer::get('id')->count();

        $data = $customer->create([
            'name'    => 'Hello 1',
            'phone'   => 1234,
            'email'   => "hallotest1@gmail.com",
            'address' => 'Jakarta',
        ]);

        $this->assertDatabaseCount('customers', $total + 1);

        // Delete the product as need to keep it in database for other tests
        $data = Customer::where('name', 'Hello 1')->where('phone', 1234)->first();
        $data->delete();
    }

    public function test_can_edit_customer(){
        // Login the user first.
        Auth::login(User::where('email', 'jauharimalikupil@gmail.com')->first());
        $customer = new Customer();

        $total = Customer::get('id')->count();
        $customer->create([
            'name'    => 'Hello 2',
            'phone'   => 1234,
            'email'   => "testedit@gmail.com",
            'address' => 'Jakarta',
        ]);

        $this->assertDatabaseCount('customers', $total + 1);
        // Delete the product as need to keep it in database for other tests
        $data = Customer::where('email', 'testedit@gmail.com')->first();
        if($data){
            $data = $data->update(array('phone' => 54321));
        }
        
        Customer::where('email', 'testedit@gmail.com')->delete();
    }

    public function test_can_delete_customer(){
        // Login the user first.
        Auth::login(User::where('email', 'jauharimalikupil@gmail.com')->first());
        $customer = new Customer();

        // First count total number of products
        $total = Customer::get('id')->count();

        $data = $customer->create([
            'name'    => 'Hello 3',
            'phone'   => 1234,
            'email'   => "hallotest3@gmail.com",
            'address' => 'Jakarta',
        ]);

        $this->assertDatabaseCount('customers', $total + 1);

        // Delete the product as need to keep it in database for other tests
        $data = Customer::where('name', 'Hello 3')->where('phone', 1234)->first();
        $data->delete();
    }
}
