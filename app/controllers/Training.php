<?php

class Training extends Controller
{
    public function index()
    {
        $data['judul'] = 'Halaman Training';
        $data['active'] = 'training';
        $this->view('templates/header', $data);
        $this->view('Training/index');
        $this->view('templates/footer');
    }
}