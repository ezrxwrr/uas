-- ------------------------------------------------------------
-- Database: projekuas
-- ------------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- ------------------------------------------------------------
-- Table: users
-- ------------------------------------------------------------
CREATE TABLE `users` (
  `user_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `unique_user` (`nama`, `email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Table: donasi
-- ------------------------------------------------------------
CREATE TABLE `donasi` (
  `donation_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `payment_id` INT UNSIGNED NOT NULL,
  `nominal` INT NOT NULL,
  PRIMARY KEY (`donation_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_payment_id` (`payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Table: pembayaran
-- ------------------------------------------------------------
CREATE TABLE `pembayaran` (
  `payment_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `donation_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `metode` ENUM('transfer bank','E-Wallet','QRIS') NOT NULL,
  PRIMARY KEY (`payment_id`),
  KEY `idx_donation_user` (`donation_id`, `user_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Table: reward
-- ------------------------------------------------------------
CREATE TABLE `reward` (
  `reward_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` VARCHAR(255) NOT NULL,
  `badge` ENUM('Gold','Silver','Bronze') NOT NULL,
  `total_donasi` INT UNSIGNED NOT NULL,
  `transaksi` TEXT,
  PRIMARY KEY (`reward_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Table: user_reward
-- ------------------------------------------------------------
CREATE TABLE `user_reward` (
  `user_reward_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `reward_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `donation_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`user_reward_id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_reward_id` (`reward_id`),
  KEY `idx_donation_id` (`donation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ------------------------------------------------------------
-- Foreign Keys
-- ------------------------------------------------------------

ALTER TABLE `donasi`
  ADD CONSTRAINT `fk_donasi_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_pembayaran_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pembayaran_donasi`
    FOREIGN KEY (`donation_id`) REFERENCES `donasi` (`donation_id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `user_reward`
  ADD CONSTRAINT `fk_user_reward_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_reward_donasi`
    FOREIGN KEY (`donation_id`) REFERENCES `donasi` (`donation_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_reward_reward`
    FOREIGN KEY (`reward_id`) REFERENCES `reward` (`reward_id`)
    ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;
