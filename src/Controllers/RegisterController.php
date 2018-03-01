<?php

namespace App\Controllers;


class RegisterController extends Controller
{

    public function register( $request, $response, $args ) {
        $form = $request->getParsedBody();
        $data = [];
        if($this->isUsernameDuplicated($form['username'])) {
            $data['error'] = $this->getDuplicatedErrorMessage($form['username']);
        }
        if($this->isEmailDuplicated($form['email'])) {
            $data['error'] = $this->getDuplicatedErrorMessage($form['email']);
        }
        if($form['password'] !== $form['password2']) {
            $data['error'] = "Passwords don't match";
        }
        if(!empty($data['error'])) {
            return $this->render($response, $data);
        } else {
            $this->insertNewUser($form);
            return $response->withRedirect('/login');
        }
    }

    private function isUsernameDuplicated( $username ) {
        return $this->isFieldDuplicated('username', $username);
    }

    private function isEmailDuplicated( $email ) {
        return $this->isFieldDuplicated('email', $email);
    }

    private function isFieldDuplicated( $name, $value) {
        $query = 'SELECT id FROM users where ' . $name . ' = ?';
        $stmt = $this->db->prepare( $query );
        $stmt->execute([$value]);
        return $stmt->rowCount() > 0;
    }

    private function getDuplicatedErrorMessage( $field ) {
        return $field . ' was already taken, please try with another';
    }

    private function insertNewUser($form) {
        $stmt = $this->db->prepare(
            "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        return $stmt->execute([
            ':username' => $form['username'],
            ':email' => $form['email'],
            ':password' => password_hash($form['password'], PASSWORD_DEFAULT)
        ]);
    }

    protected function getView()
    {
        return 'register.phtml';
    }
}