<?php

namespace App\Http\Controllers;

use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;
use Firebase\Auth\Token\Exception\InvalidToken;

//use Kreait\Firebase\Auth;


class AuthController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api')->except(['register', 'login']);
    }

    public $successStatus = 200;

    public function register(Request $request)
    {

        $auth = app('firebase.auth');


        $verifiedIdToken = $this->validateFirebaseToken($request, $auth);


        // Retrieve the UID (User ID) from the verified Firebase credential's token
        $uid = $verifiedIdToken->getClaim('sub');

        $firebaseUser = $auth->getUser($uid);


        $user = User::create([
                'email' => $firebaseUser->email,
                'firebase_id' => $firebaseUser->uid,
                'display_name' => $firebaseUser->displayName == null ? $firebaseUser->email : $firebaseUser->displayName,
                'pic_url' => $firebaseUser->photoUrl == null ? 'https://video.nts.nl/anonymous.jpeg' : $firebaseUser->photoUrl,
                'password' => bcrypt($firebaseUser->email)
            ]
        );


        // Once we got a valid user model
        // Create a Personnal Access Token
        $tokenResult = $user->createToken('Personal Access Token');

        // Store the created token
        $token = $tokenResult->token;

        // Add a expiration date to the token
        $token->expires_at = Carbon::now()->addWeeks(1);

        // Save the token to the user
        $token->save();

        // Return a JSON object containing the token datas
        // You may format this object to suit your needs
        return response()->json([
            'id' => $user->id,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);

    }


    private function validateFirebaseToken(Request $request, $auth)
    {
        // Launch Firebase Auth
//        $auth = app('firebase.auth');
        // Retrieve the Firebase credential's token


        $idTokenString = $request->input('Firebasetoken');


        try { // Try to verify the Firebase credential token with Google

            $verifiedIdToken = $auth->verifyIdToken($idTokenString);

        } catch (\InvalidArgumentException $e) { // If the token has the wrong format

            return response()->json([
                'message' => 'Unauthorized - Can\'t parse the token: ' . $e->getMessage()
            ], 401);

        } catch (InvalidToken $e) { // If the token is invalid (expired ...)

            return response()->json([
                'message' => 'Unauthorized - Token is invalide: ' . $e->getMessage()
            ], 401);

        }

        return $verifiedIdToken;
    }

    public function login(Request $request)
    {

        $auth = app('firebase.auth');

        $verifiedIdToken = $this->validateFirebaseToken($request, $auth);

        // Retrieve the UID (User ID) from the verified Firebase credential's token
        $uid = $verifiedIdToken->getClaim('sub');

        // Retrieve the user model linked with the Firebase UID
        $user = User::where('firebase_id', $uid)->first();


        if (!isset($user) && empty($user))
            return $this->register($request);
//            return response()->json(['error' => 'Unauthorised access'], 401);

        // Here you could check if the user model exist and if not create it
        // For simplicity we will ignore this step

        // Once we got a valid user model
        // Create a Personnal Access Token
        $tokenResult = $user->createToken('Personal Access Token');

        // Store the created token
        $token = $tokenResult->token;

        // Add a expiration date to the token
        $token->expires_at = Carbon::now()->addWeeks(1);

        // Save the token to the user
        $token->save();

        // Return a JSON object containing the token datas
        return response()->json([
            'id' => $user->id,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);

    }


    public function getUser()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }
}
