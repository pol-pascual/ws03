<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;

class UserController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');
        $this->db = new Database($config);
    }

    /**
     * 
     * show login page
     * 
     * @return void
     * 
     */
    public function login()
    {
        loadView('users/login');
    }

    /**
     * 
     * show create page
     * 
     * @return void
     * 
     */
    public function create()
    {
        loadView('users/create');
    }

    /**
     * store user to db
     * 
     * @return void
     */
    public function store() {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $password = $_POST['password'];
        $passwordConfirmation = $_POST['passwordConfirmation'];

        $errors = [];

        if(!Validation::email($email)) {
            $errors['$email'] = 'Please enter a valid email address';
        }

        if(!Validation::string($name, 2, 50)) {
            $errors['$name'] = 'Name must be 2-50 characters';
        }

        if(!Validation::string($password, 6, 50)) {
            $errors['$password'] = 'Password be at least 6 characters';
        }

        if(!Validation::match($password, $passwordConfirmation)) {
            $errors['$passwordConfirmation'] = 'Password Mismatch';
        }

        if(!empty($errors)) {
            loadView('users/create', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state
                ]
            ]);
            exit;
        } 

        $params = [
            'email' => $email
        ];
        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if($user) {
            $errors['email'] = 'Email Already In Used';
            loadView('users/create', [
                'errors' => $errors
            ]);
            exit;
        }

        $params = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $this->db->query('INSERT INTO users (name, email, city, state, password) VALUES (:name, :email, :city, :state, :password)', $params);

        $userId = $this->db->conn->lastInsertId();

        Session::set('user', [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state
        ]);

        redirect('/');
    }
    /**
     * 
     * logout user
     * 
     * @return void
     */
    public function logout() {
        Session::clearAll('user');
        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain'], $params['secure'], $params['httponly']);

        redirect('/');
    }

    /**
     * authenticate user
     * 
     * @return void
     * 
     */
    public function authenticate() {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $params = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if(!$user || !password_verify($password, $user->password)) {
            loadView('users/login', [
                'error' => 'Invalid email or password'
            ]);
            exit;
        }

        Session::set('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'city' => $user->city,
            'state' => $user->state
        ]);

        redirect('/');
    }
}
