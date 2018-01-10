-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2017 at 10:29 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pws`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `password`, `status`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70', 0);

-- --------------------------------------------------------

--
-- Table structure for table `history_tracker_sheet`
--

CREATE TABLE `history_tracker_sheet` (
  `id` int(10) NOT NULL,
  `tracker_sheet_id` int(10) NOT NULL,
  `project_no` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `po_number` varchar(100) NOT NULL,
  `po_date` date NOT NULL,
  `delivery_term` varchar(100) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `end_user` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `history_tracker_sheet`
--

INSERT INTO `history_tracker_sheet` (`id`, `tracker_sheet_id`, `project_no`, `password`, `po_number`, `po_date`, `delivery_term`, `customer_name`, `project_name`, `end_user`) VALUES
(1, 1, '3557', '202cb962ac59075b964b07152d234b70', '12354654', '2021-07-17', 'CAT- 3', 'Premier Explosive Limited 1st LOT', 'ISRO SHAR', 'ISRO'),
(2, 2, '3366', '202cb962ac59075b964b07152d234b70', '23246', '2011-07-17', 'CAT- 5', 'Premier Explosive Limited 1st LOT', 'ESRO', 'ESRO'),
(3, 3, '3557', '202cb962ac59075b964b07152d234b70', '12354654', '2021-07-17', 'CAT- 3', 'Premier Explosive Limited 1st LOT', 'ISRO SHAR', 'ISRO'),
(4, 4, '3366', '202cb962ac59075b964b07152d234b70', '23246', '2011-07-17', 'CAT- 5', 'Premier Explosive Limited 1st LOT', 'ESRO', 'ESRO'),
(5, 5, '3557', '202cb962ac59075b964b07152d234b70', '12354654', '2021-07-17', 'CAT- 3', 'Premier Explosive Limited 1st LOT', 'ISRO SHAR', 'ISRO'),
(6, 6, '3366', '202cb962ac59075b964b07152d234b70', '23246', '2011-07-17', 'CAT- 5', 'Premier Explosive Limited 1st LOT', 'ESRO', 'ESRO'),
(7, 7, '3377', '202cb962ac59075b964b07152d234b70', '23246', '2011-07-17', 'CAT- 5', 'Premier Explosive Limited 1st LOT', 'ESRO', 'ESRO'),
(8, 8, '3557', '51da85a3c3dfa1f360b48852b64218b2', '12354654', '2017-11-04', 'CAT- 3', 'Premier Explosive Limited', 'ISRO SHAR', 'ISRO'),
(9, 9, '3590', 'da6ea77475918a3d83c7e49223d453cc', '13216146', '2017-11-05', 'CAT - 3', 'SHRI KESHAV CEMENT AND INFRA LIMITED', '-', 'SHRI KESHAV CEMENT AND INFRA LIMITED'),
(10, 10, '30045', '5c51774e43c9db3aa687f23c27956104', '121315116', '2017-11-06', 'CAT - 2', 'QATAR METRO PROJECT - 1st LOT', 'Q Metro', 'QATAR METRO'),
(11, 11, '30045', '5c51774e43c9db3aa687f23c27956104', '121315116', '2017-11-07', 'CAT - 2', 'QATAR METRO PROJECT - 2nd LOT', 'Q Metro', 'QATAR METRO'),
(12, 12, '3584', '565767eb96d87d0d3af8dfb332c2003f', '185484231', '2017-11-08', 'CAT - 3', 'BHEL', 'NMDC', 'NMDC'),
(13, 13, '3516/5672', '715c3dbf8e9ee31487f0ecb4320a3b0d', '581489484', '2017-11-09', 'CAT - 3', 'DRDO ', 'CCR', 'DRDO'),
(14, 14, '3403', '898dd88cca7b2f65461bc491dacb9b25', '1548421', '2017-11-10', 'CAT - 3', 'GE T&D India Ltd - 4th LOT', 'URTDSM', 'URTDSM');

-- --------------------------------------------------------

--
-- Table structure for table `history_tracker_sheet_rows`
--

CREATE TABLE `history_tracker_sheet_rows` (
  `id` int(10) NOT NULL,
  `tracker_sheet_id` int(11) NOT NULL,
  `tracker_sheet_row_id` int(10) NOT NULL,
  `kom_design_submission` date NOT NULL,
  `kom_design_submission_remarks` text NOT NULL,
  `final_design_approval` date NOT NULL,
  `final_design_approval_remarks` text NOT NULL,
  `raw_material_procurement` date NOT NULL,
  `raw_material_procurement_remarks` text NOT NULL,
  `manufacturing_started` date NOT NULL,
  `manufacturing_started_remarks` text NOT NULL,
  `manufacturing_completed` date NOT NULL,
  `manufacturing_completed_remarks` text NOT NULL,
  `internal_qc_completed` date NOT NULL,
  `internal_qc_completed_remarks` text NOT NULL,
  `inspection_call_raised_waiver` date NOT NULL,
  `inspection_call_raised_waiver_remarks` text NOT NULL,
  `mdcc_received` date NOT NULL,
  `mdcc_received_remarks` text NOT NULL,
  `material_dispatched` date NOT NULL,
  `material_dispatched_date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `history_tracker_sheet_rows`
--

INSERT INTO `history_tracker_sheet_rows` (`id`, `tracker_sheet_id`, `tracker_sheet_row_id`, `kom_design_submission`, `kom_design_submission_remarks`, `final_design_approval`, `final_design_approval_remarks`, `raw_material_procurement`, `raw_material_procurement_remarks`, `manufacturing_started`, `manufacturing_started_remarks`, `manufacturing_completed`, `manufacturing_completed_remarks`, `internal_qc_completed`, `internal_qc_completed_remarks`, `inspection_call_raised_waiver`, `inspection_call_raised_waiver_remarks`, `mdcc_received`, `mdcc_received_remarks`, `material_dispatched`, `material_dispatched_date`) VALUES
(1, 1, 1, '2017-07-03', 'KOM & Design Remarks ', '2017-08-14', 'Final Design Remarks ', '2017-07-10', 'Raw Material Remarks', '2017-09-12', 'Manufacturing Remarks ', '2017-07-28', 'Manufacturing Completed Remarks', '2017-08-02', 'Internal QC Remarks', '2017-08-05', 'Inspection call Remarks', '2017-08-06', 'MDCC  Remarks', '2017-08-09', 'Material Dispatched Remarks'),
(2, 2, 2, '2017-06-05', 'KOM & Design Remarks project 2', '2017-08-22', 'Final Design Remarks project 2', '2017-07-10', 'Raw Material Remarks project 2', '2017-10-10', 'Manufacturing Remarks project 2', '2017-09-28', 'Manufacturing Completed Remarks project 2', '2017-11-02', 'Internal QC Remarks project 2', '2017-05-05', 'Inspection call Remarks project 2', '2017-03-06', 'MDCC  Remarks project 2', '2017-02-09', 'Material Dispatched Remarks project 2'),
(3, 3, 3, '1970-01-01', 'KOM & Design Remarks ', '2017-08-14', '!@#%KJBGHJBHJHJWVHJWVLJAV______________@$$@$_@_%_^_', '2017-07-10', 'Raw Material Remarks', '2017-09-12', 'Manufacturing Remarks ', '2017-07-28', 'Manufacturing Completed Remarks', '2017-08-02', 'Internal QC Remarks', '2017-08-05', 'Inspection call Remarks', '2017-08-06', 'MDCC  Remarks', '2017-08-09', 'Material Dispatched Remarks'),
(4, 4, 4, '2017-06-05', 'KOM & Design Remarks project 2', '2017-08-22', 'Final Design Remarks project 2', '1970-01-01', 'Raw Material Remarks project 2', '2017-10-10', 'Manufacturing Remarks project 2', '2017-09-28', 'dfnmskjfbwekjlbgfwe gfdsnmfkebwfklwjebfklbwefewbflwbfwlef', '2017-11-02', 'Internal QC Remarks project 2', '2017-05-05', 'Inspection call Remarks project 2', '2017-03-06', 'MDCC  Remarks project 2', '2017-02-09', 'fkenwkjfbe^&T^&R^&%&*^%*&sadbam,fbefh'),
(5, 5, 5, '1970-01-01', 'defljebfkjlqeflewkjefw', '2017-08-14', '!@#%KJBGHJBHJHJWVHJWVLJAV______________@$$@$_@_%_^_', '1970-01-01', 'Raw Material Remarks', '2017-09-12', 'Manufacturing Remarks ', '1970-01-01', 'Manufacturing Completed Remarks', '2017-08-02', 'Internal QC Remarks', '2017-08-05', 'Inspection call Remarks', '2017-08-06', 'MDCC  Remarks', '2017-08-09', 'Material Dispatched Remarks'),
(6, 6, 6, '2017-06-05', 'dfkjfnhkjslbflajfdbsfj', '2017-08-22', 'Final Design Remarks project 2', '1970-01-01', 'Raw Material Remarks project 2', '1970-01-01', 'Manufacturing Remarks project 2', '2017-09-28', 'dfnmskjfbwekjlbgfwe gfdsnmfkebwfklwjebfklbwefewbflwbfwlef', '2017-11-02', 'Internal QC Remarks project 2', '2017-05-05', 'Inspection call Remarks project 2', '2017-03-06', 'MDCC  Remarks project 2', '2017-02-09', 'fkenwkjfbe^&T^&R^&%&*^%*&sadbam,fbefh'),
(7, 7, 7, '1970-01-01', '', '2017-08-22', 'Final Design Remarks project 2', '1970-01-01', 'Raw Material Remarks project 2', '1970-01-01', 'Manufacturing Remarks project 2', '2017-09-28', 'dfnmskjfbwekjlbgfwe gfdsnmfkebwfklwjebfklbwefewbflwbfwlef', '2017-11-02', 'Internal QC Remarks project 2', '2017-05-05', 'Inspection call Remarks project 2', '2017-03-06', 'MDCC  Remarks project 2', '2017-02-09', 'fkenwkjfbe^&T^&R^&%&*^%*&sadbam,fbefh'),
(8, 8, 8, '2017-06-14', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-07-04', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-07-10', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-07-11', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-07-28', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-02', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-05', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-06', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-09', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120'),
(9, 9, 9, '2017-08-04', '11', '2017-08-09', '12', '2017-08-15', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-16', '14', '2017-09-02', '15', '2017-09-07', '16', '2017-09-10', '17', '2017-09-11', '18', '1970-01-01', '19'),
(10, 10, 10, '2017-06-28', '21', '2017-08-07', '22', '2017-08-13', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-14', '24', '2017-08-31', '25', '2017-09-05', '26', '2017-09-15', '27', '1970-01-01', '28', '1970-01-01', '29'),
(11, 11, 11, '2017-09-12', '31', '2017-09-18', '32', '2017-09-24', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-09-25', '34', '2017-10-12', '35', '2017-10-17', '36', '1970-01-01', '37', '1970-01-01', '38', '1970-01-01', '39'),
(12, 12, 12, '2017-07-28', '41', '2017-08-19', '42', '2017-08-25', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-26', '44', '2017-09-12', '45', '1970-01-01', '46', '1970-01-01', '47', '1970-01-01', '48', '1970-01-01', '49'),
(13, 13, 13, '2017-03-10', '51', '2017-08-08', '52', '2017-08-14', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-15', '54', '1970-01-01', '55', '1970-01-01', '56', '1970-01-01', '57', '1970-01-01', '58', '1970-01-01', '59'),
(14, 14, 14, '2017-03-10', '61', '2017-08-30', '62', '2017-09-05', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '1970-01-01', '64', '1970-01-01', '65', '1970-01-01', '66', '1970-01-01', '67', '1970-01-01', '68', '1970-01-01', '69');

-- --------------------------------------------------------

--
-- Table structure for table `tracker_sheet`
--

CREATE TABLE `tracker_sheet` (
  `id` int(11) NOT NULL,
  `project_no` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `session_key` varchar(255) DEFAULT NULL,
  `po_number` varchar(100) DEFAULT NULL,
  `po_date` date NOT NULL,
  `delivery_term` varchar(100) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `project_name` varchar(100) DEFAULT NULL,
  `end_user` varchar(100) DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracker_sheet`
--

INSERT INTO `tracker_sheet` (`id`, `project_no`, `password`, `session_key`, `po_number`, `po_date`, `delivery_term`, `customer_name`, `project_name`, `end_user`, `status`) VALUES
(8, '3557', '51da85a3c3dfa1f360b48852b64218b2', 'b0bd65a0dadf0d4716820ebbad58250a', '12354654', '2017-11-04', 'CAT- 3', 'Premier Explosive Limited', 'ISRO SHAR', 'ISRO', 'Active'),
(9, '3590', 'da6ea77475918a3d83c7e49223d453cc', NULL, '13216146', '2017-11-05', 'CAT - 3', 'SHRI KESHAV CEMENT AND INFRA LIMITED', '-', 'SHRI KESHAV CEMENT AND INFRA LIMITED', 'Active'),
(10, '30045', '5c51774e43c9db3aa687f23c27956104', 'f15dc6520bac74ce81eda91b9669f8ad', '121315116', '2017-11-06', 'CAT - 2', 'QATAR METRO PROJECT - 1st LOT', 'Q Metro', 'QATAR METRO', 'Active'),
(11, '30045', '5c51774e43c9db3aa687f23c27956104', 'f15dc6520bac74ce81eda91b9669f8ad', '121315116', '2017-11-07', 'CAT - 2', 'QATAR METRO PROJECT - 2nd LOT', 'Q Metro', 'QATAR METRO', 'Active'),
(12, '3584', '565767eb96d87d0d3af8dfb332c2003f', NULL, '185484231', '2017-11-08', 'CAT - 3', 'BHEL', 'NMDC', 'NMDC', 'Active'),
(13, '3516/5672', '715c3dbf8e9ee31487f0ecb4320a3b0d', '8ce5fd10860253bdf08e5233061e2921', '581489484', '2017-11-09', 'CAT - 3', 'DRDO ', 'CCR', 'DRDO', 'Active'),
(14, '3403', '202cb962ac59075b964b07152d234b70', 'd5530d2c7d67fe8c4425d7580b1b41c4', '1548421', '2017-11-10', 'CAT - 3', 'GE T&D India Ltd - 4th LOT', 'URTDSM', 'URTDSM', 'Active');

--
-- Triggers `tracker_sheet`
--
DELIMITER $$
CREATE TRIGGER `HistoryTrackerSheetIns` AFTER INSERT ON `tracker_sheet` FOR EACH ROW INSERT into history_tracker_sheet SET tracker_sheet_id = New.id,project_no = New.project_no,password = New.password,po_number = New.po_number,po_date = NEW.po_date,delivery_term = New.delivery_term,customer_name = NEW.customer_name,project_name = NEW.project_name,end_user = NEW.end_user
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tracker_sheet_rows`
--

CREATE TABLE `tracker_sheet_rows` (
  `id` int(11) NOT NULL,
  `tracker_sheet_id` int(11) DEFAULT NULL,
  `kom_design_submission` date DEFAULT NULL,
  `kom_design_submission_remarks` text,
  `final_design_approval` date DEFAULT NULL,
  `final_design_approval_remarks` text,
  `raw_material_procurement` date DEFAULT NULL,
  `raw_material_procurement_remarks` text,
  `manufacturing_started` date DEFAULT NULL,
  `manufacturing_started_remarks` text,
  `manufacturing_completed` date DEFAULT NULL,
  `manufacturing_completed_remarks` text,
  `internal_qc_completed` date DEFAULT NULL,
  `internal_qc_completed_remarks` text,
  `inspection_call_raised_waiver` date DEFAULT NULL,
  `inspection_call_raised_waiver_remarks` text,
  `mdcc_received` date DEFAULT NULL,
  `mdcc_received_remarks` text,
  `material_dispatched` date DEFAULT NULL,
  `material_dispatched_date` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tracker_sheet_rows`
--

INSERT INTO `tracker_sheet_rows` (`id`, `tracker_sheet_id`, `kom_design_submission`, `kom_design_submission_remarks`, `final_design_approval`, `final_design_approval_remarks`, `raw_material_procurement`, `raw_material_procurement_remarks`, `manufacturing_started`, `manufacturing_started_remarks`, `manufacturing_completed`, `manufacturing_completed_remarks`, `internal_qc_completed`, `internal_qc_completed_remarks`, `inspection_call_raised_waiver`, `inspection_call_raised_waiver_remarks`, `mdcc_received`, `mdcc_received_remarks`, `material_dispatched`, `material_dispatched_date`) VALUES
(8, 8, '2017-06-14', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-07-04', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-07-10', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-07-11', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-07-28', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-02', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-05', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-06', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-09', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120'),
(9, 9, '2017-08-04', '11', '2017-08-09', '12', '2017-08-15', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-16', '14', '2017-09-02', '15', '2017-09-07', '16', '2017-09-10', '17', '2017-09-11', '18', '1970-01-01', '19'),
(10, 10, '2017-06-28', '21', '2017-08-07', '22', '2017-08-13', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-14', '24', '2017-08-31', '25', '2017-09-05', '26', '2017-09-15', '27', '1970-01-01', '28', '1970-01-01', '29'),
(11, 11, '2017-09-12', '31', '2017-09-18', '32', '2017-09-24', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-09-25', '34', '2017-10-12', '35', '2017-10-17', '36', '1970-01-01', '37', '1970-01-01', '38', '1970-01-01', '39'),
(12, 12, '2017-07-28', '41', '2017-08-19', '42', '2017-08-25', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-26', '44', '2017-09-12', '45', '1970-01-01', '46', '1970-01-01', '47', '1970-01-01', '48', '1970-01-01', '49'),
(13, 13, '2017-03-10', '51', '2017-08-08', '52', '2017-08-14', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '2017-08-15', '54', '1970-01-01', '55', '1970-01-01', '56', '1970-01-01', '57', '1970-01-01', '58', '1970-01-01', '59'),
(14, 14, '2017-03-10', '61', '2017-08-30', '62', '2017-11-25', '1--------10---------20---------30---------40---------50---------60---------70---------80---------90---------100---------110---------120', '1970-01-01', '64', '2017-11-25', '65', '1970-01-01', '66', '1970-01-01', '67', '1970-01-01', '68', '1970-01-01', '69');

--
-- Triggers `tracker_sheet_rows`
--
DELIMITER $$
CREATE TRIGGER `HistoryTrackerSheetRowIns` AFTER INSERT ON `tracker_sheet_rows` FOR EACH ROW INSERT into history_tracker_sheet_rows SET tracker_sheet_id=NEW.tracker_sheet_id,tracker_sheet_row_id = New.id,kom_design_submission =NEW.kom_design_submission,kom_design_submission_remarks =NEW.kom_design_submission_remarks,final_design_approval = NEW.final_design_approval,final_design_approval_remarks = NEW.final_design_approval_remarks,raw_material_procurement = NEW.raw_material_procurement,raw_material_procurement_remarks = NEW.raw_material_procurement_remarks,manufacturing_started =NEW.manufacturing_started,manufacturing_started_remarks =NEW.manufacturing_started_remarks,manufacturing_completed=NEW.manufacturing_completed,manufacturing_completed_remarks =NEW.manufacturing_completed_remarks,internal_qc_completed =NEW.internal_qc_completed,internal_qc_completed_remarks =NEW.internal_qc_completed_remarks,inspection_call_raised_waiver=NEW.inspection_call_raised_waiver,inspection_call_raised_waiver_remarks = NEW.inspection_call_raised_waiver_remarks,mdcc_received =NEW.mdcc_received,mdcc_received_remarks =NEW.mdcc_received_remarks,material_dispatched=NEW.material_dispatched,material_dispatched_date=NEW.material_dispatched_date
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_tracker_sheet`
--
ALTER TABLE `history_tracker_sheet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_tracker_sheet_rows`
--
ALTER TABLE `history_tracker_sheet_rows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tracker_sheet`
--
ALTER TABLE `tracker_sheet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tracker_sheet_rows`
--
ALTER TABLE `tracker_sheet_rows`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `history_tracker_sheet`
--
ALTER TABLE `history_tracker_sheet`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `history_tracker_sheet_rows`
--
ALTER TABLE `history_tracker_sheet_rows`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `tracker_sheet`
--
ALTER TABLE `tracker_sheet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `tracker_sheet_rows`
--
ALTER TABLE `tracker_sheet_rows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
