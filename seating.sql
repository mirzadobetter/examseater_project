-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Feb 23, 2022 at 03:19 PM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `seating`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminid`, `name`, `email`, `password`) VALUES
(1, 'Admin1002', 'admin1002@gmail.com', 'root12'),
(2, 'Admin', 'xyz@gmail.com', 'PjwnJTF6'),
(9, 'Admin1001', 'admin1001@gmail.com', 'AWlxauaL');

-- --------------------------------------------------------

--
-- Table structure for table `batch`
--

CREATE TABLE `batch` (
  `batch_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `startno` int(11) NOT NULL,
  `endno` int(11) NOT NULL,
  `date` date NOT NULL,
  `total` int(11) GENERATED ALWAYS AS (`endno` - `startno` + 1) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `batch`
--

INSERT INTO `batch` (`batch_id`, `class_id`, `room_id`, `startno`, `endno`, `date`) VALUES
(40, 7, 18, 1, 7, '2021-06-08'),
(41, 8, 18, 1, 3, '2021-06-08');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `class_id` int(11) NOT NULL,
  `year` varchar(20) NOT NULL,
  `dept` varchar(30) NOT NULL,
  `division` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`class_id`, `year`, `dept`, `division`) VALUES
(7, 'SE', 'Computer', 'A'),
(8, 'SE', 'ETRX', 'A'),
(32, 'TE', 'Computer', 'A');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `rid` int(11) NOT NULL,
  `room_no` int(11) NOT NULL,
  `floor` int(11) NOT NULL,
  `capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`rid`, `room_no`, `floor`, `capacity`) VALUES
(9, 1, 3, 5),
(10, 2, 3, 5),
(18, 3, 4, 10);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `https://img.icons8.com/?size=30&id=50897&format=png&color=FFFFFF` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `class` int(11) NOT NULL,
  `rollno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`https://img.icons8.com/?size=30&id=50897&format=png&color=FFFFFF`, `password`, `name`, `email`, `class`, `rollno`) VALUES
(7, 'john44', 'John', 'john@gmail.com', 8, 11),
(8, 'h@rry', 'Harry', 'harry2@gmail.com', 8, 8),
(9, 'Jam#s', 'James', 'james@gmail.com', 8, 2),
(10, 'Paul45', 'Paul', 'paul@gmail.com', 8, 3),
(11, 'p@uli', 'Pauly', 'pauly@gmail.com', 8, 4),
(13, 'lisa12', 'Lisa', 'lisa@gmail.com', 8, 7),
(14, 'daniel98', 'Daniel', 'daniel@gmail.com', 8, 5),
(15, 'anthony', 'Anthony', 'anthony@gmail.com', 8, 6),
(16, 'mary', 'Mary', 'mary@gmail.com', 8, 9),
(17, 'laura12', 'Laura', 'laura@gmail.com', 8, 10),
(18, 'michelle', 'Michelle', 'michelle@gmail.com', 7, 1),
(19, 'robert', 'Robert', 'robert@gmail.com', 7, 2),
(20, 'ronald', 'Ronald', 'ronald@gmail.com', 7, 3),
(21, 'patrica', 'Patrica', 'patrica@gmail.com', 7, 4),
(22, 'nancy', 'Nancy', 'Nancy@gmail.com', 7, 5),
(23, 'christopher', 'Christopher', 'christopher@gmail.com', 7, 6),
(32, 'clark', 'Clark', 'clark@gmail.com', 8, 1),
(41, 'thomas', 'Thomas', 'thomas@gmail.com', 7, 7);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminid`),
  ADD UNIQUE KEY `admin_email` (`email`);

--
-- Indexes for table `batch`
--
ALTER TABLE `batch`
  ADD PRIMARY KEY (`batch_id`),
  ADD KEY `batch_ibfk_1` (`room_id`),
  ADD KEY `batch_ibfk_2` (`class_id`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`),
  ADD UNIQUE KEY `uniqueclass` (`year`,`dept`,`division`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`rid`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`https://img.icons8.com/?size=30&id=50897&format=png&color=FFFFFF`),
  ADD UNIQUE KEY `Student_email` (`email`),
  ADD KEY `students_ibfk_1` (`class`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `adminid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `batch`
--
ALTER TABLE `batch`
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `https://img.icons8.com/?size=30&id=50897&format=png&color=FFFFFF` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `batch`
--
ALTER TABLE `batch`
  ADD CONSTRAINT `batch_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`rid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `batch_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`class`) REFERENCES `class` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";





CREATE TABLE `admin` (
  `adminid` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


INSERT INTO `admin` (`adminid`, `name`, `email`, `password`) VALUES
(1, 'Admin1002', 'admin1002@gmail.com', 'root12'),
(2, 'Admin', 'xyz@gmail.com', 'PjwnJTF6'),
(9, 'Admin1001', 'admin1001@gmail.com', 'AWlxauaL');



CREATE TABLE `batch` (
  `batch_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `startno` int(11) NOT NULL,
  `endno` int(11) NOT NULL,
  `date` date NOT NULL,
  `total` int(11) GENERATED ALWAYS AS (`endno` - `startno` + 1) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



INSERT INTO `batch` (`batch_id`, `class_id`, `room_id`, `startno`, `endno`, `date`) VALUES
(40, 7, 18, 1, 7, '2021-06-08'),
(41, 8, 18, 1, 3, '2021-06-08');



CREATE TABLE `class` (
  `class_id` int(11) NOT NULL,
  `year` varchar(20) NOT NULL,
  `dept` varchar(30) NOT NULL,
  `division` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



INSERT INTO `class` (`class_id`, `year`, `dept`, `division`) VALUES
(7, 'SE', 'Computer', 'A'),
(8, 'SE', 'ETRX', 'A'),
(32, 'TE', 'Computer', 'A');


CREATE TABLE `room` (
  `rid` int(11) NOT NULL,
  `room_no` int(11) NOT NULL,
  `floor` int(11) NOT NULL,
  `capacity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



INSERT INTO `room` (`rid`, `room_no`, `floor`, `capacity`) VALUES
(9, 1, 3, 5),
(10, 2, 3, 5),
(18, 3, 4, 10);



CREATE TABLE `students` (
  `https://img.icons8.com/?size=30&id=50897&format=png&color=FFFFFF` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `class` int(11) NOT NULL,
  `rollno` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



INSERT INTO `students` (`https://img.icons8.com/?size=30&id=50897&format=png&color=FFFFFF`, `password`, `name`, `email`, `class`, `rollno`) VALUES
(7, 'john44', 'John', 'john@gmail.com', 8, 11),
(8, 'h@rry', 'Harry', 'harry2@gmail.com', 8, 8),
(9, 'Jam#s', 'James', 'james@gmail.com', 8, 2),
(10, 'Paul45', 'Paul', 'paul@gmail.com', 8, 3),
(11, 'p@uli', 'Pauly', 'pauly@gmail.com', 8, 4),
(13, 'lisa12', 'Lisa', 'lisa@gmail.com', 8, 7),
(14, 'daniel98', 'Daniel', 'daniel@gmail.com', 8, 5),
(15, 'anthony', 'Anthony', 'anthony@gmail.com', 8, 6),
(16, 'mary', 'Mary', 'mary@gmail.com', 8, 9),
(17, 'laura12', 'Laura', 'laura@gmail.com', 8, 10),
(18, 'michelle', 'Michelle', 'michelle@gmail.com', 7, 1),
(19, 'robert', 'Robert', 'robert@gmail.com', 7, 2),
(20, 'ronald', 'Ronald', 'ronald@gmail.com', 7, 3),
(21, 'patrica', 'Patrica', 'patrica@gmail.com', 7, 4),
(22, 'nancy', 'Nancy', 'Nancy@gmail.com', 7, 5),
(23, 'christopher', 'Christopher', 'christopher@gmail.com', 7, 6),
(32, 'clark', 'Clark', 'clark@gmail.com', 8, 1),
(41, 'thomas', 'Thomas', 'thomas@gmail.com', 7, 7);


ALTER TABLE `admin`
  ADD PRIMARY KEY (`adminid`),
  ADD UNIQUE KEY `admin_email` (`email`);

ALTER TABLE `batch`
  ADD PRIMARY KEY (`batch_id`),
  ADD KEY `batch_ibfk_1` (`room_id`),
  ADD KEY `batch_ibfk_2` (`class_id`);


ALTER TABLE `class`
  ADD PRIMARY KEY (`class_id`),
  ADD UNIQUE KEY `uniqueclass` (`year`,`dept`,`division`);

ALTER TABLE `room`
  ADD PRIMARY KEY (`rid`);


ALTER TABLE `students`
  ADD PRIMARY KEY (`https://img.icons8.com/?size=30&id=50897&format=png&color=FFFFFF`),
  ADD UNIQUE KEY `Student_email` (`email`),
  ADD KEY `students_ibfk_1` (`class`);



ALTER TABLE `admin`
  MODIFY `adminid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;



ALTER TABLE `batch`
  MODIFY `batch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;


ALTER TABLE `class`
  MODIFY `class_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;


ALTER TABLE `room`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;


ALTER TABLE `students`
  MODIFY `https://img.icons8.com/?size=30&id=50897&format=png&color=FFFFFF` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;


ALTER TABLE `batch`
  ADD CONSTRAINT `batch_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`rid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `batch_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `class` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`class`) REFERENCES `class` (`class_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;