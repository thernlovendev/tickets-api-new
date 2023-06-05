<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Auth;
use Log;
use App\Http\Requests\UserRequest;
use Redirect;
use App\Services\Users\ServiceCrud;
use App\Services\Users\ServiceGeneral;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\UserByAdminRequest;
use Carbon\Carbon;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $users = User::query();
        $params = $request->query();
        $elements = ServiceGeneral::filterCustom($params, $users);
        $elements = $this->httpIndex($elements, []);
        $response = ServiceGeneral::mapCollection($elements);
        return Response($response, 200);
    }

    public function authenticate(Request $request)
    {
      $credentials = $request->only('email', 'password');
      try {
          if (! $token = JWTAuth::attempt($credentials)) {
              return response()->json(['error' => 'invalid_credentials'], 400);
          }
      } catch (JWTException $e) {
          return response()->json(['error' => 'could_not_create_token'], 500);
      }

      $user = User::where('email',$credentials['email'])->first();
      
      $user->last_login_at = Carbon::now();
      
      $user->save();

      return response()->json(compact('token'));
    }

    public function getAuthenticatedUser()
    {
        try {
          if (!$user = JWTAuth::parseToken()->authenticate()) {
                  return response()->json(['user_not_found'], 404);
          }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }


    public function register(UserRequest $request)
    {
        $response = $request->validated();
        $user = User::create([
            'name' => $response['firstname'].' '.$response['lastname'],
            'firstname' => $response['firstname'],
            'lastname' => $response['lastname'],
            'email' => $response['email'],
            'password' => Hash::make($response['password']),
            'phone' => $response['phone'],
            'active' => true,
        ]);

        // event(new Registered($user));
        
        $user->assignRole('customer');
        $token = JWTAuth::fromUser($user);
        $user->sendEmailVerificationNotification();
        
        return response()->json(compact('user','token'),201);
    }

    public function getProfile(Request $request)
    {
        return response()->json(auth()->user());
    }

    public function updateProfile(Request $request)
    {   
        $request->validate([
            'firstname' => 'min:2| max:55',
            'lastname' => 'min:2| max:55',
            'email' => 'email',
            'phone' => 'max:15',
        ]);

        $user = auth()->user();

        $user->update([
            'firstname' => $request->firstname !== null ? $request->firstname : $user->firstname,
            'lastname' => $request->lastname !== null ? $request->lastname.' '.$request->lastname : $user->lastname,
            'email' => $request->email !== null ? $request->email: $user->email,
            'phone' => $request->phone !== null ? $request->phone : $user->phone,
            'updated_at' => now(),
        ]);

        $user->save();

        return response()->json([
            'message' => 'Profile Update',
        ]);
    }

    public function logout()
    {
        auth()->logout();
        
        return response()->json([
            'message' => 'User logged out',
        ]);
    }

    public function deleteMyUser(Request $request){
        $myUser = auth()->user();
        auth()->logout();
        User::find($myUser->id)->delete();
        
        return response()->json(['message'=> 'Your account has been deleted'], 204);
    }

    public function deleteUser(User $user){
        
        $user->delete();
        
        return response()->json([
            'message'=> 'Delete User Successfully'
        ]);
    }

    public function create(UserByAdminRequest $request)
    {
        $response = $request->validated();
        $user = User::create([
            'name' => $response['fullname'],
            'firstname' => $response['firstname'],
            'lastname' => $response['lastname'],
            'email' => $response['email'],
            'password' => Hash::make($response['password']),
            'phone' => $response['phone'],
            'active' => true
        ]);
        
        $user->assignRole($response['role']);
        
        return response()->json(compact('user'),201);
    }

    public function edit(UserByAdminRequest $request, User $user)
    {
        $response = $request->validated();

        $user->name = $response['fullname'];
        $user->firstname = $response['firstname'];
        $user->lastname = $response['lastname'];
        $user->email = $response['email'];
        $user->phone = $response['phone'];

        if($request->filled('password')){
            $user->password = Hash::make($response['password']);
        }

        
        $role = $user->roles->first();
        if($response['role'] !== $role->id){
            $user->removeRole($role->id);
            $user->assignRole($response['role']);
        }      
        
        $user->save();
        return response()->json(compact('user'),201);
    }

    public function changeStatus(Request $request, User $user){
        $request->validate([
            'active' => 'boolean',
        ]);

        $user->active = $request->active;

        $user->save();

        return Response(['message'=> 'The account status has been updated'], 200);
    }

    public function show(User $user)
    {
        $response = $user->load(['roles']);
        return Response($response, 200);
    }

    public function refresh(Request $request)
    {
        $token = auth()->refresh();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 720
        ]);
    }

}
