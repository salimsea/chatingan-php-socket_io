-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 07 Jul 2020 pada 17.37
-- Versi server: 10.1.37-MariaDB
-- Versi PHP: 5.6.39

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `accounts`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_messages`
--

CREATE TABLE `tb_messages` (
  `id_message` int(11) NOT NULL,
  `id_room` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `file` text NOT NULL,
  `status` int(1) NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_messages`
--

INSERT INTO `tb_messages` (`id_message`, `id_room`, `id_user`, `message`, `file`, `status`, `created_date`) VALUES
(1, 3, 1, 'bGFnaSBhcGEgYnJv', '-', 1, '2020-06-21 08:47:22'),
(2, 2, 1, 'aGFsbG8=', '-', 1, '2020-06-21 08:47:43'),
(3, 2, 4, 'eWE=', '-', 1, '2020-06-21 08:48:05'),
(4, 2, 1, 'c3VkYWggc29sYXQ/', '-', 1, '2020-06-21 09:33:04'),
(5, 2, 4, 'c3VkYWggYnJvIPCfmY8=', '-', 1, '2020-06-21 09:33:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_roles`
--

CREATE TABLE `tb_roles` (
  `id_role` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` int(1) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_roles`
--

INSERT INTO `tb_roles` (`id_role`, `name`, `status`, `created_date`, `created_by`) VALUES
(1, 'pengguna', 1, '2020-06-19 12:00:00', '2020-06-19 04:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_rooms`
--

CREATE TABLE `tb_rooms` (
  `id_room` int(11) NOT NULL,
  `id_user_update` int(11) NOT NULL,
  `last_message` text NOT NULL,
  `status` int(1) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_rooms`
--

INSERT INTO `tb_rooms` (`id_room`, `id_user_update`, `last_message`, `status`, `created_date`, `updated_date`) VALUES
(1, 3, 'eHh4eHh4eA==', 1, '2020-06-20 10:48:59', '2020-06-20 10:48:59'),
(2, 4, 'c3VkYWggYnJvIPCfmY8=', 1, '2020-06-21 02:24:23', '2020-06-21 09:33:23'),
(3, 1, 'bGFnaSBhcGEgYnJv', 1, '2020-06-21 02:25:28', '2020-06-21 08:47:22'),
(4, 4, 'bWFiYXIgYnJv', 1, '2020-06-21 03:32:53', '2020-06-21 03:32:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_room_users`
--

CREATE TABLE `tb_room_users` (
  `id_room_user` int(11) NOT NULL,
  `id_room` int(11) NOT NULL,
  `id_user_sender` int(11) NOT NULL,
  `id_user_receiver` int(11) NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_room_users`
--

INSERT INTO `tb_room_users` (`id_room_user`, `id_room`, `id_user_sender`, `id_user_receiver`, `created_date`) VALUES
(1, 1, 3, 4, '2020-06-20 10:48:59'),
(2, 1, 4, 3, '2020-06-20 10:48:59'),
(3, 2, 1, 4, '2020-06-21 02:24:24'),
(4, 2, 4, 1, '2020-06-21 02:24:24'),
(5, 3, 1, 2, '2020-06-21 02:25:28'),
(6, 3, 2, 1, '2020-06-21 02:25:28'),
(7, 4, 4, 2, '2020-06-21 03:32:53'),
(8, 4, 2, 4, '2020-06-21 03:32:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_users`
--

CREATE TABLE `tb_users` (
  `id_user` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `foto` varchar(50) NOT NULL,
  `status` int(1) NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tb_users`
--

INSERT INTO `tb_users` (`id_user`, `id_role`, `username`, `password`, `name`, `email`, `foto`, `status`, `created_date`, `created_by`) VALUES
(1, 1, 'pengguna', 'salim', 'Salim Segaf', 'salim@salim.com', '', 1, '2020-06-19 00:00:00', '2020-06-19 00:00:00'),
(2, 1, 'pengguna1', 'yasin', 'Ahmad Yasin', 'yasin@yasin.com', '', 1, '2020-06-19 00:00:00', '2020-06-19 00:00:00'),
(3, 1, 'pengguna2', 'fatih', 'Fatih Syuqi', 'fatih@fatih.com', '', 1, '2020-06-19 00:00:00', '2020-06-19 00:00:00'),
(4, 1, 'hanum', 'hanum', 'Hanum Hanifa', 'hanum@hanum.com', '', 1, '2020-06-19 00:00:00', '2020-06-19 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_messages`
--
ALTER TABLE `tb_messages`
  ADD PRIMARY KEY (`id_message`);

--
-- Indeks untuk tabel `tb_roles`
--
ALTER TABLE `tb_roles`
  ADD PRIMARY KEY (`id_role`);

--
-- Indeks untuk tabel `tb_rooms`
--
ALTER TABLE `tb_rooms`
  ADD PRIMARY KEY (`id_room`);

--
-- Indeks untuk tabel `tb_room_users`
--
ALTER TABLE `tb_room_users`
  ADD PRIMARY KEY (`id_room_user`);

--
-- Indeks untuk tabel `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_messages`
--
ALTER TABLE `tb_messages`
  MODIFY `id_message` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tb_roles`
--
ALTER TABLE `tb_roles`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tb_rooms`
--
ALTER TABLE `tb_rooms`
  MODIFY `id_room` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tb_room_users`
--
ALTER TABLE `tb_room_users`
  MODIFY `id_room_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
