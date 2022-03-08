<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\user_additional;
use App\Models\user_position;
use Illuminate\Support\Facades\Validator;


class ProfileDetailController extends Controller
{
    public function createProfileDetail(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'gender' => 'required|string|max:255',
                'nip' => 'required|string|max:255',
                'nik' => 'required|string|max:255',
                'work_unit' => 'required|string|max:255',

                'position_kpb' => 'required|string|max:255',
                'position_department' => 'required|string|max:255',

            ]);
            if($validator->fails()){
                $error = $validator->errors()->all()[0];
                return response()->json([
                    'status'=>400,
                    'message'=> $error,
                ]);
            }else{
                $user_auth = auth()->user();
                $user_additional = user_additional::create([
                    'gender' => $request->gender,
                    'nip' => $request->nip,
                    'nik' => $request->nik,
                    'work_unit' => $request->work_unit,
                    'user_id' => $user_auth->id
                ]);

                $user_position = user_position::create([
                    'position_kpb' => $request->position_kpb,
                    'position_department' => $request->position_department,
                    'user_id' => $user_auth->id
                ]);

                return response([
                    'status' => 200,
                    'message' => 'profile detail create success',
                    'data' => [
                        'user_additional' => $user_additional,
                        'user_position' => $user_position
                    ]
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
    public function updateProfileDetail(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'gender' => 'required|string|max:255',
                'nip' => 'required|string|max:255',
                'nik' => 'required|string|max:255',
                'work_unit' => 'required|string|max:255',

                'position_kpb' => 'required|string|max:255',
                'position_department' => 'required|string|max:255',

            ]);
            if($validator->fails()){
                $error = $validator->errors()->all()[0];
                return response()->json([
                    'status'=>400,
                    'message'=> $error,
                ]);
            }else{
                $user_id = auth()->user()->id;
                $user_additional = user_additional::where('user_id', $user_id)->update(
                    [
                        'gender' => $request->gender,
                        'nip' => $request->nip,
                        'nik' => $request->nik,
                        'work_unit' => $request->work_unit,
                    ]
                );
                // $user_additional->gender = $request->gender;
                // $user_additional->nip = $request->nip;
                // $user_additional->nik = $request->nik;
                // $user_additional->work_unit = $request->work_unit;

                // $user_additional->update();


                $user_position = user_position::where('user_id', $user_id)->update(
                    [
                        'position_kpb' => $request->position_kpb,
                        'position_department' => $request->position_department,
                    ]
                );
                // $user_position->position_kpb = $request->position_kpb;
                // $user_position->position_department = $request->position_department;

                // $user_position->update();

                return response([
                    'status' => 200,
                    'message' => 'profile change success',
                    'data' => [
                        'user_additional' => $user_additional,
                        'user_position' => $user_position
                    ]
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
}
