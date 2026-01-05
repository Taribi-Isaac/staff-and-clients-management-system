-- SQL to add 'quote' to invoices type enum in production
-- Run this in phpMyAdmin or via MySQL command line

-- Make sure you're connected to raslorde_admin database first!

ALTER TABLE `invoices` MODIFY COLUMN `type` ENUM('invoice', 'receipt', 'quote') NOT NULL DEFAULT 'invoice';






















