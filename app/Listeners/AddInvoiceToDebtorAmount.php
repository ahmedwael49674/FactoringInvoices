<?php

namespace App\Listeners;

use App\Events\InvoiceMarkedAsOpenEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddInvoiceToDebtorAmount implements ShouldQueue
{
    /**
     * Add the given invoice amount to debtor total debet
     *
     * @param  \App\Events\InvoiceMarkedAsOpenEvent $event
     *
     * @return void
     */
    public function handle(InvoiceMarkedAsOpenEvent $event)
    {
        $invoice            = $event->invoice;
        $debtor             = $invoice->debtor;
        $debtor->total_debt = $debtor->total_debt + $invoice->total_amount;
        $debtor->save();
    }
}
