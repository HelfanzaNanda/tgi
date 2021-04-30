<?php

namespace App\Http\Controllers\API\Ref;

use App\Http\Controllers\Controller;
use App\Models\Ref\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function get($id=null, Request $request)
    {
        $request = $request->all();

        if ($id != null) {
            $res = Country::getById($id, $request);
        } else if (isset($request['all']) && $request['all']) {
            $res = Country::getAllResult($request);
        } else {
            $res = Country::getPaginatedResult($request);
        }

        return $res;
    }
}
