<?php

namespace App\Events;

use App\Models\Invoice;

class InvoiceMarkedAsOpenEvent extends Event
{
    public $invoice;

    /**
     * invoice has been marked as open
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
