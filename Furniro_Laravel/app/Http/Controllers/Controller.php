<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
//
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
//
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    function userCan($action, $option = null)
    {
        $user = Auth::user();
        return Gate::forUser($user)->allows($action, $option);
    }
}
