-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 23, 2024 at 09:29 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vehiclebookings`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `facility_id` int(30) NOT NULL,
  `reserved_by` varchar(255) NOT NULL,
  `name` text DEFAULT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `color` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `image`, `facility_id`, `reserved_by`, `name`, `start`, `end`, `color`) VALUES
(13, NULL, 8, 'Coores', 'Socialization Night', '2024-05-17 09:00:00', '2024-05-17 14:30:00', NULL),
(27, NULL, 8, 'Rustan Ediong', 'Guidance Counselling', '2024-05-21 08:00:00', '2024-05-21 18:00:00', NULL),
(31, NULL, 9, 'Elvis Salas', 'Graduation Day', '2024-07-24 08:00:00', '2024-07-24 14:30:00', NULL),
(32, NULL, 9, 'John Doe', 'trialsyslog', '2024-07-16 00:00:00', '2024-07-18 00:00:00', NULL),
(149, NULL, 9, 'fsdfsd', 'Event', '2024-07-25 00:00:00', '2024-07-26 00:00:00', NULL),
(150, NULL, 9, 'rere', 'Event', '2024-07-18 00:00:00', '2024-07-19 00:00:00', NULL),
(152, NULL, 9, 'vdvsd', 'Event', '2024-07-26 00:00:00', '2024-07-27 00:00:00', NULL),
(191, 'uploads/events/IMG_0001_4.JPG', 8, 'Coores', 'Webscripting', '2024-07-10 00:00:00', '2024-07-11 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `facility_schedule`
--

CREATE TABLE `facility_schedule` (
  `id` int(11) NOT NULL,
  `facility_id` int(11) DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  `status` enum('available','reserved') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facility_schedule`
--

INSERT INTO `facility_schedule` (`id`, `facility_id`, `schedule_date`, `status`) VALUES
(1, 1, '2024-03-21', 'reserved');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_events`
--

CREATE TABLE `tbl_events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tms_admin`
--

CREATE TABLE `tms_admin` (
  `a_id` int(20) NOT NULL,
  `a_name` varchar(200) NOT NULL,
  `a_email` varchar(200) NOT NULL,
  `a_pwd` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tms_admin`
--

INSERT INTO `tms_admin` (`a_id`, `a_name`, `a_email`, `a_pwd`) VALUES
(1, 'System Admin', 'admin@mail.com', 'codeastro.com'),
(2, 'Myrel Nginsayan', 'myrel.nginsayan@kcp.edu.ph', 'Password123!');

-- --------------------------------------------------------

--
-- Table structure for table `tms_feedback`
--

CREATE TABLE `tms_feedback` (
  `f_id` int(20) NOT NULL,
  `f_uname` varchar(200) NOT NULL,
  `f_content` longtext NOT NULL,
  `f_status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tms_feedback`
--

INSERT INTO `tms_feedback` (`f_id`, `f_uname`, `f_content`, `f_status`) VALUES
(1, 'Elliot Gape', 'This is a demo feedback text. This is a demo feedback text. This is a demo feedback text.', 'Published'),
(2, 'Mark L. Anderson', 'Sample Feedback Text for testing! Sample Feedback Text for testing! Sample Feedback Text for testing!', 'Published'),
(3, 'Liam Moore ', 'test number 3', '');

-- --------------------------------------------------------

--
-- Table structure for table `tms_pwd_resets`
--

CREATE TABLE `tms_pwd_resets` (
  `r_id` int(20) NOT NULL,
  `r_email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tms_pwd_resets`
--

INSERT INTO `tms_pwd_resets` (`r_id`, `r_email`) VALUES
(2, 'admin@mail.com');

-- --------------------------------------------------------

--
-- Table structure for table `tms_syslogs`
--

CREATE TABLE `tms_syslogs` (
  `l_id` int(20) NOT NULL,
  `u_id` varchar(200) NOT NULL,
  `u_email` varchar(200) NOT NULL,
  `u_logintime` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `event_id` int(20) DEFAULT NULL,
  `booking_time` datetime NOT NULL DEFAULT current_timestamp(),
  ` facility_name` varchar(255) DEFAULT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `action` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tms_user`
--

CREATE TABLE `tms_user` (
  `u_id` int(20) NOT NULL,
  `u_fname` varchar(200) NOT NULL,
  `u_lname` varchar(200) NOT NULL,
  `u_phone` varchar(200) NOT NULL,
  `u_addr` varchar(200) NOT NULL,
  `u_category` varchar(200) NOT NULL,
  `u_email` varchar(200) NOT NULL,
  `u_pwd` varchar(20) NOT NULL,
  `u_car_type` varchar(200) NOT NULL,
  `u_car_regno` varchar(200) NOT NULL,
  `u_car_bookdate` varchar(200) NOT NULL,
  `u_car_book_status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tms_user`
--

INSERT INTO `tms_user` (`u_id`, `u_fname`, `u_lname`, `u_phone`, `u_addr`, `u_category`, `u_email`, `u_pwd`, `u_car_type`, `u_car_regno`, `u_car_bookdate`, `u_car_book_status`) VALUES
(17, 'Jeric ', 'Selvino', '0948203111', 'KCP', 'Custodian', 'jeric.selvino@kcp.edu.ph', '12345', '', '', '', ''),
(19, 'Myrel', 'Nginsayan', 'myrel.nginsayan@kcp.edu.ph', 'KCP', 'Admin', 'myrel.nginsayan@kcp.edu.ph', '12345', '', '', '', ''),
(20, 'Rustan', 'Ediong', 'rustan.ediong@kcp.edu.ph', 'Betag, La Trinidad, Benguet', 'Custodian', 'rustan.ediong@kcp.edu.ph', '12345', '', '', '', ''),
(21, 'Rhema Joy ', 'Alboc', 'rhemajoy.alboc@kcp.edu.ph', 'KCP', 'Custodian', 'rhemajoy.alboc@kcp.edu.ph', '12345', '', '', '', ''),
(22, 'Rhema Joy ', 'Alboc', 'rhemajoy.alboc@kcp.edu.ph', 'KCP', 'Admin', 'rhemajoy.alboc@kcp.edu.ph', '12345', '', '', '', ''),
(24, 'Angie', 'Bugtong', 'angiebugtong@kcp.edu.ph', 'KCP', 'Admin', 'angiebugtong@kcp.edu.ph', '12345', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tms_vehicle`
--

CREATE TABLE `tms_vehicle` (
  `v_id` int(20) NOT NULL,
  `v_name` varchar(200) NOT NULL,
  `v_reg_no` varchar(200) NOT NULL,
  `v_pass_no` varchar(200) NOT NULL,
  `v_driver` varchar(200) NOT NULL,
  `v_category` varchar(200) NOT NULL,
  `v_dpic` varchar(200) NOT NULL,
  `v_status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tms_vehicle`
--

INSERT INTO `tms_vehicle` (`v_id`, `v_name`, `v_reg_no`, `v_pass_no`, `v_driver`, `v_category`, `v_dpic`, `v_status`) VALUES
(8, 'Ebenezer Convention Center', '30000', '', 'Jeric Selvino', 'Academic Facilities', 'Gym.png', 'Booked'),
(9, 'Function Hall', '500', '', 'Jeric Selvino', 'Academic Facilities', 'Function Hall.png', 'Booked'),
(10, 'HRM Laboratory', '50', '', 'Jeric Selvino', 'Specialized Facilities', 'HRM Laboratory.png', 'HRM Laboratory'),
(11, 'Chemistry Laboratory', '50', '', 'Jeric Selvino', 'Academic Facilities', 'Chemistry Laboratory.png', 'Chemistry Laboratory'),
(12, 'Classroom', '50', '', 'Jeric Selvino', 'Academic Facilities', 'Classroom.png', 'Classroom'),
(13, 'LM Church', '100', '', 'Jeric Selvino', 'Support Facilities', 'LM Church.png', 'LM Church'),
(15, 'Basketball Court', '200', '', 'Jeric Selvino', 'Recreational Facilities', 'basketball-court.png', 'Basketball Court'),
(16, 'KCP Firing Range', '25', '', 'Jeric Selvino', 'Recreational Facilities', 'KCP Firing Range 1.png', 'KCP Firing Range'),
(17, 'Faculty Lounge', 'KCP Lounge', '', 'Jeric Selvino', 'Academic Facilities', 'Faculty Lounge.png', 'Booked'),
(18, 'KCP Gym Dormitory', '20', '', 'Jeric Selvino', 'Support Facilities', 'dorm.png', 'KCP Gym Dormitory'),
(19, 'School Front Court', '150', '', 'Jeric Selvino', 'Recreational Facilities', 'tennis-court.png', 'School Front Court');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `facility_schedule`
--
ALTER TABLE `facility_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_events`
--
ALTER TABLE `tbl_events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tms_admin`
--
ALTER TABLE `tms_admin`
  ADD PRIMARY KEY (`a_id`);

--
-- Indexes for table `tms_feedback`
--
ALTER TABLE `tms_feedback`
  ADD PRIMARY KEY (`f_id`);

--
-- Indexes for table `tms_pwd_resets`
--
ALTER TABLE `tms_pwd_resets`
  ADD PRIMARY KEY (`r_id`);

--
-- Indexes for table `tms_syslogs`
--
ALTER TABLE `tms_syslogs`
  ADD PRIMARY KEY (`l_id`);

--
-- Indexes for table `tms_user`
--
ALTER TABLE `tms_user`
  ADD PRIMARY KEY (`u_id`);

--
-- Indexes for table `tms_vehicle`
--
ALTER TABLE `tms_vehicle`
  ADD PRIMARY KEY (`v_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `facility_schedule`
--
ALTER TABLE `facility_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_events`
--
ALTER TABLE `tbl_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tms_admin`
--
ALTER TABLE `tms_admin`
  MODIFY `a_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tms_feedback`
--
ALTER TABLE `tms_feedback`
  MODIFY `f_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tms_pwd_resets`
--
ALTER TABLE `tms_pwd_resets`
  MODIFY `r_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tms_syslogs`
--
ALTER TABLE `tms_syslogs`
  MODIFY `l_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;

--
-- AUTO_INCREMENT for table `tms_user`
--
ALTER TABLE `tms_user`
  MODIFY `u_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tms_vehicle`
--
ALTER TABLE `tms_vehicle`
  MODIFY `v_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
