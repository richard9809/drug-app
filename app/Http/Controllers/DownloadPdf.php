<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Doctrine\DBAL\Connections\PrimaryReadReplicaConnection;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;


class DownloadPdf extends Controller
{
    public function downloadReceipt(Receipt $record)
    {
        $client = new Party([
            'name'          => 'Ministry of Health',
            'phone'         => '(254) 7318-9486',
            'custom_fields' => [
                'note'        => 'IDDQD',
                'business id' => '365#GG',
            ],
        ]);

        $customer = new Party([
            'name'          => $record->hospital->name,
            'address'       => $record->hospital->address,
            'code'          => $record->hospital->id,
            'custom_fields' => [
                'order number' => $record->receipt_number,
            ],
        ]);

        $items = [];
        // foreach ($record->receiptItems as $receiptItem) {
        //     $items[] = (new InvoiceItem())
        //         ->title($receiptItem->drug->name)
        //         ->units($receiptItem->drug->size->name)
        //         ->quantity($receiptItem->quantity ?? 0);
        // }

        $notes = [
            'your multiline',
            'additional notes',
            'in regards of delivery or something else',
        ];
        $notes = implode("<br>", $notes);

        $invoice = Invoice::make('receipt')
            ->series('BIG')
            // ability to include translated invoice status
            // in case it was paid
            ->status(__('invoices::invoice.paid'))
            ->sequence(667)
            ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($client)
            ->buyer($customer)
            ->date(now()->subWeeks(3))
            ->dateFormat('m/d/Y')
            ->payUntilDays(14)
            ->currencySymbol('KSh')
            ->currencyCode('KES')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator(',')
            ->currencyDecimalPoint('.')
            ->filename($client->name . ' ' . $customer->name)
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('vendor/invoices/sample-logo.png'));

        // And return invoice itself to browser or have a different view
        return $invoice->stream();
    }

    public function trial(Receipt $record)
    {
        $items = [];
        foreach ($record->receiptItems as $receiptItem) {
            $items[] = (new InvoiceItem())
                ->title($receiptItem->drug->name)
                ->units($receiptItem->drug->size->name)
                ->quantity($receiptItem->quantity ?? 0)
                ->pricePerUnit(15);
        }

        return $items;
    }
}
