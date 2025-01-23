<?php

namespace App\Http\Controllers;

use App\Models\Clients;
use App\Models\Issues;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    
    public function index(){
        $allClients = Clients::count();
        $allIssues = Issues::count();
        $user = User::findall();

        return view(
'home', compact('allClients', 'allIssues', 'user')
        );
    }
}
