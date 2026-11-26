<?php
// Data deskripsi & galeri tiap tipe kamar (diambil dari frontend tim; PDM tidak punya kolom deskripsi).
const FE_TIPE = [
    1 => ['deskripsi' => "Kamar Tipe 1 merupakan pilihan ekonomis yang nyaman di Kos Bu Nining, dirancang untuk memaksimalkan sirkulasi udara alami dan pencahayaan ruangan. Dilengkapi fasilitas kamar mandi luar yang selalu terawat bersih, tipe ini cocok bagi penghuni yang menginginkan hunian tenang dengan harga terjangkau di kawasan Malang.", 'galeri' => [['Lemari.jpeg', 'Lemari'], ['meja-belajar.jpeg', 'Meja Belajar']]],
    2 => ['deskripsi' => "Kamar Tipe 2 memberikan privasi lebih dengan fasilitas kamar mandi pribadi di dalam kamar tanpa menggunakan AC. Ruangan didesain sejuk dan tertata rapi untuk mendukung aktivitas istirahat maupun belajar sehari-hari.", 'galeri' => [['kamar-mandi.jpeg', 'Kamar Mandi'], ['Lemari.jpeg', 'Lemari'], ['meja-belajar.jpeg', 'Meja Belajar']]],
    3 => ['deskripsi' => "Kamar Tipe 3 dilengkapi pendingin ruangan (AC) untuk membantu menciptakan suasana istirahat yang nyaman. Tipe ini menggunakan fasilitas kamar mandi luar yang bersih dan berada dekat area kamar.", 'galeri' => [['Lemari.jpeg', 'Lemari'], ['meja-belajar.jpeg', 'Meja Belajar']]],
    4 => ['deskripsi' => "Kamar Tipe 4 merupakan pilihan dengan fasilitas lebih lengkap, dilengkapi pendingin ruangan (AC) dan kamar mandi privat di dalam kamar. Ruangan tertata rapi untuk mendukung istirahat, belajar, maupun bekerja.", 'galeri' => [['kamar-mandi.jpeg', 'Kamar Mandi'], ['Lemari.jpeg', 'Lemari'], ['meja-belajar.jpeg', 'Meja Belajar']]],
];

const FE_IKON_FASILITAS = ['AC' => '❄️', 'Kamar Mandi Dalam' => '🚿', 'Kamar Mandi Luar' => '🚪', 'Kasur & Bantal' => '🛏️', 'Lemari Pakaian' => '🚪', 'Meja & Kursi' => '🪑',
    'Wi-Fi' => '📶', 'Dapur Bersama' => '🍳', 'Listrik Token' => '⚡', 'Parkir Motor' => '🛵', 'Laundry' => '🧺', 'CCTV 24 Jam' => '📹'];
