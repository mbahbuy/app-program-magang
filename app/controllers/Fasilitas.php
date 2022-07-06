<?php

class Fasilitas extends Controller
{
    public function index()
    {
        $data['judul'] = 'Halaman Fasilitas';
        $data['active'] = 'fasilitas';
        $this->view('templates/header', $data);
        $this->view('Fasilitas/index');
        $this->view('templates/footer');
    }
}