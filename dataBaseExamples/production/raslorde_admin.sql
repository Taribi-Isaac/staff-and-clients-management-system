-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 01, 2025 at 08:57 AM
-- Server version: 8.0.43-cll-lve
-- PHP Version: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `raslorde_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` bigint UNSIGNED NOT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dish_serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kit_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `starlink_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_duration` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_start_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_end_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_address` text COLLATE utf8mb4_unicode_ci,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_details` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `client_name`, `business_name`, `location`, `account_number`, `dish_serial_number`, `kit_number`, `starlink_id`, `subscription_duration`, `subscription_start_date`, `subscription_end_date`, `email`, `password`, `phone`, `service_address`, `account_name`, `card_details`, `status`, `created_at`, `updated_at`) VALUES
(141, 'Franklin Ogan', 'Raslordeck Limited', '10 Ada George opposite Market square', 'ACC-1974599-25862-32', '2DWC235100031385', 'KIT3013215447', '01000000-00000000-00BFCF89', NULL, NULL, NULL, 'frank@raslordeckltd.com', 'Itsj4me@247', NULL, NULL, 'Franklin Ogan', NULL, 'inactive', NULL, NULL),
(142, 'Destiny', 'Raslordeck limited 5', '10 Ada George', 'ACC-3554157-50238-9', '2DWC235000054673', 'KIT303099399', '01000000-00000000-00C06FBF', 'Destiny', NULL, NULL, 'login detail in owner\'s custody', NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(143, 'Franklin Ogan', 'Residential', '3 Circular Road , Presidential Estate', 'ACC-1974599-25862-32a', '2DCP206652805302', 'KIT301322176', '01000000-00000000-0054A0C7', NULL, NULL, NULL, 'frank@raslordeckltd.com', 'Itsj4me@247', '', NULL, 'Franklin Ogan', NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(144, 'Omo-agege-jimmy', 'Country Home', 'Oromumu Delta State', 'ACC-3565090-87338-22', '2DWC234500010451', 'KIT303066392', '01000000-00000000-00B36661', 'omo-agege-jimmy', NULL, NULL, 'link@rodnav.com', '1234567@ab', '08033135915', '10 ada george', 'Raslordeck ltd', 'owners card', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(145, 'Joseph Francis Alagoa', 'Swiss Mabisel', '9 Mabisel avenue off peterodili road', 'ACC-1974599-25862-32', '2DWC223800001758', 'KIT301321811', '01000000-00000000-0031AD37', NULL, NULL, NULL, 'frank@raslordeckltd.com', 'Itsj4me@247', '', NULL, 'Franklin Ogan', NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(146, 'Omo-agege Jimmy', 'Country Home', 'Oromumu Delta State', 'ACC-3580473-48001-8', '2DWC235000030539', 'KIT303007411', '01000000-00000000-00BDBDDD', 'omo-agege-jimmy', NULL, NULL, 'custom@rodnav.com', '1234567@ab', NULL, NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(147, 'Joseph Francis Alagoa', 'Swiss Mabisel', '9 Mabisel avenue off peterodili road', 'ACC-1974599-25862-32b', '2DCP206652800702', 'KIT301322175', '01000000-00000000-0054A78F', NULL, NULL, NULL, 'frank@raslordeckltd.com', 'Itsj4me@247', '', NULL, 'Franklin Ogan', NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(148, 'Sold to someone in Delta state', 'Delta state', 'Oromumu Delta State', 'ACC-3580456-12184-12', '2DWC234600021035', 'KIT303007407', '01000000-00000000-00B36661', 'podesta', NULL, NULL, 'beautybeauty20201@gmail.com', '1234567@ab', '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(149, 'Joseph Francis Alagoa', 'Mabisel Yard', 'World wide road Trans- Amadi port harcourt', 'ACC-1974599-25862-32', '2DCP206171403601', 'KIT301321701', '01000000-00000000-005228DF', NULL, NULL, NULL, 'frank@raslordeckltd.com', 'Itsj4me@247', '', NULL, 'Franklin Ogan', NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(150, 'Raslordeck Limited', 'Raslordeck Limited 10', '10 Ada George', 'ACC-3583840-94900-18', '2DWC234700013363', 'KIT303007244', '01000000-00000000-00BDA7F0', 'sold to someone in DELTA STATE WARRI', NULL, NULL, 'login detail in owner\'s custody', NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(151, 'Joseph Francis Alagoa', 'Mabisel Main office', 'Peter odili Road', 'ACC-1974599-25862-32', '2DCP205567202101', 'KIT301322194', '01000000-00000000-005484F1', NULL, NULL, NULL, 'frank@raslordeckltd.com', 'Itsj4me@247', '', NULL, 'Franklin Ogan', NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(152, 'Chakins institute', 'Raslordeck Limited 11', '10 Ada George', 'ACC-3600865-66517-18', '2DUNI00000195615', 'KIT303082942', '01000000-00000000-007737B1', 'east west road', NULL, NULL, 'inspector@rodnav.com', '1234567@ab', NULL, 'km 4 E - W Rd Mgbuoba', 'Raslordeck ltd', NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(153, 'Joseph Francis Alagoa', 'Residential', 'Obanoban Street, Old GRA port Harcourt', 'ACC-2844698-87203-26', '2DWC224500035548', 'KIT301323725', '01000000-00000000-0051A9EA', NULL, NULL, NULL, 'raslordecklimited@gmail.com', '1234567@JOE', NULL, NULL, 'Alagoa Joe', 'Joseph Francis AlagoaCARD ending in 7163', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(154, 'Chakins institute', 'Raslordeck Limited 12', '10 Ada George', 'ACC-3626194-82434-17', '2DWC23500001740', 'KIT303015597', '01000000-00000000-00ADD601', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(155, 'Hope Ideozu', 'Nemesis', '21 King perekule road  Gra, port harcourt', 'ACC-2990817-17349-25', '2DCP207704406802', 'KIT301323452', '01000000-00000000-00522313', '6 month', '6-Oct', 'March 06 2024', 'deinmo@raslordeckltd.com', '1234567@AB', '', NULL, 'Hope Ideozu', 'HOPE IDEOZU\r\nCARD ending in 9345', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(156, 'Richard', 'Mv Aderinsola', '10 Ada George', 'ACC-3626149-82657-24', '2DUNI000002229022', 'KIT303015600', '01000000-00000000-005E26D7', NULL, NULL, NULL, 'info@nemeraoilandgas.com', '1234567@ab', NULL, NULL, 'Franklin Ogan', 'VISA ending in 7304 (fidelity)Exp: 8/25', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(157, 'Tombra Ebgegi', 'Tomsepet', '12 Orogbum crescent Gra port harcourt', 'ACC-3007853-21507-6', '2DCP208461606601', 'KIT301327755', '01000000-00000000-00525BA3', '1 Month', '2-Oct', 'Novermber 02 2023', 'Changed to owners login details', NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(158, 'Raslordeck Ltd (our office old kit)', 'Raslordeck Limited 15', '10 Ada George', 'ACC-3747215-47671-19', '2DWC234300010170', 'KIT302925574', '01000000-00000000-005E26D7', NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(159, 'Chief Victor Uwane', 'Residential', '40 Nasir Elurufai Guzape Abuja', 'ACC-1974599-25862-32', '2DWC223800003538', 'KIT301322186', '01000000-00000000-0037C60E', '3 Month', '23-Oct', '24-Mar', 'Changed to owners login details', NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(160, 'Raslordeck Limited', 'Raslordeck Limited 15', '10 Ada George', 'ACC-4018284-46501-8', '2DUNI00000341280', 'KIT303264044', '01000000-00000000-0060798B', 'MINIM TONYE AKPAJO', 'login detail in owner\'s custody', '1234567@ab', NULL, NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(161, 'Mr Nathaniel Otie', 'Residential', 'EAGLE ISLAND', '2152986-13564-17', '01000000-00000000-00521e92', 'KIT301324006', '01000000-00000000-00547AE4', '30 days', '2025-06-19', '2025-07-20', 'otienathaniel@gmail.com', '1234567@AB', '08034584147', NULL, 'Franklin Ogan', 'NAT 3480 exp 9/27', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(162, 'Emomotimi George', 'Cherubim road', 'mile 3', 'ACC-4051451-13407-0', '2DWC24300006777', 'KIT303283630', '01000000-00000000-0070A1A8', NULL, 'login detail in owner\'s custody', '1234567@ab', NULL, NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(163, 'Chief matthew offeh', 'Raslordeck Limited 17', '10 Ada George', 'ACC-4027051-98743-15', '2DWC235000048443', 'KIT303282324', '01000000-00000000-00BF53F0', NULL, NULL, NULL, 'mattoffeh@raslordeckltd.com', '1234567@AB', '08033135915', 'gra pahse 2 phc', NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(164, 'Mr Chibuike', 'TMCH', 'GRA PHASE 111', 'ACC-2771563-74366-22', '2DWC223800002016', 'KIT301322048', '01000000-00000000-00314AD2', NULL, NULL, NULL, 'info@tmchgroup.com', NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(165, 'Chike Obiri (Now sold back to us) wisdom osiri', 'pastor wisdom osiri', 'rumumasi junction', 'ACC-4010881-84400-3', '2DUNI00000344660', 'KIT303264049', '01000000-00000000-002cfc20', '30DAYS', '2025-06-18', '2025-07-17', 'obiri@raslordeckltd.com', '1234567@ab', '08033135915', '10 Ada George', 'Raslordeck Ltd', 'ogan fidelity end 1402 exp 07/28', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(166, 'Mr Telema Graham-Douglas', 'HERBERT', '15 Herbert Old Gra', 'ACC-3038098-23279-19', '2DUN100000339436', 'KIT303247199', '01000000-00000000-005AA022', NULL, NULL, NULL, 'tgdouglasss@gmail.com', 'tgd@herbert2025', NULL, NULL, 'ISAAC TARIBI', 'owners card', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(167, 'Peter Odili house', 'Old Gra', NULL, NULL, NULL, NULL, NULL, NULL, 'login detail in owner\'s custody', NULL, NULL, NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(168, 'Nancy Ideozu', 'Residential', '12 Omaduma Rumuola', 'ACC-3023766-88404-16', '2DWC225200007014', 'KIT301372053', '01000000-00000000-003A5317', NULL, 'Novermber', 'December 6th', 'Changed to owners login details', NULL, '', NULL, 'ISAAC TARIBI', NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(169, 'Raslordeck Limited', 'Sold to the Akpajo guy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(170, 'Chinedu Okafor', 'clems 05 house boat', 'abuloma residence', 'ACC-4197708-62692-26', '2DWC234700022000', 'KIT302981339', '01000000-00000000-00BE047B', NULL, NULL, NULL, 'meetmrharry@gmail.com', '1234567@ab', '08033123690', 'abuloma', NULL, 'owner', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(171, 'Mr Collins', 'Office', '21 Total Gospel Road peter Odili', 'ACC-3096675-15896-30', '2DWC224000010230', 'KIT301372451', '01000000-00000000-0037D0AB', NULL, NULL, NULL, 'Changed to owners login details', NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(172, 'Samuel Anya', 'sam.anya@raslordeckltd.com', 'apara link road gra phase II', 'ACC-4398546-63100-14', '2DUNI00000239749', 'KIT303283984', '01000000-00000000-0060670B', 'monthly', '2024-06-15', NULL, 'sammyanya@yahoo.com', '1234567@ab', '08033135915', '10 ada george', 'Raslordeck Ltd', 'Anya Samuel CARD ending in 2107Exp: 02/27', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(173, 'Mr Roland', 'SJ ABED STAR', '310 Danjuma Drive transamadi', 'ACC-3147917-81167-20', '2DCP208144501401', 'KIT301378047', '01000000-00000000-005C5FB2', NULL, NULL, NULL, 'Changed to owners login details', NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(174, 'Mr Eli', 'ENS STAR CATERING', '28 Transamadi Road', 'ACC-3140408-54896-17', '2DCP208371405602', 'KIT301375329', '01000000-00000000-005A2874', NULL, NULL, NULL, 'Changed to owners login details', NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(175, 'Chimuanya Collns Obumene(Glenn Heights)', 'Sea dolphin', 'Plot 11 Peter Odili road', 'ACC-4608364-96049-24', '2DWC235000012482', 'KIT302952040', '01000000-00000000-00575E23', NULL, NULL, NULL, 'mvseadolphin@rodnav.com', '1234567@AB', NULL, '10 Ada george', 'cHIMUANYA COLLI OBUMENE', 'Clinton Tonte LawsonMasterCard ending in 8546', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(176, 'MR EBI SPIFF', 'GREEN VILLA HOTEL', 'Okaka behind Airforce building Bayelsa', 'ACC-3135576-49568-27', '2DCP208591406102', 'KIT301375328', '01000000-00000000-00545AA0', '30DAYS', '2024-08-09', '2025-09-08', 'greenvillaresidence@gmail.com', 'Residence@2025', '08056263611   bongus', 'yenegoa', 'green villa', 'owner', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(177, 'Bonn Boutique', 'BONN BOUTIQUE HOTEL (faulted)', '2 Love close off Aganorlu street', 'ACC-3219507-12534-7', '2DWC230300007358', 'KIT301471684', '01000000-00000000-00545AA0', NULL, NULL, NULL, 'Changed to owners login details', NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(178, 'OSELOKA NELSON ONUBOGU', 'Decoon', 'RUMUEME, PH', 'ACC-4617475-72566-25', '2DWC20400002587', 'KIT303268188', '01000000-00000000-007083D5', NULL, NULL, NULL, 'operations@de-coon.com', '1234567@ab', NULL, '10 ada george', 'oseloka nelson onubogu', NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(179, 'Opus Torubiri', 'Residential', '6 Mbiama Street Old Gra', 'ACC-3313171-97164-11', '2DUNI00000184912', 'KIT302893812', '01000000-00000000-005BBA1E', NULL, NULL, NULL, 'opus@raslordeckltd.com', '1234567@AB', NULL, NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(180, 'Gideon Elele', 'kings Assembly', 'port harcourt', 'ACC-4631943-47079-22', '2DUNI00000286209', 'KIT303249507', '01000000-00000000-00790DEC', 'Gideon Okeke', NULL, NULL, 'support@thekingsassembly.org', '1234567@ab', NULL, '56 Tombia road gra phase 3 phc', NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(181, 'Joseph Francis Alagoa (robot tests offshore)', 'Mabisel Yard', 'Mabisel Yard', 'ACC-3324394-18050-7', '2DWC234000029091', 'KIT302896323', '01000000-00000000-00ADA1AA', NULL, NULL, NULL, 'info@jfaog.com', '1234567@ab', NULL, NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(182, 'Eke Azuka Stanley', 'Mease-Energy', '10 Ada george', 'ACC-4668861-20125-14', '2DWC24010011875', 'KIT303250749', '01000000-00000000-00C244BD', NULL, 'stanley.eke.azuka@mease-energy.com (login in their custody)', NULL, NULL, NULL, '', 'Port Harcourt Aba Road Oyigbo', 'Eke Azuka Stanley', NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(183, 'Joseph Francis Alagoa (robot tests offshore)', 'mabisel  yard', 'Mabisel Yard', 'ACC-3438668-16690-25', '2DWC234600007273', 'KIT302953264', '01000000-00000000-00BDC564', NULL, NULL, NULL, 'mabisel@mabisel.com', '1234567@ab', NULL, '1234567@ab', '(some kits under frank@raslordeckltd.com', NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(184, 'Wordu Henry', 'Residential', '58 Onne street, GRA-------', 'ACC-4804756-8872-28', '2DWC242100009910', 'KIT303913053', '01000000-00000000-0069E4B6', NULL, NULL, NULL, 'bfemsinvestltd@yahoo.com', '1234567@ab', NULL, '2 circular road presidential estate gra phc', NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(185, 'Omo-Agege Jimmy', 'PH Residence', 'Eliozu Road Port Harcourt', 'ACC-3520513-88486-18', '2DCP207138703901', 'KIT301467807', '01000000-00000000-0060DD43', 'JIMMY', NULL, NULL, 'deinmo@rodnav.com', '1234567@ab', NULL, NULL, 'Rachael Purgatorio', NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(186, 'Godslove Ochuko emonina', 'N/A', 'Lagos', 'ACC-4788094-75636-32', '2DWC241800015341', 'KIT303848091', '01000000-00000000-00E01B9E', NULL, NULL, NULL, 'ochulele1@yahoo.com', NULL, NULL, 'house 12 crystal estate off harris drive vgc', NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(187, 'Ojay boat ( patricia beauty) (bill cleared, details handed over to innocent)', 'Raslordeck limited', '10 Ada-George Road, Mgbuoba, Port Harcourt 500102', 'ACC-3747215-47671-19', '2DWC234300010170', 'KIT302925574', '01000000-00000000-00aebd43', NULL, NULL, NULL, 'alekimarine208@gmail.com', '1234567@ab', NULL, NULL, 'Ogan tamuno franklin', 'CARD ending in 7084Exp: 9/25VISA ending in 7304 FIDE', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(188, 'Izu (sweet tooth)', 'Sweet Tooth', 'Garrison', 'ACC-3535671-78651-22', '2DCP206667507102', 'KIT301468412', '01000000-00000000-005D573E', NULL, NULL, NULL, 'logindetailinownercustody@yahoo.com', '1234567@ab', NULL, NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(189, 'Villa Tusk Hotel 1', 'N/A', 'Orogbum, Port Harcourt', 'ACC-4904907-33128-15', '2DWC242300021062', 'KIT303999207', '01000000-00000000-00eda732', NULL, NULL, NULL, 't.villa.2025@gmail.com', 'Taribi4YOU', NULL, NULL, 'stella chichi', 'Omeke Ebere JaneCARD ending in 1092', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(190, 'MY First Coffee', 'My First Coffe', '49 Evo Road GRA', 'ACC-3543771-42385-33', '2DCP209037003302', 'KIT301469402', NULL, NULL, NULL, NULL, 'login detail in owner\'s custody', NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(191, 'Jack', 'Platform Express', '49 Crown Estate, Peter Odili', 'ACC-3488877-49786-44', '2DWC234900002377', 'KIT303018820', '01000000-00000000-00BDDDC9', NULL, NULL, NULL, 'info@platform-express.com', '1234567@ab', '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(192, 'Sotonye Isaac', 'Residential', 'Orazi, PH', 'ACC-4714740-63484-17', '2DWC235100032036', 'KIT303252613', '01000000-00000000-00C025B3', 'dillysot@yahoo.com', NULL, NULL, 'sotonyeisaac@raslordeckltd.com', '1234567@2025', '08156666663', '6 harsrup road eligbam street PH', 'stella chichi', 'SOTONYE ISAACMasterCard ending in 8726Exp: 4/28', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(193, 'Mrs Rachael\r\n\r\nnew kit (Not installed yet)', 'Residential', 'Apara street GRA', 'ACC-3543670-41020-0', '2DCP208496401402', 'KIT301468989\r\n\r\nKIT303933908', '01000000-00000000-005D7C83', NULL, NULL, NULL, 'obiekwechidinmagift@gmail.com', NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(194, 'gail hartzenberg', 'Best Western Elomaz, Asaba', 'DBS Rd, Central Area, Asaba,', 'ACC-5080294-58255-18', '2DWC242100002887', 'KIT303933292', '01000000-00000000-00e0837d', NULL, NULL, NULL, 'elomaz2024@gmail.com', '1234567@ab', NULL, NULL, NULL, 'Oberabor Wisdom AjiriCARD ending in 5203', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(195, 'Senator Adole', 'Delta Hospitality ltd', '16 Port Harcourt - Aba Expy, PH', 'ACC-5096795-65931-30', '2DWC235000002459', 'KIT303257924', '01000000-00000000-00beb44b', NULL, NULL, NULL, 'senator.adole@deltahospitalityltd.com', '@AB1234567', NULL, NULL, NULL, 'Senator AdoleCARD ending in 3697', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(196, 'The edge hotel', 'Karolus Christine', 'Tombia Rd, New GRA, Port Harcourt, Rivers, Nigeria', 'ACC-5100311-68499-12', '2DUNI00000339331', 'KIT303253531', '01000000-00000000-005a9ea8', NULL, NULL, NULL, 'christine_plaatjies@yahoo.com', '1234567@ab', NULL, NULL, NULL, 'Karolus ChristineVISA ending in 6725', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(197, 'Gardenia', 'Raslordeck Limited', 'Elelenwo', 'ACC-3549183-48461-21', '2DWC235100017786', 'KIT303091952', '01000000-00000000-00BFBD99', 'Gardenia', NULL, NULL, 'login detail in owner\'s custody', NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(198, 'Akpos Home (formerly Elvis Donkenemo)Akpos marine (inactive for now', 'N/A', 'FGC Abuloma', 'ACC-4804814-16622-11', '2DWC241900019624', 'KIT303913065KIT304005159', '01000000-00000000-00e6a36c', NULL, NULL, NULL, 'elvisstarlink@raslordeckltd.com', '1234567@ab', '07047100010 popla ogan', NULL, NULL, 'Franklin OganCARD ending in 7084 (FCMB)', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(199, 'Emmanuel Georgewill', 'Home kit', 'GRA  TOMBIA EXTENTION', 'ACC-DF-9130584-62437-0', '2DWC241100036645', 'KIT303707804', '01000000-00000000-00d0313b', '30days', '2025-09-25', '2025-10-24', 'emmanuel.george@raslordeckltd.com', '1234567@2025', '08033135915', '10 Ada-George Road', 'emmanuel georgewill', 'owner', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(200, 'Eugene Oki', 'princes NIN', '30 Tombia St, GRA, PH', 'ACC-3275423-11805-6', '2DWC235000002459', 'KIT303257924', '01000000-00000000-00beb44b', '3months', '2023-03-03', '2025-04-02', 'boyo@raslordeckltd.com', '1234567@ab', '09039664821. precious staff', '10 Ada George road,', 'eugene oki', 'ogan franklinVISA ending in 7304 FIDELITY', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(201, 'IZU', 'Raslordeck limited 4', '10 Ada George', 'ACC-3554142-10214-97', '2DWC2351000219882', 'KIT303099406', '01000000-00000000-00C086BA', 'IZU', NULL, NULL, 'logindetailinownercustody@gmail.com', NULL, NULL, NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(202, 'Brawal Oil Services Limited', 'N/A', 'FLT shed 1, Onne port complex, Okrika PORT-HARCOURT Rivers NG', 'ACC-5242431-52096-8', '2DWC241900022976', 'KIT303876984', '01000000-00000000-00e6757b', 'WIFI; PW; brawal@2025-onne', '2025-07-19', '2025-06-20', 'brawaloil@raslordeckltd.com', '1234567@ab', NULL, 'FOT ONNE', 'Okechukwu Opene', 'Franklin OganCARD ending in 2151 zenith Exp: 0828', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(203, 'Ingres', 'N/A', 'Omachi Rd, Rumuodomaya, Rumuokoro 500102, Rivers, Nigeria(installed at Apapa, Lagos)', 'ACC-5243550-35349-13', '2DWC241900006121', 'KIT303933623', '01000000-00000000-00e68251', NULL, NULL, NULL, 'ingres@raslordeckltd.com', '123456@ABCDE', NULL, NULL, NULL, 'Chidiebere AnosikeMasterCard ending in 1391Exp: 8/28', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(204, 'Mrs Sunshine OkaforCountry Home Kit (Inactive for now)', 'Anyanwu Okafor', 'Harmony estate house 3B Road 21 PH', 'ACC-5528371-34548-20', '2DWC242100000987', 'KIT304002672KIT30999166', '01000000-00000000-00E05106', NULL, NULL, NULL, 'sunshineokafor24@gmail.com', 'AB@1234567', NULL, NULL, NULL, 'Anyanwu Okafor CARD ending in 7117', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(205, 'admiral ombu', 'violas court', 'Chief G U Ake Road, Port Harcourt 500102, Rivers, Nigeria', 'ACC-3170663-32253-6', '2DCP208813404101', 'KIT301470780', '01000000-00000000-0060dac7', '1 month', '2025-08-23', '2025-09-22', 'reelchick1@yahoo.com', 'viola@2025.com', '07013331477', 'GU AKE DRIVE GRA PHASE 8', 'timipri ombu', 'EIO investment 4062340001980132', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(206, 'Hano guest house replacement kit (Ikechi Amawhule) (Details in owner\'s custody)', NULL, NULL, NULL, NULL, 'KIT304002205', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(207, 'Marine Derivatives (High Performance) (managed by fieldbase)', NULL, NULL, NULL, NULL, 'KITP00209608', NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(208, 'The Wisdom place church (not in use)', 'Wisdom Osiri', 'Stadium Road (5.1933, 7.9304)', 'ACC-6175456-78582-29', '2DWC242500018366', 'KIT304123187', '01000000-00000000-00f5075c', NULL, NULL, NULL, 'thewisdomplace@raslordeckltd.com', '1234567@2025', NULL, NULL, NULL, NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(209, 'Francis Alagoa', 'Francis Alagoa', 'Port Harcourt', 'ACC-6369347-79243-28', 'KIT4M004592393F7', 'KIT4M004592393F7', 'KIT4M004592393F7', '1 month', NULL, NULL, 'Ogan4frank@gmail.com', '2348033135915', '08033135915', '10 Ada George road Port Harcourt', 'joseph alagoa', NULL, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(210, 'Chimuanya Collns', 'Hatrick', 'Plot 11 Peter Odili road', 'ACC-4609122-78105-10', '2DWC235100007595', 'KIT303144274', '01000000-00000000-00BDC65C', NULL, NULL, NULL, 'mvhatrick@rodnav.com', '1234567@AB', NULL, '10 Ada george', 'cHIMUANYA COLLI OBUMENE', 'Clinton Tonte LawsonMasterCard ending in 8546', 'inactive', NULL, NULL),
(211, 'Chimuanya Collns Obumene', 'Gerton Boat (formerly madam chidinma)', 'tabancy estate eliozu opposite NAF estate', 'ACC-482876-8692', '2DWC24170000', 'KIT303849062', '01000000-00000000-00D5', NULL, NULL, NULL, 'gerton@raslordeckltd.com', '1234567@ab', NULL, '10 Ada george', 'charles tabancy residence', 'Clinton Tonte LawsonMasterCard ending in 8546', 'inactive', NULL, NULL),
(213, 'Mr Nathaniel Otie', 'A and N Engineering', 'GRA', 'ACC-2152986-13564-17', '2DCP205314002701', 'KIT301324006', '01000000-00000000-00521E92', NULL, NULL, NULL, 'anitaotie@gmail.com', 'Otie2009', NULL, NULL, 'Frank Ogan', NULL, 'inactive', NULL, NULL),
(214, 'Gloria Jumbo Gods\'swill (reseller - Alex)', 'Pellican Princess LTD', 'Bonny Island', 'KIT302854783', '2DUNI00000119948', 'KIT302854783', '01000000-00000000-005bbe8b', 'FieldBase', NULL, NULL, 'Info@raslordeckltd.com', 'ggodswilljumbo@gmail.com', NULL, 'Morgan Estate Finima Bonny', NULL, NULL, 'inactive', NULL, NULL),
(216, 'IZU', 'Raslordeck Limited 6', '10 Ada George', 'ACC-3554169-66296-27', '2DUNI00000262580', 'KIT303099392', '01000000-00000000-007A87CI', NULL, NULL, NULL, 'logindetailinowner_scustody@gmail.com', 'login detail in owner\'s custody', NULL, NULL, NULL, NULL, 'inactive', NULL, NULL),
(217, 'Izu (Sweet Tooth)', NULL, '51 Tombia Street GRA', 'ACC-3549286-23680-21', '2DWC235100017786', 'KIT303091961', '01000000-00000000-007AB29D', '1 month', NULL, NULL, 'rarigbodi@yahoo.com', '1234567@ab', NULL, NULL, NULL, NULL, 'inactive', NULL, NULL),
(218, 'Emmanuel Georgewill', 'Cortech oil and gas service', '110 Trans Amadi', 'ACC-3554115-24986-18', '2DWC235000050207', 'KIT303099402', '01000000-00000000-00C074EF', NULL, NULL, NULL, 'login_detailinownercustody@gmail.com', 'George1234567@ab', NULL, NULL, NULL, NULL, 'inactive', NULL, NULL),
(219, 'Richard', 'mv seina', '10 Ada George', 'ACC-3794057-74621-20', '2DUNI00000288585', 'KIT303106954', '01000000-00000000-00580487', '1 month', NULL, NULL, 'mvseina@raslordeckltd.com', '1234567@ab ', NULL, NULL, 'Franklin Ogan', 'VISA ending in 7304 (fidelity) Exp: 8/25', 'inactive', NULL, NULL),
(220, 'Wordu Henry', 'air bnb', 'henry wordu street circular rd presidential estate', 'ACC-4829011-27098-16', '2DWC241700030736', 'KIT303849159', '01000000-00000000-00e08425', NULL, NULL, NULL, 'ivyhomes2024@gmail.com', '1234567@ab       Email password: Taribi4YOU.', '08077391909  stephenie abraham', NULL, NULL, NULL, 'inactive', NULL, NULL),
(221, 'Patricia beauty (active with mr kelly oserigha )', 'kelly oserigah', 'okaki road old house of assembly quaters bayelsa state', 'ACC-5525851-90251-13', '2DWC242100041258', 'KIT304002208', '01000000-00000000-00e5b5ea', '1 month', '2025-06-17', '2025-07-16', 'pbv@raslordeckltd.com', '0000@@@@AAAA', '+2348037109000', 'okaki old assembly quaters yenegoa', NULL, 'FIDELTY BANK  7304 frank', 'inactive', NULL, NULL),
(222, 'Sotonye Isaac', 'Villa Tusk Hotel 2 (Sold to Sotonye Isaac (Disotos))', 'Orogbum, Port Harcourt (installed at 50 king perekule street GRA)', 'ACC-4913939-68617-31', '2DWC242200012024', 'KIT303875551', '01000000-00000000-00e07981', NULL, NULL, NULL, 'sotonyeisaac@rodnav.com', 'Reset@2024.', NULL, NULL, 'stella chichi', 'SOTONYE ISAAC MasterCard ending in 8726 Exp: 4/28', 'inactive', NULL, NULL),
(223, 'Omo-Agege Jimmy', 'PH RESIDENCE', 'Eliozu Road Port Harcourt', 'ACC-3549153-46174-17', '2DWC235000038258', 'KIT303091959', '01000000-00000000-00BE98FB', NULL, NULL, NULL, 'ioagege@gmail.com', 'Zane@Asher1525', NULL, NULL, 'JIMMY', NULL, 'inactive', NULL, NULL),
(224, 'Clinton Lawson', 'Glen Heights', 'No 1 Timothy', '12', '12', '12', '12', '12', NULL, NULL, 'chimuanya.collins@ghogl.com.ng', '1234567@ab', 'clinton.lawson@raslordeckltd.com', NULL, NULL, NULL, 'inactive', NULL, NULL),
(225, 'Glen Heights', 'IVY-KING', 'Port Harcourt', 'KIT304003395', '2DWC242300023182', 'KIT304003395', 'KIT304003395', NULL, NULL, NULL, 'ivy.king@raslordeckltd.com', '1234567@2025', NULL, NULL, NULL, NULL, 'inactive', NULL, NULL),
(226, 'OKORIE BEAUTY', 'OKORIE BEAUTY', '4 Akassa st, Port Harcourt', '1', '4PBA02249803', 'KIT402329927DVS', '0040810b-c0aO581b-187292d1', '1', NULL, NULL, 'okorie@gmail.com', '1234567@ab', NULL, NULL, NULL, NULL, 'inactive', NULL, NULL),
(227, 'VON HUB', 'VON HUB HOTEL AND APARTMENTS', '1 Replenish avenue agalanga finima near NLNG roundabout', '2', '2', '2', '2', '11287355018', NULL, NULL, 'vonhubha@gmail.com', '1234567@ab', '08033079681/07054200018', NULL, NULL, NULL, 'inactive', NULL, NULL),
(228, 'Henry Oge=henekevwe Erayan', 'SOMMETVINE SOLUTIONS', 'Milkah Plaza, along Nta Road Ozuoba, PH', '3', '3', '3', '3', '77573595875', NULL, NULL, 'henryerayen@gmail.com', '1234567@ab', '08130360574', NULL, NULL, NULL, 'inactive', NULL, NULL),
(229, 'CEPET', 'C EPET LYFSTYLE', 'Port Harcourt', '3', '3', '3', '3', '3', NULL, NULL, 'cepetlyfstyle@gamil.com', '1234567@ab', NULL, NULL, NULL, NULL, 'inactive', NULL, NULL),
(230, 'LESODA', 'LESODA OIL AND GAS', 'Port Harcourt', '4', '4', '4', '4', '4', NULL, NULL, 'oilandgas@gmail.com', '1234567@ab', NULL, NULL, NULL, NULL, 'inactive', NULL, NULL),
(231, '4E', '4E LIMITED', 'pearl paradise resort, finima', '5', '5', '5', '5', '29313117586', NULL, NULL, 'investment@eeee.ng', '1234567@2025', '090123152420', NULL, NULL, NULL, 'inactive', NULL, NULL),
(232, 'Kelly Oserigah', 'M3VILLA GUEST HOUSE', 'PALM AVENUE OKAKI ROAD  Yenagoa  Bayelsa state', 'ACC-7287552-65111-15', '2DWC241700011789', 'KIT304188383', '01000000-00000000-00e02c17', '30DAYS', '2025-06-05', '2025-07-04', 'Osarobigshoe@yahoo.co.uk', 'Kelly12345@2025', '08037109000', 'PALM AVENUE OKAKI YENEGOA', 'KELLY OSERIGAH', 'KELLY MC/3154 - EXP/2/28', 'inactive', NULL, NULL),
(233, 'isreal  okoli', 'lucy and jacob apartment', 'presidential estate gra phase 2', 'ACC 7367692-7446-32', '4PBA02249574', 'KIT4023296899BC', '50108e14-c0a0581b-18fa61af', '30DAYS', '2025-05-12', '2025-06-11', 'LJA@RASLORDECKLTD.COM', '1234567@2025', NULL, '13 PRESIDENTIAL ESTATE, GRA PHASE 2', 'FRANKLIN OGAN', 'FIDELTY BANK  7304', 'inactive', NULL, NULL),
(234, 'ANIDAY', 'Aniday Enterprises Limited (Raslordeck Ltd)', '23 Maple street lekki epe expressway', '1', '31186339820', 'KIT402413382SGH', '3', NULL, NULL, NULL, 'Dannysclub74@gmail.com', 'logindetailinownercustody', '07077762487,08033135915', NULL, NULL, NULL, 'inactive', NULL, NULL),
(235, 'chiinedu okafor', 'okuama house boat', 'okuama community', '1111111000000', '2DWC241500001319', 'KIT303748032', '01000000-00000000-00d0ce6c', '1 month', '2025-05-23', '2025-09-15', 'neduci@yahoo.com', '1234567okuama2025', '08033123690', 'okuama community. FRCQ+Fj', 'okafor chinedu', 'chinedu okafor 3471', 'inactive', NULL, NULL),
(236, 'Chief Emo Omerhime', 'FLYING INGOT FOUNDRY LTD', 'rumuekini road by Arden oil filling station', '766211565260-12', '2DWC242100024930', 'KIT303957469', '2DWC242100024930', '1 month', '2025-06-03', '2025-07-02', 'ibibaka@yahoo.com', 'Reset@2025..', '07047100010 popla ogan', 'holifield jetty okuruama', 'ogan franklin', 'fidelity 7304', 'inactive', NULL, NULL),
(237, 'CLINTON LAWSON', 'GLEN HEIGHTS  LTD', 'portharcourt', '2233333300000', '000000003333', 'KIT4M00718084QUP', '000555666777', '30 days', '2025-06-15', '2025-07-07', 'ibibakam9@gmail.com', 'Starlink1234', '07047100030', 'nil', 'mail password  Ibibakam@reset2025', 'nil', 'inactive', NULL, NULL),
(238, 'CLINTON LAWSON', 'glen heights', 'mopol 19 back', '7770762-39738-31', 'HPUNE00000018959', 'KITP00209710', '01000000-00000000-008bba19', '30 DAYS', '2025-06-12', '2025-07-11', 'frank@giwa.cybersfera.tech', '12345678@@@@', '08033135915', NULL, 'MUHAMED KURFI', 'MC 5757 EXP 4/28', 'inactive', NULL, NULL),
(239, 'eddy belonwu / GM', 'lemehol hotels ltd', 'Kilo ebeledike st, off stadium rd phc', 'ACC-8005826-75068-20', '2DWC234700005281', 'KIT302948595', '01000000-00000000-00adb920', '30DAYS', '2025-07-01', '2025-07-31', 'gm@lemeholhotel.com', 'lemehol@1234', '08033135915', 'off stadium rd phc', 'lemehol hotels ltd', 'fidelity visa 7304  frank', 'inactive', NULL, NULL),
(240, 'telema grahamdouglas', NULL, 'GRA PHC', '00000000', '000000000', 'KIT303708229', '000000000', NULL, '2025-07-18', '2025-08-18', 'telemagrahamdouglas@gmail.com', '1234567@telema.com', NULL, 'cherubim road', 'telemangrahamdouglas', 'owner', 'inactive', NULL, NULL),
(241, 'zack awaraka', 'zack awaraka', 'pear garden estate eneka', 'ACC-7469452-83772-29', '00000000', 'KIT303708230', '01000000-00000000-00d019c7', '30DAYS', '2025-07-19', '2025-08-20', 'iykeawaraka@yahoo.com', 'Kemjika@2874', '07034311999', 'pera garden estate eneka', 'zak', 'owner ends 4492', 'inactive', NULL, NULL),
(242, 'CLINTON LAWSON', 'glen heights', 'phc', 'ACC-7770762-39738-31', 'NULL', 'KITP00209610', 'KITP00209610', '30DAYS', '2025-06-12', NULL, 'nictan@raslordeckltd.com', '1234567@2025', '08033135915', 'frank@giwa.cybersfera.tech', NULL, 'frank  dollar / ends 5757 exp 0428', 'inactive', NULL, NULL),
(243, 'pacific retriver', 'glenheights ltd', 'port harcourt', 'null', 'NULL', 'KITP00208508', 'NULL', '500gb', '2025-08-09', '2025-09-08', 'pacific.gerton@raslordeckltd.com', 'Starlink2025', '08033135915', 'marine offshore boat', NULL, 'frank gt dollar card', 'inactive', NULL, NULL),
(244, 'MV Honour', NULL, 'NULL', 'null', 'NULL', 'KITP00208531', 'NULL', 'NULL', NULL, NULL, 'mvhonour@raslordeckltd.com', '00000000', NULL, NULL, NULL, NULL, 'inactive', NULL, NULL),
(245, 'Oak Haven Hotel', NULL, 'NULL', 'NULL', 'NULL', 'SN:KIT402326730P2H', 'NULL', 'NULL', NULL, NULL, 'NULL@CO.COM', '00000000', NULL, NULL, NULL, NULL, 'inactive', NULL, NULL),
(246, 'STARLINK', 'MINI', 'ADA GEORGE', '1', '1', 'KIT4M01782467G9F', '1', '1 month', NULL, NULL, 'frank0@gmail.com', '1234567@ab', NULL, '10 Ada George road Port Harcourt', 'MINI', NULL, 'inactive', NULL, NULL),
(247, 'preye berezi', 'LES ENGINEERING', 'KIAMA BAYELSA', '00000000', '000000', 'KIT402326755H8F', 'PN02534202-502/E', '24 months', '2025-08-27', '2025-09-26', 'kaiama.pb@lesenergy.net', '1234567@ab', '08036612067', 'bayelsa state', '000000', 'les energy', 'inactive', NULL, NULL),
(248, 'RACHEL ONU', 'Residential', '17a CHIEF ANYIEBE STREET ALCON, GBALAJAM, WOJI', '2', '2', 'KIT303933908', '2', 'Market plus - password', NULL, NULL, 'rachelonu@albralalliance.com', '1234567@ab', NULL, 'onu@2025.com', 'Rachel Onu', 'VISA ending 2532 UBA) exp 1028', 'inactive', NULL, NULL),
(249, 'charles tabancy', 'charles gym', 'stadium road phc', 'DF-88964758843624', 'PN 02533002-508/A', 'KIT304027185', '0011001100', '12 MONTHS', '2025-09-06', '2026-02-05', 'gym@raslordeckltd.com', 'charlesgym@2025', NULL, 'phc', 'charles gym', 'frank fidelity 1402', 'inactive', NULL, NULL),
(250, 'engineer ibisiki', 'bond apartments', 'tombia street gra phase 2 porthacourt', 'SL-DF-6837096-80222-61', '4PBA02245638', 'KIT402325890NHV', '50100b99-c410851b-98f6e138', NULL, '2025-09-18', '2026-09-17', 'p.bond@raslordeckltd.com', '1234567@bond2025', '08033135915', '80 tombia street', '240289x650', 'frank zenith end 2151', 'inactive', NULL, NULL),
(251, 'dennis osubu', 'dennis', 'patani delta state 54QQ+8W', 'SL-DF-6829449-93176-76', '2DWC241900028848', 'KIT304027184', '01000000-00000000-005b49e6', '30 days', '2025-09-17', '2025-10-17', 'barigbor200@gmail.com', '@dennis@2025', '08033135915', 'patani delta state', 'anthony barigbor', 'tony zenith ends 3438 exp 1125', 'inactive', NULL, NULL),
(252, 'ememotimi george', 'garnet care', 'abacha road port harcourt', 'SL-DF-7038626-23376-61', '2DWC241100036663', 'KIT303707810', '01000000-00000000-00d05766', '30 days', '2025-10-01', '2025-10-31', 'garnet.care@raslordeckltd.com', '1234567@garnet', '08033135915', '38 sani abacha road', 'ememotimi george', 'zenith 2151 ogan', 'inactive', NULL, NULL),
(253, 'emmanuel ogbonnaya', 'EMMADROT GLOBAL RES.LTD.', 'suncity estate 2 transamadi', 'SL-DF-7079484-15742-66', '2DWC242400000675', 'KIT304026476', '01000000-00000000-00e65e5d', '30 days', '2025-10-03', '2025-11-02', 'emma.ogbonnaya@raslordeckltd.com', '1234567@emma', '08033135915', 'suncity garden estate', 'emmanuel ogbonnaya', 'ogan zenith exp 0828 2151', 'inactive', NULL, NULL),
(254, 'emma geogewill', 'oaks residence', 'dan close gra phase 2', 'SL-DF-7319233-84227-58', '4PBA02616409', 'KIT402681384KQ6', '30989f88-00c2151c-18179bca', '30DAYS', '2025-10-16', '2025-11-15', 'oaks@raslordeckltd.com', 'oaks@2025@12345', '08033135915', 'dan close gra phc', 'franklin ogan', 'fidelity 1402 exp 0728', 'inactive', NULL, NULL),
(255, 'Ebi Egbe', 'monimitchel sports', '1 legion close,off ingbi rd,amarat, yenegoa', 'SL-DF7358320-55827-63', '4PBA02624669', 'KIT402689035K72', '4PBA02624669', '30DAYS', '2025-10-18', '2025-10-17', 'ebi.egbe@raslordeckltd.com', '1234567@2025', '08033135915', 'yenegoa bayelsa', 'ebi egbe', 'ogan fidelity end 1402 exp 07/28', 'inactive', NULL, NULL),
(256, 'Samuel Anya', 'LESAM SUITES', 'Apara street gra 2 phc', '000000', '000000', 'KIT304026485', '000000', '000000', NULL, NULL, 'lesam.suites@raslordeckltd.com', '000000000000', NULL, '00000', '00000', '00000', 'inactive', NULL, NULL),
(257, 'tony osuagwu', 'calaya engineering ltd', 'ordinace road trans amadi', 'SL-DF-7551624-38400-53', '4PBA02244545', 'KIT4023248884JQ', '40700708-c191891b-9862285d', '30 days', '2025-10-26', '2025-11-25', 'calayaengineering@yahoo.co.uk', 'Calaya@2025@', '07083381005 MD', 'info@raslordeckltd.com', 'calaya engineering', 'fidelty ogan ends 1402', 'inactive', NULL, NULL),
(258, 'joyce Nnenda george', 'joyce home kit', 'timothy lane rumuola', '000', '0000', 'KIT303707793', '0000', '30 days', '2025-10-20', '2025-11-19', 'ibejoyce42@yahoo.com', 'Nnedaibe42@', '08033135915', 'rumuola timothy lane', 'joyce nnedaibe', 'visa joyce end 2369 exp 08/28', 'inactive', NULL, NULL),
(259, 'douglas okafor', '2nd dredger.    princes NIN', 'NLNG JETTY AMADI AMA', 'SL-DF-8078472-20191-57', 'M1HT018769975B2', 'KIT4M01780066QCD', '20281184-00f2641c-19a90211', '30 DAYS', '2025-11-13', '2025-11-12', 'Edbusdev@pearlhpw.com', '1234567@2025', '08033135915', 'PORTHARCOURT', '2nd dredger', 'frank fidelity 1402 exp07/28', 'inactive', NULL, NULL),
(260, 'EBINYO TURNER', 'MAVTECH LTD', 'suncity estate 2 transamadi', 'SL-DF-8102971-36218-56', '00000000000', 'KIT403662457Q4Q', '20389384-84c0501c-d9a41644', '30 days', '2025-11-14', '2025-11-13', 'rent@raslordeckltd.com', 'rent1234@2025@', '08033135915', 'phc', 'FRANK', 'fidelity 1402 exp 0728', 'inactive', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dish_serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kit_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `starlink_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_duration` int DEFAULT NULL,
  `subscription_start_date` date DEFAULT NULL,
  `subscription_end_date` date DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_address` text COLLATE utf8mb4_unicode_ci,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `card_details` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive','leave','suspension') COLLATE utf8mb4_unicode_ci NOT NULL,
  `employment_type` enum('full-time','contract','intern') COLLATE utf8mb4_unicode_ci NOT NULL,
  `passport` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_of_origin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `local_government_area` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `home_town` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `residential_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guarantor_1_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_1_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_1_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_1_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_2_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_2_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_2_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_2_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submit_doc_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submit_doc_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submit_doc_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `phone`, `email`, `start_date`, `end_date`, `role`, `status`, `employment_type`, `passport`, `state_of_origin`, `local_government_area`, `home_town`, `residential_address`, `guarantor_1_name`, `guarantor_1_email`, `guarantor_1_phone`, `guarantor_1_address`, `guarantor_2_name`, `guarantor_2_email`, `guarantor_2_phone`, `guarantor_2_address`, `bank_name`, `account_number`, `account_name`, `submit_doc_1`, `submit_doc_2`, `submit_doc_3`, `created_at`, `updated_at`) VALUES
(7, 'MR. SAMUEL', '8146468642', 'testemail1@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'ABIA ', '', 'OZUABAM, AROCHUKWU', 'PIPELINE RUMUNDURU FARM RD, ENEKA PH', 'OKON BENEDICT N.', '', '*09018815440', 'PIPELINE RUMUNDURE, ENEKA', 'URUEJOMA DAVIDSON J.', '', '*09124425898', 'NO.8 NGECHE STR. OGINIGBA', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'MR. TOM', '09060120842, 08083004465', 'testemail2@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'RIVERS', 'GOKANA', '', 'NO.12 AKPAJO ROAD, PH.', 'BONALO BROWN', '', '*08116226107', '29 ANONI STREET  TRANSAMADI OKUVAGU', 'PRINCE SAMUEL', '', '*07067176164', '1 LAS VEGAS RUUIBEKWE ROAD, PH', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'MR. EMMANUEL L.', '8090160902', 'testemail3@gmail.com', '2024-09-01', '2025-03-05', 'Rider', 'inactive', 'full-time', '', 'RIVERS', 'KHANA', 'khana', 'NO. 5 OKOA STREET OFF NTA RUMUOKWUTA, PH.', 'AMADI UCHECHUKWU', NULL, '*07030619677', 'NO. 5 OKOA SREET OFF NTA RUMUOKWUTA, PH.', NULL, NULL, NULL, NULL, 'ZENITH', NULL, NULL, '', '', '', '0000-00-00 00:00:00', '2025-04-23 09:38:36'),
(10, 'MR. PRECIOUS', '7039946589', 'testemail4@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'ABIA ', 'OBINGWA', '', 'NO. 6 AKAR ROAD IWOFE PH', 'ONYEBUCHI JOSHUA A.', '', '*08109323758', 'NO.5 AKAR ROAD, IWOFE', '', '', '', '', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(11, 'MR. VALENTINE ', '8137474627', 'testemail5@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'CROSS-RIVERS', 'ODUKPANI', '', 'NO.22 OHIELEM STR., RUMUOLUMENI PH', 'PATRICIA SORBARI L.', '', '*08071163469', 'PRINCE AWWU, CLOSE 15, HUS 1, RUMUOLUMENI', '', '', '', '', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(12, 'MR. CHIKWUDI', '8106015326', 'testemail6@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'EBONYI', 'ABAKALIKI', '', 'RUMUCHAKARA BY INDOMIE GATE, CHOBA', 'IZUCHUKWU NWANONAKU', '', '*08103794398', 'SDA ROAD, CHOBA', '', '', '', '', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(13, 'MR. SOLOMON', '8152770610', 'testemail7@gmail.com', '2024-01-01', '2026-01-01', 'Rider', 'inactive', 'full-time', '', 'Rivers', 'gh', 'kha', '10 Ada George road Port Harcourt', 'MUYIWA GEORGE', NULL, '*09069011934', 'NO.140 AIRPORT ROAD, IGWURUTA', NULL, NULL, NULL, NULL, 'ZENITH', NULL, NULL, '', '', '', '0000-00-00 00:00:00', '2025-04-23 09:40:36'),
(14, 'MR. BROWN', '8116226107', 'testemail8@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'RIVERS', 'GOKANA', '', 'NO.23 AMONI STREET, AZUABIEB TOWN, TRANS-AMADI, PH.', 'GABRIEL BONALO', '', '*07030289112', 'NO.4 DAKORAMA STREET, AZUABIE TRANS-AMADI, PH.', '', '', '', '', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(15, 'MR LUCKY', '9155857562', 'testemail9@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'RIVERS', 'ETCHE', '', 'NO. 7 MGBUOSIMINI STREET, IWOFE ROAD, RUMUOLUMINI', 'ONYEKACHI NWANKWO', '', '*08037310566', '2ND ERICO STREET IWOFE RUMUOLUMENI', '', '', '', '', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(16, 'MR. THANKGOD', '9073788228', 'testemail10@gmail.com', '2023-01-01', '2025-03-05', 'Rider', 'inactive', 'full-time', '', 'RIVERS', 'KHANA', 'khana', 'NO.5 RUMUOMOI,OFF PSYCHIATRIC RD, RUMUIGBO', 'GOBARI NAMANEE', NULL, '*08075510830', 'NO.5 RUMUOMOI,OFF PSYCHIATRIC RD, RUMUIGBO', NULL, NULL, NULL, NULL, 'ZENITH', NULL, NULL, '', '', '', '0000-00-00 00:00:00', '2025-03-17 07:37:23'),
(17, 'MR. CHIEBUKA', '9074800084', 'testemail11@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'IMO ', 'OBOWO', '', 'N0.55 MARKET ROAD, RUMUOMASI, PH.', 'ONUCHA NKEMAKULAM S.', '', '*08038458796', '30 OMASI ROAD RUMUOMASI PH.', 'VICTOR HENRY', '', '*09151306355', 'NO. 46 MARKET ROAD RUMUOMASI', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(18, 'MR. BRIGHT', '8148520343', 'testemail12@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'AKWA-IBOM', 'ESSIEN UDIM', '', 'NO. 4 OKIS CLOSE PARLLAND ESTATE, OFF PETER ODILI', 'GODWIN PASCHAL', '', '*07038920498', 'NO. 4 OKIS CLOSE PARLLAND ESTATE, OFF PETER ODILI', '', '', '', '', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 'MR. EMEKA', '09139384005, 08162498299', 'testemail13@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'RIVERS', '', '', 'NO.11 EHORO ESTATE OFF SCHOOL ROAD, ELENLEWO', 'PRINCE LENCHI', '', '*07039554388', 'NO.20 PETERSIDEAMADI AMA, TRANSAMADI', 'NWAMENEFU DOUGLAS', '', '*08131267704', '.11 EHORO ESTATE OFF SCHOOL ROAD, ELENLEWO', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(20, 'MR. EMMANUEL U. ', '9165638988', 'testemail14@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'AKWA-IBOM', 'IKOT EKPENE', '', 'B0. 9 OGBOROMA STREET, WOJI, PH.', 'ENU ROBERT OUT', '', '*08103938467', 'NO. 55 WOJI ESTATE OFF WOJI ROAD, PH.', 'CHUKWU MOSES', '', '*07032210126', 'NO. 5 UNITY ESTATE WOJI, PH.', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(21, 'MR. ERNEST', '08133398373, 09021008756', 'testemail15@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'ABIA', 'UKWA WEST', 'AZUMNI', 'N0. 42 WOJI ESTATE BY YKC, PH.', 'IHUMNO FRANK M.', '', '*08069492108', 'NO.1 GRACEGATE DRIVE OFF ONUOKOLO STREET WOJI ESTSTE', '', '', '', '', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'MR. PRINCE', '8038760144', 'testemail16@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'AKWA-IBOM', 'OKOBO', '', 'NO.14 MARSHAL CLOSE, RUMUOGBA', 'FABIAN PETER U.', '', '*07041868565', '101 OGINIGBA PH', 'DANIEL MKPAYANGA', '', '*08037679341', '30 OPARA-OYA STR. OFF RUMOBIAKANI,PH', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(23, 'MR. UCHENNA', '7032385737', 'testemail17@gmail.com', '2024-01-01', '2025-03-05', 'Rider', 'inactive', 'full-time', '', 'ABIA', 'ISUIKWUATO', 'UMUAKWUA', 'ADAMAC ESTATE ENEKA ROAD, PH.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ZENITH', NULL, NULL, '', '', '', '0000-00-00 00:00:00', '2025-03-17 07:38:38'),
(24, 'MR. BENEDICT', '8144076531', 'testemail18@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', '', '', '', 'ADP RUMODUMAYA PH', 'LAWRENCE ONUH,', '', '*08074600291', 'ADP RUMUODUMAYA', '', '', '', '', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(25, 'MR. PROMISE O.', '9139384007', 'testemail19@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'IMO ', 'IKEDURU', '', 'OMUOKU LION GATE ALUU', 'MIRIAN OKORIE', '', '*08117551718', 'OMUOKU LION GATE ALUU', '', '', '', '', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'MR. LLOYD', '8037639207', 'testemail20@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'RIVERS', 'EMUOHA', 'OGAKIRI', 'JARACRAYFISH CLOSE OFF OKOCHA STREET OWHIPA PH ', 'PRISICILLA KAMALU', '', '*08037756492', 'NO. 12 OKORO STREET RUMUOKOCHA CHOBA', 'GODPOWER O. KAMALU', '', '*08037357003', 'JARACRAYFISH CLOSE OFF OKOCHA STREET OWHIPA PH ', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'MR. HOPE UKA', '8038660841', 'testemail21@gmail.com', '2023-01-30', '2025-04-03', 'Rider', 'inactive', 'full-time', '', 'Rivers State', 'abu/odual', 'abua', 'NO.6 IMENWO CLOSE-OFF OBIWALI ROAD', 'CYNTHIA ALEXANORA', NULL, '*09138177322', 'NO.6 IMENWO CLOSE-OFF OBIWALI ROAD', NULL, NULL, NULL, NULL, 'ZENITH', NULL, NULL, '', '', '', '0000-00-00 00:00:00', '2025-04-23 10:53:57'),
(28, 'MR. PROMINENT', '8108240397', 'testemail22@gmail.com', '0000-00-00', '0000-00-00', 'Rider', 'active', 'full-time', '', 'AKWA-IBOM', 'UKANAFUN', '', 'NO. 2 HALLELUYAH STREET, AKER ROAD, RUMUOLUMENI, P.H', 'EUGRO CHRISTAIN', '', '*09032777580', 'AKER ROAD, IWOFE', 'SAMUEL EMGHKINGO', '', '*09078242950', 'OPPOSITE SCHOOL GATE, IWOFE RUMUOLUMENI', 'ZENITH', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'OSSAI CORNELIUS', '08158193595', '0ssaicornel111@gmail.com', '2025-04-28', NULL, 'Rider', 'active', 'full-time', NULL, 'unknown', 'unknown', 'unknown', '16 chuku close off be stret elekhi ph', 'Emmanuel sunday edet', NULL, '09011434357', 'No 11 william street rumukalagbor', 'Lawerence Brown', NULL, '08131858934', 'No. 54 victory road Rumukalagbor', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-23 09:49:06', '2025-04-23 09:49:06'),
(30, 'Paul Aloke', '07070673818', 'testemail33@gmail.com', '2025-04-11', NULL, 'Rider', 'active', 'full-time', NULL, 'Rivers State', 'ikwerre', 'Aluu', 'No. 16 Larry Street, Mgboshimini, beside Agip Estate, ph', 'Joseph Ewa Aloke', NULL, '08105346165', 'Limuchiolu-Aluu ph opposite OPM Hqtrs', 'Raymond pepple', NULL, '09037924865', 'No. 16 Larry Street, Mgboshimini, beside Agip Estate, ph', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-23 10:05:37', '2025-04-23 10:05:37'),
(31, 'Cletus Ebba Onne', '07037962823', 'testemail71@gmail.com', '2025-04-24', NULL, 'Rider', 'active', 'full-time', NULL, 'Cross River', 'Obubra', 'ph', 'No. 3 Obidike Lane Elelenwo school road ph', 'Destine Okafora', NULL, '07025861557', 'N. 22 Nga street Eleme ph', 'Joseph Egba Onne', NULL, '09163595873', 'No. 3 Obidike Lane Elelenwo school road ph', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-23 10:15:55', '2025-04-23 10:15:55'),
(32, 'Prince Ngboki Agbor', '09050920588', 'testemail31@gmail.com', '2024-11-01', NULL, 'Rider', 'active', 'full-time', NULL, 'Cross River', 'Ikom', 'Ikom', '1 Grace gate drive off onuokolo road, woji ph', 'Success Walter', NULL, '07066945552', 'No. 52 emmanuel estate transamadi ph', 'Promise friday', NULL, '08039879572', '110 woji road, woji ph', NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-23 11:27:17', '2025-04-23 11:27:17');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('invoice','receipt','quote') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'invoice',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` bigint UNSIGNED DEFAULT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `terms` text COLLATE utf8mb4_unicode_ci,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `tax_rate` decimal(5,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `status` enum('draft','sent','paid','overdue','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_by` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_number`, `type`, `title`, `client_id`, `client_name`, `client_email`, `client_address`, `invoice_date`, `due_date`, `notes`, `terms`, `subtotal`, `tax_rate`, `tax_amount`, `discount`, `total`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'INV-2025-0001', 'receipt', 'test', 205, 'admiral ombu', 'reelchick1@yahoo.com', 'GU AKE DRIVE GRA PHASE 8', '2025-11-28', '2025-11-29', NULL, NULL, 10000.00, 0.00, 0.00, 0.00, 10000.00, 'draft', 1, '2025-11-28 13:40:07', '2025-11-28 13:40:07'),
(2, 'INV-2025-0002', 'quote', 'test', 249, 'charles tabancy', 'gym@raslordeckltd.com', 'phc', '2025-11-28', '2025-11-29', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 1000.00, 'sent', 4, '2025-11-28 13:51:22', '2025-11-28 14:49:51'),
(3, 'INV-2025-0003', 'invoice', 'test', 205, 'admiral ombu', 'reelchick1@yahoo.com', 'GU AKE DRIVE GRA PHASE 8', '2025-11-28', '2025-11-29', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 1000.00, 'draft', 1, '2025-11-28 14:45:04', '2025-11-28 14:45:04'),
(4, 'QUO-2025-0001', 'quote', 'test', 205, 'admiral ombu', 'reelchick1@yahoo.com', 'GU AKE DRIVE GRA PHASE 8', '2025-11-28', '2025-11-29', NULL, NULL, 1000.00, 0.00, 0.00, 0.00, 1000.00, 'draft', 1, '2025-11-28 14:50:23', '2025-11-28 14:50:23'),
(5, 'RCP-2025-0001', 'receipt', 'december', 202, 'Brawal Oil Services Limited', 'brawaloil@raslordeckltd.com', 'FOT ONNE', '2025-11-28', '2025-11-30', NULL, NULL, 120000.00, 0.00, 0.00, 0.00, 120000.00, 'sent', 2, '2025-11-28 14:55:01', '2025-11-28 14:55:01');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint UNSIGNED NOT NULL,
  `invoice_id` bigint UNSIGNED NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `description`, `quantity`, `unit_price`, `total`, `order`, `created_at`, `updated_at`) VALUES
(1, 1, 'test', 1, 10000.00, 10000.00, 0, '2025-11-28 13:40:07', '2025-11-28 13:40:07'),
(3, 3, 'test', 1, 1000.00, 1000.00, 0, '2025-11-28 14:45:04', '2025-11-28 14:45:04'),
(5, 2, 'sub', 1, 1000.00, 1000.00, 0, '2025-11-28 14:49:51', '2025-11-28 14:49:51'),
(6, 4, 'test', 1, 1000.00, 1000.00, 0, '2025-11-28 14:50:23', '2025-11-28 14:50:23'),
(7, 5, 'starlink sub', 1, 120000.00, 120000.00, 0, '2025-11-28 14:55:01', '2025-11-28 14:55:01');

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `id` bigint UNSIGNED NOT NULL,
  `issue_description` text COLLATE utf8mb4_unicode_ci,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kit_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date` date NOT NULL,
  `status` enum('received','in-progress','resolved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'received',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_20_151045_create_customers_table', 1),
(5, '2025_01_20_152029_create_clients_table', 1),
(6, '2025_01_21_092058_add_primary_key_to_clients_table', 1),
(7, '2025_01_21_122139_add_timestamps_to_clients_table', 1),
(8, '2025_01_21_124708_add_timestamps_to_clients_table', 1),
(9, '2025_01_22_120629_create_issues_table', 1),
(10, '2025_01_23_100136_create_permission_tables', 1),
(11, '2025_01_24_094140_create_staff_table', 1),
(12, '2025_01_24_094204_create_employees_table', 1),
(13, '2025_11_28_105717_create_invoices_table', 2),
(14, '2025_11_28_105722_create_invoice_items_table', 2),
(15, '2025_11_28_150259_add_quote_type_to_invoices_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(1, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 6);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('control@raslordeckltd.com', '$2y$12$C1F.dtSwFzuIlCLM8Ul/5.TncBRXbHa6jN5lOIfFPdvGFeYXXufyi', '2025-11-28 13:58:41'),
('frank@raslordeckltd.com', '$2y$12$k.H.UjJ/TiBlcaAKWZT1d.Y6bdIc1H7HYoPDybBFwZ6u/aV2.9yFS', '2025-11-28 13:46:39');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super-admin', 'web', '2025-01-30 07:28:43', '2025-01-30 07:28:43'),
(2, 'admin', 'web', '2025-01-30 07:28:43', '2025-01-30 07:28:43');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('oKuNXZNxtch40SgA2cSXvypdZupPsi3RPzcmJ199', 1, '143.105.174.187', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTjNKaU9ROWlxSnBtcUdPaElVclJGR3dFc1I5cmZ3QUpzd0Z5dGZJaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHBzOi8vY29udHJvbC5yYXNsb3JkZWNrbHRkLmNvbS9ob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1764574810),
('vj2u6sCq97cdBumECNTYshMzrDYidLS4MVq71eYb', 4, '143.105.174.187', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicmRkVFVYb0lDNjlSenFvN0E1V3FZWmpMTHgzcnBaMjB2OFo4cGtHWSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vY29udHJvbC5yYXNsb3JkZWNrbHRkLmNvbS9pbnZvaWNlcyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==', 1764573957);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive','leave','suspension') COLLATE utf8mb4_unicode_ci NOT NULL,
  `employment_type` enum('full-time','contract','intern') COLLATE utf8mb4_unicode_ci NOT NULL,
  `passport` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_of_origin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `local_government_area` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `home_town` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `residential_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guarantor_1_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_1_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_1_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_1_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_2_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_2_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_2_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `guarantor_2_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submit_doc_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submit_doc_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `submit_doc_3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `role`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin', 'control@raslordeckltd.com', NULL, '$2y$12$/YExeDnbGfdDwOpPL0w5hecGvkluboE1OXKrHE4dxLrvXC8S1anW2', NULL, '2025-01-30 07:28:43', '2025-01-30 07:28:43'),
(2, 'Frank', 'admin', 'frank@raslordeckltd.com', NULL, '$2y$12$RucXLRpyFeLUf8hxLloNSORXGFtwtuDZhhZncF64GKmK3HhR3FBIa', NULL, '2025-01-30 07:35:10', '2025-11-28 14:31:50'),
(3, 'Princess', 'admin', 'princess@raslordeckltd.com', NULL, '$2y$12$1XSRPN6vRJRDeO7dBR5QyOoOIUA43Ve./Fx1.kllUfmrvogPdTLZa', NULL, '2025-01-30 07:36:00', '2025-02-21 09:29:19'),
(4, 'Beauty Kwame', 'admin', 'k.beauty@raslordeckltd.com', NULL, '$2y$12$cD/PKRvKjHKZNuz6ltxJgO9qTAUbahrInDlEwxY6lzWiPcGc/nn8a', NULL, '2025-08-04 14:43:31', '2025-08-04 14:48:22'),
(5, 'David Makinwa', 'admin', 'david@raslordeckltd.com', NULL, '$2y$12$Pxih/f3KpbaAXUaAAw6iZ.bwcrfx4/4ylKEVHy94ABuMd/Cfa/7YS', NULL, '2025-08-11 12:14:50', '2025-08-11 12:14:50'),
(6, 'T. Isaac', 'admin', 't.isaac@raslordeckltd.com', NULL, '$2y$12$or.PLU9Yk1.qGOWCd7rkW.E.5fhlMMmw00w50m/Bf.XFoQ8qUHFKG', NULL, '2025-11-28 14:28:49', '2025-11-28 14:31:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customers_account_number_unique` (`account_number`),
  ADD UNIQUE KEY `customers_email_unique` (`email`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_email_unique` (`email`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_number_unique` (`invoice_number`),
  ADD KEY `invoices_client_id_foreign` (`client_id`),
  ADD KEY `invoices_created_by_foreign` (`created_by`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`);

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_email_unique` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
