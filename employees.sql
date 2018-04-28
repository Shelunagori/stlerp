-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 28, 2018 at 11:49 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shilpa_treding`
--

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `sex` varchar(6) NOT NULL,
  `dipartment_id` int(10) NOT NULL,
  `designation_id` int(5) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(100) NOT NULL,
  `signature` varchar(100) NOT NULL,
  `account_type` varchar(50) NOT NULL,
  `account_no` varchar(20) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `ifsc_code` varchar(11) NOT NULL,
  `permanent_address` text NOT NULL,
  `residence_address` text NOT NULL,
  `phone_no` varchar(12) NOT NULL,
  `dob` date NOT NULL,
  `marital_status` varchar(10) NOT NULL,
  `date_of_anniversary` date NOT NULL,
  `spouse_name` varchar(40) NOT NULL,
  `children` int(11) NOT NULL,
  `spouse_working` varchar(3) NOT NULL,
  `qualification` varchar(40) NOT NULL,
  `last_company` varchar(50) NOT NULL,
  `join_date` date NOT NULL,
  `permanent_join_date` date NOT NULL,
  `blood_group` varchar(4) NOT NULL,
  `account_category_id` int(10) NOT NULL,
  `account_group_id` int(10) NOT NULL,
  `account_first_subgroup_id` int(10) NOT NULL,
  `account_second_subgroup_id` int(10) NOT NULL,
  `ledger_account_id` int(10) NOT NULL,
  `otp_no` int(10) NOT NULL,
  `status` int(11) NOT NULL,
  `identity_mark` varchar(255) NOT NULL,
  `caste` varchar(255) NOT NULL,
  `religion` varchar(100) NOT NULL,
  `home_state` varchar(100) NOT NULL,
  `home_district` varchar(100) NOT NULL,
  `adhar_card_no` varchar(100) NOT NULL,
  `passport_no` varchar(100) NOT NULL,
  `height` varchar(100) NOT NULL,
  `driving_licence_no` varchar(100) NOT NULL,
  `pan_card_no` varchar(100) NOT NULL,
  `appointment_date` date NOT NULL,
  `employee_id_no` varchar(100) NOT NULL,
  `dept_joining_date` date NOT NULL,
  `initial_designation` varchar(100) NOT NULL,
  `office_name` varchar(100) NOT NULL,
  `recruitment_mode` varchar(100) NOT NULL,
  `reporting_to` varchar(100) NOT NULL,
  `present_state` varchar(100) NOT NULL,
  `present_district` varchar(100) NOT NULL,
  `present_pin_code` varchar(100) NOT NULL,
  `present_mobile_no` varchar(10) NOT NULL,
  `present_phone_no` varchar(100) NOT NULL,
  `present_email` varchar(100) NOT NULL,
  `present_address` text NOT NULL,
  `permanent_state` varchar(100) NOT NULL,
  `permanent_district` varchar(100) NOT NULL,
  `permanent_pin_code` varchar(100) NOT NULL,
  `permanent_mobile_no` varchar(10) NOT NULL,
  `permanent_phone_no` varchar(50) NOT NULL,
  `permanent_email` varchar(100) NOT NULL,
  `nominee_name` varchar(100) NOT NULL,
  `permanent_address2` text NOT NULL,
  `relation_with_employee` varchar(100) NOT NULL,
  `nomination_type` varchar(100) NOT NULL,
  `nominee_state` varchar(100) NOT NULL,
  `nominee_district` varchar(100) NOT NULL,
  `nominee_pin_code` varchar(100) NOT NULL,
  `nominee_mobile_no` varchar(10) NOT NULL,
  `nominee_present_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `name`, `sex`, `dipartment_id`, `designation_id`, `mobile`, `email`, `signature`, `account_type`, `account_no`, `bank_name`, `branch_name`, `ifsc_code`, `permanent_address`, `residence_address`, `phone_no`, `dob`, `marital_status`, `date_of_anniversary`, `spouse_name`, `children`, `spouse_working`, `qualification`, `last_company`, `join_date`, `permanent_join_date`, `blood_group`, `account_category_id`, `account_group_id`, `account_first_subgroup_id`, `account_second_subgroup_id`, `ledger_account_id`, `otp_no`, `status`, `identity_mark`, `caste`, `religion`, `home_state`, `home_district`, `adhar_card_no`, `passport_no`, `height`, `driving_licence_no`, `pan_card_no`, `appointment_date`, `employee_id_no`, `dept_joining_date`, `initial_designation`, `office_name`, `recruitment_mode`, `reporting_to`, `present_state`, `present_district`, `present_pin_code`, `present_mobile_no`, `present_phone_no`, `present_email`, `present_address`, `permanent_state`, `permanent_district`, `permanent_pin_code`, `permanent_mobile_no`, `permanent_phone_no`, `permanent_email`, `nominee_name`, `permanent_address2`, `relation_with_employee`, `nomination_type`, `nominee_state`, `nominee_district`, `nominee_pin_code`, `nominee_mobile_no`, `nominee_present_address`) VALUES
(1, 'Sadasds', 'Male', 1, 6, '8986754345', 'dsf@wer.erte', '5ae4438680712.png', 'Current ', '32543534543543', 'Ewrew', 'Ewr', 'wer', '', 'werew', '', '2018-04-12', 'Single', '1970-01-01', '', 0, '', '', '', '2018-04-17', '2018-04-28', '', 1, 2, 17, 24, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '2', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(4, 'Jagdish Salvi', 'Male', 1, 1, '9983247774', 'jagdish@mogragroup.com', '58b3ddec698e8.png', '', '61333933354', 'SBBJ', 'Shobhagpura', 'SBBJ0011485', 'Tulsi Ram Salvi,\r\nHouse No. 77, B Block, Sector 9,\r\nSavina , Udaipur 313001 ( Raj )', 'R C Gehlot, \r\nHouse No. 20/21, Behind Mahadev Properties, Gokul Village, Sector 9,\r\nSavina , Udaipur 313001 ( Raj )', '7300277882', '1980-10-07', 'Married', '2008-02-23', 'Lalita Salvi', 2, 'Yes', 'B Sc.', 'Manikaran Enterprises', '2016-08-24', '2016-09-24', 'A+', 1, 2, 7, 10, 22, 3962, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(8, 'Jitendra Singh Jhala', 'Male', 2, 2, '9785177828', 'dispatch@mogragroup.com', '58b3ef21c6926.png', '', '394002010066287', 'Union Bank Of India', 'Fatehpura', 'UBIN0539406', '11, Ganpati Nagar A, Near Bohra Ganesh ji Temple, Sunderwas, Udaipur', '11, Ganpati Nagar A, Near Bohra Ganesh ji Temple, Sunderwas, Udaipur', '', '1985-01-05', 'Married', '2012-02-10', 'Asha Ranawat', 1, 'Yes', 'M.A., M.Sc., M.B.A.', 'Lakecity Infraproject Pvt. Ltd.,', '2014-08-19', '2015-02-20', 'O+', 1, 2, 7, 10, 31, 9397, 1, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(9, 'Usha Mali', 'Female', 3, 2, '9571352779', 'dispatch@mogragroup.com', '592402e63235a.png', '', '51111161500', 'SBBJ ', 'Madri ', 'SBBJ0010415', 'H.N. 114 UIT Colony Kanpur Madri .Udaipur  ', 'H.N. 114 UIT Colony Kanpur Madri .Udaipur  ', '0294 2980435', '1992-06-27', 'Single', '1970-01-01', '', 0, '', 'XII', 'AGHQ ', '2015-07-15', '2016-01-15', 'B+', 1, 2, 7, 10, 32, 9160, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(11, 'Pushpendra Nath Chauhan', 'Male', 1, 10, '9672994770', 'pushpendra@mogragroup.com', '58bd0c2f3fb02.png', '', '394002010064093', 'Union Bank Of India', 'New Fatehpura Udaipur', 'UBIN0539406', 'T 62 Tilak Nagar Hiran Mangri Sector 3 Udaipur', 'T 62 Tilak Nagar Hiran Mangri Sector 3 Udaipur', '8696029999', '1984-12-21', 'Married', '2010-11-23', 'Mrs. Kavita Kanwar', 1, 'No', 'MBA (MKTG & HR)', 'Bhatnagar Engineers', '2010-10-20', '2010-10-20', 'O+', 1, 2, 7, 10, 44, 6083, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(12, 'Vikram Singh Rao', 'Male', 1, 1, '9772604777', 'vikram@mogragroup.com', '58df6633240bc.png', '', '55545154566', 'SBBJ', 'Shobhagpura', 'SBBJ0011485', 'Bhanwar Singh \r\nVPO -Basantgarh\r\nTehsil -Pindwara\r\nDist. Sirohi', 'C/O Bhagwat Singh\r\nMeera Nagar\r\nbehind Ambience Hotel\r\nUdaipur', '9828232546', '1994-03-30', 'Single', '1970-01-01', '', 0, '', 'B.Tech ( Mech.)', 'Fresher', '2017-02-27', '2017-08-28', 'A+', 1, 2, 7, 10, 45, 6380, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(13, 'Bhopal Singh Jhala', 'Male', 8, 8, '9828447774', 'works@mogragroup.com', '5937ca3fbaa10.png', '', '394002010064416', 'Union Bank Of India', 'New Fatehpura', 'UBIN0539406', 'Narayan Singh Jhala\r\nVillage-Talavada\r\nPO -Bilot Teh -Dungla\r\nDist. Chittorgarh', 'C/O Ramesh Jain\r\nH.N. 159 Vivek Nagar\r\nSector no 3\r\nUdaipur', '9828447774', '1980-01-16', 'Married', '2007-03-12', 'Jaya kunwer jhala', 1, 'Yes', 'B.A.', 'Shilpa Trade Links', '2011-11-21', '2012-05-15', 'A+', 1, 2, 7, 10, 46, 8694, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(14, 'Reena Khandelwal', 'Female', 6, 4, '9649965452', 'info@mogragroup.com', '5abdc5b059e5f.png', '', '394002100066517', 'Union Bank', 'New Fatehpura', 'UBIN0539406', 'C/o N.K.Gupta , 13-B Kanta Niwas, Snatosh Nagar , Street  No-7,Gariyawas, Udaipur', 'C/o N.K.Gupta , 13-B Kanta Niwas, Snatosh Nagar , Street  No-7,Gariyawas, Udaipur', '9649965452', '1979-01-31', 'Married', '1970-01-01', 'Gopal Khandelwal', 2, 'Yes', 'MA ', 'Phosphate India Pvt.Ltd', '2015-01-16', '2015-07-16', 'B+', 1, 2, 7, 10, 52, 7669, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(15, 'Mohammed Arif', 'Male', 1, 11, '9649004777', 'arif@mogragroup.com', '58c397efb67fe.jpg', '', '310102010457570', 'Union Bank Of India', 'Town Hall', 'UBIN0531014', '15/3 Opposite Matlob Masjid, Rajput Colony, \r\n62 futta road Saharanpur UP', 'Opposite soni general Store, Ganesh nagaer\r\npayda University Road, Udaipur raj', '', '1987-09-17', 'Married', '2014-10-27', 'Bushra Arif', 1, 'No', 'B.Sc and PGDBM', 'Kirloskar', '2011-11-16', '2012-04-02', 'B-', 1, 2, 7, 10, 70, 5414, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(16, 'Anshul Mogra', 'Male', 1, 6, '9772704777', 'anshul@mogragroup.com', '58e3324748064.png', '', '001410091506700', 'IDBI', 'Udaipur', 'IDBI', '560, OTC Scheme,\r\nAmbamata,\r\nUdaipur', '560, OTC Scheme,\r\nAmbamata,\r\nUdaipur', '0294 2980435', '1977-05-23', 'Married', '2007-04-20', 'Priya Mogra', 1, 'Yes', 'CA', 'Danube Building ', '2008-11-12', '2008-11-12', 'B+', 2, 6, 2, 37, 75, 4135, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(17, 'Priya Mogra', 'Female', 2, 6, '9772704888', 'priya@mogragroup.com', '58e0ffa003ced.jpg', '', '1234567890', 'UCO Bank', 'Ambamata', 'uCO12345', '560 OTC Scheme \r\nAmbamata Udaipur', '560 OTC Scheme \r\nAmbamata Udaipur', '02942431510', '1981-11-08', 'Married', '2007-04-20', 'Anshul Mogra', 1, 'Yes', 'MCA', 'None', '2007-05-01', '2007-05-01', 'A-', 2, 6, 2, 37, 0, 3880, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(18, 'Mukesh Jain ', 'Male', 1, 11, '9829042542', 'mukesh@mogragroup.com', '58e33d60a0bf9.docx', '', '394002010064091', 'Union Bank ', 'Kota Arodurm Barnch', 'UBIN0535265', '1488,  RK.PURAM, NEAR WATER TANK, KOTA,', '113/28 , Partap Nagar Sector -11, JAIPUR', '', '2069-10-15', 'Married', '1995-04-20', 'Sonali Jain', 1, 'Yes', 'BSC', 'Shilpa Trade Links Pvt. Ltd.', '2009-07-15', '2010-01-15', 'AB-', 1, 2, 7, 10, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(19, 'Jayanti Jain ', 'Female', 2, 12, '9414253644', 'accounts@mogragroup.com', '59368f3f59071.png', '', '000', 'SBBJ', 'Shobhagpura', 'SBBJ0011485', '-----', '-----', '', '2063-08-16', 'Married', '2007-05-01', 'Subhas  Mehta ', 0, 'No', 'BCOM', '-', '2016-12-02', '2016-12-02', 'A-', 1, 2, 7, 10, 0, 9580, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(20, 'Devraj Singh Chouhan ', 'Male', 1, 1, '9784960745', 'devrajsinghchouhan4201@gmail.com', '590343e0d6477.docx', '', '51092208537', 'SBBJ ', 'Bhopal Ganj ', 'SBBJ0010094', '446, Chandra Shaker Azad Nagar, Bhilwara', '446, Chandra Shaker Azad Nagar, Bhilwara', '', '1992-08-04', 'Single', '1970-01-01', '', 0, '', 'B.Tech Elect.', 'J.K.Cement', '2017-04-12', '2017-04-12', 'B+', 1, 2, 7, 10, 0, 4541, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(21, 'Pallav Pujari', 'Male', 1, 1, '9950595253', 'pallavpujari@gmail.com', '5938f20dd8a6b.png', '', '00', 'SBBJ', 'Mavli', '00', '7, Nakoda Nagar II, Near Transport Nagar, Udaipur', '7, Nakoda Nagar II, Near Transport Nagar, Udaipur', '', '1993-01-13', 'Married', '2017-01-22', 'Komal Sharma', 0, 'No', 'B.Tech', 'Tempsons', '2017-04-12', '2017-04-12', '', 1, 2, 7, 10, 0, 3894, 1, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(22, 'Vikram Singh Meena', 'Male', 5, 13, '9928544560', 'vsm@mogragroup.com', '590f15094c9fb.jpg', '', '3040040100999', 'Union Bank Of India', 'New Fatehpura', 'UBIN0539406', 'Kherwara', 'Sector 11', '', '1980-07-10', 'Married', '1970-01-01', '', 0, 'No', '12th', 'Courier', '1992-04-10', '1992-04-10', 'A-', 1, 2, 7, 10, 0, 0, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(23, 'Ankit Sisodiya', 'Male', 5, 4, '9549993335', 'gopalkrishanp3@gmail.com', '59301553ca9ed.png', '', '111111', 'Canra Bank', 'Qqqq', 'qq', '-', '-', '', '1991-06-16', 'Single', '1970-01-01', '', 0, '', '', '', '2017-06-01', '1970-01-01', '', 2, 6, 2, 37, 0, 8964, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(24, 'Mahendra Kumar Darji', 'Male', 2, 12, '7891970769', 'mahitailor6@gmail.com', '5964cb2f7f139.png', '', '61176715083', 'State Bank Of India ', 'Sarada Branch  ', 'SBIN0031224', 'Village Post Chawand , Tehsil Sarada', 'Behind Choudahry Hospital , Sector -4, Hiran Magari- Udaipur ', '7014167322', '1993-05-01', 'Single', '1970-01-01', '', 0, '', 'CA', '', '2017-06-26', '2017-06-26', 'A+', 1, 2, 7, 10, 0, 1112, 1, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(25, 'Deepika  Totla', 'Female', 2, 12, '8829900446', 'finance@mogragroup.com', '5a02cde93faf8.png', '', '20418670360', 'State Bank Of India', 'Madhuban  Branch ', 'SBIN0001533', 'Laddha Professional Academy , 3rd floor , MM plaza, Ayad  Road , Udaipur -313001', 'Laddha Professional Academy , 3rd floor , MM plaza, Ayad  Road , Udaipur -313001', '', '1991-11-05', 'Married', '2016-04-30', 'Ganpat  Laddha', 0, 'Yes', 'CA', 'Manish Agarwal & sons ', '2017-09-14', '2017-09-14', 'A+', 1, 2, 7, 10, 0, 4713, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(26, 'Priyanka  Sewarsa', 'Female', 2, 12, '7200521763', 'priyankasewarsa@gmail.com', '5a02ce2494bc5.png', '', '0', 'SBI', 'S', 's', 'Meera Nagar, Opposite to water Tank, Udaipur -313001', '-----', '', '1996-07-17', 'Single', '1970-01-01', '', 0, '', 'B.COM', 'NA', '2017-09-27', '2017-09-27', 'A-', 1, 2, 7, 10, 0, 1572, 1, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(27, 'Pushpendra Singh Ranawat', 'Male', 1, 1, '8003800676', 'psr@mogragroup.com', '5a1bf6e9472e6.png', '', '35557293628', 'SBI Bank', 'Chittorgarh ', 'SBIN', 'Ranawato ka Mohhla , Near Higher Secondri school, Narela , Chittaurgarh -312207', 'Ranawato ka Mohhla , Near Higher Secondri school, Narela , Chittaurgarh -312207', '', '1994-08-08', 'Single', '1970-01-01', '', 0, '', 'BTECH', '', '2017-10-03', '2017-10-03', 'B+', 1, 2, 7, 10, 0, 8116, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(28, 'Deepak  Mehta ', 'Male', 2, 12, '9983412091', 'deepak.mehta874@gmail.com', '5a1bf64165b15.png', '', '6096213163', 'Indian Bank', 'Sardarpura Branch', 'IDIB000U006', '4 G 25, housing Board colony , Vidhya Nagar Sector -4, Hiran Magri', '4 G 25, housing Board colony , Vidhya Nagar Sector -4, Hiran Magri', '', '1985-09-17', 'Married', '2010-01-26', 'Tripti Mehta', 2, 'No', 'Bcom', 'Roopatul  chemical', '2017-11-20', '2017-11-20', '', 1, 2, 7, 10, 0, 2640, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(29, 'Murlidhar  Shrotriya', 'Male', 1, 1, '9461683177', 'murlidharshrotriya5@gmail.com', '5abdc54757b5a.png', '', '20234426921', 'State Bank Of India ', ' Bhilwara ', 'SBIN007364', 'Radheshyam Shrotriya\r\n1020 Tehsil Area  -Karera   Bhilwara ', '14, Rajenbdra Nagar , Nr. New RTO office , Bhuwana', '', '1994-03-07', 'Single', '1970-01-01', '', 0, '', 'B-Tech Mechnical', '', '2018-03-05', '2018-03-05', 'A+', 1, 2, 7, 10, 0, 6871, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', ''),
(30, 'Mukesh Jain', 'Male', 1, 11, '9829042542', 'mukesh@mogragroup.com', '5ab25fb7181d9.docx', '', '394002010064091', 'Union Bank  Of India', 'Arodram ', '....', '93, Greater Kalish , Sunal Road, Nr. Kashev Globel Acedmy , Purani Chungi, Jaipur Agra Road, Jaipur', '93, Greater Kalish , Sunal Road, Nr. Kashev Globel Acedmy , Purani Chungi, Jaipur Agra Road, Jaipur', '', '1969-10-15', 'Married', '1994-04-20', 'Sonali Jain ', 1, 'Yes', 'BSC', 'Shilpa Trade Links Pvt. Ltd.', '2009-06-17', '2009-06-18', 'AB-', 1, 2, 7, 10, 0, 9514, 0, '', '', '', '', '', '', '', '', '', '', '0000-00-00', '', '0000-00-00', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
