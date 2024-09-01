<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //
    public function register(UserRegisterRequest $request): JsonResponse  {
        $data =  $request->validated();

        if(User::where("username", $data["username"])->count() == 1) {
            //exist on database
            throw new HttpResponseException(response([
                "errors" => [
                    "username" => [
                        "username is already registered"
                    ]
                ]
            ], 400));
        }

        $user = new User($data);
        $user->password = Hash::make($data["password"]);
        $user->save();

        return (new UserResource($user))->response()->setStatusCode(201);
    } 

    public function login(UserRegisterRequest $request): UserResource {
        $data = $request->validated();
        $user = User::where("username", $data["username"])->first();
        if(!$user || !Hash::check($data["password"] , $user->password)) {
            throw new HttpResponseException(response([
                "errors" => [
                    "message" => [
                        "Login Failed"
                    ]
                ]
            ], 401));
        }

        $user->token = Str::uuid()->toString();
        $user->save();

        return new UserResource($user);
    }
}
