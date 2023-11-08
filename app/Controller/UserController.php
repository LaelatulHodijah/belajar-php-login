<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Controller;

use ProgrammerZamanNow\Belajar\PHP\MVC\App\View;
use ProgrammerZamanNow\Belajar\PHP\MVC\Exception\ValidationException;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserLoginRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserRegisterRequest;

class UserController
{
    private $userService;

    public function register(){
        View::render('User/register', [
            'title' => "Register new User"
        ]);

    }
    public function postRegister(){
        $request = new UserRegisterRequest();
        $request->id = $_POST['id'];
        $request->name = $_POST['name'];
        $request->password = $_POST['password'];

        try {
            $this->userService->register($request);
            View::redirect('/users/login');
        }catch (ValidationException $exception){
            View::render('User/register',[
                'title' => 'register new User',
            'error' => $exception->getMessage()
                ]);
        }

    }

    public function login()
    {
        View::render('User/login', [
            "title" => "Login user"
        ]);
    }

    public function postLofin()
    {
        $requset = new UserLoginRequest();
        $requset->id = $_POST['id'];
        $requset->password = $_POST ['password'];

      try{
          $this->userService->login($requset);
          View::redirect('/');
      }catch (ValidationException $exception){
          View::render('user/login',[
              'title' => 'Login user',
          ]);

      }


    }

}