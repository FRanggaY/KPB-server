<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProfileController extends Controller

{
    public function getProfile()
    {
        try{
            $user = auth()->user();
            $data = User::with('additional_user', 'position_user', 'social_media_user')->where('id', $user->id)->first();
            return response()->json(['status'=>200,'data'=>$data]);

        }catch(\Exception $e){
            return response()
            ->json([
                'status'=>500,
                'message'=> $e->getMessage(),
            ]);
        }
    }
    public function updateProfile(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'profile_picture' => 'nullable|image'
            ]);
            if($validator->fails()){
                $error = $validator->errors()->all()[0];
                return response()->json([
                    'status'=>400,
                    'message'=> $error,
                ]);
            }else{
                $user = User::find($request->user()->id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->role = $request->role;

                if($request->profile_picture && $request->profile_picture->isValid()){
                    if($user->profile_picture){
                        // Storage::delete($user->profile_picture);
                        File::delete(public_path($user->profile_picture));
                    }
                    $random = Str::random(5);
                    $file_name = time().''.$random. '.' . $request->profile_picture->extension();
                    $request->profile_picture->move(public_path('images/profile'), $file_name);
                    $path = "images/profile/$file_name";
                    $user->profile_picture = $path;
                }
                $user->update();
                return response([
                    'status' => 200,
                    'message' => 'profile change success',
                    'data' => $user,

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
    public function showAllUsers($id)
    {
        try{
            $user = User::with('additional_user', 'position_user', 'social_media_user')->paginate($id);
            return response()
            ->json([
                'status'=>200,
                'data'=> $user,
            ]);
        }catch(\Exception $e){
            return response()
            ->json([
                'status'=>500,
                'message'=> $e->getMessage(),
            ]);
        }
    }
    public function showAllUsersPaginate($id){
        try{
            $user = User::with('position_user', 'social_media_user')->paginate($id);
            return response()
            ->json([
                'status'=>200,
                'total' => $user->count(),
                'per_page' => $user->perPage(),
                'data' => $user->items()
            ]);
        }catch(\Exception $e){
            return response()
            ->json([
                'status'=>500,
                'message'=> $e->getMessage(),
            ]);
        }
    }
    public function delete($id)
    {
        try{
            $data = User::where('id', $id)->delete();
            return response()->json(['status'=>200, 'message'=>'success delete','data'=>$data]);

        }catch(\Exception $e){
            return response()
            ->json([
                'status'=>500,
                'message'=> $e->getMessage(),
            ]);
        }
    }
}
