<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school = School::get();
        $response = [
            'message' => 'success fetched',
            'data' => $school
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'address' => ['required'],
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $school = School::create($request->all());
            $response = [
                'message' => 'success created',
                'data' => $school
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch(QueryException $e){
            return response()->json([
                'message' => 'failed created - ' .$e->errorInfo
            ]);
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
        $school = School::findOrFail($id);
        $response = [
            'message' => 'success detail fetched',
            'data' => $school
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        $school = School::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'address' => ['required'],
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try{
            $school->update($request->all());
            $response = [
                'message' => 'success update',
                'data' => $school
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch(QueryException $e){
            return response()->json([
                'message' => 'failed update - ' .$e->errorInfo
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $school = School::findOrFail($id);

        try{
            $school->delete();
            $response = [
                'message' => 'success delete',
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch(QueryException $e){
            return response()->json([
                'message' => 'failed delete - ' .$e->errorInfo
            ]);
        }
    }
}
