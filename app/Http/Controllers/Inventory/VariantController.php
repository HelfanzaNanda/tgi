<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class VariantController extends Controller
{
    public function index()
    {
    	$title = 'Variant';

        return view('inventory.variant.'.__FUNCTION__, compact('title'));
    }
}
