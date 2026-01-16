<?php

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    public function login()
    {
        // If already logged in, redirect to home
        if (Auth::check() && isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $this->redirect('home');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                $_SESSION['error'] = 'Vui lòng nhập đầy đủ thông tin!';
                $this->view('auth/login', ['title' => 'Đăng nhập']);
                return;
            }

            $user = $this->userModel->findByUsername($username);

            if (!$user) {
                $_SESSION['error'] = 'Tên đăng nhập không tồn tại!';
                $this->view('auth/login', ['title' => 'Đăng nhập']);
                return;
            }

            if ($user['status'] !== 'active') {
                $_SESSION['error'] = 'Tài khoản đã bị khóa!';
                $this->view('auth/login', ['title' => 'Đăng nhập']);
                return;
            }

            if (!$this->userModel->verifyPassword($password, $user['password'])) {
                $_SESSION['error'] = 'Mật khẩu không đúng!';
                $this->view('auth/login', ['title' => 'Đăng nhập']);
                return;
            }

            // Login successful
            Auth::login($user['id']);
            $_SESSION['success'] = 'Đăng nhập thành công!';
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $this->redirect('home');
        }

        $this->view('auth/login', ['title' => 'Đăng nhập']);
    }

    public function logout()
    {
        Auth::logout();
        $this->redirect('auth/login');
    }
}
