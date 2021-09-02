<?php

namespace App\Services;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Validators\InvoiceValidator;
use App\Repositories\InvoiceRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class InvoiceService
{
    protected $repository;
    protected $validator;

    public function __construct(InvoiceRepository $invoiceRepository, InvoiceValidator $invoiceValidator)
    {
        $this->repository       = $invoiceRepository;
        $this->validator        = $invoiceValidator;
    }

    /**
     * index with pagination of 10 invoices
     *
     * @return LengthAwarePaginator
     */
    public function index():LengthAwarePaginator
    {
        $invoices   = $this->repository->index();

        return $invoices;
    }
        
    /**
     * Display a listing of the resource by debtor id
     *
     * @param  int $id
     *
     * @return LengthAwarePaginator
     */
    public function indexByDebtor(int $debtorId):LengthAwarePaginator
    {
        $invoices   = $this->repository->indexByDebtor($debtorId);

        return $invoices;
    }

    /**
     * Display a listing of the resource by creditor id
     *
     * @param  int $id
     *
     * @return LengthAwarePaginator
     */
    public function indexByCreditor(int $creditorId):LengthAwarePaginator
    {
        $invoices   = $this->repository->indexByCreditor($creditorId);

        return $invoices;
    }
    
    /**
     * Mark given invoice as Opened
     *
     * @param  int $invoiceId
     *
     * @return Invoice
     */
    public function markAsOpen(int $invoiceId):Invoice
    {
        $invoice   = $this->repository->findOrFail($invoiceId);
        $this->validator->CanBeOpen($invoice);
        $invoice   = $this->repository->markAsOpen($invoice);
        
        event(new \App\Events\InvoiceMarkedAsOpenEvent($invoice));

        return $invoice;
    }

    /**
     * Mark given invoice as Paid
     *
     * @param  int $invoiceId
     *
     * @return Invoice
     */
    public function markAsPaid(int $invoiceId):Invoice
    {
        $invoice   = $this->repository->findOrFail($invoiceId);
        $this->validator->CanBePaid($invoice);
        $invoice   = $this->repository->markAsPaid($invoice);

        event(new \App\Events\InvoiceMarkedAsPaidEvent($invoice));


        return $invoice;
    }

    /**
     * create new invoice with given attributes
     *
     * @param Request $request
     *
     * @return Invoice
     */
    public function create(Request $request):Invoice
    {
        $this->validator->CanBeCreated($request->all());
        $invoices   = $this->repository->create($request->except(Invoice::ProtectedAttributes));

        return $invoices;
    }

    /**
     * update given invoice with given attributes
     *
     * @param Request $request
     *
     * @return Invoice
     */
    public function update(Request $request):Invoice
    {
        $invoice   = $this->repository->findOrFail($request->id);
        $this->validator->CanBeUpdated($invoice);
        $invoice   = $this->repository->update($invoice, $request->except(Invoice::NotUpdatableAttributes));

        return $invoice;
    }

    /**
     * delete given invoice
     *
     * @param int $invoiceId
     *
     * @return void
     */
    public function delete(int $invoiceId):void
    {
        $invoice   = $this->repository->findOrFail($invoiceId);
        $this->validator->CanBeDeleted($invoice);
        $invoice   = $this->repository->delete($invoice);
    }
}
