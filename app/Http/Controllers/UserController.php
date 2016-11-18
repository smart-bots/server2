<?php

namespace SmartBots\Http\Controllers;

use Illuminate\Cache\RateLimiter;
use Illuminate\Auth\Events\Lockout;

use Illuminate\Http\Request;
use Validator;
use JWTAuth;

use SmartBots\User;

class UserController extends Controller
{
    /**
     * Maximun login attempts for user
     * @var integer
     */
    public $maxLoginAttempts = 5; // Comment to disable throttles

    /**
     * If user reach maximun login attemps, lock them for second
     * @var integer
     */
    public $lockoutTime = 60; // second

    /**
     * Handle a api call to sign up for a new account
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function signUp(Request $request) {

        $rules = [
            'agree_with_terms'      => 'required',
            'username'              => 'required|between:6,32|unique:users',
            'name'                  => 'required|between:6,128',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|between:6,32|confirmed',
            'password_confirmation' => 'required'
        ];

        $messages = [
            'agree_with_terms.required' => trans('user/signUp.terms_disagreement')
        ];

        $validator = Validator::make($request->only(array_keys($rules)), $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $newUser = new User;

        $newUser->username = $request->username;
        $newUser->name     = $request->name;
        $newUser->email    = $request->email;
        $newUser->password = bcrypt($request->password);

        $newUser->save();

        $error = ['success' => true];

        return response()->json($error);
    }

    /**
     * Handle a api call to sign in to an account and get the jwt-token
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function signIn(Request $request) {

        switch ($request->login_with) {
            case 1:
                $rules = ['username' => 'required|between:6,32'];
                break;
            case 2:
                $rules = ['email' => 'required|email'];
                break;
        }

        $rules['password'] = 'required|between:6,32';

        $messages = [];

        $validator = Validator::make($request->only(array_keys($rules)), $rules, $messages);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $isUsingThrottles = property_exists($this, 'maxLoginAttempts') ? true : false;

        $lockedOut = $this->hasTooManyLoginAttempts($request);

        if ($isUsingThrottles && $lockedOut) {
            event(new Lockout($request));
            $seconds = $this->secondsRemainingOnLockout($request);
            $error = trans('user/signIn.throttle',['second' => $seconds]);
            return response()->json(compact('error'));
        }

        try {
            if (!$token = JWTAuth::attempt($request->only(array_keys($rules)))) {

                if ($isUsingThrottles && !$lockedOut) {
                    $this->incrementLoginAttempts($request);
                }
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        if ($isUsingThrottles) {
            $this->clearLoginAttempts($request);
        }

        return response()->json(compact('token'));
    }

    /**
     * Handle a api call to sign out an account via blacklisted the present jwt-token
     * @param  Illuminate\Http\Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function signOut(Request $request) {
        JWTAuth::parseToken()->invalidate();
        return response()->json(['success']);
    }

    //---------------------------------------------------------------------------------------------------------------------

    /**
     * Create a throtte-key from user's ip
     * @param  Request $request
     * @return string
     */
    public function getThrottleKey(Request $request)
    {
        return $request->ip();
        // return mb_strtolower($request->username).'|'.$request->ip();
    }
    /**
     * Check if user has too many login attempts
     * @param  Request $request
     * @return boolean
     */
    public function hasTooManyLoginAttempts(Request $request)
    {
        return app(RateLimiter::class)->tooManyAttempts(
            $this->getThrottleKey($request),
            $this->maxLoginAttempts, $this->lockoutTime / 60
        );
    }
    /**
     * Increate user's login attempts
     * @param  Request $request
     * @return void
     */
    public function incrementLoginAttempts(Request $request)
    {
        app(RateLimiter::class)->hit(
            $this->getThrottleKey($request)
        );
    }
    /**
     * Get retries attempts left
     * @param  Request $request
     * @return int
     */
    public function retriesLeft(Request $request)
    {
        return app(RateLimiter::class)->retriesLeft(
            $this->getThrottleKey($request),
            $this->maxLoginAttempts
        );
    }
    /**
     * Availavle to login in (second)
     * @param  Request $request
     * @return int
     */
    public function secondsRemainingOnLockout(Request $request)
    {
        return app(RateLimiter::class)->availableIn(
            $this->getThrottleKey($request)
        );
    }
    /**
     * Clear loggin attempts when user loged in succesfully
     * @param  Request $request
     * @return void
     */
    public function clearLoginAttempts(Request $request)
    {
        app(RateLimiter::class)->clear(
            $this->getThrottleKey($request)
        );
    }

}
