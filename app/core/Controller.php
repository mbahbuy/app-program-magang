<?php

class Controller {
    public function view($view, $data = []){
        require_once 'app/views/' . $view . '.php';
    }

    public function model($model) {
        require_once 'app/models/' . $model . '.php';
        return new $model;
    }

    // fungsi untuk mengambil isi GET dalam URL 
    public function getURL(){
        if( isset($_GET['url']) ){
            $token = rtrim($_GET['url'], '/');
            $token = filter_var($token, FILTER_SANITIZE_URL);
            $token = explode('/', $token);
            return $token;
        }
    }
}