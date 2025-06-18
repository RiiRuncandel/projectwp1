<?php
// includes/header.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Universitas Bina Sarana Informatika</title>
    <link rel="icon" type="png" href="img/bsi.png">
    <link rel="stylesheet" href="style.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body>
    <button onclick="topFunction()" id="myBtn" title="Go to top"><i class='bx bx-up-arrow-alt' ></i></button>
    <!-- HEADER DESIGN -->
    <header>
        <a href="#" class="logo">Universitas<span> BSI</span></a>
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Cari...">
            <button id="search-btn"><i class="fa fa-search"></i></button>
        </div>
        <ul class="navlist">
            <!-- Menu navigasi -->
        </ul>

        <div class="bx bx-menu" id="menu-icon"></div>
        <ul class="navlist">
            <li><a href="#home" class="active">Beranda</a></li>
            <li><a href="#about">Tentang</a></li>
            <li><a href="#fasilitas">Fasilitas</a></li>
            <li><a href="#fakultas">Fakultas</a></li>
            <li><a href="#organisasi">Organisasi</a></li>
            <li><a href="#organisasi">Galeri</a></li>
            <li><a href="#contact">Kontak</a></li>
            <li><a href="auth/login.php">Login</a></li>
            <div class="top-btn"><a href="timkami/timkami.html" class="h-btn">Tim Kami!</a></div>
        </ul>
        <div class="bx bx-menu" id="menu-icon"></div>
    </header>