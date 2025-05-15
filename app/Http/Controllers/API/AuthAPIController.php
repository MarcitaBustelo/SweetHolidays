<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthAPIController extends BaseController
{

    public function login(Request $request)
    {
        try {
            if (Auth::attempt(['employee_id' => $request->employee_id, 'password' => $request->password])) {
                $user = Auth::user();
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['name'] = $user->name;

                return response()->json([
                    'success' => true,
                    'data' => $success,
                    'message' => 'User logged in successfully.',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.',
                    'errors' => ['error' => 'Invalid employee_id or password.'],
                ], 401);
            }
        } catch (\Exception $e) {
            Log::error('Error login: ' . $e->getMessage());
        }
    }
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'NIF' => 'required',
            'delegation_id' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json($this->sendError('Validation Error.', $validator->errors()));
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;

        return response()->json($this->sendResponse($success, 'User register successfully.'));
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->currentAccessToken()->delete();
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully.'
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'No authenticated user found.'
        ], 401);
    }

}
