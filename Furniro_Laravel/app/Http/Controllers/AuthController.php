<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;
use App\Models\User;
class AuthController extends Controller
{


    public function firebase(Request $request)
    {
        $auth = (new Factory)
            ->withServiceAccount(base_path(config('firebase.credentials.file')))
            ->createAuth();

        $verified = $auth->verifyIdToken($request->token);
        $uid = $verified->claims()->get('sub');
        $email = $verified->claims()->get('email');
        $name = $verified->claims()->get('name') ?? 'Firebase User';
       $user = User::where('email', $email)->first();

    if (!$user) {
        $user = User::create([
        'firebase_uid' => $uid,
        'email' => $email,
        'name' => $name
    ]);
    }
    else
        {
    // Nếu Firebase UID khác trước, cập nhật lại
        if ($user->firebase_uid !== $uid) {
        $user->update(['firebase_uid' => $uid]);
        }
    }
        Auth::login($user); // Sanctum sẽ tạo session cookie

        return response()->json(['message' => 'Đăng nhập thành công']);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (!Auth::attempt($credentials, true)) {
            return response()->json(['message' => 'Sai thông tin đăng nhập'], 401);
        }
        return response()->json(Auth::user());
    }

    public function register(Request $request)
    {
       $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        Auth::login($user);
        return response() -> json(Auth::user());
    }

    public function user(Request $request)
    {
        $user = $request->user();
        return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role, // 👈 Thêm rõ ràng
    ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['message' => 'Đã đăng xuất']);
    }
}
