<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json([
                'validation_errors'=> $validator->messages(),
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => true,
            'role' => $request->role
         ]);

        // $token = $user->createToken($user->email.'_Token')->plainTextToken;

        return response()
            ->json([
                'status'=>200,
                'username'=>$user->name,
                // 'token'=>$token,
                'message' => 'Registered Successfully',
            ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password')))
        {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()
            ->json(['message' => 'Hi '.$user->name.', welcome to home','access_token' => $token, 'token_type' => 'Bearer', ]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'password' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            return response()->json([
                'validation_errors'=> $validator->messages(),
            ]);
        }else{
            $user = User::find($request->user()->id);
            $user->password = Hash::make($request->password);
            $user->update();
            return response([
                'status' => 200,
                'message' => 'change password success',
                'data' => $user,

            ]);
        }
    }

    // method for user logout and delete token
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'You have successfully logged out and the token was successfully deleted'
        ];
    }
}
