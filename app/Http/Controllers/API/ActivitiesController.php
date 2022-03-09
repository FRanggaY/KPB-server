<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\activities;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ActivitiesController extends Controller
{
    public function index()
    {
        try{
            $activities = activities::paginate();
            return response([
                'status' => 200,
                'message' => 'activities get success',
                'total' => $activities->count(),
                'per_page' => $activities->perPage(),
                'data' => $activities->items()

            ]);
        }catch(\Exception $e){
            return response([
                'status' => 500,
                'message' => 'activities get failed',
                'data' => $e
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'time' => 'nullable|string|max:255',
                'link' => 'nullable|string',
                'image' => 'nullable|image',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors());
            }

            if($request->image && $request->image->isValid()){
                $random = Str::random(5);
                $file_name = time().''.$random. '.' . $request->image->extension();
                $request->image->move(public_path('images/activities'), $file_name);
                $path = "images/activities/$file_name";
            }

            $data = activities::create([
                'title' => $request->title,
                'description' => $request->description,
                'time' => $request->time,
                'link' => $request->link,
                'image' => $path,
             ]);

            return response([
                'status' => 500,
                'message' => 'activities store success',
                'data' => $data
            ]);
        }catch(\Exception $e){
            return response([
                'status' => 500,
                'message' => 'activities store failed',
                'data' => $e
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show($id)
    // {
    //     try{
    //         // $data = activities::first();
    //         // $data = activities::find($id);
    //         if (is_null($data)) {
    //             return response()->json('Data not found', 404);
    //         }
    //         return response([
    //             'status' => 200,
    //             'message' => 'activities show success',
    //             'data' => $data
    //         ]);
    //     }catch(\Exception $e){
    //         return response([
    //             'status' => 500,
    //             'message' => 'activities show failed',
    //             'data' => $e
    //         ]);
    //     }
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $validator = Validator::make($request->all(),[
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'time' => 'nullable|string|max:255',
                'link' => 'nullable|string',
                'image' => 'nullable|image',
            ]);
            if($validator->fails()){
                $error = $validator->errors()->all()[0];
                return response()->json([
                    'status'=>400,
                    'message'=> $error,
                ]);
            }else{
                $data = activities::findOrFail($id);
                $data->title = $request->title;
                $data->description = $request->description;
                $data->time = $request->time;
                $data->link = $request->link;

                if($request->image && $request->image->isValid()){
                    if($data->image){
                        File::delete(public_path($data->image));
                    }
                    $random = Str::random(5);
                    $file_name = time().''.$random. '.' . $request->image->extension();
                    $request->image->move(public_path('images/activities'), $file_name);
                    $path = "images/activities/$file_name";
                    $data->image = $path;
                }

                // $data->update($request->all());
                $data->update();

                return response([
                    'status' => 200,
                    'message' => 'activities update success',
                    'data' => $data
                ]);
            }
        }catch(\Exception $e){
            return response()
            ->json([
                'status'=>500,
                'message'=> $e->getMessage(),
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
        $data = activities::findOrFail($id);
        // $data = profile_desc::find($id);

        try{
            $data->delete();
            if($data->image){
                File::delete(public_path($data->image));
            }

            return response([
                'status' => 200,
                'message' => 'activities delete success',
                'data' => $data
            ]);
        } catch(\Exception $e){
            return response([
                'status' => 500,
                'message' => 'activities delete failed',
                'data' => $e
            ]);
        }
    }
}
