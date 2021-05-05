<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class StockOpnameController extends Controller
{
    public function index()
    {
    	$title = 'Stok Opname';

        return view('inventory.stock_opname.'.__FUNCTION__, compact('title'));
    }

    public function create()
    {
        $title = 'Tambah Stok Opname';

        return view('inventory.stock_opname.'.__FUNCTION__, compact('title'));  
    }

    public function detail($id)
    {
        $title = 'Detail Stok Opname';

        return view('inventory.stock_opname.'.__FUNCTION__, compact('title', 'id'));  
    }
}
