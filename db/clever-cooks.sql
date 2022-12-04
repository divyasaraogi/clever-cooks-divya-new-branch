-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Dec 04, 2022 at 01:20 PM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clever-cooks`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `email` varchar(128) NOT NULL,
  `password` varchar(128) DEFAULT NULL,
  `display_name` varchar(64) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `user_type` varchar(10) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`user_id`, `email`, `password`, `display_name`, `full_name`, `photo_url`, `user_type`) VALUES
(1, 'admin@clevercooks.duckdns.org', '$2y$10$R3h/8P8eYufK1vHGWn2nmOcG2z2c7grKNo3BSvaCMPUVwV6lHVtI6', 'Admin', 'Site Admin', 'assets/catprofile.jpeg', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `groupings`
--

CREATE TABLE `groupings` (
  `grp_id` int(11) NOT NULL,
  `grp_desc` varchar(255) NOT NULL,
  `grp_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groupings`
--

INSERT INTO `groupings` (`grp_id`, `grp_desc`, `grp_name`) VALUES
(1, 'categories that best fit your eating preferences', 'diet'),
(2, 'cuisine from around the world', 'cuisine');

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `part_id` int(11) NOT NULL,
  `part_name` varchar(64) NOT NULL,
  `part_grp` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`part_id`, `part_name`, `part_grp`) VALUES
(1, 'bread', 'proteins'),
(2, 'cheese', 'proteins'),
(3, 'coffee', 'proteins'),
(4, 'rice', 'proteins'),
(5, 'eggs', 'proteins'),
(6, 'onions', 'veggies'),
(7, 'potatoes', 'veggies'),
(8, 'chicken', 'proteins'),
(9, 'banana', 'fruits'),
(10, 'grapes', 'fruits'),
(11, 'milk', 'proteins'),
(12, 'jam', 'proteins'),
(13, 'peanut butter', 'proteins'),
(14, 'oats', 'proteins'),
(15, 'strawberry', 'fruits'),
(16, 'parsely', 'veggies'),
(17, 'spaghetti', 'proteins'),
(18, 'pasta sauce', 'proteins'),
(19, 'tomatoes', 'veggies'),
(20, 'basil', 'veggies'),
(21, 'noodles', 'proteins'),
(22, 'coconut cream', 'proteins'),
(23, 'peas', 'veggies'),
(24, 'carrots', 'veggies'),
(25, 'bell peppers', 'veggies'),
(26, 'green chilies', 'veggies'),
(27, 'red chilies', 'veggies'),
(28, 'shrimp', 'proteins'),
(29, 'fish', 'proteins'),
(30, 'crabs', 'proteins'),
(33, 'durian', 'fruits');

-- --------------------------------------------------------

--
-- Table structure for table `recipe-ing`
--

CREATE TABLE `recipe-ing` (
  `map_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `quantity` decimal(6,2) NOT NULL,
  `unit` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `recipe_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `steps` varchar(4096) NOT NULL,
  `cook_time` smallint(6) NOT NULL DEFAULT '5',
  `diet` int(11) NOT NULL,
  `cuisine` int(11) NOT NULL,
  `calories_low` int(11) NOT NULL,
  `calories_high` int(11) NOT NULL,
  `photo` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tag_id` int(11) NOT NULL,
  `tag_grp` int(11) NOT NULL,
  `tag_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`tag_id`, `tag_grp`, `tag_name`) VALUES
(1, 1, 'pescatarian'),
(2, 1, 'non-vegetarian'),
(3, 1, 'vegetarian'),
(4, 1, 'vegan'),
(5, 1, 'flexitarian'),
(6, 1, 'macrobiotic'),
(7, 1, 'paleo'),
(9, 1, 'ketogenic'),
(10, 1, 'mediterranean'),
(11, 2, 'indian'),
(12, 2, 'filipino'),
(13, 2, 'asian'),
(14, 2, 'chinese'),
(15, 2, 'western');

-- --------------------------------------------------------

--
-- Table structure for table `userpantry`
--

CREATE TABLE `userpantry` (
  `map_id` int(11) NOT NULL,
  `part_id` int(11) NOT NULL,
  `unit` int(11) NOT NULL,
  `pieces` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userpantry`
--

INSERT INTO `userpantry` (`map_id`, `part_id`, `unit`, `pieces`) VALUES
(1, 1, 0, 0),
(2, 4, 0, 0),
(3, 5, 0, 0),
(4, 14, 0, 0),
(5, 16, 0, 0),
(6, 17, 0, 0),
(7, 9, 0, 0),
(8, 20, 0, 0),
(9, 25, 0, 0),
(10, 1, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `in_email` (`email`);

--
-- Indexes for table `groupings`
--
ALTER TABLE `groupings`
  ADD PRIMARY KEY (`grp_id`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`part_id`);

--
-- Indexes for table `recipe-ing`
--
ALTER TABLE `recipe-ing`
  ADD PRIMARY KEY (`map_id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`recipe_id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`tag_id`),
  ADD KEY `tag_grp` (`tag_grp`);

--
-- Indexes for table `userpantry`
--
ALTER TABLE `userpantry`
  ADD PRIMARY KEY (`map_id`),
  ADD KEY `part_id` (`part_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `groupings`
--
ALTER TABLE `groupings`
  MODIFY `grp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `part_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `recipe-ing`
--
ALTER TABLE `recipe-ing`
  MODIFY `map_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `recipe_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `tag_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `userpantry`
--
ALTER TABLE `userpantry`
  MODIFY `map_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tags`
--
ALTER TABLE `tags`
  ADD CONSTRAINT `tags_ibfk_1` FOREIGN KEY (`tag_grp`) REFERENCES `groupings` (`grp_id`);

--
-- Constraints for table `userpantry`
--
ALTER TABLE `userpantry`
  ADD CONSTRAINT `userpantry_ibfk_1` FOREIGN KEY (`part_id`) REFERENCES `ingredients` (`part_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
