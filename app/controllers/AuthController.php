<?php

namespace App\Controllers;

use App\Core\Abstracts\Controller;
use App\Core\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public ?string $layout = 'layouts/default';

    /**
     * Call login form
     *
     * @return string
     */
    public function loginForm(): string
    {
        return $this->view('login');
    }

    /**
     * User login
     *
     * @param Request $request
     * @return string
     */
    public function login(Request $request): string
    {
        $validated = $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        if ($user = User::query()->where('login', $validated['login'])->first()) {
            if ($user->passwordVerify($validated['password'])) {
                $this->app->authUser($user->id);

                $this->redirect('home');
            }
        }

        $this->session->setFlash('errors', [
            'login' => ['Incorrect login or password'],
        ]);
        $this->response->back();
    }

    /**
     * User logout
     *
     * @return void
     */
    public function logout(): void
    {
        $this->app->logout();

        $this->redirect('home');
    }
}