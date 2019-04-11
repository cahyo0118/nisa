{!! $php_prefix !!}

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Hash;

class RegisterController extends Controller
{

    public function register(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:150',
            'password' => 'required',
            'email' => 'required|email|max:150',
        ]);

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = str_replace(' ', '', $request->name);
        $user->password = Hash::make($request->password);
        $user->address = $request->address;
        $user->organization = $request->organization;
        $user->about = $request->about;

        $user->save();

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Awesome, successfully create new user !'
        ], 200);
    }
}
