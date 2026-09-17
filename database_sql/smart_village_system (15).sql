-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2026 at 06:51 AM
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
-- Database: `smart_village_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `email`, `password`) VALUES
(1, 'sharmilas29075@gmail.com', 'sharmi@0709'),
(2, 'admin2712@gmail.com', '1234');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` varchar(20) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `event_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `description`, `category`, `date`, `status`, `event_date`) VALUES
('ANN9186', 'Grama Sabha Meeting', 'On Account of Gandhi Jayanthi our village panchayat is going to conduct a Grama sabha meeting on 2nd october 2026.Everyone are asked to participate in the meeting.', 'Event', '2026-06-22', 'Active', '2026-10-02');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `certificate_no` varchar(30) DEFAULT NULL,
  `citizen` varchar(100) NOT NULL,
  `certificate_type` varchar(100) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `application_data` longtext DEFAULT NULL,
  `issue_date` date NOT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `user_id` int(11) DEFAULT NULL,
  `applied_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_comment` text DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificate_documents`
--

CREATE TABLE `certificate_documents` (
  `id` int(11) NOT NULL,
  `certificate_no` varchar(30) DEFAULT NULL,
  `document_name` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificate_updates`
--

CREATE TABLE `certificate_updates` (
  `id` int(11) NOT NULL,
  `certificate_no` varchar(30) DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `response` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `message`, `response`) VALUES
(134, 'tax collection', '💰 Total tax amount: ₹45600.00'),
(135, 'paid', '✅ Paid tax records: 1'),
(136, 'paid amount', '✅ Paid tax records: 1'),
(137, 'hi', '👋 Hello! Welcome to Smart Village AI Assistant.\r\n\r\nI am here to help you with village services, complaints, taxes, certificates, agriculture, development and general questions.'),
(138, 'total tax', '💰 Total tax amount: ₹57900.00');

-- --------------------------------------------------------

--
-- Table structure for table `citizens`
--

CREATE TABLE `citizens` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `village` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `citizens`
--

INSERT INTO `citizens` (`id`, `name`, `age`, `address`, `village`) VALUES
(5, 'Sharmila', 20, '4/7 Main Road', 'Reddiyarpatti'),
(6, 'Meena', 35, '2/3 north street ', 'Reddiyarpatti'),
(7, 'Priya', 17, '318/90 East Street ', 'Reddiyarpatti'),
(8, 'devi', 28, '267 North Street', 'Reddiyarpatti');

-- --------------------------------------------------------

--
-- Table structure for table `citizen_login`
--

CREATE TABLE `citizen_login` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `village` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `dob` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `citizen_login`
--

INSERT INTO `citizen_login` (`id`, `name`, `email`, `password`, `phone`, `village`, `created_at`, `dob`, `gender`, `address`, `profile_pic`) VALUES
(9, 'Sharmila', 'sharmila@gmail.com', '12345', '6374764590', 'sathankulam', '2026-06-22 05:30:56', '2006-09-07', 'Female', '4/7 Main road vijayaramapuram', '1782106370_person.png'),
(10, 'Priya', 'priya123@gmail.com', '1234', '6737398834', 'Sathankulam', '2026-06-23 04:17:24', '2009-01-21', 'Female', '378/90 East Street sathankulam', '1782188624_1781799292_siva.jpeg'),
(11, 'Robi', 'robi123@gmail.com', '12345', NULL, NULL, '2026-06-23 17:17:23', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` varchar(20) NOT NULL,
  `citizen` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `priority` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `status` varchar(30) NOT NULL,
  `officer` varchar(100) NOT NULL,
  `zone` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `citizen`, `category`, `priority`, `description`, `date`, `status`, `officer`, `zone`) VALUES
('CMP000001', 'Sharmila', 'Garbage Collection', 'Medium', 'Title: Improper Garbage Collection | Desc: Garbage Collection in our is not regular. It causes many problems in our area. | Location: 345 b north street  | Mobile: 9943393585 | Email: sharmilas29075@gmail.com | Village: Sathankulam', '2026-06-21', 'Resolved', 'Sanitary Worker:Kumar', 'North'),
('CMP000002', 'Priya', 'Road Damage', 'Medium', 'Title: Damage in our area main road. | Desc: In our area the main road is damaged in drastic way.It affects all the vehicles and imposing the danger of causing accidents. | Location: 15B velan store opposite road,east street,sathankulam | Mobile: 9789567456 | Email: priya123@gmail.com | Village: Sathankulam', '2026-06-23', 'Pending', 'Raja', 'East');

-- --------------------------------------------------------

--
-- Table structure for table `complaint_updates`
--

CREATE TABLE `complaint_updates` (
  `id` int(11) NOT NULL,
  `complaint_id` varchar(20) DEFAULT NULL,
  `update_text` text DEFAULT NULL,
  `update_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emergency_contacts`
--

CREATE TABLE `emergency_contacts` (
  `id` int(11) NOT NULL,
  `department` varchar(100) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_contacts`
--

INSERT INTO `emergency_contacts` (`id`, `department`, `contact_person`, `phone`, `address`) VALUES
(1, 'Hospital', 'Dr.meena', '6374764590', '\r\n2/0978 main road Vedha Clinic Sathankulam\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `status` varchar(20) NOT NULL,
  `dept` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `title`, `category`, `amount`, `expense_date`, `status`, `dept`) VALUES
(6, 'Electrician Ravi salary', 'Salary', 25000.00, '2026-06-12', 'Paid', 'Electricity');

-- --------------------------------------------------------

--
-- Table structure for table `government_schemes`
--

CREATE TABLE `government_schemes` (
  `id` int(11) NOT NULL,
  `scheme_name` varchar(200) NOT NULL,
  `eligibility` text DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `government_schemes`
--

INSERT INTO `government_schemes` (`id`, `scheme_name`, `eligibility`, `description`) VALUES
(1, 'Pension yojana scheme', '\r\nage above 60\r\n', '\r\nhelp elder people to survive\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `important_places`
--

CREATE TABLE `important_places` (
  `id` int(11) NOT NULL,
  `place_name` varchar(150) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `important_places`
--

INSERT INTO `important_places` (`id`, `place_name`, `location`, `description`, `image`) VALUES
(1, 'Kasi visalatchi amman temple', '234 b therku ratha veethi', '\r\n100 yrs old famous spritual site.\r\n', '1781939797_temple.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `insights`
--

CREATE TABLE `insights` (
  `id` int(11) NOT NULL,
  `insight` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_history`
--

CREATE TABLE `login_history` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `user_type` varchar(20) DEFAULT NULL,
  `login_time` datetime DEFAULT current_timestamp(),
  `logout_time` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_history`
--

INSERT INTO `login_history` (`id`, `email`, `user_type`, `login_time`, `logout_time`) VALUES
(1, 'admin2712@gmail.com', 'Admin', '2026-06-23 22:34:59', NULL),
(2, 'admin2712@gmail.com', 'Admin', '2026-06-23 22:35:06', NULL),
(3, 'admin2712@gmail.com', 'Admin', '2026-06-23 22:35:51', NULL),
(4, 'admin2712@gmail.com', 'Admin', '2026-06-23 22:36:37', '2026-06-23 22:38:11'),
(5, 'admin2712@gmail.com', 'Admin', '2026-06-23 22:38:23', '2026-06-23 22:38:26'),
(6, 'robi123@gmail.com', 'Citizen', '2026-06-23 22:47:39', NULL),
(7, 'sharmila@gmail.com', 'Citizen', '2026-06-23 22:48:44', NULL),
(8, 'sharmila@gmail.com', 'Citizen', '2026-06-23 22:49:03', '2026-06-23 22:49:08'),
(9, 'sharmila@gmail.com', 'Citizen', '2026-06-23 22:50:43', '2026-06-23 22:50:48'),
(10, 'sharmila@gmail.com', 'Citizen', '2026-06-23 22:53:45', '2026-06-23 22:53:51'),
(11, 'admin2712@gmail.com', 'Admin', '2026-06-23 22:54:03', '2026-06-23 22:54:06'),
(12, 'admin2712@gmail.com', 'Admin', '2026-06-23 22:58:54', '2026-06-23 23:06:40'),
(13, 'admin2712@gmail.com', 'Admin', '2026-06-23 23:06:41', '2026-06-23 23:06:53'),
(14, 'admin2712@gmail.com', 'Admin', '2026-06-23 23:07:24', NULL),
(15, 'admin2712@gmail.com', 'Admin', '2026-06-23 23:10:17', '2026-06-23 23:10:30'),
(16, 'robi123@gmail.com', 'Citizen', '2026-06-23 23:10:47', '2026-06-23 23:11:12'),
(17, 'robi123@gmail.com', 'Citizen', '2026-06-23 23:11:18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `predictions`
--

CREATE TABLE `predictions` (
  `id` int(11) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `property_code` varchar(20) DEFAULT NULL,
  `citizen_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `property_type` enum('House','Land','Shop') NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `village` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `property_code`, `citizen_id`, `name`, `property_type`, `price`, `village`, `status`, `date`) VALUES
(17, 'PROP178210616938', 5, 'Farm house', 'House', 120000.00, 'sathankulam', 'Available', '2026-06-22'),
(18, 'PROP178210681136', 5, 'Ram Hotel', 'Shop', 250000.00, 'sathankulam', 'Available', '2026-06-22'),
(19, 'PROP178218873770', 7, 'Anand Vilas', 'House', 123000.00, 'Sathankulam', 'Available', '2026-06-23');

-- --------------------------------------------------------

--
-- Table structure for table `public_facilities`
--

CREATE TABLE `public_facilities` (
  `id` int(11) NOT NULL,
  `facility_name` varchar(150) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `timings` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `public_facilities`
--

INSERT INTO `public_facilities` (`id`, `facility_name`, `location`, `timings`, `description`, `image`) VALUES
(1, 'Public library', '345 b north street ', '9.30 am to 5.00 pm', '\r\nhelp the students to learn new things\r\n', '1781939537_library.avif');

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` int(11) NOT NULL,
  `citizen_id` int(11) NOT NULL,
  `property_id` int(11) DEFAULT NULL,
  `property_name` varchar(100) DEFAULT NULL,
  `type` enum('House','Land','Shop','Water','Sanitation') NOT NULL,
  `amount` decimal(12,2) DEFAULT 0.00,
  `fine` decimal(12,2) DEFAULT 0.00,
  `total` decimal(12,2) DEFAULT 0.00,
  `due_date` date NOT NULL,
  `status` enum('Pending','Paid') DEFAULT 'Pending',
  `payment_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`id`, `citizen_id`, `property_id`, `property_name`, `type`, `amount`, `fine`, `total`, `due_date`, `status`, `payment_date`) VALUES
(11, 5, 0, 'Water', 'Water', 500.00, 100.00, 600.00, '2026-06-08', 'Pending', NULL),
(12, 5, 18, 'Sai Ram Hotel', 'Shop', 45000.00, 0.00, 45000.00, '2026-06-09', 'Paid', '2026-06-22'),
(13, 7, 19, 'Anand Vilas', 'House', 12300.00, 0.00, 12300.00, '2026-05-07', 'Paid', '2026-06-23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `village_officials`
--

CREATE TABLE `village_officials` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `village_officials`
--

INSERT INTO `village_officials` (`id`, `name`, `designation`, `phone`, `email`, `photo`) VALUES
(1, 'Sharmila ', 'VAO', '6737398834', 'sharmilas29075@gmail.com', '1781939843_person.png');

-- --------------------------------------------------------

--
-- Table structure for table `village_profile`
--

CREATE TABLE `village_profile` (
  `id` int(11) NOT NULL,
  `village_name` varchar(100) NOT NULL,
  `panchayat_name` varchar(100) NOT NULL,
  `district` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `population` int(11) NOT NULL,
  `total_families` int(11) NOT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `office_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `village_profile`
--

INSERT INTO `village_profile` (`id`, `village_name`, `panchayat_name`, `district`, `state`, `population`, `total_families`, `occupation`, `description`, `office_image`) VALUES
(1, 'Sathankulam', 'Sathankulam village panchayat', 'thoothukudi Dist', 'tamilnadu', 350000, 780, 'farming', '\r\n\r\nbeautiful village located in thoothukudi district near tisayanvilai\r\n\r\n', '1781939368_sathankulam.avif');

-- --------------------------------------------------------

--
-- Table structure for table `workers`
--

CREATE TABLE `workers` (
  `id` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `dept` varchar(100) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `status` varchar(20) NOT NULL,
  `complaint` varchar(20) DEFAULT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `workers`
--

INSERT INTO `workers` (`id`, `name`, `role`, `dept`, `phone`, `status`, `complaint`, `date`) VALUES
('WRK2719', 'Kumar', 'Cleaner', 'Sanitation', '6374764590', 'Busy', 'CMP000001', '2026-06-09');

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `id` int(11) NOT NULL,
  `zone` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificate_no` (`certificate_no`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_id_2` (`user_id`),
  ADD KEY `user_id_3` (`user_id`),
  ADD KEY `certificate_type` (`certificate_type`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `certificate_documents`
--
ALTER TABLE `certificate_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificate_updates`
--
ALTER TABLE `certificate_updates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `citizens`
--
ALTER TABLE `citizens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `citizen_login`
--
ALTER TABLE `citizen_login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaint_updates`
--
ALTER TABLE `complaint_updates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `government_schemes`
--
ALTER TABLE `government_schemes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `important_places`
--
ALTER TABLE `important_places`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `insights`
--
ALTER TABLE `insights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_history`
--
ALTER TABLE `login_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `predictions`
--
ALTER TABLE `predictions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `property_code` (`property_code`),
  ADD KEY `citizen_id` (`citizen_id`);

--
-- Indexes for table `public_facilities`
--
ALTER TABLE `public_facilities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `citizen_id` (`citizen_id`),
  ADD KEY `property_id` (`property_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `village_officials`
--
ALTER TABLE `village_officials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `village_profile`
--
ALTER TABLE `village_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workers`
--
ALTER TABLE `workers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaint` (`complaint`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `certificate_documents`
--
ALTER TABLE `certificate_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificate_updates`
--
ALTER TABLE `certificate_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=139;

--
-- AUTO_INCREMENT for table `citizens`
--
ALTER TABLE `citizens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `citizen_login`
--
ALTER TABLE `citizen_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `complaint_updates`
--
ALTER TABLE `complaint_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emergency_contacts`
--
ALTER TABLE `emergency_contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `government_schemes`
--
ALTER TABLE `government_schemes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `important_places`
--
ALTER TABLE `important_places`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `insights`
--
ALTER TABLE `insights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login_history`
--
ALTER TABLE `login_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `predictions`
--
ALTER TABLE `predictions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `public_facilities`
--
ALTER TABLE `public_facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `village_officials`
--
ALTER TABLE `village_officials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `village_profile`
--
ALTER TABLE `village_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `workers`
--
ALTER TABLE `workers`
  ADD CONSTRAINT `workers_ibfk_1` FOREIGN KEY (`complaint`) REFERENCES `complaints` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
