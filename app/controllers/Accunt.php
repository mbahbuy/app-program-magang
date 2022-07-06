<?php

class Accunt extends Controller
{
    public function index()
    {
        $data['judul'] = 'Halaman Accunt';
        $data['active'] = 'accunt';
        $this->view('templates/header', $data);
        $this->view('Accunt/index');
        $this->view('templates/footer');
    }

}