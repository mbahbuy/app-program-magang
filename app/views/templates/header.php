<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman <?= $data['judul'];?></title>
    <link rel="stylesheet" href="<?= BASEURL;?>public/css/bootstrap.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
    <a class="navbar-brand" href="<?= BASEURL;?>">RGI RPL 26</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link <?php echo ( $data['active'] == 'home' ) ? 'active' : '';?>" href="<?= BASEURL;?>">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ( $data['active'] == 'fasilitas' ) ? 'active' : '';?>" href="<?= BASEURL;?>fasilitas">Fasilitas</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ( $data['active'] == 'healthcare' ) ? 'active' : '';?>" href="<?= BASEURL;?>healthcare">Healthcare</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ( $data['active'] == 'training' ) ? 'active' : '';?>" href="<?= BASEURL;?>training">Training</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ( $data['active'] == 'about' ) ? 'active' : '';?>" href="<?= BASEURL;?>about">About</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ( $data['active'] == 'accunt' ) ? 'active' : '';?>" href="<?= BASEURL;?>Accunt">Accunt</a>
        </li>
      </ul>
    </div>
  </div>
</nav>