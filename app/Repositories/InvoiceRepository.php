<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Invoice;
use Illuminate\Pagination\LengthAwarePaginator;

class InvoiceRepository
{
    /**
     * find by id or fail
     *
     * @param int $id
     *
     * @return Invoice
     */
    public function findOrFail(int $id):Invoice
    {
        return Invoice::findOrFail($id);
    }

    /**
     * index with pagination of 10 invoices
     *
     * @return LengthAwarePaginator
     */
    public function index():LengthAwarePaginator
    {
        return Invoice::with(['currency'])->paginate(10);
    }

    /**
     * index with pagination of 10 invoices with given debtor id
     *
     * @param int $debtorId
     *
     * @return LengthAwarePaginator
     */
    public function indexByDebtor(int $debtorId):LengthAwarePaginator
    {
        return Invoice::with(['currency'])
                      ->debtor($debtorId)
                      ->paginate(10);
    }

    /**
     * index with pagination of 10 invoices with given creditor id
     *
     * @param int $creditorId
     *
     * @return LengthAwarePaginator
     */
    public function indexByCreditor(int $creditorId):LengthAwarePaginator
    {
        return Invoice::with(['currency'])
                    ->creditor($creditorId)
                    ->paginate(10);
    }
        
    /**
     * Mark given invoice as Opened
     *
     * @param  Invoice $invoice
     *
     * @return Invoice
     */
    public function markAsOpen(Invoice $invoice):Invoice
    {
        $invoice->status        = Invoice::Open;
        $invoice->open_date     = Carbon::now();
        $invoice->save();

        return $invoice;
    }

    /**
     * Mark given invoice as Paided
     *
     * @param  Invoice $invoice
     *
     * @return Invoice
     */
    public function markAsPaid(Invoice $invoice):Invoice
    {
        $invoice->status        = Invoice::Paid;
        $invoice->paid_date     = Carbon::now();
        $invoice->save();

        return $invoice;
    }

    /**
     * create new invoice with given attributes
     *
     * @param array $attributes
     *
     * @return Invoice
     */
    public function create(array $attributes):Invoice
    {
        return Invoice::create($attributes);
    }
    
    /**
     * update given invoice with given attributes
     *
     * @param Invoice $invoice
     * @param array $attributes
     *
     * @return Invoice
     */
    public function update(Invoice $invoice, array $attributes):Invoice
    {
        $invoice->update($attributes);

        return $invoice;
    }
    
    /**
     * delete given invoice
     *
     * @param Invoice $invoice
     *
     * @return Invoice
     */
    public function delete(Invoice $invoice):void
    {
        $invoice->delete();
    }
}
