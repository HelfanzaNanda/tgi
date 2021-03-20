<?php

namespace App\Helper;

use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeHelper
{
    public static function generate($data, $count = 1)
    {
        $ret = [];
        for ($n = 0; $n < $count; $n++) {
            foreach ($data as $row) {
                $ret[] = [
                    'name' => $row['name'],
                    'code' => base64_encode(QrCode::size(125)->generate($row['code']))
                ];
            }
        }

        return $ret;
    }
}