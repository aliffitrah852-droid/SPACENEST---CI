<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public const PRODI = [
        'Teknik Informatika', 'Teknik Elektro', 'Teknik Mesin',
        'Teknik Sipil', 'Dosen / Staf FTST',
    ];

    // ---------------------------------------------------------------- LOGIN
    public function login()
    {
        return view('auth/login');
    }

    public function attemptLogin()
    {
        $email    = trim((string) $this->request->getPost('email'));
        $password = trim((string) $this->request->getPost('password'));

        if ($email === '' || $password === '') {
            return redirect()->back()->withInput()->with('error', 'Silakan isi semua kolom!');
        }

        $user = (new UserModel())->findByEmail($email);

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Email atau kata sandi tidak valid!');
        }

        session()->regenerate();
        session()->set([
            'user_id' => (int) $user['id'],
            'name'    => $user['name'],
            'role'    => $user['role'],
        ]);

        return redirect()->to($user['role'] === 'admin' ? '/admin' : '/');
    }

    // ------------------------------------------------------------- REGISTER
    public function register()
    {
        return view('auth/register', ['prodiList' => self::PRODI]);
    }

    public function attemptRegister()
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'prodi'    => 'required|in_list[' . implode(',', self::PRODI) . ']',
            'password' => 'required|min_length[6]',
        ];
        $messages = [
            'email'    => ['is_unique' => 'Email sudah terdaftar! Silakan gunakan email lain atau masuk.'],
            'password' => ['min_length' => 'Kata sandi minimal 6 karakter.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('error', $this->firstError());
        }

        (new UserModel())->insert([
            'name'     => trim($this->request->getPost('name')),
            'email'    => trim($this->request->getPost('email')),
            'password' => password_hash(trim($this->request->getPost('password')), PASSWORD_DEFAULT),
            'role'     => 'pelapor',
            'prodi'    => $this->request->getPost('prodi'),
        ]);

        return redirect()->to('/register')->with('success', 'Pendaftaran berhasil! Silakan masuk dengan akun Anda.');
    }

    // --------------------------------------------------------- ADMIN REGISTER
    public function adminRegister()
    {
        return view('auth/admin_register');
    }

    public function attemptAdminRegister()
    {
        // Kode rahasia mencegah sembarang orang membuat akun admin.
        $secret = (string) env('admin.registrationCode', '');
        $given  = (string) $this->request->getPost('kode_admin');

        if ($secret === '' || ! hash_equals($secret, $given)) {
            return redirect()->back()->withInput()->with('error', 'Kode registrasi admin tidak valid.');
        }

        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|max_length[100]|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];
        $messages = [
            'email'    => ['is_unique' => 'Email sudah terdaftar! Silakan gunakan email lain.'],
            'password' => ['min_length' => 'Kata sandi minimal 6 karakter.'],
        ];

        if (! $this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('error', $this->firstError());
        }

        (new UserModel())->insert([
            'name'     => trim($this->request->getPost('name')),
            'email'    => trim($this->request->getPost('email')),
            'password' => password_hash(trim($this->request->getPost('password')), PASSWORD_DEFAULT),
            'role'     => 'admin',
            'prodi'    => null,
        ]);

        return redirect()->to('/admin/register')->with('success', 'Pendaftaran Admin FTST berhasil! Silakan masuk.');
    }

    // --------------------------------------------------------------- LOGOUT
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }

    private function firstError(): string
    {
        $errors = $this->validator->getErrors();

        return $errors ? reset($errors) : 'Data tidak valid.';
    }
}
