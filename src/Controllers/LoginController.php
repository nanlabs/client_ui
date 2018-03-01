<?php

namespace App\Controllers;


class LoginController extends Controller
{

    public function login( $request, $response, $args ) {
        $form = $request->getParsedBody();
        $user = $this->loadUser($form['username']);
        if(empty($user) || !password_verify($form['password'], $user['password'])) {
            session_destroy();
            return $this->render($response, ['error' => 'You have entered an invalid username or password']);
        } else {
            $_SESSION['username'] = $user['username'];
            return $response->withRedirect('/');
        }
    }

    public function logout($request, $response, $args) {
        session_destroy();
        return $response->withRedirect('/login');
    }


    private function loadUser($username) {
        $stmt = $this->db->prepare("SELECT username, password FROM users where username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }


    protected function getView()
    {
        return 'login.phtml';
    }
}