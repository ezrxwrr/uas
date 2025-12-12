USE `projekuas`;

-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;

-- Database: `projekuas`

-- --------------------------------------------------------
-- Table structure for table `donasi`
-- --------------------------------------------------------

CREATE TABLE `donasi` (
  `donation_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `payment_id` int UNSIGNED NOT NULL,
  `nominal` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `pembayaran`
-- --------------------------------------------------------

CREATE TABLE `pembayaran` (
  `payment_id` int UNSIGNED NOT NULL,
  `donation_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `metode` enum('transfer bank','E-Wallet','QRIS') 
      CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `reward`
-- --------------------------------------------------------

CREATE TABLE `reward` (
  `reward_id` int UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `badge` enum('Gold','Silver','Bronze') NOT NULL,
  `total_donasi` int UNSIGNED NOT NULL,
  `transaksi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------

CREATE TABLE `users` (
  `user_id` int UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `user_reward`
-- --------------------------------------------------------

CREATE TABLE `user_reward` (
  `user_reward_id` int UNSIGNED NOT NULL,
  `reward_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `donation_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Indexes
-- --------------------------------------------------------

ALTER TABLE `donasi`
  ADD PRIMARY KEY (`donation_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `payment_id` (`payment_id`);

ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `donation_id` (`donation_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `reward`
  ADD PRIMARY KEY (`reward_id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `nama` (`nama`,`email`);

ALTER TABLE `user_reward`
  ADD PRIMARY KEY (`user_reward_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `donation_id` (`donation_id`),
  ADD KEY `reward_id` (`reward_id`);

-- --------------------------------------------------------
-- AUTO_INCREMENT Set
-- --------------------------------------------------------

ALTER TABLE `donasi`
  MODIFY `donation_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `pembayaran`
  MODIFY `payment_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `reward`
  MODIFY `reward_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `user_reward`
  MODIFY `user_reward_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- Constraints
-- --------------------------------------------------------

ALTER TABLE `donasi`
  ADD CONSTRAINT `donasi_ibfk_1` FOREIGN KEY (`user_id`)
      REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`user_id`)
      REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`donation_id`)
      REFERENCES `donasi` (`donation_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_reward`
  ADD CONSTRAINT `user_reward_ibfk_1` FOREIGN KEY (`user_id`)
      REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_reward_ibfk_2` FOREIGN KEY (`donation_id`)
      REFERENCES `donasi` (`donation_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_reward_ibfk_3` FOREIGN KEY (`reward_id`)
      REFERENCES `reward` (`reward_id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;
