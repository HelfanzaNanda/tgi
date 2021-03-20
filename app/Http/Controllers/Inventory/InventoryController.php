<?php

namespace App\Http\Controllers\Inventory;

use App\Helper\QrCodeHelper;
use App\Http\Controllers\Controller;
use App\Models\Inventories;
use App\Models\Media;
use Illuminate\Http\Request;
use PDF;
use Session;

class InventoryController extends Controller
{
    public function index()
    {
    	$title = 'Barang';

        return view('inventory.inventory.'.__FUNCTION__, compact('title'));
    }

    public function printQrcode(Request $request)
    {
    	$count = 1;

    	$data = Inventories::get();

    	if (isset($request->count) && $request->count > 0) {
    		$count = $request->count;
    	}

    	$generateQr = QrCodeHelper::generate($data, $count);
    	
		$pdf = PDF::loadview('qrcode_pdf', ['data' => $generateQr]);
		
		return $pdf->stream();
    }

    public function gallery($id, Request $request)
    {
        $title = 'Gambar';

        $media = Media::where('model', 'App\Models\Inventories')->where('model_id', $id)->get();

        return view('inventory.inventory.'.__FUNCTION__, compact('id', 'title', 'media'));
    }

    public function postGallery($id, Request $request)
    {
        dd($id);
    }

    public function deleteGallery($id, $itemId, Request $request)
    {
        dd($id);
    }

}
