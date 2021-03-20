<?php

namespace App\Http\Controllers\Storage;

use PDF;
use App\Helper\QrCodeHelper;
use App\Http\Controllers\Controller;
use App\Models\Warehouses;
use Illuminate\Http\Request;
use Session;

class WarehouseController extends Controller
{
    public function index()
    {
    	$title = 'Gudang';

        return view('storage.warehouse.'.__FUNCTION__, compact('title'));
    }

    public function printQrcode(Request $request)
    {
    	$count = 1;

    	$data = Warehouses::get();

    	if (isset($request->count) && $request->count > 0) {
    		$count = $request->count;
    	}

    	$generateQr = QrCodeHelper::generate($data, $count);
    	
		$pdf = PDF::loadview('qrcode_pdf', ['data' => $generateQr]);
		
		return $pdf->stream();
    }
}
