<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function store(StoreUserRequest $request): Response
    public function store(StoreUserRequest $request): Response
    {
        // code generation part (move to CodeGenerateService)
        $station_code = 'USR';
        $random_num = sprintf('%03d', rand(1, 999)); // random 3 digit number with padded leading zeroes (always 3 digits/characters)
        $incremented_user_count = sprintf('%05d', User::count() + 1); // user count with padded leading zeroes (always 5 digits/characters),
        $generated_code = "{$station_code}-{$incremented_user_count}-{$random_num}";
        // end of code generation part

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
            'code' => $generated_code,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->noContent();
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $user->update($request->validated());

            return (new UserResource($user))
                ->additional(['message' => 'User information updated successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
