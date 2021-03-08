<?php

namespace App\Http\Controllers\Ref;

use App\Http\Controllers\Controller;
use App\Models\Ref\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityController extends Controller
{
    public function cityByProvince($id)
    {
        return City::cityByProvince($id);
    }
}