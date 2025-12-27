<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected $Users;

    public function __construct(User $Users)
    {
        $this->Users = $Users;
    }

    public function admin()
    {
          if (!$this->userCan('crud-customer')) {
            abort(403);
        }
    return response()->json(['message' => 'Welcome admin']);
    }
}
