<?php

namespace App\Http\Controllers\Storage;

use PDF;
use App\Helper\QrCodeHelper;
use App\Http\Controllers\Controller;
use App\Models\Racks;
use Illuminate\Http\Request;
use Session;

class RackController extends Controller
{
    public function index()
    {
    	$title = 'Rack';

        return view('storage.rack.'.__FUNCTION__, compact('title'));
    }

    public function printQrcode(Request $request)
    {
    	$count = 1;

    	$data = Racks::get();

    	if (isset($request->count) && $request->count > 0) {
    		$count = $request->count;
    	}

    	$generateQr = QrCodeHelper::generate($data, $count);
    	
		$pdf = PDF::loadview('qrcode_pdf', ['data' => $generateQr]);
		
		return $pdf->stream();
    }
}
