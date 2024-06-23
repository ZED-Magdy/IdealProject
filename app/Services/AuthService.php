<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class AuthService
{
    /**
     * Create a new user.
     *
     * @param string $name
     * @param string $email
     * @param string $phone_number
     * @param UploadedFile|null $image
     * @return User
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function register(string $name, string $email, string $phone_number, UploadedFile|null $image): User
    {
        /**
         * @var User $user
         */
        $user =  User::create([
            'name' => $name,
            'email' => $email,
            'phone_number' => $phone_number,
        ]);
        if($image){
            $user->addMedia($image)->toMediaCollection('user_image');
        }
        $this->generateOtpCode($user);

        return $user;
    }

    public function login(string $phone_number): void
    {
        /**
         * @var User|null $user
         */
        $user = User::where('phone_number' , $phone_number)->first();

        if (!$user) {
            throw new \Exception('User not found');
        }
        $this->generateOtpCode($user);
    }

    public function generateOtpCode(User $user): void
    {
        $random_code = app()->isProduction() ? (string)rand(100000, 999999) : '123456';
        $user->update([
            'otp_code' => bcrypt($random_code),
        ]);
    }

    /**
     * @param string $phone_number
     * @param string $otp_code
     * @return array{access_token : string, user : UserResource}
     * @throws \Exception
     */
    public function verify(string $phone_number, string $otp_code): array
    {
        $user = User::where('phone_number', $phone_number)->first();
        if(!$user)
        {
            throw new \Exception('user not found');
        }
        if ($user->otp_code && !\Hash::check($otp_code, $user->otp_code)) {
            throw new \Exception('Invalid code');
        }
        /**
         * @var string $token
         */
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'access_token' => $token,
            'user' => new UserResource($user),
        ];
    }

    /**
     * Show a user.
     *
     * @param User $user
     * @return User
     */
    public function show(User $user): User
    {
        return $user;
    }
}
