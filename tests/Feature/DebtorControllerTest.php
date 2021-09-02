<?php

use App\Models\Debtor;
use Laravel\Lumen\Testing\DatabaseMigrations;

class DebtorControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * should return paginated 10 debtors
     *
     * @return void
     */
    public function test_debtor_Controller_index()
    {
        Debtor::factory()->count(20)->create();
        
        $this->json("get", '/api/debtors')
            ->seeJsonStructure([
                "data" => [
                    [
                        "id",
                        "name",
                        "email",
                        "phone",
                        "contact_info",
                        "total_debt"
                    ]
                ]
            ])->seeJson([
                "current_page"  => 1,
                "last_page"     => 2,
                "total"         => 20,
                "per_page"      => 10,
                "from"          => 1,
                "to"            => 10,
            ])->assertResponseOk();
    }

    /**
     * should create new debtor
     *
     * @return void
     */
    public function test_debtor_Controller_create()
    {
        $debtor = Debtor::factory()->make();

        $this->json("post", '/api/debtors', $debtor->toArray() + ["total_debt" => 200])
            ->seeInDatabase('debtors', [
                "name"              => $debtor->name ,
                "email"             => $debtor->email ,
                "phone"             => $debtor->phone ,
                "total_debt"        => "0.00", //default can't be setted throw api
                "contact_info"      => json_encode($debtor->contact_info) ,
            ])->seeJson([
                "name"              => $debtor->name ,
                "email"             => $debtor->email ,
                "phone"             => $debtor->phone ,
                "contact_info"      => $debtor->contact_info ,
            ])->assertResponseOk();
    }

    /**
     * should update debtor attributes
     *
     * @return void
     */
    public function test_debtor_Controller_update()
    {
        $debtor = Debtor::factory()->create();
        
        //name
        $this->json("patch", '/api/debtors', ["id" => $debtor->id, "name" => "test_company"])
            ->seeInDatabase('debtors', [
                "name"              => 'test_company',
            ])->seeJson([
                "name"              => 'test_company',
            ])->assertResponseOk();
        
        //phone
        $this->json("patch", '/api/debtors', ["id" => $debtor->id, "phone" => "00000000"])
            ->seeInDatabase('debtors', [
                "phone"              => '00000000',
            ])->seeJson([
                "phone"              => '00000000',
            ])->assertResponseOk();
        
        //email
        $this->json("patch", '/api/debtors', ["id" => $debtor->id, "email" => "test@testing.com"])
            ->seeInDatabase('debtors', [
                "email"              => 'test@testing.com',
            ])->seeJson([
                "email"              => 'test@testing.com',
            ])->assertResponseOk();
        
        //total_debt (unupdatable)
        $this->json("patch", '/api/debtors', ["id" => $debtor->id, "total_debt" => 200])
            ->seeInDatabase('debtors', [
                "total_debt"              => "0.00",
            ])->seeJson([
                "total_debt"              => "0.00",
            ])->assertResponseOk();
        
        //contact_info (append new attr)
        $contactInfo = $debtor->contact_info + ['test' => "testAttribute"];
        $this->json("patch", '/api/debtors', ["id" => $debtor->id, "contact_info" => $contactInfo])
            ->seeInDatabase('debtors', [
                "contact_info"              => json_encode($contactInfo),
            ])->seeJson([
                "contact_info"              => $contactInfo
            ])->assertResponseOk();
    }
}
