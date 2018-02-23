-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 23, 2018 at 07:14 PM
-- Server version: 5.6.35
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `vk-test`
--
CREATE DATABASE IF NOT EXISTS `vk-test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `vk-test`;

-- --------------------------------------------------------

--
-- Table structure for table `goods`
--

CREATE TABLE `goods` (
  `ID` int(11) UNSIGNED NOT NULL,
  `Name` varchar(50) NOT NULL DEFAULT '',
  `Description` varchar(400) NOT NULL DEFAULT '',
  `Price` decimal(10,2) UNSIGNED NOT NULL,
  `PhotoURL` varchar(100) NOT NULL DEFAULT '',
  `Deleted` tinyint(1) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `goods`
--
ALTER TABLE `goods`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `Price` (`Price`),
  ADD KEY `ID` (`ID`),
  ADD KEY `Name` (`Name`),
  ADD KEY `Deleted` (`Deleted`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `goods`
--
ALTER TABLE `goods`
  MODIFY `ID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;