<?php

use App\Models\Creditor;
use Laravel\Lumen\Testing\DatabaseMigrations;

class CreditorControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * should return paginated 10 creditors
     *
     * @return void
     */
    public function test_creditor_Controller_index()
    {
        Creditor::factory()->count(20)->create();
        
        $this->json("get", '/api/creditors')
            ->seeJsonStructure([
                "data" => [
                    [
                        "id",
                        "name",
                        "email",
                        "phone",
                        "contact_info",
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
     * should create new creditor
     *
     * @return void
     */
    public function test_creditor_Controller_create()
    {
        $creditor = Creditor::factory()->make();
        
        $this->json("post", '/api/creditors', $creditor->toArray())
            ->seeInDatabase('creditors', [
                "name"              => $creditor->name ,
                "email"             => $creditor->email ,
                "phone"             => $creditor->phone ,
                "contact_info"      => json_encode($creditor->contact_info) ,
            ])->seeJson([
                "name"              => $creditor->name ,
                "email"             => $creditor->email ,
                "phone"             => $creditor->phone ,
                "contact_info"      => $creditor->contact_info ,
            ])->assertResponseOk();
    }

    /**
     * should update creditor attributes
     *
     * @return void
     */
    public function test_creditor_Controller_update()
    {
        $creditor = Creditor::factory()->create();
        
        //name
        $this->json("patch", '/api/creditors', ["id" => $creditor->id, "name" => "test_company"])
            ->seeInDatabase('creditors', [
                "name"              => 'test_company',
            ])->seeJson([
                "name"              => 'test_company',
            ])->assertResponseOk();
        
        //phone
        $this->json("patch", '/api/creditors', ["id" => $creditor->id, "phone" => "00000000"])
            ->seeInDatabase('creditors', [
                "phone"              => '00000000',
            ])->seeJson([
                "phone"              => '00000000',
            ])->assertResponseOk();
        
        //email
        $this->json("patch", '/api/creditors', ["id" => $creditor->id, "email" => "test@testing.com"])
            ->seeInDatabase('creditors', [
                "email"              => 'test@testing.com',
            ])->seeJson([
                "email"              => 'test@testing.com',
            ])->assertResponseOk();
        
        //contact_info (append new attr)
        $contactInfo = $creditor->contact_info + ['test' => "testAttribute"];
        $this->json("patch", '/api/creditors', ["id" => $creditor->id, "contact_info" => $contactInfo])
            ->seeInDatabase('creditors', [
                "contact_info"              => json_encode($contactInfo),
            ])->seeJson([
                "contact_info"              => $contactInfo
            ])->assertResponseOk();
    }
}
