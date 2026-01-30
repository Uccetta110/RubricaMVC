-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Gen 30, 2026 alle 13:05
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `telefono`
--
CREATE DATABASE IF NOT EXISTS `telefono` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `telefono`;

-- --------------------------------------------------------

--
-- Struttura della tabella `rubrica`
--

CREATE TABLE `rubrica` (
  `id` int(11) NOT NULL,
  `nome` text NOT NULL,
  `numero` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `rubrica`
--

INSERT INTO `rubrica` (`id`, `nome`, `numero`) VALUES
(1, 'Davy D. Xebec ', 3243544354),
(2, 'Demo Nico', 6767676767),
(3, 'Binardo', 2322342324),
(4, 'Persona 2', 3453453453),
(5, 'Marco Vasalli', 1100011),
(6, 'Inserena Lavatroni', 1234567890);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `rubrica`
--
ALTER TABLE `rubrica`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `rubrica`
--
ALTER TABLE `rubrica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
