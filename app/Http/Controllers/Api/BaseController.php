<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DateInterval;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    protected $guard;

    public function __construct($guard)
    {
        $this->guard = $guard;
    }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (!$token = auth($this->guard)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth($this->guard)->user());
    }

    public function refresh()
    {
        return $this->respondWithToken(auth($this->guard)->refresh());
    }

    public function logout()
    {
        auth($this->guard)->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        $created_at = (new DateTime('now', new DateTimeZone(config('app.timezone'))));
        $expire_at = (new DateTime('now', new DateTimeZone(config('app.timezone'))))
            ->add(new DateInterval("PT" . config('jwt.ttl') . "M"));
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'created_at' => $created_at,
            'expired_at' => $expire_at,
            'expires_in' => auth($this->guard)->factory()->getTTL() * 60,
            'user' => auth($this->guard)->user()
        ]);
    }
}
