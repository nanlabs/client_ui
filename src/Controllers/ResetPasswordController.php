<?php

namespace App\Controllers;


class ResetPasswordController extends Controller
{

    public function show ($request, $response, $args) {
        $params = $request->getQueryParams();
        $token = $params['t'];
        $username = $this->getUsername($token);
        if($username) {
           $auth = $this->buildAuthToken($username);
           $this->deleteToken($token);
        } else {
            $error = $this->getDefaultErrorMessage();
            $disableForm = true;
        }
        echo "$username, $token";
        $this->render($response, [
            'error' => $error,
            'disabled' => $disableForm,
            'auth' => $auth
        ]);
    }

    public function reset($request, $response, $args) {
        $form = $request->getParsedBody();
        $newPassword = $form['password'];
        $username = $_SESSION[$form['auth']];
        if(!$username) {
            return $this->showError($response, $this->getDefaultErrorMessage(), $form['auth'], true);
        }
        if($newPassword !== $form['password2']) {
            return $this->showError($response, "Passwords don't match", $form['auth']);
        }
        $this->resetPassword($username, $newPassword);
        return $response->withRedirect('/login');

    }

    private function showError($response, $error, $auth, $disableForm = false) {
        return $this->render($response, [
            'error' => $error,
            'auth' => $auth,
            'disabled' => $disableForm
        ]);
    }

    private function buildAuthToken($username) {
        $auth = base64_encode(random_bytes(32));
        $_SESSION[$auth] = $username;
        return $auth;
    }

    private function getUsername($token) {
        $stmt = $this->db->prepare("SELECT username FROM recovery_tokens where token = ?");
        $stmt->execute([$token]);
        $recovery = $stmt->fetch();
        return $recovery ? $recovery['username'] : false;
    }

    private function deleteToken($token) {
        $stmt = $this->db->prepare("DELETE FROM recovery_tokens where token = ?");
        return $stmt->execute([$token]);
    }

    private function resetPassword($username, $newPassword) {
        $stmt = $this->db->prepare("UPDATE users SET password=:password WHERE username=:username");
        return $stmt->execute([
            ':password' => password_hash($newPassword, PASSWORD_DEFAULT),
            ':username' => $username
        ]);
    }

    private function getDefaultErrorMessage() {
        return 'Sorry, you clicked on an invalid password reset link';
    }

    protected function getView() {
        return 'reset_password.phtml';
    }
}