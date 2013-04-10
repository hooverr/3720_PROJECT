-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 10, 2013 at 07:41 PM
-- Server version: 5.5.30-cll
-- PHP Version: 5.3.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `robh_3720`
--

-- --------------------------------------------------------

--
-- Table structure for table `Doctor`
--

CREATE TABLE IF NOT EXISTS `Doctor` (
  `Doctor_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Phone` varchar(50) DEFAULT NULL,
  `Start_Date` date NOT NULL,
  `End_Date` date DEFAULT NULL,
  PRIMARY KEY (`Doctor_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `Doctor`
--

INSERT INTO `Doctor` (`Doctor_ID`, `Name`, `Phone`, `Start_Date`, `End_Date`) VALUES
(2, 'Rob Hoover', '', '2013-04-01', NULL),
(3, 'steve Fowler', '', '2013-04-01', NULL),
(4, 'test test', '', '2013-04-01', NULL);

--
-- Triggers `Doctor`
--
DROP TRIGGER IF EXISTS `History  Settings Auto`;
DELIMITER //
CREATE TRIGGER `History  Settings Auto` AFTER INSERT ON `Doctor`
 FOR EACH ROW BEGIN
INSERT INTO Doctor_History(Doctor_ID)
VALUES (NEW.Doctor_ID);
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Doctor_History`
--

CREATE TABLE IF NOT EXISTS `Doctor_History` (
  `Doctor_ID` int(11) NOT NULL,
  `Weekday` int(11) NOT NULL DEFAULT '0',
  `Weekend` int(11) NOT NULL DEFAULT '0',
  `Holiday` int(11) NOT NULL DEFAULT '0',
  `Theoretical_Weekend` int(11) NOT NULL DEFAULT '0',
  `Theoretical_Weekday` int(11) NOT NULL DEFAULT '0',
  `Theoretical_Holiday` int(11) NOT NULL DEFAULT '0',
  `Total_Holiday` int(11) NOT NULL DEFAULT '0',
  `Total_Weekend` int(11) NOT NULL DEFAULT '0',
  `Total_Weekday` int(11) NOT NULL DEFAULT '0',
  `Previous_Weekday` int(11) NOT NULL DEFAULT '0',
  `Previous_Weekend` int(11) NOT NULL DEFAULT '0',
  `Previous_Holiday` int(11) NOT NULL DEFAULT '0',
  KEY `Doctor_ID` (`Doctor_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Doctor_History`
--

INSERT INTO `Doctor_History` (`Doctor_ID`, `Weekday`, `Weekend`, `Holiday`, `Theoretical_Weekend`, `Theoretical_Weekday`, `Theoretical_Holiday`, `Total_Holiday`, `Total_Weekend`, `Total_Weekday`, `Previous_Weekday`, `Previous_Weekend`, `Previous_Holiday`) VALUES
(2, 21, 8, 1, 0, 0, 0, 1, 8, 21, 0, 0, 0),
(3, 0, 0, 0, 8, 21, 1, 1, 8, 21, 0, 0, 0),
(4, 0, 0, 0, 4, 11, 1, 1, 4, 11, 0, 0, 0);

--
-- Triggers `Doctor_History`
--
DROP TRIGGER IF EXISTS `Theoretical`;
DELIMITER //
CREATE TRIGGER `Theoretical` BEFORE INSERT ON `Doctor_History`
 FOR EACH ROW BEGIN
IF (SELECT COUNT(*) FROM Doctor_History) <> 0 THEN 
	BEGIN 
	SET NEW.Theoretical_Weekday = (SELECT AVG(Weekday) FROM Doctor_History);
        SET NEW.Theoretical_Weekend = (SELECT AVG(Weekend) FROM Doctor_History);
        SET NEW.Theoretical_Holiday = (SELECT AVG(Holiday) FROM Doctor_History);
        SET NEW.Total_Weekday = (SELECT AVG(Weekday) FROM Doctor_History);
        SET NEW.Total_Weekend = (SELECT AVG(Weekend) FROM Doctor_History);
        SET NEW.Total_Holiday = (SELECT AVG(Holiday) FROM Doctor_History);
        END;
END IF;
END
//
DELIMITER ;
DROP TRIGGER IF EXISTS `Total`;
DELIMITER //
CREATE TRIGGER `Total` BEFORE UPDATE ON `Doctor_History`
 FOR EACH ROW BEGIN
SET NEW.Total_Weekday = NEW.Weekday + NEW.Theoretical_Weekday;
SET NEW.Total_Weekend = NEW.Weekend + NEW.Theoretical_Weekend;
SET NEW.Total_Holiday = NEW.Holiday + NEW.Theoretical_Holiday;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Requests`
--

CREATE TABLE IF NOT EXISTS `Requests` (
  `Doctor_ID` int(11) NOT NULL,
  `Type` int(1) NOT NULL,
  `Date` date NOT NULL,
  KEY `Doctor_ID` (`Doctor_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Schedule`
--

CREATE TABLE IF NOT EXISTS `Schedule` (
  `Month` int(2) NOT NULL,
  `Year` int(4) NOT NULL,
  `1` int(11) NOT NULL,
  `2` int(11) NOT NULL,
  `3` int(11) NOT NULL,
  `4` int(11) NOT NULL,
  `5` int(11) NOT NULL,
  `6` int(11) NOT NULL,
  `7` int(11) NOT NULL,
  `8` int(11) NOT NULL,
  `9` int(11) NOT NULL,
  `10` int(11) NOT NULL,
  `11` int(11) NOT NULL,
  `12` int(11) NOT NULL,
  `13` int(11) NOT NULL,
  `14` int(11) NOT NULL,
  `15` int(11) NOT NULL,
  `16` int(11) NOT NULL,
  `17` int(11) NOT NULL,
  `18` int(11) NOT NULL,
  `19` int(11) NOT NULL,
  `20` int(11) NOT NULL,
  `21` int(11) NOT NULL,
  `22` int(11) NOT NULL,
  `23` int(11) NOT NULL,
  `24` int(11) NOT NULL,
  `25` int(11) NOT NULL,
  `26` int(11) NOT NULL,
  `27` int(11) NOT NULL,
  `28` int(11) NOT NULL,
  `29` int(11) DEFAULT NULL,
  `30` int(11) DEFAULT NULL,
  `31` int(11) DEFAULT NULL,
  PRIMARY KEY (`Month`,`Year`),
  KEY `1` (`1`),
  KEY `2` (`2`),
  KEY `3` (`3`),
  KEY `4` (`4`),
  KEY `5` (`5`),
  KEY `6` (`6`),
  KEY `7` (`7`),
  KEY `8` (`8`),
  KEY `9` (`9`),
  KEY `10` (`10`),
  KEY `11` (`11`),
  KEY `12` (`12`),
  KEY `13` (`13`),
  KEY `14` (`14`),
  KEY `15` (`15`),
  KEY `16` (`16`),
  KEY `17` (`17`),
  KEY `18` (`18`),
  KEY `19` (`19`),
  KEY `20` (`20`),
  KEY `21` (`21`),
  KEY `22` (`22`),
  KEY `23` (`23`),
  KEY `24` (`24`),
  KEY `25` (`25`),
  KEY `26` (`26`),
  KEY `27` (`27`),
  KEY `28` (`28`),
  KEY `29` (`29`),
  KEY `30` (`30`),
  KEY `31` (`31`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Schedule`
--

INSERT INTO `Schedule` (`Month`, `Year`, `1`, `2`, `3`, `4`, `5`, `6`, `7`, `8`, `9`, `10`, `11`, `12`, `13`, `14`, `15`, `16`, `17`, `18`, `19`, `20`, `21`, `22`, `23`, `24`, `25`, `26`, `27`, `28`, `29`, `30`, `31`) VALUES
(4, 2013, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Doctor_History`
--
ALTER TABLE `Doctor_History`
  ADD CONSTRAINT `Doctor_History_ibfk_1` FOREIGN KEY (`Doctor_ID`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Requests`
--
ALTER TABLE `Requests`
  ADD CONSTRAINT `Requests_ibfk_1` FOREIGN KEY (`Doctor_ID`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Schedule`
--
ALTER TABLE `Schedule`
  ADD CONSTRAINT `Schedule_ibfk_1` FOREIGN KEY (`1`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_10` FOREIGN KEY (`9`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_11` FOREIGN KEY (`10`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_12` FOREIGN KEY (`10`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_13` FOREIGN KEY (`11`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_14` FOREIGN KEY (`11`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_15` FOREIGN KEY (`12`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_16` FOREIGN KEY (`13`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_17` FOREIGN KEY (`14`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_18` FOREIGN KEY (`15`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_19` FOREIGN KEY (`16`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_2` FOREIGN KEY (`2`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_20` FOREIGN KEY (`17`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_21` FOREIGN KEY (`18`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_22` FOREIGN KEY (`19`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_23` FOREIGN KEY (`20`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_24` FOREIGN KEY (`21`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_25` FOREIGN KEY (`22`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_26` FOREIGN KEY (`23`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_27` FOREIGN KEY (`24`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_28` FOREIGN KEY (`25`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_29` FOREIGN KEY (`26`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_3` FOREIGN KEY (`3`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_30` FOREIGN KEY (`27`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_31` FOREIGN KEY (`28`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_32` FOREIGN KEY (`29`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_33` FOREIGN KEY (`30`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_34` FOREIGN KEY (`31`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_5` FOREIGN KEY (`4`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_6` FOREIGN KEY (`5`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_7` FOREIGN KEY (`6`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_8` FOREIGN KEY (`7`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_9` FOREIGN KEY (`8`) REFERENCES `Doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
