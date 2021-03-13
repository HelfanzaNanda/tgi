<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;

class CategoryController extends Controller
{
    public function index()
    {
    	$title = 'Pemasok';

        return view('inventory.category.'.__FUNCTION__, compact('title'));
    }
}
