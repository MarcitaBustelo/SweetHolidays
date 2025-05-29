<?php

namespace App\Http\Controllers\API;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;


class AuthAPIController extends BaseController
{

    public function login(Request $request)
    {
        $departments = Department::select('department_id', 'name')->get();

        try {
            if (Auth::attempt(['employee_id' => $request->employee_id, 'password' => $request->password])) {
                $user = Auth::user();
                $departmentName = $departments->firstWhere('department_id', $user->department_id)?->name ?? 'Unknown';

                if ((int) $user->active === 0) {
                    Auth::logout();
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized.',
                        'errors' => ['error' => 'Your account is deactivated.'],
                    ], 403);
                }

                if ($user->role !== 'employee') {
                    Auth::logout();
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized.',
                        'errors' => ['error' => 'Only employees can log in.'],
                    ], 403);
                }

                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['name'] = $user->name;
                $success['employee_id'] = $user->employee_id;
                $success['department'] = $departmentName;



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
            return response()->json([
                'success' => false,
                'message' => 'Server error.',
            ], 500);
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

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => [
                'required',
                'confirmed',
                \Illuminate\Validation\Rules\Password::defaults(), // e.g. min:8, mixed, etc.
            ],
        ], [
            'current_password.required' => 'The current password is required.',
            'new_password.required' => 'The new password is required.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
            'new_password.min' => 'The new password must be at least 8 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => [
                    'current_password' => ['The current password is incorrect.']
                ]
            ], 422);
        }

        if (Hash::check($request->new_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => [
                    'new_password' => ['The new password must be different from the current password.']
                ]
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'status' => 'success',
            'message' => 'Password updated correctly!',
        ]);

    }

}
