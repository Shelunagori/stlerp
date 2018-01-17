-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 17, 2018 at 10:01 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stl_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee_records`
--

CREATE TABLE `employee_records` (
  `id` int(10) NOT NULL,
  `employee_id` int(10) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `total_attenence` bigint(20) NOT NULL,
  `overtime` bigint(100) NOT NULL,
  `month_year` date NOT NULL,
  `create_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `loan_applications`
--

CREATE TABLE `loan_applications` (
  `id` int(10) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `reason_for_loan` varchar(100) NOT NULL,
  `salary_pm` decimal(15,2) NOT NULL,
  `amount_of_loan` varchar(100) NOT NULL,
  `amount_of_loan_in_word` varchar(200) NOT NULL,
  `starting_date_of_loan` date NOT NULL,
  `ending_date_of_loan` date NOT NULL,
  `remark` text NOT NULL,
  `create_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loan_applications`
--

INSERT INTO `loan_applications` (`id`, `employee_name`, `reason_for_loan`, `salary_pm`, `amount_of_loan`, `amount_of_loan_in_word`, `starting_date_of_loan`, `ending_date_of_loan`, `remark`, `create_date`) VALUES
(1, 'ravi kishan', 'health', '25000.00', '500000', 'panch lack', '2018-01-01', '2018-01-31', 'hhhhhhhhhhhhhhh', '2018-01-11'),
(2, 'manoj', 'health', '28000.00', '400000', 'four lack', '2018-01-01', '2019-05-02', 'djddjdjjdjdd', '2018-01-11');

-- --------------------------------------------------------

--
-- Table structure for table `salary_advances`
--

CREATE TABLE `salary_advances` (
  `id` int(10) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `reason` text NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `create_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `salary_advances`
--

INSERT INTO `salary_advances` (`id`, `employee_name`, `reason`, `amount`, `create_date`) VALUES
(1, 'gopal kumar', 'dcfdsfdsfdfdsfdsf', '250000.00', '2018-01-11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee_records`
--
ALTER TABLE `employee_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loan_applications`
--
ALTER TABLE `loan_applications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salary_advances`
--
ALTER TABLE `salary_advances`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee_records`
--
ALTER TABLE `employee_records`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `loan_applications`
--
ALTER TABLE `loan_applications`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `salary_advances`
--
ALTER TABLE `salary_advances`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
