<?php

namespace Bennent\Geauth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelloWorldController extends Controller
{
    //
    public function hello()
    {
        echo 'hello world controller hello';
    }

    public function index()
    {
        return view('geauth::hello');
    }
}
