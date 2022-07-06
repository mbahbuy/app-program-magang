<?php

class Healthcare extends Controller
{
    public function index()
    {
        $data['judul'] = 'Halaman Healthcare';
        $data['active'] = 'healthcare';
        $this->view('templates/header', $data);
        $this->view('Healthcare/index');
        $this->view('templates/footer');
    }
}