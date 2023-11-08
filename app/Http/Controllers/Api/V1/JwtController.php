<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class JwtController extends Controller
{
    /**
     * Create a new JwtController instance.
     */
    public function __construct()
    {
        Auth::setDefaultDriver('api');
    }
}
