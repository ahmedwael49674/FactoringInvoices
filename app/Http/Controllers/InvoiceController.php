<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\InvoiceService;
use App\Http\Requests\CreateInvoice;
use App\Http\Requests\DeleteInvoice;
use App\Http\Requests\UpdateInvoice;
use App\Http\Requests\MarkInvoiceAsOpen;
use App\Http\Requests\MarkInvoiceAsPaid;
use App\Http\Requests\IndexInvoicesByDebtor;
use App\Http\Requests\IndexInvoicesByCreditor;

class InvoiceController extends Controller
{
    protected $service;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->service = $invoiceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices   = $this->service->index();

        return response()->json($invoices);
    }
    
    /**
     * Display a listing of the resource by debtor id
     *
     * @param  IndexInvoicesByDebtor  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByDebtor(IndexInvoicesByDebtor $request)
    {
        $invoices   = $this->service->indexByDebtor($request->debtor_id);

        return response()->json($invoices);
    }

    /**
     * Display a listing of the resource by creditor id
     *
     * @param  IndexInvoicesByCreditor $request
     *
     * @return \Illuminate\Http\Response
     */
    public function indexByCreditor(IndexInvoicesByCreditor $request)
    {
        $invoices   = $this->service->indexByCreditor($request->creditor_id);

        return response()->json($invoices);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateInvoice $request)
    {
        $invoice   = $this->service->create($request);

        return response()->json($invoice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  markAsOpen $request
     *
     * @return \Illuminate\Http\Response
     */
    public function markAsOpen(MarkInvoiceAsOpen $request)
    {
        $invoice   = $this->service->markAsOpen($request->id);

        return response()->json($invoice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  markAsPaid $request
     *
     * @return \Illuminate\Http\Response
     */
    public function markAsPaid(MarkInvoiceAsPaid $request)
    {
        $invoice   = $this->service->markAsPaid($request->id);

        return response()->json($invoice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateInvoice $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateInvoice $request)
    {
        $invoice   = $this->service->update($request);

        return response()->json($invoice);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteInvoice  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(DeleteInvoice $request)
    {
        $this->service->delete($request->id);

        return response()->json(['Given invoice deleted successfully.']);
    }
}
