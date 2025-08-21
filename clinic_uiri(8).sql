-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2025 at 08:12 AM
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
-- Database: `clinic_uiri`
--

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `doctor_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`doctor_id`, `user_id`, `first_name`, `last_name`, `email`, `phone_number`, `created_at`, `updated_at`) VALUES
(3, 1, 'Doctor', 'Musawo', 'doctormusawo@gmail.com', '+256774282821', '2025-05-05 05:52:07', '2025-08-13 08:53:46'),
(4, NULL, 'Alvin', 'Alynda', 'ausvin60@gmail.com', '+256768398930', '2025-08-20 03:05:23', '2025-08-20 03:05:23');

-- --------------------------------------------------------

--
-- Table structure for table `drugs`
--

CREATE TABLE `drugs` (
  `drug_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `batch_no` varchar(100) DEFAULT NULL,
  `dosage` varchar(255) NOT NULL,
  `quantity_in_stock` int(10) UNSIGNED NOT NULL,
  `reorder_level` int(11) NOT NULL DEFAULT 0,
  `reorder_quantity` int(11) DEFAULT NULL,
  `manufacture_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `is_usable` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `drugs`
--

INSERT INTO `drugs` (`drug_id`, `name`, `batch_no`, `dosage`, `quantity_in_stock`, `reorder_level`, `reorder_quantity`, `manufacture_date`, `expiration_date`, `status`, `is_usable`, `created_at`, `updated_at`) VALUES
(14, 'pylo kit', '4KA2079', '780', 7, 0, NULL, '2024-12-01', '2026-12-01', 'Available', 1, '2025-08-07 04:39:55', '2025-08-07 04:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `contact_info` varchar(255) DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `next_of_kin_contact` varchar(255) DEFAULT NULL,
  `next_of_kin_relationship` enum('Spouse','Child','Parent','Sibling','Guardian') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `first_name`, `last_name`, `date_of_birth`, `gender`, `contact_info`, `medical_history`, `next_of_kin_contact`, `next_of_kin_relationship`, `created_at`, `updated_at`) VALUES
(5, 'Patient', 'Mulwade', '1997-08-07', 'Male', '0773920384', 'None', '0771928374', 'Guardian', '2025-06-07 16:24:59', '2025-06-07 16:24:59'),
(18, 'Alvin', 'Alinda', '2025-08-07', 'Male', '0774282821', 'none', '0768398930', 'Sibling', '2025-08-07 03:47:36', '2025-08-07 03:47:36');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `session_token` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplies`
--

CREATE TABLE `supplies` (
  `supply_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity_in_stock` int(11) DEFAULT 0,
  `reorder_level` int(11) NOT NULL DEFAULT 0,
  `reorder_quantity` int(11) DEFAULT NULL,
  `batch_no` varchar(100) DEFAULT NULL,
  `manufacture_date` date DEFAULT NULL,
  `expiration_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplies`
--

INSERT INTO `supplies` (`supply_id`, `name`, `quantity_in_stock`, `reorder_level`, `reorder_quantity`, `batch_no`, `manufacture_date`, `expiration_date`, `created_at`, `updated_at`) VALUES
(14, 'Syringe with needle', 97, 0, NULL, '22EA5', '2022-05-15', '2027-05-14', '2025-08-07 03:51:41', '2025-08-07 04:07:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(20) NOT NULL DEFAULT 'Admin',
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `created_at`, `role`, `status`, `updated_at`) VALUES
(1, 'Musawo', '$2y$10$wjwgZw3cI6J6uXMeA6Qhd.2In6CSX0cUNBAvwbMfpxeVXlMampxB2', '2025-04-23 13:28:30', 'Admin', 'active', NULL),
(4, 'Alynda', '$2y$10$VPhCGQ/TKgfZ3LqroU7WuedeQaS43FBXroFzzCcVDbTLJQ4Yh8RUW', '2025-08-20 03:04:50', 'Admin', 'active', '2025-08-20 03:04:50');

-- --------------------------------------------------------

--
-- Table structure for table `visits`
--

CREATE TABLE `visits` (
  `visit_id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `visit_date` datetime DEFAULT current_timestamp(),
  `weight` decimal(5,2) DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `admission_time` datetime DEFAULT NULL,
  `blood_pressure` varchar(20) DEFAULT NULL,
  `pulse` int(11) DEFAULT NULL,
  `temperature` decimal(4,1) DEFAULT NULL,
  `visit_category` enum('in-patient','out-patient') NOT NULL DEFAULT 'out-patient',
  `patient_complaints` text DEFAULT NULL,
  `examination_notes` text DEFAULT NULL,
  `investigations` text DEFAULT NULL,
  `sp02` decimal(4,1) DEFAULT NULL,
  `respiration_rate` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visits`
--

INSERT INTO `visits` (`visit_id`, `patient_id`, `doctor_id`, `visit_date`, `weight`, `diagnosis`, `created_at`, `updated_at`, `admission_time`, `blood_pressure`, `pulse`, `temperature`, `visit_category`, `patient_complaints`, `examination_notes`, `investigations`, `sp02`, `respiration_rate`) VALUES
(8, 18, 3, '2025-08-07 00:00:00', 78.00, 'PUD', '2025-08-07 04:02:22', '2025-08-07 04:02:22', '0000-00-00 00:00:00', '120/70', 72, 36.7, 'out-patient', 'HEART BURN\r\nABDOMINAL PAIN\r\nEPIGASTRIC PAIN', 'No swelling in the fundus.', 'HP PYROLI\r\nWIDOW\r\nMRDT', 98.0, 26);

-- --------------------------------------------------------

--
-- Table structure for table `visit_outcomes`
--

CREATE TABLE `visit_outcomes` (
  `outcome_id` int(11) NOT NULL,
  `visit_id` int(11) NOT NULL,
  `treatment_notes` text DEFAULT NULL,
  `outcome` enum('Referred','Discharged') NOT NULL,
  `referral_reason` text DEFAULT NULL,
  `discharge_time` datetime DEFAULT NULL,
  `discharge_condition` text DEFAULT NULL,
  `return_date` date DEFAULT NULL,
  `follow_up_notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visit_outcomes`
--

INSERT INTO `visit_outcomes` (`outcome_id`, `visit_id`, `treatment_notes`, `outcome`, `referral_reason`, `discharge_time`, `discharge_condition`, `return_date`, `follow_up_notes`, `created_at`, `updated_at`) VALUES
(2, 8, 'tabs pyro kit(780mg) BD for 3 days', 'Discharged', '', '2025-08-07 11:00:00', 'Improved', '2025-08-10', 'TCA 10/08/25', '2025-08-07 04:09:06', '2025-08-07 04:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `visit_prescriptions`
--

CREATE TABLE `visit_prescriptions` (
  `prescription_id` int(11) NOT NULL,
  `visit_id` int(11) NOT NULL,
  `drug_id` int(11) NOT NULL,
  `dosage` varchar(100) DEFAULT NULL,
  `duration` varchar(100) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `route` enum('Oral','IV','IM','Vaginal','Topical','Others') NOT NULL DEFAULT 'Oral',
  `other_route` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visit_supplies`
--

CREATE TABLE `visit_supplies` (
  `visit_supplies_id` int(11) NOT NULL,
  `visit_id` int(11) NOT NULL,
  `supply_id` int(11) NOT NULL,
  `quantity_used` int(11) NOT NULL DEFAULT 1,
  `usage_type` enum('standard','estimated','bulk') NOT NULL DEFAULT 'standard',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visit_supplies`
--

INSERT INTO `visit_supplies` (`visit_supplies_id`, `visit_id`, `supply_id`, `quantity_used`, `usage_type`, `created_at`, `updated_at`) VALUES
(7, 8, 14, 3, 'standard', '2025-08-07 04:07:39', '2025-08-07 04:07:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`doctor_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `uq_doctors_user` (`user_id`),
  ADD UNIQUE KEY `ux_doctors_user_id` (`user_id`);

--
-- Indexes for table `drugs`
--
ALTER TABLE `drugs`
  ADD PRIMARY KEY (`drug_id`),
  ADD UNIQUE KEY `uq_drug_batch` (`name`,`dosage`,`batch_no`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `supplies`
--
ALTER TABLE `supplies`
  ADD PRIMARY KEY (`supply_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `visits`
--
ALTER TABLE `visits`
  ADD PRIMARY KEY (`visit_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `visit_outcomes`
--
ALTER TABLE `visit_outcomes`
  ADD PRIMARY KEY (`outcome_id`),
  ADD KEY `fk_visit_outcomes_visit_id` (`visit_id`);

--
-- Indexes for table `visit_prescriptions`
--
ALTER TABLE `visit_prescriptions`
  ADD PRIMARY KEY (`prescription_id`),
  ADD KEY `visit_id` (`visit_id`),
  ADD KEY `drug_id` (`drug_id`);

--
-- Indexes for table `visit_supplies`
--
ALTER TABLE `visit_supplies`
  ADD PRIMARY KEY (`visit_supplies_id`),
  ADD KEY `visit_id` (`visit_id`),
  ADD KEY `supply_id` (`supply_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `doctor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `drugs`
--
ALTER TABLE `drugs`
  MODIFY `drug_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `session_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplies`
--
ALTER TABLE `supplies`
  MODIFY `supply_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `visits`
--
ALTER TABLE `visits`
  MODIFY `visit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `visit_outcomes`
--
ALTER TABLE `visit_outcomes`
  MODIFY `outcome_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `visit_prescriptions`
--
ALTER TABLE `visit_prescriptions`
  MODIFY `prescription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `visit_supplies`
--
ALTER TABLE `visit_supplies`
  MODIFY `visit_supplies_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `fk_doctors_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `visits`
--
ALTER TABLE `visits`
  ADD CONSTRAINT `fk_visits_doc` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`doctor_id`),
  ADD CONSTRAINT `fk_visits_pat` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`patient_id`);

--
-- Constraints for table `visit_outcomes`
--
ALTER TABLE `visit_outcomes`
  ADD CONSTRAINT `fk_visit_outcomes_visit_id` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`visit_id`) ON DELETE CASCADE;

--
-- Constraints for table `visit_prescriptions`
--
ALTER TABLE `visit_prescriptions`
  ADD CONSTRAINT `visit_prescriptions_ibfk_1` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`visit_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `visit_prescriptions_ibfk_2` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`drug_id`) ON DELETE CASCADE;

--
-- Constraints for table `visit_supplies`
--
ALTER TABLE `visit_supplies`
  ADD CONSTRAINT `visit_supplies_ibfk_1` FOREIGN KEY (`visit_id`) REFERENCES `visits` (`visit_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `visit_supplies_ibfk_2` FOREIGN KEY (`supply_id`) REFERENCES `supplies` (`supply_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
