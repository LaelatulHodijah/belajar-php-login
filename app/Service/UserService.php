<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Service;

use ProgrammerZamanNow\Belajar\PHP\MVC\config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;
use ProgrammerZamanNow\Belajar\PHP\MVC\Exception\ValidationException;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserLoginRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserLoginResponse;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserRegisterRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserResgisterResponse;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(UserRegisterRequest $request): UserResgisterResponse
    {
        $this->validateUserRegistrationRequest($request);


        try {
            Database::beginTransaction();
            $user = $this->userRepository->findById($request->id);
            if ($user != null) {
                throw new ValidationException("User id alredy");
            }

            $user = new  User();
            $user->id = $request->id;
            $user->name = $request->name;
            $user->password = password_hash($request->password, PASSWORD_BCRYPT);

            $this->userRepository->save($user);

            $response = new UserResgisterResponse();
            $response->user = $user;

            Database::comitTransaction();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    public function validateUserRegistrationRequest(UserRegisterRequest $request)
    {
        if ($request->id == null || $request->name == null || $request->password == null ||
            trim($request->id) == "" || trim($request->name) == "" || trim($request->password) == "") {
            throw new ValidationException("Id, Name, Password can not blank");
        }
    }

    public function login(UserLoginRequest $request, $response): UserLoginResponse
    {
        $this->validateUserLoginRequest($request);

        $user = $this->userRepository->findById($request->id);
        if ($user == null) {
            throw new ValidationException("Id or password iss wrong");
        }
        if (password_verify($request->password, $user->password)) {
            $rseponse = new UserLoginResponse();
            $response->user = $user;
            return $rseponse;
        } else {
            throw new ValidationException("Id or password is wrong");
        }
    }


    private function validateUserLoginRequest(UserLoginRequest $request): void
    {
        if ($request->id == null || $request->password == null ||
            trim($request->id) == "" || trim($request->password) == "") {
            throw new ValidationException("Id, Password can not blank");

        }
    }

    private function updateProfile(UserProfileUpdateRequest $request): UserProfileUpdateResponse
    {
$this->validateUserProfileUpdateRequest($request);

        try {
            Database:: beginTransaction();

            $user = $this->userRepository->findById($request->id);
            if($user == null){
                throw new ValidationException("User is not found");
            }

            $user ->name = $request -> name;
            $this->userRepository->save($user);

            Database::commitTransaction();

            $response = new UserProfileUpdateResponse();
            $response->user = $user;
            return $response;
        }catch (\Exception $exception){
            Database::rollbackTransaction();
            throw $exception;
        }

    }

    private function validateUserProfileUpdateRequest(UserProfileUpdateRequest $request){
        if ($request->id == null || $request->password == null ||
            trim($request->id) == "" || trim($request->password) == "") {
            throw new ValidationException("Id, Password can not blank");

        }
    }
}