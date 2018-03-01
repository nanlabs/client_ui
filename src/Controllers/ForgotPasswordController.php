<?php

namespace App\Controllers;


use App\Services\MailerService;
use PDO;
use Slim\Views\PhpRenderer;

class ForgotPasswordController extends Controller
{

    protected $mailer;

    public function __construct( PhpRenderer $renderer, PDO $db, MailerService $mailer) {
        parent::__construct($renderer, $db);
        $this->mailer = $mailer;
    }

    public function forgot( $request, $response, $args) {
        $form = $request->getParsedBody();
        $email = $form['email'];
        $username = $this->getUsername($email);
        if($username) {
            $token = $this->generateResetToken($username);
            $this->sendMail($username, $email, $token);
            return $this->render($response, ['sentTo' => $email]);
        } else {
            return $this->render($response , ['error' => "There is no user registered with that email address."]);
        }
    }

    private function getUsername($email) {
        $stmt = $this->db->prepare("SELECT username FROM users where email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        return $user ? $user['username'] : false;
    }

    private function sendMail($username, $email, $token) {
        $text = $this->buildResetMessage($username, $token);
        $this->mailer->send($email, 'Password reset', $text);
    }

    private function buildResetMessage($username, $token) {
        return $this->renderer->fetch('reset_email.phtml', [
            'username' => $username,
            'recoveryLink' => $this->buildResetLink($token)
        ]);
    }

    private function buildResetLink($token) {
        return "http://localhost:8080/password-reset?t=$token";
    }

    private function generateResetToken($username) {
        $token = bin2hex(random_bytes(32));
        $stmt = $this->db->prepare(
            "INSERT INTO recovery_tokens (username, token) VALUES (:username, :token)");
        $stmt->execute([
            ':username' => $username,
            ':token' => $token
        ]);
        return $token;
    }

    protected function getView() {
        return 'forgot_password.phtml';
    }
}