SET NAMES utf8mb4;
SET time_zone = '+00:00';

CREATE DATABASE IF NOT EXISTS luxury_fitness
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE luxury_fitness;

CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','trainer','client') NOT NULL DEFAULT 'client',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uk_users_email (email),
  INDEX idx_users_role (role)
) ENGINE=InnoDB;

CREATE TABLE classes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT NULL,
  instructor_id BIGINT UNSIGNED NULL,
  start_time DATETIME NOT NULL,
  end_time DATETIME NOT NULL,
  capacity INT UNSIGNED NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT chk_classes_time CHECK (end_time > start_time),
  CONSTRAINT chk_classes_capacity CHECK (capacity > 0),
  CONSTRAINT chk_classes_price CHECK (price >= 0),
  CONSTRAINT fk_classes_instructor FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_classes_start_time (start_time),
  INDEX idx_classes_instructor (instructor_id)
) ENGINE=InnoDB;

CREATE TABLE class_registrations (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  class_id BIGINT UNSIGNED NOT NULL,
  status ENUM('pending','paid','cancelled') NOT NULL DEFAULT 'pending',
  stripe_payment_intent_id VARCHAR(255) NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_reg_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_reg_class FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
  UNIQUE KEY uk_registration_user_class (user_id, class_id),
  UNIQUE KEY uk_registration_payment_intent (stripe_payment_intent_id),
  INDEX idx_reg_class_status (class_id, status),
  INDEX idx_reg_user_status (user_id, status)
) ENGINE=InnoDB;

CREATE TABLE payments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  class_id BIGINT UNSIGNED NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  stripe_payment_intent_id VARCHAR(255) NOT NULL,
  status ENUM('paid','pending','failed','refunded') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT chk_payments_amount CHECK (amount >= 0),
  CONSTRAINT fk_pay_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_pay_class FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
  UNIQUE KEY uk_payments_payment_intent (stripe_payment_intent_id),
  INDEX idx_payments_user_created (user_id, created_at),
  INDEX idx_payments_class_created (class_id, created_at),
  INDEX idx_payments_status_created (status, created_at)
) ENGINE=InnoDB;

INSERT INTO users (name, email, password, role)
VALUES (
  'System Admin',
  'admin@luxfit.com',
  '$2y$10$4S1mPj4hA9VTLfF8n2uI6ONk4A2R8f16raI9bL5Xn3WJ5wy2CsIs2',
  'admin'
)
ON DUPLICATE KEY UPDATE email = VALUES(email);
