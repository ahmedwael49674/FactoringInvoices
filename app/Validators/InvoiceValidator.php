<?php

namespace App\Validators;

use App\Models\Invoice;
use App\Repositories\DebtorRepository;

class InvoiceValidator
{
    protected $debtorRepository;

    public function __construct(DebtorRepository $debtorRepository)
    {
        $this->debtorRepository = $debtorRepository;
    }
 
    /**
     * validate if invoice in given status
     *
     * @param Invoice $invoice
     *
     * @return void
     */
    public function isStatusOrFail(Invoice $invoice, String $status):void
    {
        $method = "is_{$status}";
        
        if (!$invoice->$method) {
            abort(422, "Given invoice is in {$invoice->status} state only invoices with {$status} state allowed to this action.");
        }
    }
 
    /**
     * validate if invoice in given already in given status
     *
     * @param Invoice $invoice
     *
     * @return void
     */
    public function isStatusAlready(Invoice $invoice, String $status):void
    {
        $method = "is_{$status}";
        
        if ($invoice->$method) {
            abort(422, "Given invoice is already in {$invoice->status} state.");
        }
    }

    /**
     * validate if invoice statues could be open
     *
     * @param Invoice $invoice
     *
     * @return void
     */
    public function validateOpenStatus(Invoice $invoice):void
    {
        $this->isStatusAlready($invoice, Invoice::Open);
        $this->isStatusOrFail($invoice, Invoice::Initialize);
    }

    /**
     * validate if invoice statues could be paid
     *
     * @param Invoice $invoice
     *
     * @return void
     */
    public function validatePaidStatus(Invoice $invoice):void
    {
        $this->isStatusAlready($invoice, Invoice::Paid);
        $this->isStatusOrFail($invoice, Invoice::Open);
    }

    /**
     * check debtor total debt lumit
     *
     * @param float $totalAmount
     * @param int $debtorId
     *
     * @return void
     */
    public function checkTotalDebtLimit(float $totalAmount, int $debtorId):void
    {
        $totalDebt = $this->debtorRepository->findOrFail($debtorId, ['total_debt'])->total_debt;
        $total     = $totalAmount + $totalDebt;

        if ($total > Invoice::TotalDebtLimit) {
            abort(422, "Total debt exceeded the limit for given debtor.");
        }
    }

    /**
     * includes all validation checks before creating invoice
     *
     * @param array $attributes
     *
     * @return void
     */
    public function CanBeCreated(array $attributes):void
    {
        $this->checkTotalDebtLimit($attributes['total_amount'], $attributes['debtor_id']);
    }

    
    /**
     * includes all validation checks before mark invoice as open
     *
     * @param Invoice $invoice
     *
     * @return void
     */
    public function CanBeOpen(Invoice $invoice):void
    {
        $this->validateOpenStatus($invoice);
        $this->checkTotalDebtLimit($invoice->total_amount, $invoice->debtor_id);
    }
    
    /**
     * includes all validation checks before mark invoice as paid
     *
     * @param Invoice $invoice
     *
     * @return void
     */
    public function CanBePaid(Invoice $invoice):void
    {
        $this->validatePaidStatus($invoice);
    }
    
    /**
     * includes all validation checks before update invoice
     *
     * @param Invoice $invoice
     *
     * @return void
     */
    public function CanBeUpdated(Invoice $invoice):void
    {
        $this->isStatusOrFail($invoice, Invoice::Initialize);
    }
    
    /**
     * includes all validation checks before delete invoice
     *
     * @param Invoice $invoice
     *
     * @return void
     */
    public function CanBeDeleted(Invoice $invoice):void
    {
        $this->isStatusOrFail($invoice, Invoice::Initialize);
    }
}
