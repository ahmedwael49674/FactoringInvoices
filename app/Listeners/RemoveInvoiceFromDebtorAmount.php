<?php

namespace App\Listeners;

use App\Events\InvoiceMarkedAsPaidEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveInvoiceFromDebtorAmount implements ShouldQueue
{
    /**
     * sub the given invoice amount from debtor total debet
     *
     * @param  \App\Events\InvoiceMarkedAsPaidEvent $event
     *
     * @return void
     */
    public function handle(InvoiceMarkedAsPaidEvent $event)
    {
        $invoice            = $event->invoice;
        $debtor             = $invoice->debtor;
        $debtor->total_debt = $debtor->total_debt - $invoice->total_amount;
        $debtor->save();
    }
}
