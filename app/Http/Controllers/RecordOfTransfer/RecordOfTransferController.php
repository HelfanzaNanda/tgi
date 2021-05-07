<?php

namespace App\Http\Controllers\RecordOfTransfer;

use App\Http\Controllers\Controller;
use App\Models\RecordOfTransfers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

class RecordOfTransferController extends Controller
{
    public function pdf($id, $path)
    {
        $rot = RecordOfTransfers::where('id', $id)
        ->first();
        if ($path == 'transaction.inventory_in') {
            $rot_relation = 'incoming_inventory';
            $rot->with('incoming_inventory.transaction.transactionDetails.inventory')
            ->first();
            $to = $rot->supplier->name;
        }else{
            $rot_relation = 'outcoming_inventory';
            $rot->with('outcoming_inventory.transaction.transactionDetails.inventory')
            ->first();
            $to = $rot->customer->name;
        }

        Carbon::setLocale('id');
        $result = [
            'date' => $rot->date->translatedformat('d F Y'),
            'to' => $to,
            'items' => []
        ];
        foreach ($rot->{$rot_relation}->transaction->transactionDetails as $transaction_detail) {
            $item = [
                'product_name' => $transaction_detail->inventory->name,
                'batch_number' => $transaction_detail->batch_number,
                'product_desc' => $transaction_detail->inventory->product_description,
                'product_code' => $transaction_detail->inventory->code,
                'color' => $transaction_detail->inventory->inventoryVariant->sub_variant_name,
                'qty' => $transaction_detail->qty,
            ];

            array_push($result['items'], $item);
        }

        $pdf = PDF::loadView($path. '.pdf', [
            'result' => $result
        ]);
        return $pdf->download('serah terima barang.pdf');
        // return view($path. '.pdf', [
        //     'result' => $result
        // ]);
    }
}
