<?php

namespace App\Http\Controllers;

use App\Models\BaseImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BaseImageController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|max:1000'
        ]);

        $image = $request->file('image');
        $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));
        $image->storeAs('public/images/', $filename, 'local');

        BaseImage::create([
            'filename' => $filename
        ]);

        return Storage::url('public/images/' . $filename);
    }
}
