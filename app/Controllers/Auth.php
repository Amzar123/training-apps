<?php

namespace App\Controllers;

use App\Models\User;

class Auth extends BaseController
{
    public function login()
    {
        helper(['form']);

        // // Tangkap input
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('errors', $validation->getErrors());
        }

        // // Proses login
        $userModel = new User();
        $user = $userModel->checkLogin($email, $password);

        if ($user) {
            session()->set('isLoggedIn', true);
            session()->set('user', $user);

            return redirect()->to('/dashboard');
        }

        return redirect()->back()->with('error', 'Email atau password salah');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function loginPage()
    {   
        return view('login');
    }

    public function registerPage()
    {
        return view('register');
    }

    public function register()
    {
        $validation = \Config\Services::validation();

        // Aturan validasi
        $validation->setRules([
            'name'     => 'required|min_length[3]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ]);

        // Mengecek validasi
        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Data pengguna baru
        $data = [
            'name'     => $this->request->getPost('name'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),  // Hash password
        ];

        // Simpan ke database
        $userModel = new User();
        $userModel->insert($data);

        // Redirect ke halaman login atau dashboard
        return redirect()->to('/dashboard')->with('success', 'Registration successful!');
    }
}
