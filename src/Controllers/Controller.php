<?php

namespace App\Controllers;


use PDO;
use Slim\Views\PhpRenderer;

abstract class Controller
{

    protected  $renderer;
    protected  $db;

    public function __construct( PhpRenderer $renderer, PDO $db) {
        $this->renderer = $renderer;
        $this->db = $db;
    }

    public function show($request, $response, $args) {
        return $this->render($response);
    }

    protected abstract function getView();

    protected function render($response, $data = []) {
        return $this->renderer->render($response, $this->getView(), $data);
    }

}