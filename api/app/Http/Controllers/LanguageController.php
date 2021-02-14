<?php

namespace App\Http\Controllers;

use App\Http\Requests\Language\LanguageStoreRequest;
use App\Http\Requests\Language\LanguageUpdateRequest;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('only.admin')->only(['store', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return LanguageResource::collection(Language::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Language\LanguageStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(LanguageStoreRequest $request)
    {
        $validated = $request->validated();

        $lang = Language::create($validated);

        return Response::json($lang, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function show(Language $language)
    {
        return new LanguageResource($language);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Language\LanguageUpdateRequest $request
     * @param  \App\Models\Language  $language
     * @return \Illuminate\Http\Response
     */
    public function update(LanguageUpdateRequest $request, Language $language)
    {
        $validated = $request->validated();
        $language->update($validated);
        $language->save();

        return new LanguageResource($language);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
