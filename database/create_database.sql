-- Script untuk membuat database
-- Jalankan di phpMyAdmin atau MySQL client

CREATE DATABASE IF NOT EXISTS internal_website 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- Pilih database
USE internal_website;

-- Berikan hak akses (opsional, sesuaikan dengan kebutuhan)
-- GRANT ALL PRIVILEGES ON internal_website.* TO 'root'@'localhost';
-- FLUSH PRIVILEGES;
