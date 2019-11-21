<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Zipper;

class Zipping extends Model
{
    public function zip($uuid, $path, $filename)
    {
        $files = glob(public_path('storage/' . $uuid)."/results/*");
        $path_zip = public_path('storage/' . $uuid . "/results.zip");
        Zipper::make($path_zip)->add($files)->close();
        return true;
    }
}
