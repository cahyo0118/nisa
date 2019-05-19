{!! $php_prefix !!}

namespace App\Http\Controllers\api;

use App\Helpers\QueryHelpers;
use App\Http\Controllers\Controller;
use App\User;
use App\Permission;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use Image;
use Validator;

use Mail;

class UserProfileController extends Controller
{
    public function getCurrentUser()
    {
        $user = Auth::user();
        $permissions = [];

        foreach ($user->roles as $role) {
            foreach ($role->permissions as $permission) {
                if (!array_search($permission->name, $permissions))
                    array_push($permissions, $permission->name);
            }
        }

        $user = User::where('id', Auth::id())->first();
        $user->setAttribute('permissions', $permissions);

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Awesome, successfully get current user data !',
        ], 200);
    }

    public function checkAuth()
    {
        if (Auth::check()) {
            return response()->json([
                'success' => true,
                'data' => NULL,
                'message' => 'Awesome, successfully get current user data !',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'data' => NULL,
                'message' => 'unauthorized',
            ], 401);
        }
    }

    public function updateCurrentUser(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:150',
            'username' => 'required|max:15',
        ]);

        $user = Auth::user();
@foreach(\App\Table::with(['fields'])->where('project_id', $project->id)->where('name', 'users')->first()->fields as $field)
@if ($field->ai)
@elseif($field->name == "updated_by")
        $user->{!! $field->name !!} = Auth::id();
@elseif($field->input_type == "hidden")
@elseif($field->input_type == "password")
        $user->{!! $field->name !!} = Hash::make($request->{!! $field->name !!});
@elseif($field->type == "varchar")
        $user->{!! $field->name !!} = $request->{!! $field->name !!};
@else
        $user->{!! $field->name !!} = $request->{!! $field->name !!};
@endif
@endforeach
        $user->save();

        return response()->json([
            'success' => true,
            'data' => Auth::user(),
            'message' => 'Awesome, successfully update current user data !',
        ], 200);
    }

    public function updateCurrentUserPassword(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|max:150',
            'new_password' => 'required|max:15',
        ]);

        $user = Auth::user();

        if (Hash::check($request->current_password, Auth::user()->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Awesome, successfully update current user password !',
            ], 200);

        } else {

            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Oops, password does not match !',
            ], 400);

        }

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Oops, Internal server error !',
        ], 500);

    }

    public function updateCurrentUserPhoto(Request $request)
    {
        // return $request;
        // Validation
        $validator = Validator::make($request->all(), [
            'photo' => 'required|image',
        ]);

        $user = Auth::user();

        /* Image */
        if ($request->file('photo') !== null) {
            if ($user->photo !== null) {
                if (file_exists('users/photos/' . $user->photo)) {
                    unlink(public_path('users/photos/' . $user->photo));
                }
            }
            $photo = $request->file('photo');
            $filename = time() . '.' . $photo->getClientOriginalName();
            $path = public_path('/users/photos/' . $filename);
            Image::make($photo->getRealPath())->resizeCanvas(800, 800, 'center', false, '#ffffff')->save($path);
            $user->photo = 'users/photos/' . $filename;

            $user->save();

            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Awesome, successfully update current user photo !',
            ], 200);

        } else {

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, somethings when wrong !',
            ], 400);

        }

    }

    public function loginWithSocialCredentials(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        // return $user;
        if (Auth::loginUsingId($user->id)) {
            $user = Auth::user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1)->timestamp;
            }

            $token->save();
            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->timestamp,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, please login to continue !',
            ], 401);
        }
    }

    public function loginWithTokens(Request $request)
    {
//        return $request->all();
        $vote_token = VoteToken::where('token', $request->token)->first();

        if (empty($vote_token)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Data not found !',
            ], 400);
        }

        if ($vote_token->active_flag !== 'active') {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Token already used !'
            ], 400);
        }

        $vote = $vote_token->vote();

        if (empty($vote)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Oops, Data not found !',
            ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $vote_token,
            'message' => 'Awesome, successfully login !',
        ], 200);
    }

    public function loginSocial(Request $request)
    {
        // $request = json_decode(base64_decode($request->data), true);
        $user_data = new Vote;
        $user_data->fill($request->toArray());

        $user_data->is_user_exist = true;
        // return $user_data;

        $user = User::where('email', $user_data->email)
            ->first();

        // user not found
        if ($user === null && $user_data->password === null) {
            $user_data->is_user_exist = false;

            return response()->json([
                'success' => true,
                'data' => base64_encode($user_data),
                'message' => 'Awesome, successfully logged out !',
            ], 200);
        }

        if ($user === null && $user_data->password !== null) {
            // // Validation
            // $validator = Validator::make($user_data, [
            //     'name' => 'required|max:150',
            //     'password' => 'required',
            //     'email' => 'required|email|max:150',
            // ]);

            $user_data->is_user_exist = false;

            $user = new User;
            $user->name = $user_data->name;
            $user->email = $user_data->email;
            $user->username = str_replace(' ', '', $user_data->name) . rand(1000, 9999);
            $user->password = Hash::make($user_data->password);

            // Image Store
            $filename = time() . '.' . $user->username . '.' . pathinfo($user_data->image, PATHINFO_EXTENSION);
            $path = public_path('/users/photos/' . $filename);
            Image::make($user_data->image)->resizeCanvas(800, 800, 'center', false, '#ffffff')->save($path);
            $user->photo = 'users/photos/' . $filename;

            $user->save();

            return response()->json([
                'success' => true,
                'data' => base64_encode($user_data),
                'message' => 'Awesome, successfully login !',
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => base64_encode($user_data),
            'message' => 'Awesome, successfully synchronizing !',
        ], 200);
    }

    public function logout()
    {
        foreach (Auth::user()->tokens as $token) {
            $token->revoke();
        }

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Awesome, successfully logged out !',
        ], 200);
    }

    public function handleUnauthentication()
    {
        return response()->json([
            'success' => false,
            'data' => null,
            'message' => 'Session expired, please login to continue !',
        ], 401);
    }

    public function testMail(Request $request)
    {
        Mail::send('welcome', ['key' => 'value'], function ($message) {
            $message->to('nurcahyoputro0@gmail.com', 'Nur Cahyo')->subject('Welcome!');
        });
    }

    public function testReq()
    {
        return response('Hello World', 200);

    }

    public function getAllPermissions(Request $request)
    {
        $permissions = QueryHelpers::getData($request, new Permission());

        return response()->json([
            'success' => true,
            'data' => $permissions,
            'message' => 'Awesome, successfully get data !',
        ], 200);
    }

    public function getAllRoles(Request $request)
    {
        $permissions = QueryHelpers::getData($request, new Role());

        return response()->json([
            'success' => true,
            'data' => $permissions,
            'message' => 'Awesome, successfully get data !',
        ], 200);
    }

}
