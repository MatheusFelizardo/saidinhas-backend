<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request) {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        }

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $path = $image->store('', 'profile_pictures');
            $validatedData['profile_picture'] = "profile_pictures/" . $path;
        }

        $validatedData['password'] = Hash::make($validatedData['password']);
        
        try {
            $user = User::create($validatedData);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'User creation failed' . $th->getMessage()
            ], 500);
        }
      
    }

    public function update(Request $request) {
        $user = User::findOrFail($request->id);
        
        $name = $request->name;
        $new_password = $request->password;
       
        try {
            $validatedData = $request->validate([
                'name' => 'string|max:255',
                'password' => 'string|min:6',
            ]);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        }

        if ($request->hasFile('profile_picture')) {
            $image = $request->file('profile_picture');
            $path = $image->store('', 'profile_pictures');
            $validatedData['profile_picture'] = "profile_pictures/" . $path;
        }

        $credentials = [
            'email' => $user->email,
            'password' => $request->old_password
        ];

        if ($new_password && !Auth::attempt($credentials)) {
            return response()->json([
            'message' => 'Invalid password'
            ], 401);
        }

        try {
            $user->update($validatedData);

            return response()->json([
                'user' => $user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'User update failed' . $th->getMessage()
            ], 500);
        }
    }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
            'message' => 'Invalid login details'
            ], 401);
        }
            
        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Logout successful'
        ], 200);
    }

    public function user(Request $request) {
        try {
            $user = $request->user();

            return response()->json([
                'user' => $user
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
    }

    public function delete(string $id) {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'error' => 'User not found'
                ], 404);
            }
           
            if ($user->expenses()->exists()) {
                $user->expenses()->delete();
            }
            $user->delete();
            return response()->json([
                'message' => 'User deleted sucessfuly',
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Expense deletion failed',
                'error' => $e->getMessage(),
            ], 500);
        }

    }
}
