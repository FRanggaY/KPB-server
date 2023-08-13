<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\profile_desc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ProfileDescController extends Controller
{
    public function index()
    {
        try{
            $profile_desc = profile_desc::first();
            return response([
                'status' => 200,
                'message' => 'profile desc get success',
                'data' => $profile_desc
            ]);
        }catch(\Exception $e){
            return response([
                'status' => 500,
                'message' => 'profile desc get failed',
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
                'vision' => 'required|string|max:255',
                'mission' => 'required|string|max:255',
                'description' => 'nullable',
                'image' => 'nullable|image',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors());
            }

            if($request->image && $request->image->isValid()){
                $random = Str::random(5);
                $file_name = time().''.$random. '.' . $request->image->extension();
                $request->image->move(public_path('images/profile-website'), $file_name);
                $path = "images/profile-website/$file_name";
            }

            $data = profile_desc::create([
                'vision' => $request->vision,
                'mission' => $request->mission,
                'description' => $request->description,
                'image' => $path,
             ]);

            return response([
                'status' => 500,
                'message' => 'profile desc store success',
                'data' => $data
            ]);
        }catch(\Exception $e){
            return response([
                'status' => 500,
                'message' => 'profile desc store failed',
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
    // public function show()
    // {
    //     try{
    //         $data = profile_desc::first();
    //         // $data = profile_desc::find($id);
    //         if (is_null($data)) {
    //             return response()->json('Data not found', 404);
    //         }
    //         return response([
    //             'status' => 200,
    //             'message' => 'profile desc show success',
    //             'data' => $data
    //         ]);
    //     }catch(\Exception $e){
    //         return response([
    //             'status' => 500,
    //             'message' => 'profile desc show failed',
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
    public function update(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'vision' => 'required|string|max:255',
                'mission' => 'required|string|max:255',
                'description' => 'nullable',
                'image' => 'nullable|image',
            ]);
            if($validator->fails()){
                $error = $validator->errors()->all()[0];
                return response()->json([
                    'status'=>400,
                    'message'=> $error,
                ]);
            }else{
                $data = profile_desc::first();
                $data->vision = $request->vision;
                $data->mission = $request->mission;
                $data->description = $request->description;

                if($request->image && $request->image->isValid()){
                    if($data->image){
                        File::delete(public_path($data->image));
                    }
                    $random = Str::random(5);
                    $file_name = time().''.$random. '.' . $request->image->extension();
                    $request->image->move(public_path('images/profile-website'), $file_name);
                    $path = "images/profile-website/$file_name";
                    $data->image = $path;
                }

                // $data->update($request->all());
                $data->update();

                return response([
                    'status' => 200,
                    'message' => 'profile desc update success',
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
    // public function destroy()
    // {
    //     $data = profile_desc::first();
    //     // $data = profile_desc::find($id);

    //     try{
    //         $data->delete();
    //         if($data->image){
    //             File::delete(public_path($data->image));
    //         }

    //         return response([
    //             'status' => 200,
    //             'message' => 'profile delete get success',
    //             'data' => $data
    //         ]);
    //     } catch(\Exception $e){
    //         return response([
    //             'status' => 500,
    //             'message' => 'profile delete get failed',
    //             'data' => $e
    //         ]);
    //     }
    // }
}
