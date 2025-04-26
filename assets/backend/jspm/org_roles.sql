-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2023 at 08:51 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rayalaseema_spice_v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `org_roles`
--

CREATE TABLE `org_roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `role_description` text NOT NULL,
  `organization_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `role_status` int(11) NOT NULL,
  `active_flag` varchar(1) NOT NULL DEFAULT 'Y',
  `inactive_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `last_updated_by` int(11) DEFAULT NULL,
  `last_updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `org_roles`
--

INSERT INTO `org_roles` (`role_id`, `role_name`, `role_description`, `organization_id`, `branch_id`, `role_status`, `active_flag`, `inactive_date`, `created_by`, `created_date`, `last_updated_by`, `last_updated_date`) VALUES
(2, 'Admin', 'Admins', 1, 1, 1, 'Y', NULL, NULL, NULL, NULL, NULL),
(3, 'Cashier', 'Cashier', 1, 1, 1, 'Y', NULL, NULL, NULL, NULL, NULL),
(4, 'Sales Manager', 'Sales Manager', 1, 1, 0, 'N', NULL, NULL, NULL, NULL, NULL),
(5, 'Waiter', 'Waiter', 1, 1, 1, 'Y', NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `org_roles`
--
ALTER TABLE `org_roles`
  ADD PRIMARY KEY (`role_id`),
  ADD KEY `role_name` (`role_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `org_roles`
--
ALTER TABLE `org_roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
