-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2026 at 10:24 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `calinga`
--

-- --------------------------------------------------------

--
-- Table structure for table `aics_addresses`
--

CREATE TABLE `aics_addresses` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `house_no` varchar(100) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aics_addresses`
--

INSERT INTO `aics_addresses` (`id`, `application_id`, `house_no`, `barangay`, `city`, `province`, `region`) VALUES
(36, 37, '001 Santol St', 'San Juan', 'Cainta', 'Rizal', 'CALABARZON'),
(37, 38, '001 Santol St', 'San Juan', 'Cainta', 'Rizal', 'CALABARZON'),
(38, 39, 'Block 1 Lot 1, Sampaguita Lane', 'San Andres', 'Cainta', 'Rizal', 'CALABARZON'),
(39, 40, 'Block 2 Lot 1, Acacia Street', 'San Isidro', 'Cainta', 'Rizal', 'CALABARZON'),
(40, 41, 'Block 3 Lot 1, Mabini Street', 'San Juan', 'Cainta', 'Rizal', 'CALABARZON'),
(41, 42, 'Block 4 Lot 1, Ilang-Ilang Lane', 'San Roque', 'Cainta', 'Rizal', 'CALABARZON'),
(42, 43, 'Block 5 Lot 1, Gumamela Street', 'Sto. Domingo', 'Cainta', 'Rizal', 'CALABARZON'),
(43, 44, 'Block 6 Lot 1, Jasmine Lane', 'San Francisco', 'Cainta', 'Rizal', 'CALABARZON'),
(44, 45, 'Block 7 Lot 1, Rosal Street', 'San Rafael', 'Cainta', 'Rizal', 'CALABARZON'),
(45, 46, 'Block 8 Lot 1, Camia Lane', 'San Miguel', 'Cainta', 'Rizal', 'CALABARZON'),
(46, 47, 'Block 9 Lot 1, Sampaguita Street', 'Santa Rosa', 'Cainta', 'Rizal', 'CALABARZON'),
(47, 48, 'Block 10 Lot 1, Acacia Lane', 'San Andres', 'Cainta', 'Rizal', 'CALABARZON'),
(48, 49, 'Block 1 Lot 2, Mabini Lane', 'San Isidro', 'Cainta', 'Rizal', 'CALABARZON'),
(49, 50, 'Block 2 Lot 2, Ilang-Ilang Street', 'San Juan', 'Cainta', 'Rizal', 'CALABARZON'),
(50, 51, 'Block 3 Lot 2, Gumamela Lane', 'San Roque', 'Cainta', 'Rizal', 'CALABARZON'),
(51, 52, 'Block 4 Lot 2, Jasmine Street', 'Sto. Domingo', 'Cainta', 'Rizal', 'CALABARZON'),
(52, 53, 'Block 5 Lot 2, Rosal Lane', 'San Francisco', 'Cainta', 'Rizal', 'CALABARZON'),
(53, 54, 'Block 6 Lot 2, Camia Street', 'San Rafael', 'Cainta', 'Rizal', 'CALABARZON'),
(54, 55, 'Block 7 Lot 2, Sampaguita Lane', 'San Miguel', 'Cainta', 'Rizal', 'CALABARZON'),
(55, 56, 'Block 8 Lot 2, Acacia Street', 'Santa Rosa', 'Cainta', 'Rizal', 'CALABARZON'),
(56, 57, 'Block 9 Lot 2, Mabini Street', 'San Andres', 'Cainta', 'Rizal', 'CALABARZON'),
(57, 58, 'Block 10 Lot 2, Ilang-Ilang Lane', 'San Isidro', 'Cainta', 'Rizal', 'CALABARZON'),
(58, 59, 'Block 1 Lot 3, Gumamela Street', 'San Juan', 'Cainta', 'Rizal', 'CALABARZON'),
(59, 60, 'Block 2 Lot 3, Jasmine Lane', 'San Roque', 'Cainta', 'Rizal', 'CALABARZON'),
(60, 61, 'Block 3 Lot 3, Rosal Street', 'Sto. Domingo', 'Cainta', 'Rizal', 'CALABARZON'),
(61, 62, 'Block 4 Lot 3, Camia Lane', 'San Francisco', 'Cainta', 'Rizal', 'CALABARZON'),
(62, 63, 'Block 5 Lot 3, Sampaguita Street', 'San Rafael', 'Cainta', 'Rizal', 'CALABARZON'),
(63, 64, 'Block 6 Lot 3, Acacia Lane', 'San Miguel', 'Cainta', 'Rizal', 'CALABARZON'),
(64, 65, 'Block 7 Lot 3, Mabini Lane', 'Santa Rosa', 'Cainta', 'Rizal', 'CALABARZON'),
(65, 66, 'Block 8 Lot 3, Ilang-Ilang Street', 'San Andres', 'Cainta', 'Rizal', 'CALABARZON'),
(66, 67, 'Block 9 Lot 3, Gumamela Lane', 'San Isidro', 'Cainta', 'Rizal', 'CALABARZON'),
(67, 68, 'Block 10 Lot 3, Jasmine Street', 'San Juan', 'Cainta', 'Rizal', 'CALABARZON'),
(68, 69, '12 Sampaguita Street', 'San Andres', 'Cainta', 'Rizal', 'CALABARZON'),
(69, 70, '8 Mabini Avenue', 'San Juan', 'Cainta', 'Rizal', 'CALABARZON'),
(70, 71, '24 Narra Lane', 'San Isidro', 'Cainta', 'Rizal', 'CALABARZON'),
(71, 72, '16 Acacia Street', 'San Roque', 'Cainta', 'Rizal', 'CALABARZON'),
(72, 73, '31 Mahogany Road', 'Santa Rosa', 'Cainta', 'Rizal', 'CALABARZON'),
(73, 74, '5 Ilang-Ilang Street', 'Santo Domingo', 'Cainta', 'Rizal', 'CALABARZON'),
(74, 75, '19 Rosal Street', 'San Francisco', 'Cainta', 'Rizal', 'CALABARZON'),
(75, 76, '42 Kalachuchi Street', 'San Nicolas', 'Cainta', 'Rizal', 'CALABARZON'),
(76, 77, '7 Rosalinda Street', 'San Andres', 'Cainta', 'Rizal', 'CALABARZON'),
(77, 78, '55 Bonifacio Street', 'San Juan', 'Cainta', 'Rizal', 'CALABARZON'),
(78, 79, '14 Camia Street', 'San Isidro', 'Cainta', 'Rizal', 'CALABARZON'),
(79, 80, '9 Santan Road', 'San Roque', 'Cainta', 'Rizal', 'CALABARZON'),
(80, 81, '27 Dahlia Street', 'Santa Rosa', 'Cainta', 'Rizal', 'CALABARZON'),
(81, 82, '3 Bonifacio Extension', 'Santo Domingo', 'Cainta', 'Rizal', 'CALABARZON'),
(82, 83, '20 Gumamela Street', 'San Francisco', 'Cainta', 'Rizal', 'CALABARZON'),
(83, 84, '11 Molave Street', 'San Nicolas', 'Cainta', 'Rizal', 'CALABARZON'),
(84, 85, '36 Yakal Lane', 'San Andres', 'Cainta', 'Rizal', 'CALABARZON'),
(85, 86, '18 Acacia Street', 'San Juan', 'Cainta', 'Rizal', 'CALABARZON'),
(86, 87, '62 Sampaguita Street', 'San Isidro', 'Cainta', 'Rizal', 'CALABARZON'),
(87, 88, '4 Narra Lane', 'San Roque', 'Cainta', 'Rizal', 'CALABARZON'),
(88, 89, '29 Mabini Avenue', 'Santa Rosa', 'Cainta', 'Rizal', 'CALABARZON'),
(89, 90, '10 Ilang-Ilang Street', 'Santo Domingo', 'Cainta', 'Rizal', 'CALABARZON'),
(90, 91, '23 Rosal Street', 'San Francisco', 'Cainta', 'Rizal', 'CALABARZON'),
(91, 92, '6 Kalachuchi Street', 'San Nicolas', 'Cainta', 'Rizal', 'CALABARZON'),
(92, 93, '15 Camia Street', 'San Andres', 'Cainta', 'Rizal', 'CALABARZON'),
(93, 94, '38 Santan Road', 'San Juan', 'Cainta', 'Rizal', 'CALABARZON'),
(94, 95, '21 Mahogany Road', 'San Isidro', 'Cainta', 'Rizal', 'CALABARZON'),
(95, 96, '13 Bonifacio Street', 'San Roque', 'Cainta', 'Rizal', 'CALABARZON'),
(96, 97, '47 Gumamela Street', 'Santa Rosa', 'Cainta', 'Rizal', 'CALABARZON'),
(97, 98, '2 Molave Street', 'Santo Domingo', 'Cainta', 'Rizal', 'CALABARZON');

-- --------------------------------------------------------

--
-- Table structure for table `aics_applicants`
--

CREATE TABLE `aics_applicants` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `name_extension` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `age` int(3) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `civil_status` varchar(30) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `monthly_salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aics_applicants`
--

INSERT INTO `aics_applicants` (`id`, `application_id`, `last_name`, `first_name`, `middle_name`, `name_extension`, `phone`, `birthdate`, `age`, `gender`, `civil_status`, `occupation`, `monthly_salary`) VALUES
(36, 37, 'Zabaldan', 'Edward', 'Sadangsal', NULL, '09123123123', '2003-12-30', 22, 'Male', 'Single', 'It Support', 10000.00),
(37, 38, 'Santos', 'Jade', 'Tuto', NULL, '09123123123', '2003-09-22', 23, 'Male', 'Single', 'Mechanic', 10000.00),
(38, 39, 'Del Rosario', 'Mara', '', NULL, '09000000001', '2002-10-23', 24, 'Female', 'widowed', 'vendor', 7500.00),
(39, 40, 'Mercado', 'Lina', '', NULL, '09000000002', '2001-10-23', 25, 'Female', 'separated', 'seamstress', 7875.00),
(40, 41, 'Villanueva', 'Rosa', '', NULL, '09000000003', '2000-10-23', 26, 'Female', 'single', 'food vendor', 8250.00),
(41, 42, 'Manalo', 'Elena', '', NULL, '09000000004', '1999-10-23', 27, 'Female', 'widowed', 'laundry worker', 8625.00),
(42, 43, 'Santiago', 'Nina', '', NULL, '09000000005', '1998-10-23', 28, 'Female', 'separated', 'sales clerk', 9000.00),
(43, 44, 'Bautista', 'Celia', '', NULL, '09000000006', '1997-10-23', 29, 'Female', 'single', 'home-based baker', 9375.00),
(44, 45, 'Navarro', 'Tessa', '', NULL, '09000000007', '1996-10-23', 30, 'Female', 'widowed', 'market vendor', 9750.00),
(45, 46, 'Dizon', 'Alma', '', NULL, '09000000008', '1995-10-23', 31, 'Female', 'separated', 'caregiver', 10125.00),
(46, 47, 'Aquino', 'Joy', '', NULL, '09000000009', '1994-10-23', 32, 'Female', 'single', 'utility worker', 10500.00),
(47, 48, 'Castillo', 'Lourdes', '', NULL, '09000000010', '1993-10-23', 33, 'Female', 'widowed', 'tailor', 10875.00),
(48, 49, 'Salazar', 'Mila', '', NULL, '09000000011', '1992-10-23', 34, 'Female', 'separated', 'canteen aide', 11250.00),
(49, 50, 'Cruz', 'Ana', '', NULL, '09000000012', '1991-10-23', 35, 'Female', 'single', 'online seller', 11625.00),
(50, 51, 'Ramos', 'Bea', '', NULL, '09000000013', '1990-10-23', 36, 'Female', 'widowed', 'housekeeper', 12000.00),
(51, 52, 'Garcia', 'Cora', '', NULL, '09000000014', '1989-10-23', 37, 'Female', 'separated', 'cashier', 12375.00),
(52, 53, 'Flores', 'Dina', '', NULL, '09000000015', '1988-10-23', 38, 'Female', 'single', 'dressmaker', 12750.00),
(53, 54, 'Santos', 'Eva', '', NULL, '09000000016', '1987-10-23', 39, 'Female', 'widowed', 'food stall helper', 13125.00),
(54, 55, 'Reyes', 'Faye', '', NULL, '09000000017', '1986-10-23', 40, 'Female', 'separated', 'care assistant', 13500.00),
(55, 56, 'Mendoza', 'Gina', '', NULL, '09000000018', '1985-10-23', 41, 'Female', 'single', 'home-based seller', 13875.00),
(56, 57, 'Torres', 'Hana', '', NULL, '09000000019', '1984-10-23', 42, 'Female', 'widowed', 'laundry worker', 14250.00),
(57, 58, 'Domingo', 'Ivy', '', NULL, '09000000020', '1983-10-23', 43, 'Female', 'separated', 'production worker', 14625.00),
(58, 59, 'Valdez', 'Kara', '', NULL, '09000000021', '1982-10-23', 44, 'Female', 'single', 'market vendor', 15000.00),
(59, 60, 'Pascual', 'Lea', '', NULL, '09000000022', '1981-10-23', 45, 'Female', 'widowed', 'cook', 15375.00),
(60, 61, 'Soriano', 'Mina', '', NULL, '09000000023', '1980-10-23', 46, 'Female', 'separated', 'sales assistant', 15750.00),
(61, 62, 'Espiritu', 'Nora', '', NULL, '09000000024', '2002-10-23', 24, 'Female', 'single', 'daycare aide', 16125.00),
(62, 63, 'Lazaro', 'Olive', '', NULL, '09000000025', '2001-10-23', 25, 'Female', 'widowed', 'tailor', 16500.00),
(63, 64, 'Fernandez', 'Pia', '', NULL, '09000000026', '2000-10-23', 26, 'Female', 'separated', 'food vendor', 16875.00),
(64, 65, 'Andrada', 'Queenie', '', NULL, '09000000027', '1999-10-23', 27, 'Female', 'single', 'caregiver', 17250.00),
(65, 66, 'Ocampo', 'Rina', '', NULL, '09000000028', '1998-10-23', 28, 'Female', 'widowed', 'home-based baker', 17625.00),
(66, 67, 'Vergara', 'Sela', '', NULL, '09000000029', '1997-10-23', 29, 'Female', 'separated', 'retail assistant', 18000.00),
(67, 68, 'Abad', 'Tina', '', NULL, '09000000030', '1996-10-23', 30, 'Female', 'single', 'seamstress', 18375.00),
(68, 69, 'Natividad', 'Amelia', 'Garcia', NULL, '09170000001', '1989-02-14', 37, 'Female', 'Married', 'Food Vendor', 8500.00),
(69, 70, 'Magno', 'Andres', 'Reyes', NULL, '09170000002', '1978-11-03', 47, 'Male', 'Married', 'Driver', 12500.00),
(70, 71, 'Luna', 'Bianca', 'Santos', NULL, '09170000003', '1996-06-27', 30, 'Female', 'Single', 'Service Crew', 10500.00),
(71, 72, 'Mendoza', 'Carlo', 'Cruz', NULL, '09170000004', '1960-09-18', 66, 'Male', 'Widowed', 'Retired Laborer', 6000.00),
(72, 73, 'Rosales', 'Danica', 'Lim', NULL, '09170000005', '1984-04-09', 42, 'Female', 'Separated', 'Laundry Worker', 7200.00),
(73, 74, 'Marquez', 'Edgar', 'Dela Cruz', NULL, '09170000006', '1972-12-22', 53, 'Male', 'Married', 'Construction Worker', 11000.00),
(74, 75, 'Villareal', 'Farah', 'Lopez', NULL, '09170000007', '1992-08-15', 34, 'Female', 'Single', 'Office Clerk', 14500.00),
(75, 76, 'Dominguez', 'Gabriel', 'Tan', NULL, '09170000008', '1987-01-30', 39, 'Male', 'Married', 'Delivery Rider', 13000.00),
(76, 77, 'Pineda', 'Hazel', 'Ramos', NULL, '09170000009', '1999-05-11', 27, 'Female', 'Single', 'College Student', 0.00),
(77, 78, 'Soriano', 'Ismael', 'Castro', NULL, '09170000010', '1955-03-06', 71, 'Male', 'Married', 'Small Store Owner', 9000.00),
(78, 79, 'Abad', 'Janelle', 'Flores', NULL, '09170000011', '1981-10-24', 44, 'Female', 'Widowed', 'Home-based Worker', 8000.00),
(79, 80, 'Ocampo', 'Kevin', 'Bautista', NULL, '09170000012', '2001-07-19', 25, 'Male', 'Single', 'Part-time Worker', 7000.00),
(80, 81, 'Belen', 'Lourdes', 'Navarro', NULL, '09170000013', '1965-02-02', 61, 'Female', 'Married', 'Canteen Helper', 7800.00),
(81, 82, 'Estrada', 'Manuel', 'Aquino', NULL, '09170000014', '1975-09-12', 51, 'Male', 'Separated', 'Tricycle Driver', 9500.00),
(82, 83, 'Cabrera', 'Nerissa', 'Dizon', NULL, '09170000015', '1990-12-05', 35, 'Female', 'Married', 'Tailor', 9800.00),
(83, 84, 'Villanueva', 'Oscar', 'Pascual', NULL, '09170000016', '1958-06-16', 68, 'Male', 'Widowed', 'Gardener', 5500.00),
(84, 85, 'Guevarra', 'Patricia', 'Mendoza', NULL, '09170000017', '1986-03-21', 40, 'Female', 'Single', 'Cashier', 12000.00),
(85, 86, 'Sarmiento', 'Ramon', 'Delos Santos', NULL, '09170000018', '1968-08-29', 58, 'Male', 'Married', 'Mechanic', 14000.00),
(86, 87, 'Manansala', 'Sabrina', 'Rivera', NULL, '09170000019', '1994-11-08', 31, 'Female', 'Married', 'Sales Associate', 11500.00),
(87, 88, 'Magsino', 'Teodoro', 'Garcia', NULL, '09170000020', '1952-01-17', 74, 'Male', 'Married', 'Pensioner', 6500.00),
(88, 89, 'Salonga', 'Ursula', 'Ramos', NULL, '09170000021', '1983-07-02', 43, 'Female', 'Separated', 'Market Vendor', 8200.00),
(89, 90, 'Del Mundo', 'Victor', 'Santos', NULL, '09170000022', '1979-05-25', 47, 'Male', 'Married', 'Electrician', 15000.00),
(90, 91, 'Alcantara', 'Wendy', 'Cruz', NULL, '09170000023', '1998-09-07', 28, 'Female', 'Single', 'Vocational Trainee', 5000.00),
(91, 92, 'Abella', 'Xavier', 'Lopez', NULL, '09170000024', '1970-04-13', 56, 'Male', 'Married', 'Warehouse Worker', 13500.00),
(92, 93, 'Mendoza', 'Yasmin', 'Reyes', NULL, '09170000025', '1988-10-31', 37, 'Female', 'Married', 'Caregiver', 12500.00),
(93, 94, 'Arceo', 'Zaldy', 'Castillo', NULL, '09170000026', '1963-12-10', 62, 'Male', 'Widowed', 'Repair Technician', 10000.00),
(94, 95, 'Cordero', 'Aileen', 'Flores', NULL, '09170000027', '1991-01-26', 35, 'Female', 'Married', 'Food Service Worker', 10500.00),
(95, 96, 'Santiago', 'Benjamin', 'Aquino', NULL, '09170000028', '1959-07-28', 67, 'Male', 'Married', 'Security Guard', 11500.00),
(96, 97, 'Torres', 'Clarissa', 'Dela Cruz', NULL, '09170000029', '1985-05-04', 41, 'Female', 'Single', 'Household Worker', 9000.00),
(97, 98, 'Panganiban', 'Dante', 'Navarro', NULL, '09170000030', '1976-02-19', 50, 'Male', 'Married', 'Painter', 12500.00);

-- --------------------------------------------------------

--
-- Table structure for table `aics_applications`
--

CREATE TABLE `aics_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `assistance_type` varchar(50) DEFAULT NULL,
  `assistance_subtype` varchar(100) DEFAULT NULL,
  `financial_assistance_type` varchar(50) DEFAULT NULL,
  `cash_amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `ticket_number` varchar(50) DEFAULT NULL,
  `description_of_need` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_notes` text DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  `schedule_time` time DEFAULT NULL,
  `schedule_location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aics_applications`
--

INSERT INTO `aics_applications` (`id`, `user_id`, `assistance_type`, `assistance_subtype`, `financial_assistance_type`, `cash_amount`, `status`, `ticket_number`, `description_of_need`, `created_at`, `admin_notes`, `schedule_date`, `schedule_time`, `schedule_location`) VALUES
(37, 3, 'Multiple', 'FHONA', 'Financial-Medical', 0.00, 'approved', 'AICS-2026-000037', 'Sample', '2026-10-10 02:21:10', '', '2026-10-12', '09:00:00', 'MSWD Office, Cainta'),
(38, 3, 'Multiple', 'FHONA', 'Financial-Medical', 0.00, 'approved', 'AICS-2026-000038', 'Sample', '2026-10-21 03:48:00', '', '2026-10-22', '09:00:00', 'MSWD Office, Cainta'),
(39, 46, 'Multiple', 'Solo Parents', 'Financial-Medical', 0.00, 'pending', NULL, 'Household requests assistance with medical expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(40, 46, 'Multiple', 'Solo Parents', 'Financial-Educational', 0.00, 'pending', NULL, 'Household requests assistance with educational expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(41, 46, 'Multiple', 'Solo Parents', 'Financial-Funeral', 0.00, 'pending', NULL, 'Household requests assistance with funeral expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(42, 46, 'Multiple', 'Solo Parents', 'Financial-Cash Assistance', 0.00, 'pending', NULL, 'Household requests cash assistance for essential needs.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(43, 46, 'Multiple', 'Solo Parents', 'Financial-Transportation', 0.00, 'pending', NULL, 'Household requests assistance with transportation expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(44, 46, 'Multiple', 'Solo Parents', 'Financial-Medical', 0.00, 'pending', NULL, 'Household requests assistance with medical expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(45, 46, 'Multiple', 'Solo Parents', 'Financial-Educational', 0.00, 'pending', NULL, 'Household requests assistance with educational expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(46, 46, 'Multiple', 'Solo Parents', 'Financial-Funeral', 0.00, 'pending', NULL, 'Household requests assistance with funeral expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(47, 46, 'Multiple', 'Solo Parents', 'Financial-Cash Assistance', 0.00, 'pending', NULL, 'Household requests cash assistance for essential needs.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(48, 46, 'Multiple', 'Solo Parents', 'Financial-Transportation', 0.00, 'pending', NULL, 'Household requests assistance with transportation expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(49, 46, 'Multiple', 'Solo Parents', 'Financial-Medical', 0.00, 'pending', NULL, 'Household requests assistance with medical expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(50, 46, 'Multiple', 'Solo Parents', 'Financial-Educational', 0.00, 'pending', NULL, 'Household requests assistance with educational expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(51, 46, 'Multiple', 'Solo Parents', 'Financial-Funeral', 0.00, 'pending', NULL, 'Household requests assistance with funeral expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(52, 46, 'Multiple', 'Solo Parents', 'Financial-Cash Assistance', 0.00, 'pending', NULL, 'Household requests cash assistance for essential needs.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(53, 46, 'Multiple', 'Solo Parents', 'Financial-Transportation', 0.00, 'pending', NULL, 'Household requests assistance with transportation expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(54, 46, 'Multiple', 'Solo Parents', 'Financial-Medical', 0.00, 'pending', NULL, 'Household requests assistance with medical expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(55, 46, 'Multiple', 'Solo Parents', 'Financial-Educational', 0.00, 'pending', NULL, 'Household requests assistance with educational expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(56, 46, 'Multiple', 'Solo Parents', 'Financial-Funeral', 0.00, 'pending', NULL, 'Household requests assistance with funeral expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(57, 46, 'Multiple', 'Solo Parents', 'Financial-Cash Assistance', 0.00, 'pending', NULL, 'Household requests cash assistance for essential needs.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(58, 46, 'Multiple', 'Solo Parents', 'Financial-Transportation', 0.00, 'pending', NULL, 'Household requests assistance with transportation expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(59, 46, 'Multiple', 'Solo Parents', 'Financial-Medical', 0.00, 'pending', NULL, 'Household requests assistance with medical expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(60, 46, 'Multiple', 'Solo Parents', 'Financial-Educational', 0.00, 'pending', NULL, 'Household requests assistance with educational expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(61, 46, 'Multiple', 'Solo Parents', 'Financial-Funeral', 0.00, 'pending', NULL, 'Household requests assistance with funeral expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(62, 46, 'Multiple', 'Solo Parents', 'Financial-Cash Assistance', 0.00, 'pending', NULL, 'Household requests cash assistance for essential needs.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(63, 46, 'Multiple', 'Solo Parents', 'Financial-Transportation', 0.00, 'pending', NULL, 'Household requests assistance with transportation expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(64, 46, 'Multiple', 'Solo Parents', 'Financial-Medical', 0.00, 'pending', NULL, 'Household requests assistance with medical expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(65, 46, 'Multiple', 'Solo Parents', 'Financial-Educational', 0.00, 'pending', NULL, 'Household requests assistance with educational expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(66, 46, 'Multiple', 'Solo Parents', 'Financial-Funeral', 0.00, 'pending', NULL, 'Household requests assistance with funeral expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(67, 46, 'Multiple', 'Solo Parents', 'Financial-Cash Assistance', 0.00, 'pending', NULL, 'Household requests cash assistance for essential needs.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(68, 46, 'Multiple', 'Solo Parents', 'Financial-Transportation', 0.00, 'pending', NULL, 'Household requests assistance with transportation expenses.', '2026-10-23 07:38:01', NULL, NULL, NULL, NULL),
(69, 46, 'Multiple', 'FHONA', 'Financial-Medical', 0.00, 'pending', NULL, 'Requests help with prescribed medicine and follow-up care.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(70, 46, 'Multiple', 'FHONA', 'Financial-Cash Assistance', 0.00, 'pending', NULL, 'Requests cash aid for essential household expenses.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(71, 46, 'Multiple', 'YNSP', 'Financial-Educational', 0.00, 'pending', NULL, 'Requests support for school supplies and enrollment costs.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(72, 46, 'Multiple', 'SC', 'Financial-Medical', 0.00, 'pending', NULL, 'Requests help with medical consultation and maintenance medicine.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(73, 46, 'Multiple', 'FHONA', 'Material-Family Food Packs', 0.00, 'pending', NULL, 'Requests family food assistance during a temporary loss of income.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(74, 46, 'Multiple', 'FHONA', 'Financial-Transportation', 0.00, 'pending', NULL, 'Requests transportation assistance for scheduled hospital visits.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(75, 46, 'Multiple', 'PWD', 'Material-Assistive Device', 0.00, 'pending', NULL, 'Requests support for an assistive device recommended by a care provider.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(76, 46, 'Multiple', 'FHONA', 'Financial-Medical', 0.00, 'pending', NULL, 'Requests help covering diagnostic tests and prescribed treatment.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(77, 46, 'Multiple', 'YNSP', 'Financial-Educational', 0.00, 'pending', NULL, 'Requests educational assistance for required learning materials.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(78, 46, 'Multiple', 'SC', 'Financial-Medical', 0.00, 'pending', NULL, 'Requests assistance for medicine and a scheduled checkup.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(79, 46, 'Multiple', 'Solo Parents', 'Financial-Cash Assistance', 0.00, 'pending', NULL, 'Requests short-term cash support for basic family needs.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(80, 46, 'Multiple', 'YNSP', 'Material-Hygiene Sleeping Kits', 0.00, 'pending', NULL, 'Requests basic hygiene supplies while seeking stable work.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(81, 46, 'Multiple', 'FHONA', 'Financial-Medical', 0.00, 'pending', NULL, 'Requests help with medicine and laboratory expenses.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(82, 46, 'Multiple', 'FHONA', 'Financial-Transportation', 0.00, 'pending', NULL, 'Requests travel assistance to attend a medical appointment.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(83, 46, 'Multiple', 'FHONA', 'Financial-Funeral', 0.00, 'pending', NULL, 'Requests assistance with immediate funeral expenses for a family member.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(84, 46, 'Multiple', 'SC', 'Material-Other Food Items', 0.00, 'pending', NULL, 'Requests food supplies while recovering from an illness.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(85, 46, 'Multiple', 'FHONA', 'Financial-Medical', 0.00, 'pending', NULL, 'Requests help with prescribed treatment costs.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(86, 46, 'Multiple', 'FHONA', 'Psychosocial-Social Work Counseling', 0.00, 'pending', NULL, 'Requests social work counseling and referral to community support.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(87, 46, 'Multiple', 'FHONA', 'Financial-Educational', 0.00, 'pending', NULL, 'Requests assistance for a dependent child education expense.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(88, 46, 'Multiple', 'SC', 'Financial-Medical', 0.00, 'pending', NULL, 'Requests support for maintenance medicine and a clinic visit.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(89, 46, 'Multiple', 'Solo Parents', 'Material-Family Food Packs', 0.00, 'pending', NULL, 'Requests food assistance for her household.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(90, 46, 'Multiple', 'FHONA', 'Financial-Cash Assistance', 0.00, 'pending', NULL, 'Requests temporary financial support for essential expenses.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(91, 46, 'Multiple', 'YNSP', 'Financial-Educational', 0.00, 'pending', NULL, 'Requests help with training fees and required materials.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(92, 46, 'Multiple', 'FHONA', 'Financial-Medical', 0.00, 'pending', NULL, 'Requests assistance for a medical procedure and prescribed medicine.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(93, 46, 'Multiple', 'FHONA', 'Psychosocial-Referral', 0.00, 'pending', NULL, 'Requests referral to appropriate social welfare services.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(94, 46, 'Multiple', 'FHONA', 'Financial-Transportation', 0.00, 'pending', NULL, 'Requests transportation support for a follow-up consultation.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(95, 46, 'Multiple', 'FHONA', 'Financial-Medical', 0.00, 'pending', NULL, 'Requests help with laboratory examinations and medication.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(96, 46, 'Multiple', 'SC', 'Material-Assistive Device', 0.00, 'pending', NULL, 'Requests an assistive device to support daily mobility.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(97, 46, 'Multiple', 'Solo Parents', 'Financial-Cash Assistance', 0.00, 'pending', NULL, 'Requests short-term assistance for household necessities.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL),
(98, 46, 'Multiple', 'FHONA', 'Financial-Funeral', 0.00, 'pending', NULL, 'Requests assistance with funeral and related family expenses.', '2026-10-23 07:57:36', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `aics_representatives`
--

CREATE TABLE `aics_representatives` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `rep_last_name` varchar(50) DEFAULT NULL,
  `rep_first_name` varchar(50) DEFAULT NULL,
  `rep_middle_name` varchar(50) DEFAULT NULL,
  `rep_name_ext` varchar(50) DEFAULT NULL,
  `rep_relationship` varchar(100) DEFAULT NULL,
  `rep_phone` varchar(20) DEFAULT NULL,
  `rep_birthdate` date DEFAULT NULL,
  `rep_age` int(11) DEFAULT NULL,
  `rep_gender` varchar(10) DEFAULT NULL,
  `rep_civil_status` varchar(20) DEFAULT NULL,
  `rep_occupation` varchar(100) DEFAULT NULL,
  `rep_house_no` varchar(50) DEFAULT NULL,
  `rep_barangay` varchar(100) DEFAULT NULL,
  `rep_city` varchar(100) DEFAULT NULL,
  `rep_province` varchar(100) DEFAULT NULL,
  `rep_region` varchar(100) DEFAULT NULL,
  `rep_monthly_salary` decimal(10,2) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `name_ext` varchar(20) DEFAULT NULL,
  `house_no` varchar(100) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `age` int(3) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `civil_status` varchar(30) DEFAULT NULL,
  `monthly_salary` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `date_posted` datetime DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `link`, `date_posted`, `user_id`) VALUES
(6, 'Cash relief assistance for trike drivers', 'The Department of Social Welfare and Development\'s (DSWD) regional field offices are set to start the distribution of cash relief assistance to tricycle drivers affected by the escalating oil prices hike outside NCR.', 'https://www.dswd.gov.ph/more-than-140-payout-sites-ready-for-dswds-cash-relief-assistance-for-trike-drivers-outside-ncr/', '2026-04-07 11:35:00', NULL),
(7, 'Any Govt. ID issues Accepted', 'Asst. Secretary Irene Dumlao, the DSWD spokesperson, said on Thursday (September 17) that DSWD personnel in-charge of social pension payout will accept any valid government-issued ID card other than the National ID.', 'https://www.dswd.gov.ph/any-govt-issued-id-card-accepted-for-social-pension-payout-national-id-not-mandatory-dswd-spox/', '2026-10-05 15:07:22', NULL),
(8, 'DSWD warns public against fake UPLIFT links', 'The Department of Social Welfare and Development (DSWD) warned the public against fake Facebook pages and online posts offering “UPLIFT educational assistance” in exchange for personal information through unauthorized registration links.\r\n\r\nThe social media posts falsely claimed that the Unified Package for Livelihoods, Industry, Food, and Transport (UPLIFT) program provides educational assistance amounting to Php2,000 for elementary, Php3,000 for junior high school, Php5,000 for senior high school, and Php8,000 for college students.', 'https://www.dswd.gov.ph/dswd-warns-public-against-fake-uplift-educational-assistance-registration-links/', '2026-10-05 15:10:05', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `details`, `ip_address`, `created_at`) VALUES
(1, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-04-28 22:27:04'),
(2, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 22:27:23'),
(3, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 22:27:27'),
(4, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 22:27:29'),
(5, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-04-28 22:27:31'),
(6, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 22:27:36'),
(7, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 22:27:37'),
(8, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-04-28 22:27:39'),
(9, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-04-28 22:27:40'),
(10, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-04-28 22:27:41'),
(11, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-04-28 22:27:49'),
(12, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-04-28 22:27:54'),
(13, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 22:27:57'),
(14, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 23:13:13'),
(15, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-04-28 23:13:29'),
(16, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 23:13:33'),
(17, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 23:13:34'),
(18, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-04-28 23:14:26'),
(19, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-04-28 23:14:30'),
(20, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-04-28 23:14:32'),
(21, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 23:14:34'),
(22, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-04-28 23:16:08'),
(23, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-04-28 23:16:10'),
(24, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 23:16:49'),
(25, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-04-28 23:21:27'),
(26, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 23:21:30'),
(27, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 23:21:32'),
(28, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-04-28 23:21:46'),
(29, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-04-28 23:21:48'),
(30, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-04-28 23:21:49'),
(31, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-04-28 23:21:50'),
(32, 3, 'AICS_DECLINE', 'Application #18 declined.', '::1', '2026-04-28 23:21:58'),
(33, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-04-28 23:21:59'),
(34, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 23:22:02'),
(35, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 23:22:06'),
(36, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 23:22:08'),
(37, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 23:22:10'),
(38, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-04-28 23:22:12'),
(39, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-04-28 23:23:39'),
(40, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-04-28 23:23:40'),
(41, 3, 'SOLO_PARENT_APPROVED', 'Application #6 approved. Notes: ', '::1', '2026-04-28 23:23:54'),
(42, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-04-28 23:23:56'),
(43, 31, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-04-28 23:27:38'),
(44, 31, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-04-28 23:27:42'),
(45, 31, 'SOLO_PARENT_APPROVED', 'Application #7 approved. Notes: ', '::1', '2026-04-28 23:27:51'),
(46, 31, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-04-28 23:27:54'),
(47, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-05-02 15:07:29'),
(48, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-05-02 15:07:55'),
(49, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-05-02 15:07:56'),
(50, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-05-02 15:07:58'),
(51, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-05-02 15:07:59'),
(52, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-05-02 15:08:00'),
(53, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-05-02 15:08:58'),
(54, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-05-02 15:09:07'),
(55, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-05-02 15:20:35'),
(56, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-05-02 15:20:44'),
(57, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-05-02 15:20:47'),
(58, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-05-02 15:20:48'),
(59, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-05-02 15:20:50'),
(60, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-05-02 15:20:52'),
(61, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-05-02 15:20:56'),
(62, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-05-02 15:20:59'),
(63, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-05-02 15:21:02'),
(64, 31, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-05-02 15:22:36'),
(65, 31, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-05-02 15:22:41'),
(66, 31, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-05-02 15:22:44'),
(67, 31, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-05-02 15:22:47'),
(68, 31, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-05-02 15:37:31'),
(69, 31, 'AICS_APPROVE', 'Application #1 approved. Ticket: AICS-2026-000001', '::1', '2026-05-02 15:37:52'),
(70, 31, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-05-02 15:37:55'),
(71, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-05-02 15:38:14'),
(72, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-05-02 15:38:17'),
(73, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-05-02 15:38:19'),
(74, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-05-02 15:38:23'),
(75, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-05-02 16:05:28'),
(76, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-05-02 16:05:30'),
(77, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-05-02 16:05:32'),
(78, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-05-02 16:05:39'),
(79, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-05-02 16:18:30'),
(80, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-05-02 16:18:44'),
(81, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-05-02 16:19:05'),
(82, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-05-12 15:15:38'),
(83, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-05-12 15:15:55'),
(84, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-05-12 16:33:38'),
(85, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-05-12 16:33:40'),
(86, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-07-21 19:08:48'),
(87, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-07-21 19:09:14'),
(88, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-07-21 19:09:16'),
(89, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-07-21 19:09:17'),
(90, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-07-21 19:09:19'),
(91, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-07-21 19:09:21'),
(92, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-07-21 19:09:24'),
(93, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-07-21 19:09:41'),
(94, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-07-21 19:09:43'),
(95, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-07-21 19:09:44'),
(96, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-07-21 19:13:34'),
(97, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-07-21 19:29:59'),
(98, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-07-21 19:30:07'),
(99, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-07-21 19:30:47'),
(100, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-07-21 19:30:56'),
(101, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-07-21 19:30:59'),
(102, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-07-21 19:31:00'),
(103, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-07-21 19:31:02'),
(104, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-07-21 19:31:08'),
(105, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-07-21 19:31:13'),
(106, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-07-21 19:31:14'),
(107, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-07-21 19:31:27'),
(108, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-07-21 19:31:28'),
(109, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-07-21 19:31:30'),
(110, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-07-21 19:31:31'),
(111, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-07-21 19:31:33'),
(112, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-07-21 19:39:40'),
(113, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-07-21 19:39:49'),
(114, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-07-21 19:39:50'),
(115, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-07-21 19:39:54'),
(116, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-07-21 19:39:55'),
(117, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-07-21 19:39:57'),
(118, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-07-21 19:39:58'),
(119, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-07-21 19:39:59'),
(120, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-07-21 19:43:12'),
(121, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-07-21 19:43:34'),
(122, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-07-21 19:43:50'),
(123, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-07-21 19:43:52'),
(124, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-07-21 19:51:18'),
(125, 3, 'AICS_APPROVE', 'Application #2 approved. Ticket: AICS-2026-000002', '::1', '2026-07-21 19:53:25'),
(126, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-07-21 19:53:26'),
(127, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-07-21 20:16:57'),
(128, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-07-21 20:17:05'),
(129, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-07-21 20:17:06'),
(130, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-07-21 20:17:08'),
(131, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-07-21 20:17:17'),
(132, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 16:22:12'),
(133, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-08-09 16:29:39'),
(134, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-08-09 16:29:42'),
(135, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-08-09 16:29:43'),
(136, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-08-09 16:29:46'),
(137, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-08-09 16:29:52'),
(138, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-08-09 16:29:59'),
(139, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-08-09 16:31:30'),
(140, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 16:31:38'),
(141, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-08-09 16:32:25'),
(142, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-08-09 16:32:38'),
(143, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 16:38:23'),
(144, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 16:38:24'),
(145, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 16:52:00'),
(146, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 16:52:02'),
(147, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 16:56:02'),
(148, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 16:58:38'),
(149, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 16:58:40'),
(150, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 17:26:56'),
(151, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 18:08:29'),
(152, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 18:08:48'),
(153, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 18:25:50'),
(154, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-08-09 18:25:52'),
(155, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-08-09 18:25:53'),
(156, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-08-09 18:26:02'),
(157, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 18:55:33'),
(158, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 19:00:40'),
(159, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 19:01:47'),
(160, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 19:01:48'),
(161, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 19:01:49'),
(162, 3, 'AICS_APPROVE', 'Application #19 approved. Ticket: AICS-2026-000019', '::1', '2026-08-09 19:05:00'),
(163, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 19:05:01'),
(164, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 19:08:54'),
(165, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-08-09 19:08:58'),
(166, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-08-09 19:53:59'),
(167, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 20:01:03'),
(168, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 20:10:10'),
(169, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-08-09 20:10:12'),
(170, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-08-09 20:10:13'),
(171, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-08-09 20:10:15'),
(172, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-08-09 20:10:27'),
(173, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-08-09 20:10:28'),
(174, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 20:10:41'),
(175, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 20:11:04'),
(176, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 20:11:05'),
(177, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-08-09 20:11:43'),
(178, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 20:56:04'),
(179, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 20:59:11'),
(180, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 21:06:00'),
(181, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 21:13:34'),
(182, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 21:13:41'),
(183, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-09 21:13:42'),
(184, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-18 17:22:11'),
(185, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-08-18 17:22:15'),
(186, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-18 17:35:43'),
(187, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-08-18 17:35:46'),
(188, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-08-18 17:35:47'),
(189, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-08-18 17:35:51'),
(190, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-08-18 17:35:52'),
(191, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-08-18 17:35:53'),
(192, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-08-18 17:35:55'),
(193, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-18 17:36:10'),
(194, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-18 17:36:11'),
(195, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-08-18 17:36:12'),
(196, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-08-18 17:36:16'),
(197, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-08-18 17:36:56'),
(198, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-08-18 17:38:02'),
(199, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-02 11:28:55'),
(200, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-09-02 11:29:00'),
(201, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-02 11:29:56'),
(202, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-09-02 11:29:58'),
(203, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-02 11:30:02'),
(204, 3, 'ACCOUNT_APPROVED', 'User ID: 33', '::1', '2026-09-02 11:30:08'),
(205, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-02 11:30:08'),
(206, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-02 11:39:13'),
(207, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-09-02 11:39:23'),
(208, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-09-02 11:39:38'),
(209, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-02 11:40:53'),
(210, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-09-02 11:40:57'),
(211, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-09-02 11:41:02'),
(212, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-09-02 11:48:32'),
(213, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-09-02 11:48:34'),
(214, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-02 11:48:38'),
(215, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-02 11:48:41'),
(216, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-09-02 11:48:48'),
(217, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-02 12:04:01'),
(218, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-09-02 12:04:07'),
(219, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-09-02 12:04:14'),
(220, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-09-02 12:04:17'),
(221, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-02 12:05:03'),
(222, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-02 12:09:52'),
(223, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-02 12:12:05'),
(224, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-02 12:13:05'),
(225, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-09-02 12:14:07'),
(226, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-09-02 12:16:38'),
(227, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-02 12:43:29'),
(228, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-02 12:44:14'),
(229, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-02 12:44:22'),
(230, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-02 12:48:07'),
(231, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-02 12:48:12'),
(232, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-02 12:48:14'),
(233, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-10 15:44:42'),
(234, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-10 15:44:45'),
(235, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-09-10 15:46:00'),
(236, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-10 15:46:14'),
(237, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-10 15:52:56'),
(238, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-10 15:56:29'),
(239, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-10 15:56:32'),
(240, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 22:50:15'),
(241, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-09-28 22:51:00'),
(242, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-09-28 22:51:06'),
(243, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-28 22:51:10'),
(244, 3, 'ACCOUNT_APPROVED', 'User ID: 35', '::1', '2026-09-28 22:51:29'),
(245, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-28 22:51:35'),
(246, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:08:11'),
(247, 3, 'AICS_APPROVE', 'Application #20 approved. Ticket: AICS-2026-000020', '::1', '2026-09-28 23:08:38'),
(248, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:08:39'),
(249, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-09-28 23:09:33'),
(250, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:09:38'),
(251, 3, 'AICS_APPROVE', 'Application #28 approved. Ticket: AICS-2026-000028', '::1', '2026-09-28 23:10:02'),
(252, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:10:04'),
(253, 3, 'AICS_APPROVE', 'Application #27 approved. Ticket: AICS-2026-000027', '::1', '2026-09-28 23:10:17'),
(254, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:10:18'),
(255, 3, 'AICS_APPROVE', 'Application #26 approved. Ticket: AICS-2026-000026', '::1', '2026-09-28 23:10:31'),
(256, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:10:34'),
(257, 3, 'AICS_APPROVE', 'Application #22 approved. Ticket: AICS-2026-000022', '::1', '2026-09-28 23:10:47'),
(258, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:10:48'),
(259, 3, 'AICS_APPROVE', 'Application #23 approved. Ticket: AICS-2026-000023', '::1', '2026-09-28 23:11:03'),
(260, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:11:04'),
(261, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:12:45'),
(262, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:12:47'),
(263, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:12:48'),
(264, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:12:49'),
(265, 3, 'AICS_APPROVE', 'Application #25 approved. Ticket: AICS-2026-000025', '::1', '2026-09-28 23:13:06'),
(266, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:13:07'),
(267, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:13:35'),
(268, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-09-28 23:13:38'),
(269, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:13:45'),
(270, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:15:53'),
(271, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:17:11'),
(272, 3, 'AICS_APPROVE', 'Application #35 approved. Ticket: AICS-2026-000035', '::1', '2026-09-28 23:17:36'),
(273, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:17:37'),
(274, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:22:54'),
(275, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-09-28 23:23:00'),
(276, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-09-28 23:27:56'),
(277, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-28 23:27:58'),
(278, 3, 'ACCOUNT_APPROVED', 'User ID: 34', '::1', '2026-09-28 23:28:03'),
(279, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-09-28 23:28:03'),
(280, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-09-28 23:31:20'),
(281, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-05 11:01:04'),
(282, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-05 11:01:28'),
(283, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-05 11:01:31'),
(284, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-10-05 11:01:36'),
(285, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-10-05 11:01:41'),
(286, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-10-05 11:01:43'),
(287, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-10-05 11:01:45'),
(288, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-05 11:01:54'),
(289, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-05 11:11:55'),
(290, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-05 11:18:27'),
(291, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-05 11:18:50'),
(292, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-05 11:29:13'),
(293, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-05 11:39:49'),
(294, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-05 11:47:00'),
(295, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-05 12:06:09'),
(296, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-05 13:27:05'),
(297, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-10-05 13:27:15'),
(298, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-10-05 13:36:13'),
(299, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-10-05 14:50:24'),
(300, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-10-05 14:50:30'),
(301, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-10-05 14:54:10'),
(302, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-10-05 14:54:23'),
(303, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-05 15:06:03'),
(304, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-10-05 15:06:06'),
(305, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-10-05 15:06:19'),
(306, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-10-05 15:06:24'),
(307, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-10-05 15:07:22'),
(308, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-10-05 15:10:06'),
(309, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-06 11:55:48'),
(310, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-06 11:55:56'),
(311, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-06 12:16:07'),
(312, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-06 12:16:13'),
(313, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-06 12:16:20'),
(314, 3, 'ACCOUNT_APPROVED', 'User ID: 36', '::1', '2026-10-06 12:16:45'),
(315, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-06 12:16:45'),
(316, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-06 13:00:27'),
(317, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-06 13:00:34'),
(318, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-06 13:00:51'),
(319, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-06 13:01:15'),
(320, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-10-06 13:01:26'),
(321, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-10-06 13:01:46'),
(322, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-10-06 13:01:48'),
(323, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-10-06 13:01:49'),
(324, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-06 13:01:51'),
(325, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-06 13:02:51'),
(326, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-06 13:03:00'),
(327, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-06 13:03:04'),
(328, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-06 13:03:08'),
(329, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-06 13:03:11'),
(330, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-06 13:03:13'),
(331, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-06 13:03:17'),
(332, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-06 13:03:18'),
(333, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-06 13:03:21'),
(334, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-06 13:04:12'),
(335, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-06 13:04:13'),
(336, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-06 13:08:17'),
(337, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-06 13:15:11'),
(338, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-06 13:15:13'),
(339, 3, 'ACCOUNT_REJECTED', 'User ID: 40', '::1', '2026-10-06 13:15:20'),
(340, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-06 13:15:21'),
(341, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-06 13:19:04'),
(342, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-06 13:19:06'),
(343, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-06 13:19:31'),
(344, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-06 13:19:33'),
(345, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-10-06 13:19:37'),
(346, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-06 13:20:59'),
(347, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-06 13:22:21'),
(348, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-06 13:23:22'),
(349, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-06 13:23:23'),
(350, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-10-06 13:23:31'),
(351, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-06 13:23:34'),
(352, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-09 19:48:56'),
(353, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-09 20:33:53'),
(354, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-09 20:34:34'),
(355, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 20:34:39'),
(356, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-09 20:34:40'),
(357, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-09 20:34:41'),
(358, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-09 20:35:47'),
(359, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 20:36:07'),
(360, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-10-09 20:37:16'),
(361, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-10-09 20:37:40'),
(362, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-10-09 20:38:34'),
(363, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-10-09 20:38:59'),
(364, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-10-09 20:39:21'),
(365, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-09 20:39:47'),
(366, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-09 20:49:57'),
(367, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 20:50:01'),
(368, 3, 'SOLO_PARENT_APPROVED', 'Application #10 approved. Notes: ', '::1', '2026-10-09 20:50:42'),
(369, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 20:50:43'),
(370, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-09 20:57:41'),
(371, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 20:58:36'),
(372, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-09 20:59:48'),
(373, 3, 'AICS_APPROVE', 'Application #36 approved. Ticket: AICS-2026-000036', '::1', '2026-10-09 21:00:09'),
(374, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-09 21:00:09'),
(375, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 21:14:37'),
(376, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 21:14:38'),
(377, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 21:25:12'),
(378, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-09 21:45:44'),
(379, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 21:45:46'),
(380, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 21:57:44'),
(381, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 22:19:31'),
(382, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-09 22:19:33'),
(383, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-09 22:20:10'),
(384, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 22:20:37'),
(385, 3, 'SOLO_PARENT_APPROVED', 'Application #11 approved. Notes: ', '::1', '2026-10-09 22:20:45'),
(386, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 22:20:45'),
(387, 3, 'SOLO_PARENT_APPROVED', 'Application #5 approved. Notes: ', '::1', '2026-10-09 22:27:38'),
(388, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 22:27:38'),
(389, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-09 22:29:12'),
(390, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 22:29:15'),
(391, 3, 'SOLO_PARENT_APPROVED', 'Application #12 approved. Notes: ', '::1', '2026-10-09 22:29:33'),
(392, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 22:29:34'),
(393, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 22:53:13'),
(394, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-09 23:05:15'),
(395, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 23:05:16'),
(396, 3, 'SOLO_PARENT_APPROVED', 'Application #13 approved. Notes: ', '::1', '2026-10-09 23:05:26'),
(397, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-09 23:05:27'),
(398, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-10 10:16:28'),
(399, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-10 10:22:19'),
(400, 3, 'AICS_APPROVE', 'Application #37 approved. Ticket: AICS-2026-000037', '::1', '2026-10-10 10:22:38'),
(401, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-10 10:22:43'),
(402, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-13 16:01:09'),
(403, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-13 16:01:14'),
(404, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-13 16:03:48'),
(405, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-13 16:09:01'),
(406, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-10-13 16:09:48'),
(407, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-10-13 16:11:03'),
(408, 3, 'PAGE_ACCESS', 'Tab: announcements', '::1', '2026-10-13 16:12:03'),
(409, 3, 'PAGE_ACCESS', 'Tab: news', '::1', '2026-10-13 16:12:31'),
(410, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-10-13 16:13:03'),
(411, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-13 16:14:01'),
(412, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-13 16:20:23'),
(413, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-13 16:20:25'),
(414, 3, 'ACCOUNT_APPROVED', 'User ID: 41', '::1', '2026-10-13 16:20:30'),
(415, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-13 16:20:31'),
(416, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-13 16:20:47'),
(417, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 11:37:52'),
(418, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-21 11:37:57'),
(419, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 11:38:08'),
(420, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-21 11:46:39'),
(421, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 12:54:51'),
(422, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-21 12:54:54'),
(423, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 12:54:56'),
(424, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 12:55:05'),
(425, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 12:55:14'),
(426, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 12:55:20'),
(427, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 12:55:24'),
(428, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 12:55:35'),
(429, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 13:11:03'),
(430, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 13:11:10'),
(431, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 13:11:17'),
(432, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 13:11:20'),
(433, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 13:11:26'),
(434, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 13:12:00'),
(435, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-21 13:12:03'),
(436, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-21 13:12:17'),
(437, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-21 13:12:27'),
(438, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 13:12:44'),
(439, 3, 'AICS_APPROVE', 'Application #38 approved. Ticket: AICS-2026-000038', '::1', '2026-10-21 13:13:01'),
(440, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-21 13:13:47'),
(441, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-21 13:13:50'),
(442, 3, 'PAGE_ACCESS', 'Tab: locked_accounts', '::1', '2026-10-21 13:14:02'),
(443, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 11:34:58'),
(444, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-23 11:35:02'),
(445, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 11:35:31'),
(446, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 11:48:20'),
(447, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 1', '::1', '2026-10-23 11:48:29'),
(448, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 11:48:29'),
(449, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 2', '::1', '2026-10-23 11:48:35'),
(450, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 11:48:35'),
(451, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 8', '::1', '2026-10-23 11:48:43'),
(452, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 11:48:44'),
(453, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 24', '::1', '2026-10-23 11:48:51'),
(454, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 11:48:51'),
(455, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 3', '::1', '2026-10-23 11:49:37'),
(456, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 11:49:38'),
(457, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 4', '::1', '2026-10-23 11:49:47'),
(458, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 11:49:48'),
(459, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 14', '::1', '2026-10-23 11:49:55'),
(460, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 11:49:56'),
(461, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 11:50:11'),
(462, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 11:51:31'),
(463, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 11:51:34'),
(464, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 11:56:24'),
(465, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 11:59:29'),
(466, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 12:01:25'),
(467, 3, 'SOLO_PARENT_APPLICATION_DELETED', 'Application ID: 13', '::1', '2026-10-23 12:01:54'),
(468, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 12:01:54'),
(469, 3, 'SOLO_PARENT_APPLICATION_DELETED', 'Application ID: 12', '::1', '2026-10-23 12:02:02'),
(470, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 12:02:02'),
(471, 3, 'SOLO_PARENT_APPLICATION_DELETED', 'Application ID: 11', '::1', '2026-10-23 12:02:06'),
(472, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 12:02:06'),
(473, 3, 'SOLO_PARENT_APPLICATION_DELETED', 'Application ID: 10', '::1', '2026-10-23 12:02:15'),
(474, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 12:02:16'),
(475, 3, 'SOLO_PARENT_APPLICATION_DELETED', 'Application ID: 6', '::1', '2026-10-23 12:02:28'),
(476, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 12:02:29'),
(477, 3, 'SOLO_PARENT_APPLICATION_DELETED', 'Application ID: 4', '::1', '2026-10-23 12:02:34'),
(478, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 12:02:34'),
(479, 3, 'SOLO_PARENT_APPLICATION_DELETED', 'Application ID: 1', '::1', '2026-10-23 12:02:37'),
(480, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 12:02:37'),
(481, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:02:44'),
(482, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 5', '::1', '2026-10-23 12:02:50'),
(483, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:02:50'),
(484, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 6', '::1', '2026-10-23 12:02:55'),
(485, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:02:56'),
(486, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 7', '::1', '2026-10-23 12:03:21'),
(487, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:03:21'),
(488, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 9', '::1', '2026-10-23 12:03:26'),
(489, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:03:26'),
(490, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 10', '::1', '2026-10-23 12:03:31'),
(491, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:03:31'),
(492, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 11', '::1', '2026-10-23 12:03:37'),
(493, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:03:37'),
(494, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 12', '::1', '2026-10-23 12:03:43'),
(495, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:03:44'),
(496, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 13', '::1', '2026-10-23 12:03:48'),
(497, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:03:48'),
(498, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 15', '::1', '2026-10-23 12:03:52'),
(499, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:03:53'),
(500, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 16', '::1', '2026-10-23 12:04:01'),
(501, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:04:02'),
(502, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 17', '::1', '2026-10-23 12:04:07'),
(503, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:04:08'),
(504, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 20', '::1', '2026-10-23 12:04:12'),
(505, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:04:12'),
(506, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 21', '::1', '2026-10-23 12:04:27'),
(507, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:04:27'),
(508, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 22', '::1', '2026-10-23 12:04:33'),
(509, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:04:33'),
(510, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 23', '::1', '2026-10-23 12:04:40'),
(511, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:04:41'),
(512, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 25', '::1', '2026-10-23 12:04:46'),
(513, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:04:46'),
(514, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 36', '::1', '2026-10-23 12:05:02'),
(515, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:05:02'),
(516, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 33', '::1', '2026-10-23 12:05:20'),
(517, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:05:20'),
(518, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 32', '::1', '2026-10-23 12:05:28'),
(519, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:05:28'),
(520, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 34', '::1', '2026-10-23 12:05:47'),
(521, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:05:47'),
(522, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 29', '::1', '2026-10-23 12:06:07'),
(523, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:06:07'),
(524, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 26', '::1', '2026-10-23 12:06:11'),
(525, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:06:12'),
(526, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 27', '::1', '2026-10-23 12:06:15'),
(527, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:06:16'),
(528, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 28', '::1', '2026-10-23 12:06:21'),
(529, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:06:21'),
(530, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 30', '::1', '2026-10-23 12:06:28'),
(531, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:06:28'),
(532, 3, 'AICS_APPLICATION_DELETED', 'Application ID: 31', '::1', '2026-10-23 12:06:30'),
(533, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:06:31'),
(534, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 12:07:32'),
(535, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:07:40'),
(536, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-23 12:07:45'),
(537, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-23 12:11:38'),
(538, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 12:53:51'),
(539, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-23 12:53:54'),
(540, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-23 12:54:45'),
(541, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-23 12:55:21'),
(542, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-10-23 12:56:13'),
(543, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-23 12:56:53'),
(544, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-23 12:57:06'),
(545, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-23 12:57:44'),
(546, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-23 12:58:05'),
(547, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-23 12:58:26'),
(548, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 12:58:39'),
(549, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-23 12:58:40'),
(550, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-10-23 12:59:03'),
(551, 3, 'PAGE_ACCESS', 'Tab: audit_logs', '::1', '2026-10-23 12:59:04'),
(552, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-23 12:59:06'),
(553, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-23 12:59:14'),
(554, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-23 13:02:07'),
(555, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-23 13:04:14'),
(556, 3, 'PAGE_ACCESS', 'Tab: request_tracker', '::1', '2026-10-23 13:48:11'),
(557, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-23 13:48:42'),
(558, 3, 'ACCOUNT_APPROVED', 'User ID: 44', '::1', '2026-10-23 13:48:54'),
(559, 3, 'PAGE_ACCESS', 'Tab: pending_accounts', '::1', '2026-10-23 13:48:55'),
(560, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 14:09:30'),
(561, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 14:22:53'),
(562, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 14:23:04'),
(563, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 14:45:54'),
(564, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 14:54:01'),
(565, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 14:57:58'),
(566, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 15:18:19'),
(567, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 15:27:15'),
(568, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 15:27:43'),
(569, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 15:27:51'),
(570, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 15:28:09'),
(571, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 15:28:20'),
(572, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 15:28:27'),
(573, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 15:28:41'),
(574, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 15:28:51'),
(575, 3, 'PAGE_ACCESS', 'Tab: reports', '::1', '2026-10-23 15:28:57'),
(576, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 15:30:32'),
(577, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 15:36:00'),
(578, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 15:37:34'),
(579, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 15:39:40'),
(580, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 15:41:37'),
(581, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 15:46:06'),
(582, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 15:46:31'),
(583, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 15:58:51'),
(584, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 15:58:57'),
(585, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 16:15:13'),
(586, 3, 'PAGE_ACCESS', 'Tab: solo_parent', '::1', '2026-10-23 16:22:09'),
(587, 3, 'PAGE_ACCESS', 'Tab: aics', '::1', '2026-10-23 16:22:30');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `date_posted` datetime DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `content`, `image`, `date_posted`, `user_id`) VALUES
(12, 'Distributing Cash assistant of solo parents', 'On April 6, 2026 Mswd Distribute cash assistance of solo parents in Barangay San Juan', 'image_b9fbf548d43feae5.png', '2026-04-07 11:41:28', NULL),
(13, 'Solo Parent Seminar', 'Last March 31, 2026 MSWD conduct a seminar for solo parent, this happened in People\'s Center Cainta', 'image_b97a459f0e367ec0.png', '2026-04-07 11:48:42', NULL),
(14, 'Rice Subsidy Distribution', 'On October 7 MSWD Distribute the 2nd tranche of rice subsidy', 'image_29f17102724c6702.jpg', '2026-10-05 14:54:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT 'info',
  `target_url` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `target_url`, `is_read`, `created_at`) VALUES
(1, 3, 'New AICS Application Submitted', 'A new AICS application (#11) has been submitted by Isnek Dragon. Please review.', 'application', NULL, 1, '2026-03-11 11:13:49'),
(2, 3, 'New AICS Application Submitted', 'A new AICS application (#12) has been submitted by edw zaba. Please review.', 'application', NULL, 1, '2026-03-13 06:16:59'),
(3, 3, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', NULL, 1, '2026-03-13 08:36:46'),
(5, 2, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', NULL, 0, '2026-03-13 08:36:46'),
(6, 3, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', NULL, 1, '2026-03-13 08:37:27'),
(8, 2, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', NULL, 0, '2026-03-13 08:37:27'),
(9, 3, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', NULL, 1, '2026-03-13 08:38:22'),
(11, 2, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', NULL, 0, '2026-03-13 08:38:22'),
(12, 3, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', NULL, 1, '2026-03-13 08:41:07'),
(14, 2, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', NULL, 0, '2026-03-13 08:41:07'),
(15, 3, 'AICS Application DECLINED', 'Your AICS application (#8) has been DECLINED.', 'declined', NULL, 1, '2026-03-13 08:42:48'),
(16, 3, 'AICS Application APPROVED', 'Your AICS application (#7) has been APPROVED.', 'approved', NULL, 1, '2026-03-13 08:42:53'),
(17, 3, 'New Solo Parent Application', 'Application #1 submitted by edward zabaldan.', 'solo_parent', NULL, 1, '2026-03-14 10:03:37'),
(18, 3, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #1) has been submitted and is under review.', 'solo_parent', NULL, 1, '2026-03-14 10:03:37'),
(19, 3, 'AICS Application APPROVED', 'Your AICS application (#12) has been APPROVED.', 'approved', NULL, 1, '2026-03-15 09:29:36'),
(20, 2, 'AICS Application APPROVED', 'Your AICS application (#11) has been APPROVED.', 'approved', NULL, 1, '2026-03-15 09:30:45'),
(21, 3, 'AICS Application APPROVED', 'Your AICS application (#10) has been approved. Ticket: AICS-2026-000010.', 'approved', NULL, 1, '2026-03-15 09:38:44'),
(22, 3, 'AICS Application APPROVED', 'Your AICS application (#9) has been approved. Ticket: AICS-2026-000009.', 'approved', NULL, 1, '2026-03-15 09:39:51'),
(23, 3, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #2) has been submitted and is under review.', 'solo_parent', NULL, 1, '2026-03-16 08:13:44'),
(24, 3, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #3) has been submitted and is under review.', 'solo_parent', NULL, 1, '2026-03-16 10:29:29'),
(25, 3, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #1) has been submitted and is under review.', 'solo_parent', NULL, 1, '2026-03-17 10:20:32'),
(26, 3, 'New AICS Application Submitted', 'A new AICS application (#1) has been submitted by Edward Zabaldan. Please review.', 'application', NULL, 1, '2026-03-17 10:27:04'),
(27, 3, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', NULL, 1, '2026-03-17 10:27:04'),
(28, 3, 'New AICS Application Submitted', 'A new AICS application (#2) has been submitted by Thea Zabaldan. Please review.', 'application', NULL, 1, '2026-03-17 11:22:08'),
(29, 3, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', NULL, 1, '2026-03-17 11:22:08'),
(30, 3, 'New AICS Application Submitted', 'A new AICS application (#9) has been submitted by asdasdasdasd ASasadasd. Please review.', 'application', NULL, 1, '2026-03-17 11:50:38'),
(31, 3, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', NULL, 1, '2026-03-17 11:50:38'),
(32, 3, 'New AICS Application Submitted', 'A new AICS application (#10) has been submitted by dddddddddddddd ddddddddd. Please review.', 'application', NULL, 1, '2026-03-17 11:57:46'),
(33, 3, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', NULL, 1, '2026-03-17 11:57:46'),
(34, 3, 'New AICS Application Submitted', 'A new AICS application (#11) has been submitted by dddddddddddddd ddddddddd. Please review.', 'application', NULL, 1, '2026-03-17 11:57:47'),
(35, 3, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', NULL, 1, '2026-03-17 11:57:47'),
(36, 3, 'New AICS Application Submitted', 'A new AICS application (#12) has been submitted by zzzzzzzzz zzzzzzzz. Please review.', 'application', NULL, 1, '2026-03-18 03:44:18'),
(37, 3, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', NULL, 1, '2026-03-18 03:44:18'),
(38, 3, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #4) has been submitted and is under review.', 'solo_parent', NULL, 1, '2026-03-18 05:11:51'),
(39, 3, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #5) has been submitted and is under review.', 'solo_parent', NULL, 1, '2026-03-18 05:40:08'),
(40, 3, 'AICS Application DECLINED', 'Your AICS application (#12) has been declined.', 'declined', NULL, 1, '2026-03-20 05:07:15'),
(41, 3, 'AICS Application APPROVED', 'Your AICS application (#11) has been approved. Ticket: AICS-2026-000011.', 'approved', NULL, 1, '2026-03-20 05:07:28'),
(42, 3, 'Solo Parent Application DECLINED', 'Your Solo Parent application (#1) has been DECLINED.', 'declined', NULL, 1, '2026-03-20 05:21:04'),
(43, 3, 'Solo Parent Application APPROVED', 'Your Solo Parent application (#4) has been APPROVED.', 'approved', NULL, 1, '2026-03-20 05:21:10'),
(44, 3, 'AICS Application APPROVED', 'Your AICS application (#10) has been approved. Ticket: AICS-2026-000010.', 'approved', NULL, 1, '2026-03-20 09:19:46'),
(45, 3, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', '../index.php#announcements', 1, '2026-03-20 10:39:49'),
(47, 2, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', '../index.php#announcements', 1, '2026-03-20 10:39:50'),
(48, 3, 'New AICS Application Submitted', 'A new AICS application (#13) has been submitted by Edward Zabaldan. Please review.', 'application', 'aics_admin_view.php?id=13', 1, '2026-03-20 11:18:13'),
(49, 2, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', 'aics_view.php?id=13', 1, '2026-03-20 11:18:13'),
(50, 3, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #6) has been submitted and is under review.', 'solo_parent', 'solo_parent_view.php?id=6', 1, '2026-03-25 15:26:15'),
(52, 3, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', '../index.php#announcements', 1, '2026-04-07 03:35:00'),
(54, 2, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', '../index.php#announcements', 0, '2026-04-07 03:35:00'),
(55, 3, 'New AICS Application Submitted', 'A new AICS application (#14) has been submitted by LeBron James. Please review.', 'application', 'aics_admin_view.php?id=14', 1, '2026-04-07 11:58:34'),
(56, 2, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', 'aics_view.php?id=14', 0, '2026-04-07 11:58:34'),
(57, 2, 'AICS Application APPROVED', 'Your AICS application (#14) has been approved. Ticket: AICS-2026-000014.', 'approved', 'aics_view.php?id=14', 0, '2026-04-07 11:59:22'),
(58, 2, 'AICS Application APPROVED', 'Your AICS application (#14) has been approved. Ticket: AICS-2026-000014.', 'approved', 'aics_view.php?id=14', 0, '2026-04-07 11:59:32'),
(59, 3, 'New AICS Application Submitted', 'A new AICS application (#15) has been submitted by Edward Zabaldan. Please review.', 'application', 'aics_admin_view.php?id=15', 1, '2026-04-07 12:10:37'),
(60, 2, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', 'aics_view.php?id=15', 0, '2026-04-07 12:10:37'),
(61, 2, 'AICS Application APPROVED', 'Your AICS application (#15) has been approved. Ticket: AICS-2026-000015.', 'approved', 'aics_view.php?id=15', 0, '2026-04-07 12:10:59'),
(62, 2, 'AICS Application APPROVED', 'Your AICS application (#13) has been approved. Ticket: AICS-2026-000013.', 'approved', 'aics_view.php?id=13', 0, '2026-04-07 12:14:22'),
(63, 3, 'New AICS Application Submitted', 'A new AICS application (#16) has been submitted by Jade Santos. Please review.', 'application', 'aics_admin_view.php?id=16', 1, '2026-04-07 12:39:27'),
(64, 2, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', 'aics_view.php?id=16', 0, '2026-04-07 12:39:27'),
(65, 2, 'AICS Application APPROVED', 'Your AICS application (#16) has been approved. Ticket: AICS-2026-000016. Schedule: Apr 8, 2026 9:00 AM.', 'approved', 'aics_view.php?id=16', 0, '2026-04-07 12:40:51'),
(66, 3, 'New AICS Application Submitted', 'A new AICS application (#17) has been submitted by Spongebob Squarepants. Please review.', 'application', 'aics_admin_view.php?id=17', 1, '2026-04-07 12:57:56'),
(67, 2, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', 'aics_view.php?id=17', 0, '2026-04-07 12:57:56'),
(68, 2, 'AICS Application APPROVED', 'Your AICS application (#17) has been approved. Ticket: AICS-2026-000017. Schedule: Apr 8, 2026 9:00 AM. You may submit a new AICS request again after Jun 7, 2026.', 'approved', 'aics_view.php?id=17', 0, '2026-04-07 12:58:24'),
(69, 3, 'AICS Application DECLINED', 'Your AICS application (#9) has been declined.', 'declined', 'aics_view.php?id=9', 1, '2026-04-28 12:00:46'),
(70, 3, 'New AICS Application Submitted', 'A new AICS application (#18) has been submitted by Melvin Sangalang. Please review.', 'application', 'aics_admin_view.php?id=18', 1, '2026-04-28 12:33:02'),
(72, 3, 'AICS Application DECLINED', 'Your AICS application (#5) has been declined.', 'declined', 'aics_view.php?id=5', 1, '2026-04-28 14:27:48'),
(73, 3, 'AICS Application DECLINED', 'Your AICS application (#8) has been declined.', 'declined', 'aics_view.php?id=8', 1, '2026-04-28 14:27:54'),
(75, 3, 'Solo Parent Application APPROVED', 'Your Solo Parent application (#6) has been APPROVED.', 'approved', 'solo_parent_view.php?id=6', 1, '2026-04-28 15:23:54'),
(78, 3, 'AICS Application APPROVED', 'Your AICS application (#1) has been approved. Ticket: AICS-2026-000001. Schedule: May 4, 2026 9:00 AM. You may submit a new AICS request again after Jul 2, 2026.', 'approved', 'aics_view.php?id=1', 1, '2026-05-02 07:37:52'),
(79, 3, 'AICS Application APPROVED', 'Your AICS application (#2) has been approved. Ticket: AICS-2026-000002. Schedule: Jul 22, 2026 9:00 AM. You may submit a new AICS request again after Sep 21, 2026.', 'approved', 'aics_view.php?id=2', 1, '2026-07-21 11:53:25'),
(80, 3, 'New AICS Application Submitted', 'A new AICS application (#19) has been submitted by Melvin Sangalang. Please review.', 'application', 'aics_admin_view.php?id=19', 1, '2026-08-09 10:37:54'),
(83, 3, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #8) has been submitted and is under review.', 'solo_parent', 'solo_parent_view.php?id=8', 1, '2026-08-09 11:08:47'),
(84, 3, 'New AICS Application Submitted', 'A new AICS application (#26) has been submitted by Melvin Sangalang. Please review.', 'application', 'aics_admin_view.php?id=26', 1, '2026-08-09 12:51:20'),
(85, 34, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', 'aics_view.php?id=26', 0, '2026-08-09 12:51:20'),
(86, 3, 'New AICS Application Submitted', 'A new AICS application (#27) has been submitted by Melvin Sangalang. Please review.', 'application', 'aics_admin_view.php?id=27', 1, '2026-08-09 12:51:21'),
(87, 34, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', 'aics_view.php?id=27', 0, '2026-08-09 12:51:21'),
(88, 3, 'New AICS Application Submitted', 'A new AICS application (#32) has been submitted by Melvin Sangalang. Please review.', 'application', 'aics_admin_view.php?id=32', 1, '2026-08-09 12:55:42'),
(89, 34, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', 'aics_view.php?id=32', 0, '2026-08-09 12:55:42'),
(90, 3, 'New AICS Application Submitted', 'A new AICS application (#33) has been submitted by Melvin Sangalang. Please review.', 'application', 'aics_admin_view.php?id=33', 1, '2026-08-09 13:04:31'),
(91, 34, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', 'aics_view.php?id=33', 0, '2026-08-09 13:04:31'),
(92, 3, 'New AICS Application Submitted', 'A new AICS application (#34) has been submitted by Melvin Sangalang. Please review.', 'application', 'aics_admin_view.php?id=34', 1, '2026-08-09 13:04:38'),
(93, 34, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', 'aics_view.php?id=34', 0, '2026-08-09 13:04:38'),
(94, 34, 'AICS Application APPROVED', 'Your AICS application (#20) has been approved. Ticket: AICS-2026-000020. Schedule: Sep 29, 2026 9:00 AM. You may submit a new AICS request again after Nov 28, 2026.', 'approved', 'aics_view.php?id=20', 0, '2026-09-28 15:08:38'),
(95, 34, 'AICS Application APPROVED', 'Your AICS application (#28) has been approved. Ticket: AICS-2026-000028. Schedule: Sep 29, 2026 9:00 AM. You may submit a new AICS request again after Nov 28, 2026.', 'approved', 'aics_view.php?id=28', 0, '2026-09-28 15:10:02'),
(96, 34, 'AICS Application APPROVED', 'Your AICS application (#27) has been approved. Ticket: AICS-2026-000027. Schedule: Sep 29, 2026 9:00 AM. You may submit a new AICS request again after Nov 28, 2026.', 'approved', 'aics_view.php?id=27', 0, '2026-09-28 15:10:17'),
(97, 34, 'AICS Application APPROVED', 'Your AICS application (#26) has been approved. Ticket: AICS-2026-000026. Schedule: Sep 29, 2026 9:00 AM. You may submit a new AICS request again after Nov 28, 2026.', 'approved', 'aics_view.php?id=26', 0, '2026-09-28 15:10:31'),
(98, 34, 'AICS Application APPROVED', 'Your AICS application (#22) has been approved. Ticket: AICS-2026-000022. Schedule: Sep 29, 2026 9:00 AM. You may submit a new AICS request again after Nov 28, 2026.', 'approved', 'aics_view.php?id=22', 0, '2026-09-28 15:10:47'),
(99, 34, 'AICS Application APPROVED', 'Your AICS application (#23) has been approved. Ticket: AICS-2026-000023. Schedule: Sep 29, 2026 9:00 AM. You may submit a new AICS request again after Nov 28, 2026.', 'approved', 'aics_view.php?id=23', 0, '2026-09-28 15:11:03'),
(100, 34, 'AICS Application APPROVED', 'Your AICS application (#25) has been approved. Ticket: AICS-2026-000025. Schedule: Sep 29, 2026 9:00 AM. You may submit a new AICS request again after Nov 28, 2026.', 'approved', 'aics_view.php?id=25', 0, '2026-09-28 15:13:06'),
(101, 3, 'New AICS Application Submitted', 'A new AICS application (#35) has been submitted by Melvin Sangalang. Please review.', 'application', 'aics_admin_view.php?id=35', 1, '2026-09-28 15:15:40'),
(106, 3, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', '../index.php#announcements', 1, '2026-10-05 07:07:22'),
(108, 2, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', '../index.php#announcements', 0, '2026-10-05 07:07:22'),
(109, 34, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', '../index.php#announcements', 0, '2026-10-05 07:07:22'),
(112, 3, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', '../index.php#announcements', 1, '2026-10-05 07:10:05'),
(114, 2, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', '../index.php#announcements', 0, '2026-10-05 07:10:05'),
(115, 34, 'New Announcement', 'MSWD posted a new announcement. Check the announcements section.', 'announcement', '../index.php#announcements', 0, '2026-10-05 07:10:05'),
(117, 3, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #10) has been submitted and is under review.', 'solo_parent', 'solo_parent_view.php?id=10', 1, '2026-10-09 12:48:48'),
(118, 3, 'Solo Parent Application APPROVED', 'Your Solo Parent application (#10) has been APPROVED.', 'approved', 'solo_parent_view.php?id=10', 1, '2026-10-09 12:50:42'),
(119, 3, 'New AICS Application Submitted', 'A new AICS application (#36) has been submitted by Edward Zabaldan. Please review.', 'application', 'aics_admin_view.php?id=36', 1, '2026-10-09 12:57:14'),
(120, 3, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', 'aics_view.php?id=36', 1, '2026-10-09 12:57:14'),
(121, 3, 'AICS Application APPROVED', 'Your AICS application (#36) has been approved. Ticket: AICS-2026-000036. Schedule: Oct 12, 2026 9:00 AM. You may submit a new AICS request again after Dec 9, 2026.', 'approved', 'aics_view.php?id=36', 1, '2026-10-09 13:00:09'),
(122, 3, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #11) has been submitted and is under review.', 'solo_parent', 'solo_parent_view.php?id=11', 1, '2026-10-09 13:45:37'),
(123, 3, 'Solo Parent Application APPROVED', 'Your Solo Parent application (#11) has been APPROVED.', 'approved', 'solo_parent_view.php?id=11', 1, '2026-10-09 14:20:45'),
(124, 3, 'Solo Parent Application APPROVED', 'Your Solo Parent application (#5) has been APPROVED.', 'approved', 'solo_parent_view.php?id=5', 1, '2026-10-09 14:27:31'),
(125, 3, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #12) has been submitted and is under review.', 'solo_parent', 'solo_parent_view.php?id=12', 1, '2026-10-09 14:29:08'),
(126, 3, 'Solo Parent Application APPROVED', 'Your Solo Parent application (#12) has been APPROVED.', 'approved', 'solo_parent_view.php?id=12', 1, '2026-10-09 14:29:18'),
(127, 3, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #13) has been submitted and is under review.', 'solo_parent', 'solo_parent_view.php?id=13', 1, '2026-10-09 15:05:12'),
(128, 3, 'Solo Parent Application APPROVED', 'Your Solo Parent application (#13) has been APPROVED.', 'approved', 'solo_parent_view.php?id=13', 1, '2026-10-09 15:05:20'),
(129, 3, 'New AICS Application Submitted', 'A new AICS application (#37) has been submitted by Edward Zabaldan. Please review.', 'application', 'aics_admin_view.php?id=37', 1, '2026-10-10 02:21:10'),
(130, 3, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', 'aics_view.php?id=37', 1, '2026-10-10 02:21:10'),
(131, 3, 'AICS Application APPROVED', 'Your AICS application (#37) has been approved. Ticket: AICS-2026-000037. Schedule: Oct 12, 2026 9:00 AM. You may submit a new AICS request again after Dec 10, 2026.', 'approved', 'aics_view.php?id=37', 1, '2026-10-10 02:22:38'),
(133, 3, 'New AICS Application Submitted', 'A new AICS application (#38) has been submitted by Jade Santos. Please review.', 'application', 'aics_admin_view.php?id=38', 0, '2026-10-21 03:48:00'),
(134, 3, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', 'aics_view.php?id=38', 0, '2026-10-21 03:48:00'),
(135, 3, 'AICS Application APPROVED', 'Your AICS application (#38) has been approved. Ticket: AICS-2026-000038. Schedule: Oct 22, 2026 9:00 AM. You may submit a new AICS request again after Dec 21, 2026.', 'approved', 'aics_view.php?id=38', 0, '2026-10-21 05:13:01'),
(136, 44, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #15) has been submitted and is under review.', 'solo_parent', 'solo_parent_view.php?id=15', 0, '2026-10-23 06:00:44'),
(137, 44, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #16) has been submitted and is under review.', 'solo_parent', 'solo_parent_view.php?id=16', 0, '2026-10-23 06:09:01'),
(138, 44, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #17) has been submitted and is under review.', 'solo_parent', 'solo_parent_view.php?id=17', 0, '2026-10-23 06:29:31'),
(139, 44, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #18) has been submitted and is under review.', 'solo_parent', 'solo_parent_view.php?id=18', 0, '2026-10-23 06:33:29'),
(140, 44, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #19) has been submitted and is under review.', 'solo_parent', 'solo_parent_view.php?id=19', 0, '2026-10-23 06:37:24'),
(141, 44, 'Solo Parent Application Submitted', 'Your Solo Parent application (ID #110) has been submitted and is under review.', 'solo_parent', 'solo_parent_view.php?id=110', 0, '2026-10-23 08:21:55');

-- --------------------------------------------------------

--
-- Table structure for table `solo_parent_additional_info`
--

CREATE TABLE `solo_parent_additional_info` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `housing_status` varchar(50) DEFAULT NULL,
  `family_income` decimal(10,2) DEFAULT NULL,
  `solo_parent_classification` text DEFAULT NULL,
  `solo_parent_needs` text DEFAULT NULL,
  `family_resources` text DEFAULT NULL,
  `hear_about_program` text DEFAULT NULL,
  `skills_hobbies` text DEFAULT NULL,
  `solo_parent_id_purpose` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `solo_parent_additional_info`
--

INSERT INTO `solo_parent_additional_info` (`id`, `application_id`, `housing_status`, `family_income`, `solo_parent_classification`, `solo_parent_needs`, `family_resources`, `hear_about_program`, `skills_hobbies`, `solo_parent_id_purpose`) VALUES
(2, 5, 'what', 1000.00, 'asdasdsadasdasdasdasdasd', 'asdasdasdasdasdasdasdasd', 'sadasdasdasdasdasdasd', NULL, NULL, NULL),
(5, 8, 'Caretaker', 1000.00, 'nope', 'nah', 'idk', 'someone', 'ewan', 'none'),
(12, 15, 'Homeowner', 5000.00, 'Sample', 'Sample', 'Sample', 'SAmple', 'Sample', 'Sample'),
(13, 16, 'Renter', 7000.00, 'Sample', 'Sample', 'SampleSample', 'Sample', 'Sample', 'Sample'),
(14, 17, 'Homeowner', 8000.00, 'Sample', 'Sample', 'Sample', 'Sample', 'Sample', 'Sample'),
(15, 18, 'Homeowner', 6000.00, 'Sample', 'Sample', 'Sample', 'Sample', 'Sample', 'Sample'),
(16, 19, 'Homeowner', 12000.00, 'Sample', 'Sample', 'Sample', 'Sample', 'Sample', 'Sample'),
(47, 50, 'Renter', 12000.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(48, 51, 'Renter', 12375.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(49, 52, 'Renter', 12750.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(50, 53, 'Renter', 13125.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(51, 54, 'Renter', 13500.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(52, 55, 'Renter', 13875.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(53, 56, 'Renter', 14250.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(54, 57, 'Renter', 14625.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(55, 58, 'Renter', 15000.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(56, 59, 'Renter', 15375.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(57, 60, 'Renter', 15750.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(58, 61, 'Renter', 16125.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(59, 62, 'Renter', 16500.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(60, 63, 'Renter', 16875.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(61, 64, 'Renter', 17250.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(62, 65, 'Renter', 17625.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(63, 66, 'Renter', 18000.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(64, 67, 'Renter', 18375.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(65, 68, 'Renter', 18750.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(66, 69, 'Renter', 19125.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(67, 70, 'Renter', 19500.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(68, 71, 'Renter', 19875.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(69, 72, 'Renter', 20250.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(70, 73, 'Renter', 20625.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(71, 74, 'Renter', 21000.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(72, 75, 'Renter', 21375.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(73, 76, 'Renter', 21750.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(74, 77, 'Renter', 22125.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(75, 78, 'Renter', 22500.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(76, 79, 'Renter', 22875.00, 'Solo parent household', 'Childcare and livelihood support', 'Family and community support', 'Community information session', 'Cooking and sewing', 'Access to solo parent services'),
(77, 80, 'Living with family', 9200.00, 'Single', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Vendor', 'Access to social protection services'),
(78, 81, 'Living with family', 11000.00, 'Separated', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Caregiver', 'Access to social protection services'),
(79, 82, 'Living with family', 12500.00, 'Widowed', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Cashier', 'Access to social protection services'),
(80, 83, 'Living with family', 10500.00, 'Separated', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Food Service Worker', 'Access to social protection services'),
(81, 84, 'Living with family', 13800.00, 'Single', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Office Clerk', 'Access to social protection services'),
(82, 85, 'Living with family', 7800.00, 'Widowed', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Laundry Worker', 'Access to social protection services'),
(83, 86, 'Living with family', 11800.00, 'Separated', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Sales Associate', 'Access to social protection services'),
(84, 87, 'Living with family', 13000.00, 'Single', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Teaching Assistant', 'Access to social protection services'),
(85, 88, 'Living with family', 9700.00, 'Separated', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Tailor', 'Access to social protection services'),
(86, 89, 'Living with family', 11200.00, 'Widowed', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Baker', 'Access to social protection services'),
(87, 90, 'Living with family', 8600.00, 'Separated', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Market Vendor', 'Access to social protection services'),
(88, 91, 'Living with family', 10400.00, 'Single', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Service Crew', 'Access to social protection services'),
(89, 92, 'Living with family', 6900.00, 'Widowed', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Home-based Worker', 'Access to social protection services'),
(90, 93, 'Living with family', 15500.00, 'Single', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Customer Service Agent', 'Access to social protection services'),
(91, 94, 'Living with family', 9000.00, 'Separated', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Cook', 'Access to social protection services'),
(92, 95, 'Living with family', 12100.00, 'Single', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Care Assistant', 'Access to social protection services'),
(93, 96, 'Living with family', 8200.00, 'Widowed', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Canteen Helper', 'Access to social protection services'),
(94, 97, 'Living with family', 14500.00, 'Separated', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Bookkeeper', 'Access to social protection services'),
(95, 98, 'Living with family', 10800.00, 'Single', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Production Worker', 'Access to social protection services'),
(96, 99, 'Living with family', 13200.00, 'Separated', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Pharmacy Assistant', 'Access to social protection services'),
(97, 100, 'Living with family', 9400.00, 'Widowed', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Dressmaker', 'Access to social protection services'),
(98, 101, 'Living with family', 11700.00, 'Separated', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Receptionist', 'Access to social protection services'),
(99, 102, 'Living with family', 12600.00, 'Single', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Library Assistant', 'Access to social protection services'),
(100, 103, 'Living with family', 8500.00, 'Separated', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Household Worker', 'Access to social protection services'),
(101, 104, 'Living with family', 7600.00, 'Widowed', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Sari-sari Store Owner', 'Access to social protection services'),
(102, 105, 'Living with family', 14000.00, 'Separated', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Dental Assistant', 'Access to social protection services'),
(103, 106, 'Living with family', 9900.00, 'Single', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Textile Worker', 'Access to social protection services'),
(104, 107, 'Living with family', 8800.00, 'Separated', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Food Stall Attendant', 'Access to social protection services'),
(105, 108, 'Living with family', 11500.00, 'Widowed', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Caregiver', 'Access to social protection services'),
(106, 109, 'Living with family', 13600.00, 'Separated', 'Livelihood and educational assistance', 'Family support', 'Municipal Social Welfare Office', 'Accounting Clerk', 'Access to social protection services'),
(107, 110, 'Homeowner', 7500.00, 'Sample', 'Sample', 'Sample', 'Sample', 'Sample', 'Sample');

-- --------------------------------------------------------

--
-- Table structure for table `solo_parent_address`
--

CREATE TABLE `solo_parent_address` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `house_no` varchar(100) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `city_municipality` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `region` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `solo_parent_address`
--

INSERT INTO `solo_parent_address` (`id`, `application_id`, `house_no`, `barangay`, `city_municipality`, `province`, `region`) VALUES
(2, 5, '001 Estrella St.', 'San Andres', 'Cainta', 'Rizal', 'Calabarzon'),
(5, 8, '45', 'San Andres', 'Cainta', 'Rizal', 'Region IV-A'),
(12, 15, '001 Santol St', 'San Juan', 'Cainta', 'Rizal', 'Region IV-A'),
(13, 16, '002 Esperanza St.', 'San Andres', 'Cainta', 'Rizal', 'Region IV-A'),
(14, 17, '034', 'San Isidro', 'Cainta', 'Rizal', 'Region IV-A'),
(15, 18, '003 2nd st', 'Sto. Domingo', 'Cainta', 'Rizal', 'Region IV-A'),
(16, 19, 'Lot 21 Cincinnati St.', 'San Isidro', 'Cainta', 'Rizal', 'Region IV-A'),
(47, 50, 'Block 1 Lot 1, Sampaguita Lane', 'San Andres', 'Cainta', 'Rizal', 'CALABARZON'),
(48, 51, 'Block 2 Lot 1, Acacia Street', 'San Isidro', 'Cainta', 'Rizal', 'CALABARZON'),
(49, 52, 'Block 3 Lot 1, Mabini Street', 'San Juan', 'Cainta', 'Rizal', 'CALABARZON'),
(50, 53, 'Block 4 Lot 1, Ilang-Ilang Lane', 'San Roque', 'Cainta', 'Rizal', 'CALABARZON'),
(51, 54, 'Block 5 Lot 1, Gumamela Street', 'Sto. Domingo', 'Cainta', 'Rizal', 'CALABARZON'),
(52, 55, 'Block 6 Lot 1, Jasmine Lane', 'San Francisco', 'Cainta', 'Rizal', 'CALABARZON'),
(53, 56, 'Block 7 Lot 1, Rosal Street', 'San Rafael', 'Cainta', 'Rizal', 'CALABARZON'),
(54, 57, 'Block 8 Lot 1, Camia Lane', 'San Miguel', 'Cainta', 'Rizal', 'CALABARZON'),
(55, 58, 'Block 9 Lot 1, Sampaguita Street', 'Santa Rosa', 'Cainta', 'Rizal', 'CALABARZON'),
(56, 59, 'Block 10 Lot 1, Acacia Lane', 'San Andres', 'Cainta', 'Rizal', 'CALABARZON'),
(57, 60, 'Block 1 Lot 2, Mabini Lane', 'San Isidro', 'Cainta', 'Rizal', 'CALABARZON'),
(58, 61, 'Block 2 Lot 2, Ilang-Ilang Street', 'San Juan', 'Cainta', 'Rizal', 'CALABARZON'),
(59, 62, 'Block 3 Lot 2, Gumamela Lane', 'San Roque', 'Cainta', 'Rizal', 'CALABARZON'),
(60, 63, 'Block 4 Lot 2, Jasmine Street', 'Sto. Domingo', 'Cainta', 'Rizal', 'CALABARZON'),
(61, 64, 'Block 5 Lot 2, Rosal Lane', 'San Francisco', 'Cainta', 'Rizal', 'CALABARZON'),
(62, 65, 'Block 6 Lot 2, Camia Street', 'San Rafael', 'Cainta', 'Rizal', 'CALABARZON'),
(63, 66, 'Block 7 Lot 2, Sampaguita Lane', 'San Miguel', 'Cainta', 'Rizal', 'CALABARZON'),
(64, 67, 'Block 8 Lot 2, Acacia Street', 'Santa Rosa', 'Cainta', 'Rizal', 'CALABARZON'),
(65, 68, 'Block 9 Lot 2, Mabini Street', 'San Andres', 'Cainta', 'Rizal', 'CALABARZON'),
(66, 69, 'Block 10 Lot 2, Ilang-Ilang Lane', 'San Isidro', 'Cainta', 'Rizal', 'CALABARZON'),
(67, 70, 'Block 1 Lot 3, Gumamela Street', 'San Juan', 'Cainta', 'Rizal', 'CALABARZON'),
(68, 71, 'Block 2 Lot 3, Jasmine Lane', 'San Roque', 'Cainta', 'Rizal', 'CALABARZON'),
(69, 72, 'Block 3 Lot 3, Rosal Street', 'Sto. Domingo', 'Cainta', 'Rizal', 'CALABARZON'),
(70, 73, 'Block 4 Lot 3, Camia Lane', 'San Francisco', 'Cainta', 'Rizal', 'CALABARZON'),
(71, 74, 'Block 5 Lot 3, Sampaguita Street', 'San Rafael', 'Cainta', 'Rizal', 'CALABARZON'),
(72, 75, 'Block 6 Lot 3, Acacia Lane', 'San Miguel', 'Cainta', 'Rizal', 'CALABARZON'),
(73, 76, 'Block 7 Lot 3, Mabini Lane', 'Santa Rosa', 'Cainta', 'Rizal', 'CALABARZON'),
(74, 77, 'Block 8 Lot 3, Ilang-Ilang Street', 'San Andres', 'Cainta', 'Rizal', 'CALABARZON'),
(75, 78, 'Block 9 Lot 3, Gumamela Lane', 'San Isidro', 'Cainta', 'Rizal', 'CALABARZON'),
(76, 79, 'Block 10 Lot 3, Jasmine Street', 'San Juan', 'Cainta', 'Rizal', 'CALABARZON'),
(77, 80, '10 Sampaguita Street', 'San Andres', 'Cainta', 'Rizal', 'Region IV-A'),
(78, 81, '13 Mabini Street', 'San Juan', 'Cainta', 'Rizal', 'Region IV-A'),
(79, 82, '16 Narra Street', 'San Isidro', 'Cainta', 'Rizal', 'Region IV-A'),
(80, 83, '19 Acacia Street', 'San Roque', 'Cainta', 'Rizal', 'Region IV-A'),
(81, 84, '22 Mahogany Street', 'Santa Rosa', 'Cainta', 'Rizal', 'Region IV-A'),
(82, 85, '25 Camia Street', 'Santo Domingo', 'Cainta', 'Rizal', 'Region IV-A'),
(83, 86, '28 Sampaguita Street', 'San Francisco', 'Cainta', 'Rizal', 'Region IV-A'),
(84, 87, '31 Mabini Street', 'San Nicolas', 'Cainta', 'Rizal', 'Region IV-A'),
(85, 88, '34 Narra Street', 'San Andres', 'Cainta', 'Rizal', 'Region IV-A'),
(86, 89, '37 Acacia Street', 'San Juan', 'Cainta', 'Rizal', 'Region IV-A'),
(87, 90, '40 Mahogany Street', 'San Isidro', 'Cainta', 'Rizal', 'Region IV-A'),
(88, 91, '43 Camia Street', 'San Roque', 'Cainta', 'Rizal', 'Region IV-A'),
(89, 92, '46 Sampaguita Street', 'Santa Rosa', 'Cainta', 'Rizal', 'Region IV-A'),
(90, 93, '49 Mabini Street', 'Santo Domingo', 'Cainta', 'Rizal', 'Region IV-A'),
(91, 94, '52 Narra Street', 'San Francisco', 'Cainta', 'Rizal', 'Region IV-A'),
(92, 95, '55 Acacia Street', 'San Nicolas', 'Cainta', 'Rizal', 'Region IV-A'),
(93, 96, '58 Mahogany Street', 'San Andres', 'Cainta', 'Rizal', 'Region IV-A'),
(94, 97, '61 Camia Street', 'San Juan', 'Cainta', 'Rizal', 'Region IV-A'),
(95, 98, '64 Sampaguita Street', 'San Isidro', 'Cainta', 'Rizal', 'Region IV-A'),
(96, 99, '67 Mabini Street', 'San Roque', 'Cainta', 'Rizal', 'Region IV-A'),
(97, 100, '70 Narra Street', 'Santa Rosa', 'Cainta', 'Rizal', 'Region IV-A'),
(98, 101, '73 Acacia Street', 'Santo Domingo', 'Cainta', 'Rizal', 'Region IV-A'),
(99, 102, '76 Mahogany Street', 'San Francisco', 'Cainta', 'Rizal', 'Region IV-A'),
(100, 103, '79 Camia Street', 'San Nicolas', 'Cainta', 'Rizal', 'Region IV-A'),
(101, 104, '82 Sampaguita Street', 'San Andres', 'Cainta', 'Rizal', 'Region IV-A'),
(102, 105, '85 Mabini Street', 'San Juan', 'Cainta', 'Rizal', 'Region IV-A'),
(103, 106, '88 Narra Street', 'San Isidro', 'Cainta', 'Rizal', 'Region IV-A'),
(104, 107, '91 Acacia Street', 'San Roque', 'Cainta', 'Rizal', 'Region IV-A'),
(105, 108, '94 Mahogany Street', 'Santa Rosa', 'Cainta', 'Rizal', 'Region IV-A'),
(106, 109, '97 Camia Street', 'Santo Domingo', 'Cainta', 'Rizal', 'Region IV-A'),
(107, 110, '001 Santol St', 'San Isidro', 'Cainta', 'Rizal', 'Region IV-A');

-- --------------------------------------------------------

--
-- Table structure for table `solo_parent_applications`
--

CREATE TABLE `solo_parent_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `ticket_number` varchar(50) DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `certification_date` date DEFAULT NULL,
  `applicant_signature` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_name` varchar(50) NOT NULL,
  `member_4ps` tinyint(1) DEFAULT 0,
  `member_lgbtq` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `solo_parent_applications`
--

INSERT INTO `solo_parent_applications` (`id`, `user_id`, `status`, `ticket_number`, `admin_notes`, `certification_date`, `applicant_signature`, `created_at`, `updated_at`, `last_name`, `member_4ps`, `member_lgbtq`) VALUES
(5, 3, 'approved', 'SP-2026-000005', '', '2026-03-18', 'Edward Zabaldan', '2026-03-18 05:40:08', '2026-10-09 14:27:31', '', 0, 0),
(8, 3, 'pending', NULL, NULL, '2026-01-25', 'Melvin Sangalang', '2026-08-09 11:08:46', '2026-08-09 11:08:46', '', 0, 0),
(15, 44, 'pending', NULL, NULL, '2026-10-23', 'John Carlou Ogalesco', '2026-10-23 06:00:44', '2026-10-23 06:00:44', '', 0, 0),
(16, 44, 'pending', NULL, NULL, '2026-10-23', 'Mirasol Bunyi Teresita', '2026-10-23 06:09:01', '2026-10-23 06:09:01', '', 0, 0),
(17, 44, 'pending', NULL, NULL, '2026-10-23', 'Alexander Asibor Arroyo', '2026-10-23 06:29:31', '2026-10-23 06:29:31', '', 0, 0),
(18, 44, 'pending', NULL, NULL, '2026-10-23', 'Jade Tuto Santos', '2026-10-23 06:33:29', '2026-10-23 06:33:29', '', 0, 0),
(19, 44, 'pending', NULL, NULL, '2026-10-23', 'Chrisse Jay Diones', '2026-10-23 06:37:24', '2026-10-23 06:37:24', '', 0, 0),
(50, 46, 'pending', NULL, NULL, '2026-10-23', 'Mara Del Rosario', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Del Rosario', 0, 0),
(51, 46, 'pending', NULL, NULL, '2026-10-23', 'Lina Mercado', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Mercado', 0, 0),
(52, 46, 'pending', NULL, NULL, '2026-10-23', 'Rosa Villanueva', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Villanueva', 0, 0),
(53, 46, 'pending', NULL, NULL, '2026-10-23', 'Elena Manalo', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Manalo', 0, 0),
(54, 46, 'pending', NULL, NULL, '2026-10-23', 'Nina Santiago', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Santiago', 0, 0),
(55, 46, 'pending', NULL, NULL, '2026-10-23', 'Celia Bautista', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Bautista', 0, 0),
(56, 46, 'pending', NULL, NULL, '2026-10-23', 'Tessa Navarro', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Navarro', 0, 0),
(57, 46, 'pending', NULL, NULL, '2026-10-23', 'Alma Dizon', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Dizon', 0, 0),
(58, 46, 'pending', NULL, NULL, '2026-10-23', 'Joy Aquino', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Aquino', 0, 0),
(59, 46, 'pending', NULL, NULL, '2026-10-23', 'Lourdes Castillo', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Castillo', 0, 0),
(60, 46, 'pending', NULL, NULL, '2026-10-23', 'Mila Salazar', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Salazar', 0, 0),
(61, 46, 'pending', NULL, NULL, '2026-10-23', 'Ana Cruz', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Cruz', 0, 0),
(62, 46, 'pending', NULL, NULL, '2026-10-23', 'Bea Ramos', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Ramos', 0, 0),
(63, 46, 'pending', NULL, NULL, '2026-10-23', 'Cora Garcia', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Garcia', 0, 0),
(64, 46, 'pending', NULL, NULL, '2026-10-23', 'Dina Flores', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Flores', 0, 0),
(65, 46, 'pending', NULL, NULL, '2026-10-23', 'Eva Santos', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Santos', 0, 0),
(66, 46, 'pending', NULL, NULL, '2026-10-23', 'Faye Reyes', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Reyes', 0, 0),
(67, 46, 'pending', NULL, NULL, '2026-10-23', 'Gina Mendoza', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Mendoza', 0, 0),
(68, 46, 'pending', NULL, NULL, '2026-10-23', 'Hana Torres', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Torres', 0, 0),
(69, 46, 'pending', NULL, NULL, '2026-10-23', 'Ivy Domingo', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Domingo', 0, 0),
(70, 46, 'pending', NULL, NULL, '2026-10-23', 'Kara Valdez', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Valdez', 0, 0),
(71, 46, 'pending', NULL, NULL, '2026-10-23', 'Lea Pascual', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Pascual', 0, 0),
(72, 46, 'pending', NULL, NULL, '2026-10-23', 'Mina Soriano', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Soriano', 0, 0),
(73, 46, 'pending', NULL, NULL, '2026-10-23', 'Nora Espiritu', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Espiritu', 0, 0),
(74, 46, 'pending', NULL, NULL, '2026-10-23', 'Olive Lazaro', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Lazaro', 0, 0),
(75, 46, 'pending', NULL, NULL, '2026-10-23', 'Pia Fernandez', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Fernandez', 0, 0),
(76, 46, 'pending', NULL, NULL, '2026-10-23', 'Queenie Andrada', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Andrada', 0, 0),
(77, 46, 'pending', NULL, NULL, '2026-10-23', 'Rina Ocampo', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Ocampo', 0, 0),
(78, 46, 'pending', NULL, NULL, '2026-10-23', 'Sela Vergara', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Vergara', 0, 0),
(79, 46, 'pending', NULL, NULL, '2026-10-23', 'Tina Abad', '2026-10-23 06:56:54', '2026-10-23 07:16:58', 'Abad', 0, 0),
(80, 46, 'pending', NULL, NULL, NULL, 'Alyssa Panganiban', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Panganiban', 1, 0),
(81, 46, 'pending', NULL, NULL, NULL, 'Bernadette Villareal', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Villareal', 0, 0),
(82, 46, 'pending', NULL, NULL, NULL, 'Camille Dela Peña', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Dela Peña', 0, 0),
(83, 46, 'pending', NULL, NULL, NULL, 'Danica Manalo', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Manalo', 1, 0),
(84, 46, 'pending', NULL, NULL, NULL, 'Eloisa Sarmiento', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Sarmiento', 0, 0),
(85, 46, 'pending', NULL, NULL, NULL, 'Frances Magsino', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Magsino', 0, 0),
(86, 46, 'pending', NULL, NULL, NULL, 'Georgina Cabrera', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Cabrera', 1, 0),
(87, 46, 'pending', NULL, NULL, NULL, 'Hannah Ocampo', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Ocampo', 0, 0),
(88, 46, 'pending', NULL, NULL, NULL, 'Isabel Santiago', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Santiago', 0, 0),
(89, 46, 'pending', NULL, NULL, NULL, 'Janine Estrada', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Estrada', 1, 0),
(90, 46, 'pending', NULL, NULL, NULL, 'Kristine Abella', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Abella', 0, 0),
(91, 46, 'pending', NULL, NULL, NULL, 'Lorraine Valencia', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Valencia', 0, 0),
(92, 46, 'pending', NULL, NULL, NULL, 'Maricel Rosales', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Rosales', 1, 0),
(93, 46, 'pending', NULL, NULL, NULL, 'Nadine Mercado', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Mercado', 0, 0),
(94, 46, 'pending', NULL, NULL, NULL, 'Ofelia Dominguez', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Dominguez', 0, 0),
(95, 46, 'pending', NULL, NULL, NULL, 'Precious Arceo', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Arceo', 1, 0),
(96, 46, 'pending', NULL, NULL, NULL, 'Queenie Cordero', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Cordero', 0, 0),
(97, 46, 'pending', NULL, NULL, NULL, 'Rachelle Pascual', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Pascual', 0, 0),
(98, 46, 'pending', NULL, NULL, NULL, 'Sheila Andrada', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Andrada', 1, 0),
(99, 46, 'pending', NULL, NULL, NULL, 'Tricia Vergara', '2026-10-23 08:02:38', '2026-10-23 08:02:38', 'Vergara', 0, 0),
(100, 46, 'pending', NULL, NULL, NULL, 'Vanessa Lazaro', '2026-10-23 08:02:39', '2026-10-23 08:02:39', 'Lazaro', 0, 0),
(101, 46, 'pending', NULL, NULL, NULL, 'Wilma Belen', '2026-10-23 08:02:39', '2026-10-23 08:02:39', 'Belen', 1, 0),
(102, 46, 'pending', NULL, NULL, NULL, 'Xenia Mendoza', '2026-10-23 08:02:39', '2026-10-23 08:02:39', 'Mendoza', 0, 0),
(103, 46, 'pending', NULL, NULL, NULL, 'Yvette Salonga', '2026-10-23 08:02:39', '2026-10-23 08:02:39', 'Salonga', 0, 0),
(104, 46, 'pending', NULL, NULL, NULL, 'Zenaida Aquino', '2026-10-23 08:02:39', '2026-10-23 08:02:39', 'Aquino', 1, 0),
(105, 46, 'pending', NULL, NULL, NULL, 'Adriana Castillo', '2026-10-23 08:02:39', '2026-10-23 08:02:39', 'Castillo', 0, 0),
(106, 46, 'pending', NULL, NULL, NULL, 'Belinda Flores', '2026-10-23 08:02:39', '2026-10-23 08:02:39', 'Flores', 0, 0),
(107, 46, 'pending', NULL, NULL, NULL, 'Charmaine Rivera', '2026-10-23 08:02:39', '2026-10-23 08:02:39', 'Rivera', 1, 0),
(108, 46, 'pending', NULL, NULL, NULL, 'Darlene Tan', '2026-10-23 08:02:39', '2026-10-23 08:02:39', 'Tan', 0, 0),
(109, 46, 'pending', NULL, NULL, NULL, 'Emelita Reyes', '2026-10-23 08:02:39', '2026-10-23 08:02:39', 'Reyes', 0, 0),
(110, 44, 'pending', NULL, NULL, '2026-10-23', 'John Carlou Ogalesco', '2026-10-23 08:21:55', '2026-10-23 08:21:55', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `solo_parent_emergency_contact`
--

CREATE TABLE `solo_parent_emergency_contact` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `solo_parent_emergency_contact`
--

INSERT INTO `solo_parent_emergency_contact` (`id`, `application_id`, `name`, `address`, `contact_number`) VALUES
(2, 5, 'Marie Eddelyn Zabaldan', 'edwasdasdasdasdasd', '09123123123'),
(5, 8, 'mother', '98 brgy sand cainta, rizal', '09123456789'),
(12, 15, '', '', ''),
(13, 16, '', '', ''),
(14, 17, '', '', ''),
(15, 18, '', '', ''),
(16, 19, '', '', ''),
(47, 50, 'Sample Contact 01', 'Fictional Block 1 Lot 1, Sampaguita Lane, San Andres, Cainta, Rizal', '09000000000'),
(48, 51, 'Sample Contact 02', 'Fictional Block 2 Lot 1, Acacia Street, San Isidro, Cainta, Rizal', '09000000000'),
(49, 52, 'Sample Contact 03', 'Fictional Block 3 Lot 1, Mabini Street, San Juan, Cainta, Rizal', '09000000000'),
(50, 53, 'Sample Contact 04', 'Fictional Block 4 Lot 1, Ilang-Ilang Lane, San Roque, Cainta, Rizal', '09000000000'),
(51, 54, 'Sample Contact 05', 'Fictional Block 5 Lot 1, Gumamela Street, Sto. Domingo, Cainta, Rizal', '09000000000'),
(52, 55, 'Sample Contact 06', 'Fictional Block 6 Lot 1, Jasmine Lane, San Francisco, Cainta, Rizal', '09000000000'),
(53, 56, 'Sample Contact 07', 'Fictional Block 7 Lot 1, Rosal Street, San Rafael, Cainta, Rizal', '09000000000'),
(54, 57, 'Sample Contact 08', 'Fictional Block 8 Lot 1, Camia Lane, San Miguel, Cainta, Rizal', '09000000000'),
(55, 58, 'Sample Contact 09', 'Fictional Block 9 Lot 1, Sampaguita Street, Santa Rosa, Cainta, Rizal', '09000000000'),
(56, 59, 'Sample Contact 10', 'Fictional Block 10 Lot 1, Acacia Lane, San Andres, Cainta, Rizal', '09000000000'),
(57, 60, 'Sample Contact 11', 'Fictional Block 1 Lot 2, Mabini Lane, San Isidro, Cainta, Rizal', '09000000000'),
(58, 61, 'Sample Contact 12', 'Fictional Block 2 Lot 2, Ilang-Ilang Street, San Juan, Cainta, Rizal', '09000000000'),
(59, 62, 'Sample Contact 13', 'Fictional Block 3 Lot 2, Gumamela Lane, San Roque, Cainta, Rizal', '09000000000'),
(60, 63, 'Sample Contact 14', 'Fictional Block 4 Lot 2, Jasmine Street, Sto. Domingo, Cainta, Rizal', '09000000000'),
(61, 64, 'Sample Contact 15', 'Fictional Block 5 Lot 2, Rosal Lane, San Francisco, Cainta, Rizal', '09000000000'),
(62, 65, 'Sample Contact 16', 'Fictional Block 6 Lot 2, Camia Street, San Rafael, Cainta, Rizal', '09000000000'),
(63, 66, 'Sample Contact 17', 'Fictional Block 7 Lot 2, Sampaguita Lane, San Miguel, Cainta, Rizal', '09000000000'),
(64, 67, 'Sample Contact 18', 'Fictional Block 8 Lot 2, Acacia Street, Santa Rosa, Cainta, Rizal', '09000000000'),
(65, 68, 'Sample Contact 19', 'Fictional Block 9 Lot 2, Mabini Street, San Andres, Cainta, Rizal', '09000000000'),
(66, 69, 'Sample Contact 20', 'Fictional Block 10 Lot 2, Ilang-Ilang Lane, San Isidro, Cainta, Rizal', '09000000000'),
(67, 70, 'Sample Contact 21', 'Fictional Block 1 Lot 3, Gumamela Street, San Juan, Cainta, Rizal', '09000000000'),
(68, 71, 'Sample Contact 22', 'Fictional Block 2 Lot 3, Jasmine Lane, San Roque, Cainta, Rizal', '09000000000'),
(69, 72, 'Sample Contact 23', 'Fictional Block 3 Lot 3, Rosal Street, Sto. Domingo, Cainta, Rizal', '09000000000'),
(70, 73, 'Sample Contact 24', 'Fictional Block 4 Lot 3, Camia Lane, San Francisco, Cainta, Rizal', '09000000000'),
(71, 74, 'Sample Contact 25', 'Fictional Block 5 Lot 3, Sampaguita Street, San Rafael, Cainta, Rizal', '09000000000'),
(72, 75, 'Sample Contact 26', 'Fictional Block 6 Lot 3, Acacia Lane, San Miguel, Cainta, Rizal', '09000000000'),
(73, 76, 'Sample Contact 27', 'Fictional Block 7 Lot 3, Mabini Lane, Santa Rosa, Cainta, Rizal', '09000000000'),
(74, 77, 'Sample Contact 28', 'Fictional Block 8 Lot 3, Ilang-Ilang Street, San Andres, Cainta, Rizal', '09000000000'),
(75, 78, 'Sample Contact 29', 'Fictional Block 9 Lot 3, Gumamela Lane, San Isidro, Cainta, Rizal', '09000000000'),
(76, 79, 'Sample Contact 30', 'Fictional Block 10 Lot 3, Jasmine Street, San Juan, Cainta, Rizal', '09000000000'),
(77, 80, 'Teresa Panganiban', '10 Sampaguita Street, San Andres, Cainta, Rizal', '09172000001'),
(78, 81, 'Rogelio Villareal', '13 Mabini Street, San Juan, Cainta, Rizal', '09172000002'),
(79, 82, 'Carmen Dela Peña', '16 Narra Street, San Isidro, Cainta, Rizal', '09172000003'),
(80, 83, 'Eduardo Manalo', '19 Acacia Street, San Roque, Cainta, Rizal', '09172000004'),
(81, 84, 'Lourdes Sarmiento', '22 Mahogany Street, Santa Rosa, Cainta, Rizal', '09172000005'),
(82, 85, 'Manuel Magsino', '25 Camia Street, Santo Domingo, Cainta, Rizal', '09172000006'),
(83, 86, 'Rosario Cabrera', '28 Sampaguita Street, San Francisco, Cainta, Rizal', '09172000007'),
(84, 87, 'Antonio Ocampo', '31 Mabini Street, San Nicolas, Cainta, Rizal', '09172000008'),
(85, 88, 'Gloria Santiago', '34 Narra Street, San Andres, Cainta, Rizal', '09172000009'),
(86, 89, 'Ramon Estrada', '37 Acacia Street, San Juan, Cainta, Rizal', '09172000010'),
(87, 90, 'Elena Abella', '40 Mahogany Street, San Isidro, Cainta, Rizal', '09172000011'),
(88, 91, 'Roberto Valencia', '43 Camia Street, San Roque, Cainta, Rizal', '09172000012'),
(89, 92, 'Pilar Rosales', '46 Sampaguita Street, Santa Rosa, Cainta, Rizal', '09172000013'),
(90, 93, 'Danilo Mercado', '49 Mabini Street, Santo Domingo, Cainta, Rizal', '09172000014'),
(91, 94, 'Aurora Dominguez', '52 Narra Street, San Francisco, Cainta, Rizal', '09172000015'),
(92, 95, 'Ricardo Arceo', '55 Acacia Street, San Nicolas, Cainta, Rizal', '09172000016'),
(93, 96, 'Beatriz Cordero', '58 Mahogany Street, San Andres, Cainta, Rizal', '09172000017'),
(94, 97, 'Federico Pascual', '61 Camia Street, San Juan, Cainta, Rizal', '09172000018'),
(95, 98, 'Cecilia Andrada', '64 Sampaguita Street, San Isidro, Cainta, Rizal', '09172000019'),
(96, 99, 'Mariano Vergara', '67 Mabini Street, San Roque, Cainta, Rizal', '09172000020'),
(97, 100, 'Adela Lazaro', '70 Narra Street, Santa Rosa, Cainta, Rizal', '09172000021'),
(98, 101, 'Ernesto Belen', '73 Acacia Street, Santo Domingo, Cainta, Rizal', '09172000022'),
(99, 102, 'Minda Mendoza', '76 Mahogany Street, San Francisco, Cainta, Rizal', '09172000023'),
(100, 103, 'Leonardo Salonga', '79 Camia Street, San Nicolas, Cainta, Rizal', '09172000024'),
(101, 104, 'Estrella Aquino', '82 Sampaguita Street, San Andres, Cainta, Rizal', '09172000025'),
(102, 105, 'Jaime Castillo', '85 Mabini Street, San Juan, Cainta, Rizal', '09172000026'),
(103, 106, 'Amelia Flores', '88 Narra Street, San Isidro, Cainta, Rizal', '09172000027'),
(104, 107, 'Nestor Rivera', '91 Acacia Street, San Roque, Cainta, Rizal', '09172000028'),
(105, 108, 'Dalisay Tan', '94 Mahogany Street, Santa Rosa, Cainta, Rizal', '09172000029'),
(106, 109, 'Arturo Reyes', '97 Camia Street, Santo Domingo, Cainta, Rizal', '09172000030'),
(107, 110, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `solo_parent_family_members`
--

CREATE TABLE `solo_parent_family_members` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `relationship` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `school` varchar(100) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `educational_attainment` varchar(150) DEFAULT NULL,
  `occupation` varchar(150) DEFAULT NULL,
  `monthly_income` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `solo_parent_family_members`
--

INSERT INTO `solo_parent_family_members` (`id`, `application_id`, `name`, `relationship`, `age`, `school`, `birthdate`, `educational_attainment`, `occupation`, `monthly_income`) VALUES
(1, 5, 'asdasdasda', 'dasdasdasd', 12, 'sdasdadadadasdasd', NULL, NULL, NULL, 0.00),
(2, 5, 'adasdasdasd', 'asdadasdasdas', 12, 'asdadadsasd', NULL, NULL, NULL, 0.00),
(3, 5, 'asdasd', 'asdasdasd', 23, 'asdasdasd', NULL, NULL, NULL, 0.00),
(34, 50, 'Sample Child 01', 'Child', 5, 'Cainta Community School', '2021-10-23', 'Elementary', 'Student', 0.00),
(35, 51, 'Sample Child 02', 'Child', 6, 'Cainta Community School', '2020-10-23', 'Elementary', 'Student', 0.00),
(36, 52, 'Sample Child 03', 'Child', 7, 'Cainta Community School', '2019-10-23', 'Elementary', 'Student', 0.00),
(37, 53, 'Sample Child 04', 'Child', 8, 'Cainta Community School', '2018-10-23', 'Elementary', 'Student', 0.00),
(38, 54, 'Sample Child 05', 'Child', 9, 'Cainta Community School', '2017-10-23', 'Elementary', 'Student', 0.00),
(39, 55, 'Sample Child 06', 'Child', 10, 'Cainta Community School', '2016-10-23', 'Elementary', 'Student', 0.00),
(40, 56, 'Sample Child 07', 'Child', 11, 'Cainta Community School', '2015-10-23', 'Elementary', 'Student', 0.00),
(41, 57, 'Sample Child 08', 'Child', 12, 'Cainta Community School', '2014-10-23', 'Elementary', 'Student', 0.00),
(42, 58, 'Sample Child 09', 'Child', 13, 'Cainta Community School', '2013-10-23', 'Elementary', 'Student', 0.00),
(43, 59, 'Sample Child 10', 'Child', 14, 'Cainta Community School', '2012-10-23', 'Elementary', 'Student', 0.00),
(44, 60, 'Sample Child 11', 'Child', 15, 'Cainta Community School', '2011-10-23', 'Elementary', 'Student', 0.00),
(45, 61, 'Sample Child 12', 'Child', 16, 'Cainta Community School', '2010-10-23', 'Elementary', 'Student', 0.00),
(46, 62, 'Sample Child 13', 'Child', 17, 'Cainta Community School', '2009-10-23', 'Elementary', 'Student', 0.00),
(47, 63, 'Sample Child 14', 'Child', 5, 'Cainta Community School', '2021-10-23', 'Elementary', 'Student', 0.00),
(48, 64, 'Sample Child 15', 'Child', 6, 'Cainta Community School', '2020-10-23', 'Elementary', 'Student', 0.00),
(49, 65, 'Sample Child 16', 'Child', 7, 'Cainta Community School', '2019-10-23', 'Elementary', 'Student', 0.00),
(50, 66, 'Sample Child 17', 'Child', 8, 'Cainta Community School', '2018-10-23', 'Elementary', 'Student', 0.00),
(51, 67, 'Sample Child 18', 'Child', 9, 'Cainta Community School', '2017-10-23', 'Elementary', 'Student', 0.00),
(52, 68, 'Sample Child 19', 'Child', 10, 'Cainta Community School', '2016-10-23', 'Elementary', 'Student', 0.00),
(53, 69, 'Sample Child 20', 'Child', 11, 'Cainta Community School', '2015-10-23', 'Elementary', 'Student', 0.00),
(54, 70, 'Sample Child 21', 'Child', 12, 'Cainta Community School', '2014-10-23', 'Elementary', 'Student', 0.00),
(55, 71, 'Sample Child 22', 'Child', 13, 'Cainta Community School', '2013-10-23', 'Elementary', 'Student', 0.00),
(56, 72, 'Sample Child 23', 'Child', 14, 'Cainta Community School', '2012-10-23', 'Elementary', 'Student', 0.00),
(57, 73, 'Sample Child 24', 'Child', 15, 'Cainta Community School', '2011-10-23', 'Elementary', 'Student', 0.00),
(58, 74, 'Sample Child 25', 'Child', 16, 'Cainta Community School', '2010-10-23', 'Elementary', 'Student', 0.00),
(59, 75, 'Sample Child 26', 'Child', 17, 'Cainta Community School', '2009-10-23', 'Elementary', 'Student', 0.00),
(60, 76, 'Sample Child 27', 'Child', 5, 'Cainta Community School', '2021-10-23', 'Elementary', 'Student', 0.00),
(61, 77, 'Sample Child 28', 'Child', 6, 'Cainta Community School', '2020-10-23', 'Elementary', 'Student', 0.00),
(62, 78, 'Sample Child 29', 'Child', 7, 'Cainta Community School', '2019-10-23', 'Elementary', 'Student', 0.00),
(63, 79, 'Sample Child 30', 'Child', 8, 'Cainta Community School', '2018-10-23', 'Elementary', 'Student', 0.00),
(64, 80, 'Miguel Panganiban', 'Child', 16, 'Cainta Public School', '2010-01-01', 'Junior High School', 'Student', 0.00),
(65, 81, 'Sofia Villareal', 'Child', 15, 'Cainta Public School', '2011-02-02', 'Junior High School', 'Student', 0.00),
(66, 82, 'Gabriel Dela Peña', 'Child', 14, 'Cainta Public School', '2012-03-03', 'Junior High School', 'Student', 0.00),
(67, 83, 'Andrea Manalo', 'Child', 13, 'Cainta Public School', '2013-04-04', 'Junior High School', 'Student', 0.00),
(68, 84, 'Nathan Sarmiento', 'Child', 12, 'Cainta Public School', '2014-05-05', 'Junior High School', 'Student', 0.00),
(69, 85, 'Isabelle Magsino', 'Child', 11, 'Cainta Public School', '2015-06-06', 'Elementary', 'Student', 0.00),
(70, 86, 'Joshua Cabrera', 'Child', 10, 'Cainta Public School', '2016-07-07', 'Elementary', 'Student', 0.00),
(71, 87, 'Mikaela Ocampo', 'Child', 9, 'Cainta Public School', '2017-08-08', 'Elementary', 'Student', 0.00),
(72, 88, 'Daniel Santiago', 'Child', 8, 'Cainta Public School', '2018-09-09', 'Elementary', 'Student', 0.00),
(73, 89, 'Chloe Estrada', 'Child', 16, 'Cainta Public School', '2010-10-10', 'Junior High School', 'Student', 0.00),
(74, 90, 'Adrian Abella', 'Child', 14, 'Cainta Public School', '2011-11-11', 'Junior High School', 'Student', 0.00),
(75, 91, 'Bianca Valencia', 'Child', 13, 'Cainta Public School', '2012-12-12', 'Junior High School', 'Student', 0.00),
(76, 92, 'Caleb Rosales', 'Child', 13, 'Cainta Public School', '2013-01-13', 'Junior High School', 'Student', 0.00),
(77, 93, 'Faith Mercado', 'Child', 12, 'Cainta Public School', '2014-02-14', 'Junior High School', 'Student', 0.00),
(78, 94, 'Ethan Dominguez', 'Child', 11, 'Cainta Public School', '2015-03-15', 'Elementary', 'Student', 0.00),
(79, 95, 'Jasmine Arceo', 'Child', 10, 'Cainta Public School', '2016-04-16', 'Elementary', 'Student', 0.00),
(80, 96, 'Lucas Cordero', 'Child', 9, 'Cainta Public School', '2017-05-17', 'Elementary', 'Student', 0.00),
(81, 97, 'Nicole Pascual', 'Child', 8, 'Cainta Public School', '2018-06-18', 'Elementary', 'Student', 0.00),
(82, 98, 'Marco Andrada', 'Child', 16, 'Cainta Public School', '2010-07-19', 'Junior High School', 'Student', 0.00),
(83, 99, 'Rhea Vergara', 'Child', 15, 'Cainta Public School', '2011-08-20', 'Junior High School', 'Student', 0.00),
(84, 100, 'Noah Lazaro', 'Child', 14, 'Cainta Public School', '2012-09-21', 'Junior High School', 'Student', 0.00),
(85, 101, 'Shaina Belen', 'Child', 13, 'Cainta Public School', '2013-10-22', 'Junior High School', 'Student', 0.00),
(86, 102, 'Paolo Mendoza', 'Child', 11, 'Cainta Public School', '2014-11-23', 'Elementary', 'Student', 0.00),
(87, 103, 'Thea Salonga', 'Child', 10, 'Cainta Public School', '2015-12-24', 'Elementary', 'Student', 0.00),
(88, 104, 'Rafael Aquino', 'Child', 10, 'Cainta Public School', '2016-01-25', 'Elementary', 'Student', 0.00),
(89, 105, 'Ysabel Castillo', 'Child', 9, 'Cainta Public School', '2017-02-26', 'Elementary', 'Student', 0.00),
(90, 106, 'Simon Flores', 'Child', 8, 'Cainta Public School', '2018-03-27', 'Elementary', 'Student', 0.00),
(91, 107, 'Kiara Rivera', 'Child', 16, 'Cainta Public School', '2010-04-01', 'Junior High School', 'Student', 0.00),
(92, 108, 'Aaron Tan', 'Child', 15, 'Cainta Public School', '2011-05-02', 'Junior High School', 'Student', 0.00),
(93, 109, 'Mara Reyes', 'Child', 14, 'Cainta Public School', '2012-06-03', 'Junior High School', 'Student', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `solo_parent_membership`
--

CREATE TABLE `solo_parent_membership` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `philhealth_member` tinyint(1) DEFAULT NULL,
  `philhealth_number` varchar(50) DEFAULT NULL,
  `philhealth_membership_category` varchar(100) DEFAULT NULL,
  `philhealth_dependent` tinyint(1) DEFAULT NULL,
  `philhealth_dependent_name` varchar(150) DEFAULT NULL,
  `philhealth_dependent_number` varchar(50) DEFAULT NULL,
  `philhealth_relationship` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `solo_parent_membership`
--

INSERT INTO `solo_parent_membership` (`id`, `application_id`, `philhealth_member`, `philhealth_number`, `philhealth_membership_category`, `philhealth_dependent`, `philhealth_dependent_name`, `philhealth_dependent_number`, `philhealth_relationship`) VALUES
(2, 5, 1, '09111', 'Voluntary', 1, 'Edriana', '1', 'daughter'),
(5, 8, 1, '352352432423424252', '', 0, '', '', ''),
(12, 15, 0, '', '', 0, '', '', ''),
(13, 16, 1, '', 'Direct Contributor', 0, '', '', ''),
(14, 17, 0, '', '', 0, '', '', ''),
(15, 18, 0, '', '', 0, '', '', ''),
(16, 19, 0, '', '', 0, '', '', ''),
(47, 50, 0, '', '', 0, '', '', ''),
(48, 51, 0, '', '', 0, '', '', ''),
(49, 52, 0, '', '', 0, '', '', ''),
(50, 53, 0, '', '', 0, '', '', ''),
(51, 54, 0, '', '', 0, '', '', ''),
(52, 55, 0, '', '', 0, '', '', ''),
(53, 56, 0, '', '', 0, '', '', ''),
(54, 57, 0, '', '', 0, '', '', ''),
(55, 58, 0, '', '', 0, '', '', ''),
(56, 59, 0, '', '', 0, '', '', ''),
(57, 60, 0, '', '', 0, '', '', ''),
(58, 61, 0, '', '', 0, '', '', ''),
(59, 62, 0, '', '', 0, '', '', ''),
(60, 63, 0, '', '', 0, '', '', ''),
(61, 64, 0, '', '', 0, '', '', ''),
(62, 65, 0, '', '', 0, '', '', ''),
(63, 66, 0, '', '', 0, '', '', ''),
(64, 67, 0, '', '', 0, '', '', ''),
(65, 68, 0, '', '', 0, '', '', ''),
(66, 69, 0, '', '', 0, '', '', ''),
(67, 70, 0, '', '', 0, '', '', ''),
(68, 71, 0, '', '', 0, '', '', ''),
(69, 72, 0, '', '', 0, '', '', ''),
(70, 73, 0, '', '', 0, '', '', ''),
(71, 74, 0, '', '', 0, '', '', ''),
(72, 75, 0, '', '', 0, '', '', ''),
(73, 76, 0, '', '', 0, '', '', ''),
(74, 77, 0, '', '', 0, '', '', ''),
(75, 78, 0, '', '', 0, '', '', ''),
(76, 79, 0, '', '', 0, '', '', ''),
(77, 80, 0, '', '', 0, '', '', ''),
(78, 81, 0, '', '', 0, '', '', ''),
(79, 82, 0, '', '', 0, '', '', ''),
(80, 83, 0, '', '', 0, '', '', ''),
(81, 84, 0, '', '', 0, '', '', ''),
(82, 85, 0, '', '', 0, '', '', ''),
(83, 86, 0, '', '', 0, '', '', ''),
(84, 87, 0, '', '', 0, '', '', ''),
(85, 88, 0, '', '', 0, '', '', ''),
(86, 89, 0, '', '', 0, '', '', ''),
(87, 90, 0, '', '', 0, '', '', ''),
(88, 91, 0, '', '', 0, '', '', ''),
(89, 92, 0, '', '', 0, '', '', ''),
(90, 93, 0, '', '', 0, '', '', ''),
(91, 94, 0, '', '', 0, '', '', ''),
(92, 95, 0, '', '', 0, '', '', ''),
(93, 96, 0, '', '', 0, '', '', ''),
(94, 97, 0, '', '', 0, '', '', ''),
(95, 98, 0, '', '', 0, '', '', ''),
(96, 99, 0, '', '', 0, '', '', ''),
(97, 100, 0, '', '', 0, '', '', ''),
(98, 101, 0, '', '', 0, '', '', ''),
(99, 102, 0, '', '', 0, '', '', ''),
(100, 103, 0, '', '', 0, '', '', ''),
(101, 104, 0, '', '', 0, '', '', ''),
(102, 105, 0, '', '', 0, '', '', ''),
(103, 106, 0, '', '', 0, '', '', ''),
(104, 107, 0, '', '', 0, '', '', ''),
(105, 108, 0, '', '', 0, '', '', ''),
(106, 109, 0, '', '', 0, '', '', ''),
(107, 110, 0, '', '', 0, '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `solo_parent_personal`
--

CREATE TABLE `solo_parent_personal` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `name_extension` varchar(20) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `place_of_birth` varchar(150) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `civil_status` varchar(30) DEFAULT NULL,
  `education` varchar(100) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `monthly_income` decimal(10,2) DEFAULT NULL,
  `employer_address` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `solo_parent_personal`
--

INSERT INTO `solo_parent_personal` (`id`, `application_id`, `last_name`, `first_name`, `middle_name`, `name_extension`, `birthdate`, `place_of_birth`, `age`, `gender`, `civil_status`, `education`, `religion`, `occupation`, `monthly_income`, `employer_address`, `email`, `phone`) VALUES
(2, 5, 'Zabaldan', 'Edward', 'Sadangsal', 'Jr', '2003-12-30', 'Cainta, Rizal', 22, 'Male', 'Single', 'College', 'Christian', 'It support', 1000.00, 'N/A', 'Edward@gmail.com', '09123123123'),
(5, 8, 'Sangalang', 'Melvin', 'Alcantara', '', '2005-01-25', '0', 21, 'Male', 'Single', 'College', 'Christian', 'Student', 10000.00, '9st Baranggay san anders, Cainta, Rizal', 'sangalangmelvin25@gmail.com', '09334561020'),
(12, 15, 'Ogalesco', 'John', 'Carlou', '', '2004-08-04', 'Cainta', 22, 'Male', 'Separated', 'College', 'Christian', 'Delivery Rider', 5000.00, 'N/A', 'sadnaman69@gmail.com', '09123123123'),
(13, 16, 'Teresita', 'Mirasol', 'Bunyi', '', '1995-08-30', 'Cainta,Rizal', 31, 'Male', 'Widowed', 'College', 'Roman Catholic', 'Massage Therapist', 7000.00, 'McArthur Bldg San Mateo', 'sadnaman69@gmail.com', '09123123123'),
(14, 17, 'Arroyo', 'Alexander', 'Asibor', '', '2003-10-07', 'Cainta,Rizal', 23, 'Male', 'Annulled', 'College', 'Roman Catholic', 'Mechanic', 8000.00, 'N/A', 'sadnaman69@gmail.com', '09123123123'),
(15, 18, 'San', 'Jade', 'Tuto', '', '2003-09-22', 'Cainta,Rizal', 23, 'Male', 'Live-in', 'College', 'Christian', 'Delivery Rider', 6000.00, 'N/A', 'sadnaman69@gmail.com', '09123123123'),
(16, 19, 'Diones', 'Chrisse', 'Jay', '', '2004-09-13', 'Cainta,Rizal', 22, 'Male', 'Separated', 'College', 'Roman Catholic', 'Mechanic', 12000.00, 'N/A', 'sadnaman69@gmail.com', '09123123123'),
(47, 50, 'Del Rosario', 'Mara', 'Sample', '', '2002-10-23', 'Cainta, Rizal', 24, 'Female', 'widowed', 'High school graduate', 'Catholic', 'vendor', 7500.00, 'Cainta, Rizal', 'cainta.applicant01@example.invalid', '09000000001'),
(48, 51, 'Mercado', 'Lina', 'Sample', '', '2001-10-23', 'Cainta, Rizal', 25, 'Female', 'separated', 'High school graduate', 'Christian', 'seamstress', 7875.00, 'Cainta, Rizal', 'cainta.applicant02@example.invalid', '09000000002'),
(49, 52, 'Villanueva', 'Rosa', 'Sample', '', '2000-10-23', 'Cainta, Rizal', 26, 'Female', 'single', 'High school graduate', 'Iglesia ni Cristo', 'food vendor', 8250.00, 'Cainta, Rizal', 'cainta.applicant03@example.invalid', '09000000003'),
(50, 53, 'Manalo', 'Elena', 'Sample', '', '1999-10-23', 'Cainta, Rizal', 27, 'Female', 'widowed', 'High school graduate', 'Catholic', 'laundry worker', 8625.00, 'Cainta, Rizal', 'cainta.applicant04@example.invalid', '09000000004'),
(51, 54, 'Santiago', 'Nina', 'Sample', '', '1998-10-23', 'Cainta, Rizal', 28, 'Female', 'separated', 'High school graduate', 'Christian', 'sales clerk', 9000.00, 'Cainta, Rizal', 'cainta.applicant05@example.invalid', '09000000005'),
(52, 55, 'Bautista', 'Celia', 'Sample', '', '1997-10-23', 'Cainta, Rizal', 29, 'Female', 'single', 'High school graduate', 'Iglesia ni Cristo', 'home-based baker', 9375.00, 'Cainta, Rizal', 'cainta.applicant06@example.invalid', '09000000006'),
(53, 56, 'Navarro', 'Tessa', 'Sample', '', '1996-10-23', 'Cainta, Rizal', 30, 'Female', 'widowed', 'High school graduate', 'Catholic', 'market vendor', 9750.00, 'Cainta, Rizal', 'cainta.applicant07@example.invalid', '09000000007'),
(54, 57, 'Dizon', 'Alma', 'Sample', '', '1995-10-23', 'Cainta, Rizal', 31, 'Female', 'separated', 'High school graduate', 'Christian', 'caregiver', 10125.00, 'Cainta, Rizal', 'cainta.applicant08@example.invalid', '09000000008'),
(55, 58, 'Aquino', 'Joy', 'Sample', '', '1994-10-23', 'Cainta, Rizal', 32, 'Female', 'single', 'High school graduate', 'Iglesia ni Cristo', 'utility worker', 10500.00, 'Cainta, Rizal', 'cainta.applicant09@example.invalid', '09000000009'),
(56, 59, 'Castillo', 'Lourdes', 'Sample', '', '1993-10-23', 'Cainta, Rizal', 33, 'Female', 'widowed', 'High school graduate', 'Catholic', 'tailor', 10875.00, 'Cainta, Rizal', 'cainta.applicant10@example.invalid', '09000000010'),
(57, 60, 'Salazar', 'Mila', 'Sample', '', '1992-10-23', 'Cainta, Rizal', 34, 'Female', 'separated', 'High school graduate', 'Christian', 'canteen aide', 11250.00, 'Cainta, Rizal', 'cainta.applicant11@example.invalid', '09000000011'),
(58, 61, 'Cruz', 'Ana', 'Sample', '', '1991-10-23', 'Cainta, Rizal', 35, 'Female', 'single', 'High school graduate', 'Iglesia ni Cristo', 'online seller', 11625.00, 'Cainta, Rizal', 'cainta.applicant12@example.invalid', '09000000012'),
(59, 62, 'Ramos', 'Bea', 'Sample', '', '1990-10-23', 'Cainta, Rizal', 36, 'Female', 'widowed', 'High school graduate', 'Catholic', 'housekeeper', 12000.00, 'Cainta, Rizal', 'cainta.applicant13@example.invalid', '09000000013'),
(60, 63, 'Garcia', 'Cora', 'Sample', '', '1989-10-23', 'Cainta, Rizal', 37, 'Female', 'separated', 'High school graduate', 'Christian', 'cashier', 12375.00, 'Cainta, Rizal', 'cainta.applicant14@example.invalid', '09000000014'),
(61, 64, 'Flores', 'Dina', 'Sample', '', '1988-10-23', 'Cainta, Rizal', 38, 'Female', 'single', 'High school graduate', 'Iglesia ni Cristo', 'dressmaker', 12750.00, 'Cainta, Rizal', 'cainta.applicant15@example.invalid', '09000000015'),
(62, 65, 'Santos', 'Eva', 'Sample', '', '1987-10-23', 'Cainta, Rizal', 39, 'Female', 'widowed', 'High school graduate', 'Catholic', 'food stall helper', 13125.00, 'Cainta, Rizal', 'cainta.applicant16@example.invalid', '09000000016'),
(63, 66, 'Reyes', 'Faye', 'Sample', '', '1986-10-23', 'Cainta, Rizal', 40, 'Female', 'separated', 'High school graduate', 'Christian', 'care assistant', 13500.00, 'Cainta, Rizal', 'cainta.applicant17@example.invalid', '09000000017'),
(64, 67, 'Mendoza', 'Gina', 'Sample', '', '1985-10-23', 'Cainta, Rizal', 41, 'Female', 'single', 'High school graduate', 'Iglesia ni Cristo', 'home-based seller', 13875.00, 'Cainta, Rizal', 'cainta.applicant18@example.invalid', '09000000018'),
(65, 68, 'Torres', 'Hana', 'Sample', '', '1984-10-23', 'Cainta, Rizal', 42, 'Female', 'widowed', 'High school graduate', 'Catholic', 'laundry worker', 14250.00, 'Cainta, Rizal', 'cainta.applicant19@example.invalid', '09000000019'),
(66, 69, 'Domingo', 'Ivy', 'Sample', '', '1983-10-23', 'Cainta, Rizal', 43, 'Female', 'separated', 'High school graduate', 'Christian', 'production worker', 14625.00, 'Cainta, Rizal', 'cainta.applicant20@example.invalid', '09000000020'),
(67, 70, 'Valdez', 'Kara', 'Sample', '', '1982-10-23', 'Cainta, Rizal', 44, 'Female', 'single', 'High school graduate', 'Iglesia ni Cristo', 'market vendor', 15000.00, 'Cainta, Rizal', 'cainta.applicant21@example.invalid', '09000000021'),
(68, 71, 'Pascual', 'Lea', 'Sample', '', '1981-10-23', 'Cainta, Rizal', 45, 'Female', 'widowed', 'High school graduate', 'Catholic', 'cook', 15375.00, 'Cainta, Rizal', 'cainta.applicant22@example.invalid', '09000000022'),
(69, 72, 'Soriano', 'Mina', 'Sample', '', '1980-10-23', 'Cainta, Rizal', 46, 'Female', 'separated', 'High school graduate', 'Christian', 'sales assistant', 15750.00, 'Cainta, Rizal', 'cainta.applicant23@example.invalid', '09000000023'),
(70, 73, 'Espiritu', 'Nora', 'Sample', '', '2002-10-23', 'Cainta, Rizal', 24, 'Female', 'single', 'High school graduate', 'Iglesia ni Cristo', 'daycare aide', 16125.00, 'Cainta, Rizal', 'cainta.applicant24@example.invalid', '09000000024'),
(71, 74, 'Lazaro', 'Olive', 'Sample', '', '2001-10-23', 'Cainta, Rizal', 25, 'Female', 'widowed', 'High school graduate', 'Catholic', 'tailor', 16500.00, 'Cainta, Rizal', 'cainta.applicant25@example.invalid', '09000000025'),
(72, 75, 'Fernandez', 'Pia', 'Sample', '', '2000-10-23', 'Cainta, Rizal', 26, 'Female', 'separated', 'High school graduate', 'Christian', 'food vendor', 16875.00, 'Cainta, Rizal', 'cainta.applicant26@example.invalid', '09000000026'),
(73, 76, 'Andrada', 'Queenie', 'Sample', '', '1999-10-23', 'Cainta, Rizal', 27, 'Female', 'single', 'High school graduate', 'Iglesia ni Cristo', 'caregiver', 17250.00, 'Cainta, Rizal', 'cainta.applicant27@example.invalid', '09000000027'),
(74, 77, 'Ocampo', 'Rina', 'Sample', '', '1998-10-23', 'Cainta, Rizal', 28, 'Female', 'widowed', 'High school graduate', 'Catholic', 'home-based baker', 17625.00, 'Cainta, Rizal', 'cainta.applicant28@example.invalid', '09000000028'),
(75, 78, 'Vergara', 'Sela', 'Sample', '', '1997-10-23', 'Cainta, Rizal', 29, 'Female', 'separated', 'High school graduate', 'Christian', 'retail assistant', 18000.00, 'Cainta, Rizal', 'cainta.applicant29@example.invalid', '09000000029'),
(76, 79, 'Abad', 'Tina', 'Sample', '', '1996-10-23', 'Cainta, Rizal', 30, 'Female', 'single', 'High school graduate', 'Iglesia ni Cristo', 'seamstress', 18375.00, 'Cainta, Rizal', 'cainta.applicant30@example.invalid', '09000000030'),
(77, 80, 'Panganiban', 'Alyssa', 'Mariel', '', '1988-03-14', 'Cainta, Rizal', 38, 'Female', 'Single', 'College Graduate', 'Catholic', 'Vendor', 9200.00, '0', '', '09171000001'),
(78, 81, 'Villareal', 'Bernadette', 'Lourdes', '', '1983-07-22', 'Cainta, Rizal', 43, 'Female', 'Separated', 'High School Graduate', 'Catholic', 'Caregiver', 11000.00, '0', '', '09171000002'),
(79, 82, 'Dela Peña', 'Camille', 'Rosario', '', '1991-11-09', 'Cainta, Rizal', 34, 'Female', 'Widowed', 'College Level', 'Catholic', 'Cashier', 12500.00, '0', '', '09171000003'),
(80, 83, 'Manalo', 'Danica', 'Cecilia', '', '1986-05-18', 'Cainta, Rizal', 40, 'Female', 'Separated', 'Vocational Graduate', 'Catholic', 'Food Service Worker', 10500.00, '0', '', '09171000004'),
(81, 84, 'Sarmiento', 'Eloisa', 'Teresa', '', '1994-02-03', 'Cainta, Rizal', 32, 'Female', 'Single', 'College Graduate', 'Catholic', 'Office Clerk', 13800.00, '0', '', '09171000005'),
(82, 85, 'Magsino', 'Frances', 'Pilar', '', '1981-09-26', 'Cainta, Rizal', 45, 'Female', 'Widowed', 'High School Graduate', 'Catholic', 'Laundry Worker', 7800.00, '0', '', '09171000006'),
(83, 86, 'Cabrera', 'Georgina', 'Milagros', '', '1989-12-12', 'Cainta, Rizal', 36, 'Female', 'Separated', 'College Level', 'Catholic', 'Sales Associate', 11800.00, '0', '', '09171000007'),
(84, 87, 'Ocampo', 'Hannah', 'Beatriz', '', '1996-04-07', 'Cainta, Rizal', 30, 'Female', 'Single', 'College Graduate', 'Catholic', 'Teaching Assistant', 13000.00, '0', '', '09171000008'),
(85, 88, 'Santiago', 'Isabel', 'Corazon', '', '1984-06-30', 'Cainta, Rizal', 42, 'Female', 'Separated', 'Vocational Graduate', 'Catholic', 'Tailor', 9700.00, '0', '', '09171000009'),
(86, 89, 'Estrada', 'Janine', 'Remedios', '', '1990-10-16', 'Cainta, Rizal', 36, 'Female', 'Widowed', 'College Graduate', 'Catholic', 'Baker', 11200.00, '0', '', '09171000010'),
(87, 90, 'Abella', 'Kristine', 'Marina', '', '1987-01-24', 'Cainta, Rizal', 39, 'Female', 'Separated', 'High School Graduate', 'Christian', 'Market Vendor', 8600.00, '0', '', '09171000011'),
(88, 91, 'Valencia', 'Lorraine', 'Elena', '', '1993-08-11', 'Cainta, Rizal', 33, 'Female', 'Single', 'College Level', 'Christian', 'Service Crew', 10400.00, '0', '', '09171000012'),
(89, 92, 'Rosales', 'Maricel', 'Aurora', '', '1982-12-05', 'Cainta, Rizal', 43, 'Female', 'Widowed', 'Elementary Graduate', 'Christian', 'Home-based Worker', 6900.00, '0', '', '09171000013'),
(90, 93, 'Mercado', 'Nadine', 'Esperanza', '', '1998-07-19', 'Cainta, Rizal', 28, 'Female', 'Single', 'College Graduate', 'Christian', 'Customer Service Agent', 15500.00, '0', '', '09171000014'),
(91, 94, 'Dominguez', 'Ofelia', 'Adela', '', '1985-04-28', 'Cainta, Rizal', 41, 'Female', 'Separated', 'High School Graduate', 'Christian', 'Cook', 9000.00, '0', '', '09171000015'),
(92, 95, 'Arceo', 'Precious', 'Gloria', '', '1992-02-15', 'Cainta, Rizal', 34, 'Female', 'Single', 'Vocational Graduate', 'Christian', 'Care Assistant', 12100.00, '0', '', '09171000016'),
(93, 96, 'Cordero', 'Queenie', 'Rizalina', '', '1980-11-20', 'Cainta, Rizal', 45, 'Female', 'Widowed', 'College Level', 'Christian', 'Canteen Helper', 8200.00, '0', '', '09171000017'),
(94, 97, 'Pascual', 'Rachelle', 'Amelia', '', '1995-06-08', 'Cainta, Rizal', 31, 'Female', 'Separated', 'College Graduate', 'Christian', 'Bookkeeper', 14500.00, '0', '', '09171000018'),
(95, 98, 'Andrada', 'Sheila', 'Estrella', '', '1986-09-13', 'Cainta, Rizal', 40, 'Female', 'Single', 'High School Graduate', 'Christian', 'Production Worker', 10800.00, '0', '', '09171000019'),
(96, 99, 'Vergara', 'Tricia', 'Lourdes', '', '1990-01-31', 'Cainta, Rizal', 36, 'Female', 'Separated', 'College Graduate', 'Christian', 'Pharmacy Assistant', 13200.00, '0', '', '09171000020'),
(97, 100, 'Lazaro', 'Vanessa', 'Minda', '', '1983-05-06', 'Cainta, Rizal', 43, 'Female', 'Widowed', 'Vocational Graduate', 'Iglesia ni Cristo', 'Dressmaker', 9400.00, '0', '', '09171000021'),
(98, 101, 'Belen', 'Wilma', 'Rosita', '', '1989-10-25', 'Cainta, Rizal', 36, 'Female', 'Separated', 'College Level', 'Iglesia ni Cristo', 'Receptionist', 11700.00, '0', '', '09171000022'),
(99, 102, 'Mendoza', 'Xenia', 'Carmen', '', '1997-03-17', 'Cainta, Rizal', 29, 'Female', 'Single', 'College Graduate', 'Iglesia ni Cristo', 'Library Assistant', 12600.00, '0', '', '09171000023'),
(100, 103, 'Salonga', 'Yvette', 'Dalisay', '', '1984-07-01', 'Cainta, Rizal', 42, 'Female', 'Separated', 'High School Graduate', 'Iglesia ni Cristo', 'Household Worker', 8500.00, '0', '', '09171000024'),
(101, 104, 'Aquino', 'Zenaida', 'Rosario', '', '1979-12-29', 'Cainta, Rizal', 46, 'Female', 'Widowed', 'Elementary Graduate', 'Iglesia ni Cristo', 'Sari-sari Store Owner', 7600.00, '0', '', '09171000025'),
(102, 105, 'Castillo', 'Adriana', 'Mylene', '', '1991-09-04', 'Cainta, Rizal', 35, 'Female', 'Separated', 'College Graduate', 'Iglesia ni Cristo', 'Dental Assistant', 14000.00, '0', '', '09171000026'),
(103, 106, 'Flores', 'Belinda', 'Cynthia', '', '1988-02-21', 'Cainta, Rizal', 38, 'Female', 'Single', 'Vocational Graduate', 'Iglesia ni Cristo', 'Textile Worker', 9900.00, '0', '', '09171000027'),
(104, 107, 'Rivera', 'Charmaine', 'Lilia', '', '1994-11-15', 'Cainta, Rizal', 31, 'Female', 'Separated', 'College Level', 'Iglesia ni Cristo', 'Food Stall Attendant', 8800.00, '0', '', '09171000028'),
(105, 108, 'Tan', 'Darlene', 'Josephine', '', '1982-06-12', 'Cainta, Rizal', 44, 'Female', 'Widowed', 'High School Graduate', 'Iglesia ni Cristo', 'Caregiver', 11500.00, '0', '', '09171000029'),
(106, 109, 'Reyes', 'Emelita', 'Concepcion', '', '1987-08-23', 'Cainta, Rizal', 39, 'Female', 'Separated', 'College Graduate', 'Iglesia ni Cristo', 'Accounting Clerk', 13600.00, '0', '', '09171000030'),
(107, 110, 'Ogalesco', 'John', 'Carlou', '', '2004-08-04', 'Cainta,Rizal', 22, 'Male', 'Widowed', 'College', 'Christian', 'Delivery Rider', 7500.00, 'N/A', 'sadnaman69@gmail.com', '09123123123');

-- --------------------------------------------------------

--
-- Table structure for table `solo_parent_programs`
--

CREATE TABLE `solo_parent_programs` (
  `id` int(11) NOT NULL,
  `application_id` int(11) NOT NULL,
  `program_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `solo_parent_spouse`
--

CREATE TABLE `solo_parent_spouse` (
  `id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `spouse_name` varchar(150) DEFAULT NULL,
  `spouse_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `solo_parent_spouse`
--

INSERT INTO `solo_parent_spouse` (`id`, `application_id`, `spouse_name`, `spouse_address`) VALUES
(2, 5, '', ''),
(5, 8, 'N/A', 'N/A'),
(12, 15, 'Abigeille Zapanta', 'Sampaguita st. brgy mangahan Pasig City'),
(13, 16, 'N/A', 'N/A'),
(14, 17, 'Kristine Caldosa', 'N/A'),
(15, 18, 'Rodilyn Silagpo', 'N/A'),
(16, 19, 'Hailey Williams', 'N/A'),
(47, 50, '', ''),
(48, 51, '', ''),
(49, 52, '', ''),
(50, 53, '', ''),
(51, 54, '', ''),
(52, 55, '', ''),
(53, 56, '', ''),
(54, 57, '', ''),
(55, 58, '', ''),
(56, 59, '', ''),
(57, 60, '', ''),
(58, 61, '', ''),
(59, 62, '', ''),
(60, 63, '', ''),
(61, 64, '', ''),
(62, 65, '', ''),
(63, 66, '', ''),
(64, 67, '', ''),
(65, 68, '', ''),
(66, 69, '', ''),
(67, 70, '', ''),
(68, 71, '', ''),
(69, 72, '', ''),
(70, 73, '', ''),
(71, 74, '', ''),
(72, 75, '', ''),
(73, 76, '', ''),
(74, 77, '', ''),
(75, 78, '', ''),
(76, 79, '', ''),
(77, 80, '', ''),
(78, 81, '', ''),
(79, 82, '', ''),
(80, 83, '', ''),
(81, 84, '', ''),
(82, 85, '', ''),
(83, 86, '', ''),
(84, 87, '', ''),
(85, 88, '', ''),
(86, 89, '', ''),
(87, 90, '', ''),
(88, 91, '', ''),
(89, 92, '', ''),
(90, 93, '', ''),
(91, 94, '', ''),
(92, 95, '', ''),
(93, 96, '', ''),
(94, 97, '', ''),
(95, 98, '', ''),
(96, 99, '', ''),
(97, 100, '', ''),
(98, 101, '', ''),
(99, 102, '', ''),
(100, 103, '', ''),
(101, 104, '', ''),
(102, 105, '', ''),
(103, 106, '', ''),
(104, 107, '', ''),
(105, 108, '', ''),
(106, 109, '', ''),
(107, 110, 'Layla De Lima', 'N/A');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `is_staff` tinyint(1) DEFAULT 0,
  `is_verified` tinyint(1) DEFAULT 0,
  `account_status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `activation_code` varchar(255) DEFAULT NULL,
  `verification_document` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `reset_code` varchar(6) DEFAULT NULL,
  `reset_code_expiry` datetime DEFAULT NULL,
  `aics_cooldown_until` datetime DEFAULT NULL,
  `failed_login_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `password`, `otp`, `otp_expiry`, `created_at`, `is_admin`, `is_staff`, `is_verified`, `account_status`, `activation_code`, `verification_document`, `profile_image`, `reset_code`, `reset_code_expiry`, `aics_cooldown_until`, `failed_login_attempts`, `locked_until`) VALUES
(2, 'Edward Zabaldan', 'isnekdragon@gmail.com', '$2y$10$rDjvsdTDRPnF31B48LDYCeGLGOugrEy1MV4srvMiABsnK2mR2w7vG', NULL, NULL, '2026-03-03 06:48:36', 0, 0, 1, 'pending', NULL, NULL, 'profile_a748d4129234d82a.png', NULL, NULL, '2026-06-07 14:58:15', 0, NULL),
(3, 'Edward Zabaldan', 'edwardsadangsal30@gmail.com', '$2y$10$zwX27wsS95IFwpIwe9bj0OtTjwnGrHk1dVEmRrmg5woHjfGoT8Hxe', NULL, NULL, '2026-03-03 07:33:37', 1, 0, 1, 'approved', NULL, NULL, 'profile_4b52473926825ec6.png', NULL, NULL, '2026-12-21 07:12:52', 0, NULL),
(34, 'Melvin Sangalang', 'sangalangmelvin18@gmail.com', '$2y$10$m3umZhAQigZdSrIZ093rte8Rqht6bqwMudW4JlTGxzbNEstFX7C9y', NULL, NULL, '2026-08-09 12:12:29', 0, 0, 1, 'approved', NULL, NULL, NULL, NULL, NULL, '2026-11-28 17:12:55', 0, NULL),
(42, 'Edrian Senobio', 'edriansenobio98@gmail.com', '$2y$10$RZ1dCLOehv6K7cxJCk9z7ur33wtR4LorGby.4tmk3GbqECeg0DM62', NULL, NULL, '2026-10-23 03:34:16', 0, 0, 0, 'pending', '8a86ff1b5ade6157aa6bacb083bffbf4', 'uploads/verification/58ab184e068919f006c225a40c28d8e6.jpg', NULL, NULL, NULL, NULL, 0, NULL),
(43, 'Chrisse Jay Diones', '00edwardo2026@gmail.com', '$2y$10$.G71FElUoP5ZDVvCzFlf2u3vOpMUaPa9j/vEM6smAaTGEYE3/cFzK', NULL, NULL, '2026-10-23 04:51:03', 0, 0, 0, 'pending', '99787afd046e77dd9b43fd30105fd9e7', 'uploads/verification/0f837a3841accf59f655fc69341d09f7.jpg', NULL, NULL, NULL, NULL, 0, NULL),
(44, 'John Carlou Ogalesco', 'sadnaman69@gmail.com', '$2y$10$0uCg7UCTj0wnsTsSjz.3bOzgn9Ri.5ENsLFN3hO7BT2a3qkWSnjLS', NULL, NULL, '2026-10-23 05:01:21', 0, 0, 1, 'approved', NULL, 'uploads/verification/685b20b111f99ed00abae071ef2d692d.jpg', NULL, NULL, NULL, NULL, 0, NULL),
(45, 'Jhon Gray Trinidad', 'edwardzabaldan@gmail.com', '$2y$10$3uFsBWtySPfMCczPIrj2Dedow.spyf7qZeZ6/Zm69SpEv8T0ggw1a', NULL, NULL, '2026-10-23 05:03:26', 0, 0, 1, 'pending', NULL, 'uploads/verification/ad58fc434d8c3528092028f36999e6c6.jpg', NULL, NULL, NULL, NULL, 0, NULL),
(46, 'Solo Parent Synthetic Test Account', 'solo.parent.seed@example.invalid', '$2y$10$b76YKMo3lr9SFY7hPNPJ4uO3S7fI875DEWOBmmiM3THSo0JuUtMpO', NULL, NULL, '2026-10-23 06:49:53', 0, 0, 0, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aics_addresses`
--
ALTER TABLE `aics_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `aics_applicants`
--
ALTER TABLE `aics_applicants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `aics_applications`
--
ALTER TABLE `aics_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `aics_representatives`
--
ALTER TABLE `aics_representatives`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_announcement_user` (`user_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `action` (`action`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_news_user` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `solo_parent_additional_info`
--
ALTER TABLE `solo_parent_additional_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `solo_parent_address`
--
ALTER TABLE `solo_parent_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `solo_parent_applications`
--
ALTER TABLE `solo_parent_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `solo_parent_emergency_contact`
--
ALTER TABLE `solo_parent_emergency_contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `solo_parent_family_members`
--
ALTER TABLE `solo_parent_family_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `solo_parent_membership`
--
ALTER TABLE `solo_parent_membership`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `solo_parent_personal`
--
ALTER TABLE `solo_parent_personal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `solo_parent_programs`
--
ALTER TABLE `solo_parent_programs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_solo_parent_program_app` (`application_id`);

--
-- Indexes for table `solo_parent_spouse`
--
ALTER TABLE `solo_parent_spouse`
  ADD PRIMARY KEY (`id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aics_addresses`
--
ALTER TABLE `aics_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `aics_applicants`
--
ALTER TABLE `aics_applicants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `aics_applications`
--
ALTER TABLE `aics_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `aics_representatives`
--
ALTER TABLE `aics_representatives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=588;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `solo_parent_additional_info`
--
ALTER TABLE `solo_parent_additional_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `solo_parent_address`
--
ALTER TABLE `solo_parent_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `solo_parent_applications`
--
ALTER TABLE `solo_parent_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `solo_parent_emergency_contact`
--
ALTER TABLE `solo_parent_emergency_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `solo_parent_family_members`
--
ALTER TABLE `solo_parent_family_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT for table `solo_parent_membership`
--
ALTER TABLE `solo_parent_membership`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `solo_parent_personal`
--
ALTER TABLE `solo_parent_personal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `solo_parent_programs`
--
ALTER TABLE `solo_parent_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `solo_parent_spouse`
--
ALTER TABLE `solo_parent_spouse`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aics_addresses`
--
ALTER TABLE `aics_addresses`
  ADD CONSTRAINT `aics_addresses_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `aics_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aics_applicants`
--
ALTER TABLE `aics_applicants`
  ADD CONSTRAINT `aics_applicants_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `aics_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aics_applications`
--
ALTER TABLE `aics_applications`
  ADD CONSTRAINT `aics_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `aics_representatives`
--
ALTER TABLE `aics_representatives`
  ADD CONSTRAINT `aics_representatives_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `aics_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `fk_announcement_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `solo_parent_additional_info`
--
ALTER TABLE `solo_parent_additional_info`
  ADD CONSTRAINT `solo_parent_additional_info_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `solo_parent_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `solo_parent_address`
--
ALTER TABLE `solo_parent_address`
  ADD CONSTRAINT `solo_parent_address_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `solo_parent_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `solo_parent_applications`
--
ALTER TABLE `solo_parent_applications`
  ADD CONSTRAINT `solo_parent_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `solo_parent_emergency_contact`
--
ALTER TABLE `solo_parent_emergency_contact`
  ADD CONSTRAINT `solo_parent_emergency_contact_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `solo_parent_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `solo_parent_family_members`
--
ALTER TABLE `solo_parent_family_members`
  ADD CONSTRAINT `solo_parent_family_members_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `solo_parent_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `solo_parent_membership`
--
ALTER TABLE `solo_parent_membership`
  ADD CONSTRAINT `solo_parent_membership_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `solo_parent_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `solo_parent_personal`
--
ALTER TABLE `solo_parent_personal`
  ADD CONSTRAINT `solo_parent_personal_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `solo_parent_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `solo_parent_programs`
--
ALTER TABLE `solo_parent_programs`
  ADD CONSTRAINT `fk_solo_parent_program_app` FOREIGN KEY (`application_id`) REFERENCES `solo_parent_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `solo_parent_spouse`
--
ALTER TABLE `solo_parent_spouse`
  ADD CONSTRAINT `solo_parent_spouse_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `solo_parent_applications` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
