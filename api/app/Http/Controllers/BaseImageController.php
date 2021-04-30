<?php

namespace App\Http\Controllers;

use App\Models\BaseImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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

        $width = 300; // max width
        $height = 300; // max height
        $img = Image::make($image);
        $img->height() > $img->width() ? $width = null : $height = null;
        $img->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));
        $img->save(storage_path() . "/app/public/images/" . $filename, 100);

        $target = 'images/' . $filename;
        BaseImage::create([
            'filename' => $target
        ]);

        return config('app.url') . Storage::url('images/' . $filename);
    }
}
