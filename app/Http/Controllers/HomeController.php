<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index(){
        return View('home',[
            "menu"=>[
                "home"=>"active",
                "projectos"=>"",
                "administrativos"=>"",
                "comercial"=>"",
                "financeiro"=>"",
                "usuario"=>""
            ]
        ]);
    }

}
