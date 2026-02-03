<?php
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin() {
        if (Auth::check()) {
            $this->redirect(BASE_PATH . '/items');
        }
        $this->view('auth/login', [], 'auth');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_PATH . '/login');
            return;
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $_SESSION['error'] = 'Username and password are required';
            $this->redirect(BASE_PATH . '/login');
            return;
        }

        $user = $this->userModel->login($username, $password);

        if ($user) {
            Auth::login($user);
            $_SESSION['success'] = 'Welcome back, ' . $user['username'] . '!';
            $this->redirect(BASE_PATH . '/dashboard');
        } else {
            $_SESSION['error'] = 'Invalid username or password';
            $this->redirect(BASE_PATH . '/login');
        }
    }

    public function showRegister() {
        if (Auth::check()) {
            $this->redirect(BASE_PATH . '/item');
        }
        $this->view('auth/register', [], 'auth');
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_PATH . '/register');
            return;
        }

        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($username) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'All fields are required';
            $this->redirect(BASE_PATH . '/register');
            return;
        }

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Passwords do not match';
            $this->redirect(BASE_PATH . '/register');
            return;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters';
            $this->redirect(BASE_PATH . '/register');
            return;
        }

        if ($this->userModel->usernameExists($username)) {
            $_SESSION['error'] = 'Username already exists';
            $this->redirect(BASE_PATH . '/register');
            return;
        }

        if ($this->userModel->emailExists($email)) {
            $_SESSION['error'] = 'Email already exists';
            $this->redirect(BASE_PATH . '/register');
            return;
        }

        $userId = $this->userModel->register([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role' => 'user'
        ]);

        if ($userId) {
            $_SESSION['success'] = 'Registration successful! Please login.';
            $this->redirect(BASE_PATH . '/login');
        } else {
            $_SESSION['error'] = 'Registration failed. Please try again.';
            $this->redirect(BASE_PATH . '/register');
        }
    }

    public function logout() {
        Auth::logout();
        $_SESSION['success'] = 'You have been logged out successfully';
        $this->redirect(BASE_PATH . '/login');
    }
}
?>