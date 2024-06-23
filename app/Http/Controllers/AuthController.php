<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\verifyOtpCodeRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\ResponseFromApiResource;

#[
    Group('Auth'),
]
class AuthController extends Controller
{
    public function __construct(protected readonly AuthService $service)
    {

    }
    /**
     * Store a newly created resource in storage.
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    #[\Knuckles\Scribe\Attributes\Response(
        [
            'message' => 'Registration successfully',
        ],
        200,
    )]
    public function register(RegisterRequest $request) :  JsonResponse
    {
        try {
            /**
             * @var UploadedFile|null $image
             */            $image = $request->file('image');
            $user = $this->service->register(
                $request->string('name')->value(),
                $request->string('email')->value(),
                $request->string('phone_number')->value(),
                $image,
            );

            return $this->successResponse('Registration successfully');
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }
    /**
     * Store a newly created resource in storage.
     * @param LoginRequest $request
     */
    #[\Knuckles\Scribe\Attributes\Response(
        [
            'message' => 'OTP sent successfully',
        ],
        200,
    )]
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $this->service->login(
                $request->string('phone_number')->value(),
            );
            return $this->successResponse('OTP sent successfully');
         }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * @param verifyOtpCodeRequest $request
     * @return JsonResponse
     */
    #[ResponseFromApiResource(UserResource::class, User::class, 200, additional: ['access_token' => '1|127dsolhgjhudgydgi13'])]
    public function verify(verifyOtpCodeRequest $request): JsonResponse
    {
        try {
            $result = $this->service->verify(
                $request->string('phone_number')->value(),
                $request->string('otp_code')->value(),
            );
            return response()->json([
                'user' => $result["user"],
                'access_token' => $result["access_token"]
            ]);
        }catch (\Exception $e){
            return $this->errorResponse($e->getMessage());
        }
    }
}
