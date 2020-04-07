<?php


namespace App\Traits;


use Illuminate\Http\Request;

trait UploadTrait
{
    public function storeImage(Request $request, $fieldname = 'image', $directory)
    {
        return $request->file($fieldname)->store($directory,'public');
    }
}
