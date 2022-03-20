<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\gallery;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    public function index()
    {
        try{
            $gallery = gallery::get();
            return response([
                'status' => 200,
                'message' => 'gallery get success',
                // 'total' => $gallery->count(),
                // 'per_page' => $gallery->perPage(),
                'data' => $gallery

            ]);
        }catch(\Exception $e){
            return response([
                'status' => 500,
                'message' => 'gallery get failed',
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
                'image' => 'required|image',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors());
            }

            if($request->image && $request->image->isValid()){
                $random = Str::random(5);
                $file_name = time().''.$random. '.' . $request->image->extension();
                $request->image->move(public_path('images/gallery'), $file_name);
                $path = "images/gallery/$file_name";
            }
            $user_name = auth()->user()->name;

            $data = gallery::create([
                'title' => $request->title,
                'createdby' => $user_name,
                'image' => $path,
             ]);

            return response([
                'status' => 500,
                'message' => 'gallery store success',
                'data' => $data
            ]);
        }catch(\Exception $e){
            return response([
                'status' => 500,
                'message' => 'gallery store failed',
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
    public function show($id)
    {
        try{
            // $data = gallery::first();
            $data = gallery::find($id);
            if (is_null($data)) {
                return response()->json('Data not found', 404);
            }
            return response([
                'status' => 200,
                'message' => 'gallery show success',
                'data' => $data
            ]);
        }catch(\Exception $e){
            return response([
                'status' => 500,
                'message' => 'gallery show failed',
                'data' => $e
            ]);
        }
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
        try{
            Validator::make($request->all(),[
                'title' => 'required|string|max:255',
                'image' => 'nullable|image',
            ]);
            $data = gallery::findOrFail($id);

            if($request->hasFile('image')){
                if($request->image){
                    if($data->image){
                        File::delete(public_path($data->image));
                    }
                    $random = Str::random(5);
                    $file_name = time().''.$random. '.' . $request->image->extension();
                    $request->image->move(public_path('images/gallery'), $file_name);
                    $path = "images/gallery/$file_name";

                    $data->update([
                        'title' => $request->title,
                        'image' => $path,
                    ]);
                }
            }else{
                $data->update([
                    'title' => $request->title,
                ]);
            }

            return response([
                'status' => 200,
                'message' => 'gallery update success',
                'data' => $data
            ]);
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
        $data = gallery::findOrFail($id);
        // $data = profile_desc::find($id);

        try{
            $data->delete();
            if($data->image){
                File::delete(public_path($data->image));
            }

            return response([
                'status' => 200,
                'message' => 'gallery delete success',
                'data' => $data
            ]);
        } catch(\Exception $e){
            return response([
                'status' => 500,
                'message' => 'gallery delete failed',
                'data' => $e
            ]);
        }
    }
}
