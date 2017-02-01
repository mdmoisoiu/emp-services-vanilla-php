-- phpMyAdmin SQL Dump
-- version 4.4.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 01, 2017 at 03:18 AM
-- Server version: 5.6.25
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `employee_directory`
--

-- --------------------------------------------------------

--
-- Table structure for table `app_user`
--

CREATE TABLE IF NOT EXISTS `app_user` (
  `id` int(11) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role` varchar(128) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `app_user`
--

INSERT INTO `app_user` (`id`, `first_name`, `last_name`, `email`, `username`, `password`, `role`) VALUES
(1, 'test', 'test', 'test', 'test', 'test', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `app_user_roles`
--

CREATE TABLE IF NOT EXISTS `app_user_roles` (
  `id` int(11) NOT NULL,
  `code` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  `available_to_registrations` tinyint(1) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `app_user_roles`
--

INSERT INTO `app_user_roles` (`id`, `code`, `name`, `available_to_registrations`) VALUES
(1, 'admin', 'admin', 0),
(2, 'user', 'user', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ed_chat`
--

CREATE TABLE IF NOT EXISTS `ed_chat` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` tinytext NOT NULL,
  `date_time` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Table structure for table `ed_country`
--

CREATE TABLE IF NOT EXISTS `ed_country` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `code` varchar(2) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ed_country`
--

INSERT INTO `ed_country` (`id`, `name`, `code`) VALUES
(1, 'Romania', 'RO'),
(2, 'Germany', 'DE'),
(3, 'United Kingdom', 'UK'),
(4, 'France', 'FR');

-- --------------------------------------------------------

--
-- Table structure for table `ed_employee`
--

CREATE TABLE IF NOT EXISTS `ed_employee` (
  `id` int(11) NOT NULL,
  `first_name` varchar(45) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Table structure for table `ed_image`
--

CREATE TABLE IF NOT EXISTS `ed_image` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `file_name` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

--
-- Table structure for table `ed_position`
--

CREATE TABLE IF NOT EXISTS `ed_position` (
  `id` int(11) NOT NULL,
  `line_manager_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ed_position_has_employee`
--

CREATE TABLE IF NOT EXISTS `ed_position_has_employee` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `position_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

--
-- Indexes for table `app_user`
--
ALTER TABLE `app_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_user_roles`
--
ALTER TABLE `app_user_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ed_chat`
--
ALTER TABLE `ed_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ed_country`
--
ALTER TABLE `ed_country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ed_employee`
--
ALTER TABLE `ed_employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ed_image`
--
ALTER TABLE `ed_image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ed_image_ed_employee1_idx` (`employee_id`);

--
-- Indexes for table `ed_position`
--
ALTER TABLE `ed_position`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ed_position_ed_country_idx` (`country_id`),
  ADD KEY `fk_ed_position_ed_position1_idx` (`line_manager_id`);

--
-- Indexes for table `ed_position_has_employee`
--
ALTER TABLE `ed_position_has_employee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ed_position_has_employee_ed_employee1_idx` (`employee_id`),
  ADD KEY `fk_ed_position_has_employee_ed_position1_idx` (`position_id`);

--
-- AUTO_INCREMENT for table `app_user`
--
ALTER TABLE `app_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `app_user_roles`
--
ALTER TABLE `app_user_roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `ed_chat`
--
ALTER TABLE `ed_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `ed_country`
--
ALTER TABLE `ed_country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `ed_employee`
--
ALTER TABLE `ed_employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `ed_image`
--
ALTER TABLE `ed_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `ed_position`
--
ALTER TABLE `ed_position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `ed_position_has_employee`
--
ALTER TABLE `ed_position_has_employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;

