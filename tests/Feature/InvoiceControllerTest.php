<?php

use App\Models\Debtor;
use App\Models\Invoice;
use App\Models\Creditor;
use App\Events\InvoiceMarkedAsOpenEvent;
use App\Events\InvoiceMarkedAsPaidEvent;
use Laravel\Lumen\Testing\DatabaseMigrations;

class InvoiceControllerTest extends TestCase
{
    use DatabaseMigrations;

    /********************************
     ********** Test index API's *********
     **********************************/

    /**
     * should return paginated 10 invoices
     *
     * @return void
     */
    public function test_invoice_Controller_index()
    {
        Invoice::factory()->count(20)->create();
        
        $this->json("get", '/api/invoices')
            ->seeJsonStructure([
                "data" => [
                    [
                        "id",
                        "total_amount",
                        "status",
                        "debtor_id",
                        "creditor_id",
                        "currency_id",
                        "due_date",
                        "open_date",
                        "paid_date",
                        "is_paid",
                        "is_open",
                        "currency" => [
                            "id",
                            "name",
                            "code",
                        ]
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
     * should return paginated 10 invoices for given debtor
     *
     * @return void
     */
    public function test_invoice_Controller_index_by_debtor()
    {
        $debtor = Debtor::factory()->create();
        Invoice::factory()->count(5)->for($debtor)->create(); // 5 invoices for debtor
        Invoice::factory()->count(5)->create(); // 5 invoices diffrente debtors
        
        $this->json("get", "/api/invoices/debtor?debtor_id={$debtor->id}")
            ->seeJsonStructure([
                "data" => [
                    [
                        "id",
                        "total_amount",
                        "status",
                        "debtor_id",
                        "creditor_id",
                        "currency_id",
                        "due_date",
                        "open_date",
                        "paid_date",
                        "is_paid",
                        "is_open",
                        "currency" =>[
                            "id",
                            "name",
                            "code",
                        ]
                    ]
                ]
            ])->seeJson([
                "current_page"  => 1,
                "last_page"     => 1,
                "total"         => 5,
                "per_page"      => 10,
                "from"          => 1,
                "to"            => 5,
            ])->assertResponseOk();
    }

    /**
     * should return paginated 10 invoices for given creditor
     *
     * @return void
     */
    public function test_invoice_Controller_index_by_creditor()
    {
        $creditor = Creditor::factory()->create();
        Invoice::factory()->count(5)->create(['creditor_id' => $creditor->id]); // 5 invoices for creditor
        Invoice::factory()->count(5)->create(); // 5 invoices diffrente creditors
        
        $this->json("get", "/api/invoices/creditor?creditor_id={$creditor->id}")
            ->seeJsonStructure([
                "data" => [
                    [
                        "id",
                        "total_amount",
                        "status",
                        "debtor_id",
                        "creditor_id",
                        "currency_id",
                        "due_date",
                        "open_date",
                        "paid_date",
                        "is_paid",
                        "is_open",
                        "currency" =>[
                            "id",
                            "name",
                            "code",
                        ]
                    ]
                ]
            ])->seeJson([
                "current_page"  => 1,
                "last_page"     => 1,
                "total"         => 5,
                "per_page"      => 10,
                "from"          => 1,
                "to"            => 5,
            ])->assertResponseOk();
    }

    /**********************************
     ****** Test create API's ********
     **********************************/

    /**
     * should create new invoice
     *
     * @return void
     */
    public function test_invoice_Controller_create()
    {
        $invoice = Invoice::factory()->make()->toArray();
        
        $this->json("post", '/api/invoices', $invoice)
            ->seeInDatabase('invoices', [
                "total_amount"     => $invoice['total_amount'],
                "debtor_id"        => $invoice['debtor_id'],
                "creditor_id"      => $invoice['creditor_id'],
                "currency_id"      => $invoice['currency_id'],
                "status"           => Invoice::Initialize,
                "due_date"         => $invoice['due_date'],
                "open_date"        => $invoice['open_date'],
                "paid_date"        => $invoice['paid_date'],
            ])->seeJson([
                "total_amount"     => $invoice['total_amount'],
                "debtor_id"        => $invoice['debtor_id'],
                "creditor_id"      => $invoice['creditor_id'],
                "currency_id"      => $invoice['currency_id'],
                "due_date"         => $invoice['due_date'],
                "is_paid"          => false,
                "is_open"          => false,
            ])->assertResponseOk();
    }

    /**
     * should create new invoice for deptor exceeded the limit
     *
     * @return void
     */
    public function test_invoice_Controller_create_for_debtor_exceeded_the_limit()
    {
        $debtor  = Debtor::factory()->create(['total_debt' => Invoice::TotalDebtLimit]);
        $invoice = Invoice::factory()->for($debtor)->make()->toArray();

        $this->json("post", '/api/invoices', $invoice)
            ->seeJson(["Total debt exceeded the limit for given debtor."])
            ->assertResponseStatus(422);
    }

    /********************************
     *** Test open invoice API's ***
     **********************************/
    
    /**
     * should mark invoice as open
     *
     * @return void
     */
    public function test_invoice_Controller_mark_as_open()
    {
        $invoice = Invoice::factory()->create();

        Event::fake();

        $this->json("patch", '/api/invoices/open', ['id' => $invoice->id])
            ->seeInDatabase('invoices', [
                "id"               => $invoice->id,
                "status"           => Invoice::Open,
            ])->seeJson([
                "id"               => $invoice->id,
                "status"           => Invoice::Open,
            ])->assertResponseOk();
            
        Event::assertDispatched(InvoiceMarkedAsOpenEvent::class, function ($e) use ($invoice) {
            return  $e->invoice->id                === $invoice->id &&
                    $e->invoice->total_amount      === $invoice->total_amount ;
        });
    }

    /**
     * should throw 422 and didn't apply any change or fire event
     *
     * @return void
     */
    public function test_invoice_Controller_mark_as_open_with_already_opened_invoice()
    {
        $invoice = Invoice::factory()->open()->create();
        Event::fake();

        $this->json("patch", '/api/invoices/open', ['id' => $invoice->id])
            ->seeInDatabase('invoices', [
                "id"               => $invoice->id,
                "status"           => Invoice::Open,
            ])->seeJson(["Given invoice is already in open state."])
            ->assertResponseStatus(422);
            
        Event::assertNotDispatched(InvoiceMarkedAsOpenEvent::class);
    }

    /**
     * should throw 422 and didn't apply any change or fire event
     *
     * @return void
     */
    public function test_invoice_Controller_mark_as_open_paid_invoice()
    {
        $invoice = Invoice::factory()->paid()->create();
        Event::fake();

        $this->json("patch", '/api/invoices/open', ['id' => $invoice->id])
            ->seeInDatabase('invoices', [
                "id"               => $invoice->id,
                "status"           => Invoice::Paid,
            ])->seeJson(["Given invoice is in paid state only invoices with initialize state allowed to this action."])
            ->assertResponseStatus(422);
            
        Event::assertNotDispatched(InvoiceMarkedAsOpenEvent::class);
    }

    /**
     * should throw 422 and didn't apply any change or fire event
     *
     * @return void
     */
    public function test_invoice_Controller_mark_as_open_with_deptor_exceeds_debt_limit()
    {
        $debtor  = Debtor::factory()->create(['total_debt' => Invoice::TotalDebtLimit]);
        $invoice = Invoice::factory()->for($debtor)->create();
        Event::fake();

        $this->json("patch", '/api/invoices/open', ['id' => $invoice->id])
            ->seeInDatabase('invoices', [
                "id"               => $invoice->id,
                "status"           => Invoice::Initialize,
            ])->seeJson(["Total debt exceeded the limit for given debtor."])
            ->assertResponseStatus(422);
            
        Event::assertNotDispatched(InvoiceMarkedAsOpenEvent::class);
    }

    /********************************
     *** Test paid invoice API's ***
     **********************************/
    
    /**
     * should mark invoice as paid
     *
     * @return void
     */
    public function test_invoice_Controller_mark_as_paid()
    {
        $invoice = Invoice::factory()->open()->create();

        Event::fake();

        $this->json("patch", '/api/invoices/paid', ['id' => $invoice->id])
            ->seeInDatabase('invoices', [
                "id"               => $invoice->id,
                "status"           => Invoice::Paid,
            ])->seeJson([
                "id"               => $invoice->id,
                "status"           => Invoice::Paid,
            ])->assertResponseOk();
            
        Event::assertDispatched(InvoiceMarkedAsPaidEvent::class, function ($e) use ($invoice) {
            return  $e->invoice->id                === $invoice->id &&
                    $e->invoice->total_amount      === $invoice->total_amount ;
        });
    }

    /**
     * should throw 422 and didn't apply any change or fire event
     *
     * @return void
     */
    public function test_invoice_Controller_mark_as_paid_with_already_paids_invoice()
    {
        $invoice = Invoice::factory()->paid()->create();
        Event::fake();

        $this->json("patch", '/api/invoices/paid', ['id' => $invoice->id])
            ->seeInDatabase('invoices', [
                "id"               => $invoice->id,
                "status"           => Invoice::Paid,
            ])->seeJson(["Given invoice is already in paid state."])
            ->assertResponseStatus(422);
            
        Event::assertNotDispatched(InvoiceMarkedAsOpenEvent::class);
    }

    /**
     * should throw 422 and didn't apply any change or fire event
     *
     * @return void
     */
    public function test_invoice_Controller_mark_as_paid_initialize_invoice()
    {
        $invoice = Invoice::factory()->create();
        Event::fake();

        $this->json("patch", '/api/invoices/paid', ['id' => $invoice->id])
            ->seeInDatabase('invoices', [
                "id"               => $invoice->id,
                "status"           => Invoice::Initialize,
            ])->seeJson(["Given invoice is in initialize state only invoices with open state allowed to this action."])
            ->assertResponseStatus(422);
            
        Event::assertNotDispatched(InvoiceMarkedAsOpenEvent::class);
    }

    /********************************
     ** Test update attributes API's **
     **********************************/

    /**
     * should update invoice attributes
     *
     * @return void
     */
    public function test_invoice_Controller_update()
    {
        $invoice = Invoice::factory()->create();
        
        //total_amount
        $this->json("patch", '/api/invoices', ["id" => $invoice->id, "total_amount" => "25.00"])
            ->seeInDatabase('invoices', [ "total_amount" => "25.00"])
            ->seeJson(["total_amount" => "25.00"])
            ->assertResponseOk();
        
        //due_date
        $this->json("patch", '/api/invoices', ["id" => $invoice->id, "due_date" => "2020-05-01"])
            ->seeInDatabase('invoices', [ "due_date" => "2020-05-01"])
            ->seeJson(["due_date" => "2020-05-01"])
            ->assertResponseOk();
        
        //creditor_id
        $creditor = Creditor::factory()->create();
        $this->json("patch", '/api/invoices', ["id" => $invoice->id, "creditor_id" => $creditor->id])
            ->seeInDatabase('invoices', [ "creditor_id"     => $creditor->id])
            ->seeJson(["creditor_id"     => $creditor->id])
            ->assertResponseOk();
        
        //debtor_id (not updatable)
        $debtor = Debtor::factory()->create();
        $this->json("patch", '/api/invoices', ["id" => $invoice->id, "debtor_id" => $debtor->id])
            ->seeInDatabase('invoices', [ "debtor_id"     => $invoice->debtor_id])
            ->seeJson(["debtor_id"     => $invoice->debtor_id])
            ->assertResponseOk();

        //paid_date(not updatable)
        $this->json("patch", '/api/invoices', ["id" => $invoice->id, "paid_date" => "2020-05-01"])
            ->seeInDatabase('invoices', [ "paid_date" => null])
            ->seeJson(["paid_date" => null])
            ->assertResponseOk();
    
        //open_date(not updatable)
        $this->json("patch", '/api/invoices', ["id" => $invoice->id, "open_date" => "2020-05-01"])
                ->seeInDatabase('invoices', [ "open_date" => null])
                ->seeJson(["open_date" => null])
                ->assertResponseOk();
        
        //status(not updatable)
        $this->json("patch", '/api/invoices', ["id" => $invoice->id, "status" => Invoice::Open])
            ->seeInDatabase('invoices', [ "status" => Invoice::Initialize])
            ->seeJson(["status" => Invoice::Initialize])
            ->assertResponseOk();
    }

    /********************************
     ***** Test delete API's *********
     **********************************/

    /**
     * should throw 422 and didn't apply any change or fire event
     *
     * @return void
     */
    public function test_invoice_Controller_delete_initialize_invoice()
    {
        $invoice = Invoice::factory()->create();

        $this->json("delete", '/api/invoices', ['id' => $invoice->id])
            ->notSeeInDatabase('invoices', [
                "id"               => $invoice->id,
                "status"           => Invoice::Initialize,
            ])->seeJson(['Given invoice deleted successfully.'])
            ->assertResponseOk();
    }

    /**
     * should throw 422 and didn't apply any change or fire event
     *
     * @return void
     */
    public function test_invoice_Controller_delete_open_invoice()
    {
        $invoice = Invoice::factory()->open()->create();

        $this->json("delete", '/api/invoices', ['id' => $invoice->id])
            ->seeInDatabase('invoices', ["id" => $invoice->id])
            ->seeJson(["Given invoice is in open state only invoices with initialize state allowed to this action."])
            ->assertResponseStatus(422);
    }

    /**
     * should throw 422 and didn't apply any change or fire event
     *
     * @return void
     */
    public function test_invoice_Controller_delete_paid_invoice()
    {
        $invoice = Invoice::factory()->paid()->create();

        $this->json("delete", '/api/invoices', ['id' => $invoice->id])
            ->seeInDatabase('invoices', ["id" => $invoice->id])
            ->seeJson(["Given invoice is in paid state only invoices with initialize state allowed to this action."])
            ->assertResponseStatus(422);
    }
}
