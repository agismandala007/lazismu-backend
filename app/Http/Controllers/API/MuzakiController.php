<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Muzaki;
use App\Helpers\ResponseFormatter;
use App\Http\Requests\CreateMuzakiRequest;
use Exception;
use GrahamCampbell\ResultType\Success;

class MuzakiController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMuzakiRequest $request)
    {
        // $validated = $request->validate();

        // if($validated){
        //     $muzaki = Muzaki::create($validated);
        //     return ResponseFormatter::success($muzaki, 'Muzaki successfully added');
        // }

        try {
            $muzaki = Muzaki::create($request->validate());
            
            if(!$muzaki)
            {
                throw new Exception('Muzaki not created');
            }
    
            return ResponseFormatter::success($muzaki, 'Muzaki successfully added');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
