<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Storage;
use App\Zipping;

class UploadController extends Controller
{
    public function index(Request $request)
    {
        //init file to a variable
        $file = $request->file('file');
        $loop = $request->post('loop');
        $loop = $loop-1;
        //get mimetype
        $mimetype = $file->getMimeType();
        //dd($mimetype);
        //get extension
        $extension = $file->getClientOriginalExtension();
        
        $uuid = Uuid::uuid4()->toString();

        if (!Storage::exists($uuid)) {
            Storage::makeDirectory("public/" . $uuid, 0777, true, true);
            Storage::makeDirectory("public/" . $uuid. "/results", 0777, true, true);
        }
        Storage::makeDirectory("public/" . $uuid. "/results", 0777, true, true);
        $pathreal = Storage::putFileAs("public/" . $uuid,$request->file('file'),$file->getClientOriginalName());
        $storageName = basename($pathreal);
        
        putenv('PATH=' . getenv('PATH') . ':/usr/local/opt/sphinx-doc/bin:/usr/local/bin:/usr/bin:/bin:/usr/sbin:/sbin:/Library/Apple/usr/bin:/Library/Apple/bin:/Users/directoryx/.composer/vendor/bin');
        $filename = $file->getClientOriginalName();
        for ($i=0;$i<=$loop;$i++){
            shell_exec("cd ../storage/app/public/$uuid && drawio-batch -d $i '$filename' results/result$i.pdf && ls -al 2>&1");
            shell_exec("cd ../storage/app/public/$uuid && drawio-batch -d $i '$filename' results/result$i.png && ls -al 2>&1");
        }
        $zip = new Zipping();
        $zip->zip($uuid, $pathreal, $storageName);
        //dd($uuid);
        $linkimgnzip_ins = public_path('storage/' . $uuid . "/results.zip");
        return response()->download($linkimgnzip_ins);
        
    }
}
