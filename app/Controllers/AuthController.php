<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm(): void
    {
        if (Auth::check()) {
            $this->redirect('/');
            return;
        }
        $this->layout('auth.login', [
            'pageTitle' => 'Connexion',
        ]);
    }

    public function login(): void
    {
        $email = $this->sanitize($this->input('email', ''));
        $password = $this->input('password', '');

        $errors = $this->validate(
            ['email' => $email, 'password' => $password],
            ['email' => 'required|email', 'password' => 'required|min:6']
        );

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', ['email' => $email]);
            $this->redirect('/login');
            return;
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user || !$userModel->verifyPassword($password, $user['password'])) {
            Session::flash('error', 'Email ou mot de passe incorrect.');
            Session::flash('old', ['email' => $email]);
            $this->redirect('/login');
            return;
        }

        if (!$user['is_active']) {
            Session::flash('error', 'Votre compte est désactivé.');
            $this->redirect('/login');
            return;
        }

        Auth::login($user);

        // Redirect based on role
        match ($user['role']) {
            'admin'   => $this->redirect('/admin'),
            'kitchen' => $this->redirect('/kitchen'),
            default   => $this->redirect('/'),
        };
    }

    public function registerForm(): void
    {
        if (Auth::check()) {
            $this->redirect('/');
            return;
        }
        $this->layout('auth.register', [
            'pageTitle' => 'Inscription',
        ]);
    }

    public function register(): void
    {
        $name = $this->sanitize($this->input('name', ''));
        $email = $this->sanitize($this->input('email', ''));
        $phone = $this->sanitize($this->input('phone', ''));
        $password = $this->input('password', '');
        $passwordConfirm = $this->input('password_confirm', '');

        $errors = $this->validate(
            ['name' => $name, 'email' => $email, 'password' => $password],
            ['name' => 'required|min:2|max:100', 'email' => 'required|email', 'password' => 'required|min:6']
        );

        if ($password !== $passwordConfirm) {
            $errors['password_confirm'][] = 'Les mots de passe ne correspondent pas.';
        }

        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            $errors['email'][] = 'Cet email est déjà utilisé.';
        }

        if (!empty($errors)) {
            Session::flash('errors', $errors);
            Session::flash('old', ['name' => $name, 'email' => $email, 'phone' => $phone]);
            $this->redirect('/register');
            return;
        }

        $userId = $userModel->createUser($name, $email, $password, 'client');

        if ($phone) {
            $userModel->update((int)$userId, ['phone' => $phone]);
        }

        $user = $userModel->findById((int)$userId);
        Auth::login($user);

        Session::flash('success', 'Bienvenue chez Billy\'s !');
        $this->redirect('/');
    }

    public function logout(): void
    {
        Auth::logout();
        Session::start();
        Session::flash('success', 'Vous êtes déconnecté.');
        $this->redirect('/login');
    }
}
