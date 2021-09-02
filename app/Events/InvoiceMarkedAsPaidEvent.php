<?php

namespace App\Events;

use App\Models\Invoice;

class InvoiceMarkedAsPaidEvent extends Event
{
    public $invoice;

    /**
     * invoice has been marked as paid
     *
     * @param Invoice $invoice
     *
     * @return void
     */
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }
}
