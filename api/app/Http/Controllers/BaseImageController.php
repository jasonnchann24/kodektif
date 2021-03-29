<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . "_" . preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));
            $image->storeAs('public/images/', $filename, 'local');

            return;
        }
    }
}
