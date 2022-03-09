<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\advertisement;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AdvertisementController extends Controller
{
    public function index()
    {
        try{
            $advertisement = advertisement::paginate(4);
            return response([
                'status' => 200,
                'message' => 'advertisement get success',
                'total' => $advertisement->count(),
                'per_page' => $advertisement->perPage(),
                'data' => $advertisement->items()

            ]);
        }catch(\Exception $e){
            return response([
                'status' => 500,
                'message' => 'advertisement get failed',
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
                'link' => 'required|string',
                'image' => 'required|image',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors());
            }

            if($request->image && $request->image->isValid()){
                $random = Str::random(5);
                $file_name = time().''.$random. '.' . $request->image->extension();
                $request->image->move(public_path('images/advertisement'), $file_name);
                $path = "images/advertisement/$file_name";
            }

            $user_name = auth()->user()->name;
            $data = advertisement::create([
                'title' => $request->title,
                'createdby' => $user_name,
                'link' => $request->link,
                'image' => $path,
             ]);

            return response([
                'status' => 500,
                'message' => 'advertisement store success',
                'data' => $data
            ]);
        }catch(\Exception $e){
            return response([
                'status' => 500,
                'message' => 'advertisement store failed',
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
    //         // $data = advertisement::first();
    //         // $data = advertisement::find($id);
    //         if (is_null($data)) {
    //             return response()->json('Data not found', 404);
    //         }
    //         return response([
    //             'status' => 200,
    //             'message' => 'advertisement show success',
    //             'data' => $data
    //         ]);
    //     }catch(\Exception $e){
    //         return response([
    //             'status' => 500,
    //             'message' => 'advertisement show failed',
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
                'link' => 'required|string',
                'image' => 'required|image',
            ]);
            if($validator->fails()){
                $error = $validator->errors()->all()[0];
                return response()->json([
                    'status'=>400,
                    'message'=> $error,
                ]);
            }else{
                $data = advertisement::findOrFail($id);
                $data->title = $request->title;
                $data->link = $request->link;

                if($request->image && $request->image->isValid()){
                    if($data->image){
                        File::delete(public_path($data->image));
                    }
                    $random = Str::random(5);
                    $file_name = time().''.$random. '.' . $request->image->extension();
                    $request->image->move(public_path('images/advertisement'), $file_name);
                    $path = "images/advertisement/$file_name";
                    $data->image = $path;
                }

                // $data->update($request->all());
                $data->update();

                return response([
                    'status' => 200,
                    'message' => 'advertisement update success',
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
        $data = advertisement::findOrFail($id);
        // $data = profile_desc::find($id);

        try{
            $data->delete();
            if($data->image){
                File::delete(public_path($data->image));
            }

            return response([
                'status' => 200,
                'message' => 'advertisement delete success',
                'data' => $data
            ]);
        } catch(\Exception $e){
            return response([
                'status' => 500,
                'message' => 'advertisement delete failed',
                'data' => $e
            ]);
        }
    }
}
