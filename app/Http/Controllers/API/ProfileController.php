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
            Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'profile_picture' => 'nullable|image'
            ]);
            $data = User::find($request->user()->id);
            if($request->hasFile('profile_picture')){
                if($request->profile_picture){
                    if($data->profile_picture){
                        File::delete(public_path($data->profile_picture));
                    }
                    $random = Str::random(5);
                    $file_name = time().''.$random. '.' . $request->profile_picture->extension();
                    $request->profile_picture->move(public_path('images/profile'), $file_name);
                    $path = "images/profile/$file_name";

                    $data->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'role' => $request->role,
                        'profile_picture' => $path,
                    ]);
                }
            }else{
                $data->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'role' => $request->role,
                ]);
            }
            return response([
                'status' => 200,
                'message' => 'profile change success',
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

    public function showAllUsersList()
    {
        try{
            $user = User::with('additional_user', 'position_user', 'social_media_user')->get();
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
