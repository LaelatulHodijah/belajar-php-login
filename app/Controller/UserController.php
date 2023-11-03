<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Controller;

use ProgrammerZamanNow\Belajar\PHP\MVC\App\View;

class UserController
{
    public function register(){
        View::render('User/register', [
            'title' => "Register new User"
        ]);

    }
    public function postRegister(){

    }

}