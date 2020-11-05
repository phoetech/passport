<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
	public function register (Request $request) {
		$validator = Validator::make($request->all(), [
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:6|confirmed',
		]);
		if ($validator->fails())
		{
			return response(['errors'=>$validator->errors()->all()], 422);
		}
		$request['password']=Hash::make($request['password']);
		$request['remember_token'] = Str::random(10);
		$request['display_id'] = User::generateDisplayId();
		$request->request->remove('password_confirmation');
		$request['identifier']='18053001239';
		$request['ccc']='1';
		$request['phone']='18053001239';
		$request['salt']='1234abcd';
		$user = User::create($request->toArray());
		$token = $user->createToken('Phoetech Password Grant Client')->accessToken;
		$response = ['token' => $token];
		return response($response, 200);
	}

	public function login (Request $request) {
		$validator = Validator::make($request->all(), [
			'identifier' => 'required',
			'password' => 'required|string|min:6',
		]);
		if ($validator->fails())
		{
			return response(['errors'=>$validator->errors()->all()], 422);
		}
		$user = User::where('identifier', $request->identifier)->first();
		if ($user) {
			if (Hash::check($request->password, $user->password)) {
				$token = $user->createToken('Phoetech Password Grant Client')->accessToken;
				$response = ['token' => $token];
				return response($response, 200);
			} else {
				$response = ["message" => "Password mismatch"];
				return response($response, 422);
			}
		} else {
			$response = ["message" =>'User does not exist'];
			return response($response, 422);
		}
	}

	public function logout (Request $request) {
		$token = $request->user()->token();
		$token->revoke();
		$response = ['message' => 'You have been successfully logged out!'];
		return response($response, 200);
	}
}
