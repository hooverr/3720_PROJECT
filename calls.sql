-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 17, 2013 at 04:59 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

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
-- Table structure for table `doctor`
--

CREATE TABLE IF NOT EXISTS `doctor` (
  `Doctor_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `Phone` varchar(50) DEFAULT NULL,
  `Start_Date` date NOT NULL,
  `End_Date` date DEFAULT NULL,
  PRIMARY KEY (`Doctor_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Triggers `doctor`
--
DROP TRIGGER IF EXISTS `History  Settings Auto`;
DELIMITER //
CREATE TRIGGER `History  Settings Auto` AFTER INSERT ON `doctor`
 FOR EACH ROW BEGIN
INSERT INTO Doctor_History(Doctor_ID)
VALUES (NEW.Doctor_ID);
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `doctor_history`
--

CREATE TABLE IF NOT EXISTS `doctor_history` (
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
-- Triggers `doctor_history`
--
DROP TRIGGER IF EXISTS `Theoretical`;
DELIMITER //
CREATE TRIGGER `Theoretical` BEFORE INSERT ON `doctor_history`
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
CREATE TRIGGER `Total` BEFORE UPDATE ON `doctor_history`
 FOR EACH ROW BEGIN
SET NEW.Total_Weekday = NEW.Weekday + NEW.Theoretical_Weekday;
SET NEW.Total_Weekend = NEW.Weekend + NEW.Theoretical_Weekend;
SET NEW.Total_Holiday = NEW.Holiday + NEW.Theoretical_Holiday;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
  `Doctor_ID` int(11) NOT NULL,
  `Type` int(1) NOT NULL,
  `Date` date NOT NULL,
  KEY `Doctor_ID` (`Doctor_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
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
-- Constraints for dumped tables
--

--
-- Constraints for table `doctor_history`
--
ALTER TABLE `doctor_history`
  ADD CONSTRAINT `Doctor_History_ibfk_1` FOREIGN KEY (`Doctor_ID`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `requests`
--
ALTER TABLE `requests`
  ADD CONSTRAINT `Requests_ibfk_1` FOREIGN KEY (`Doctor_ID`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `schedule`
--
ALTER TABLE `schedule`
  ADD CONSTRAINT `Schedule_ibfk_1` FOREIGN KEY (`1`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_10` FOREIGN KEY (`9`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_11` FOREIGN KEY (`10`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_12` FOREIGN KEY (`10`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_13` FOREIGN KEY (`11`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_14` FOREIGN KEY (`11`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_15` FOREIGN KEY (`12`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_16` FOREIGN KEY (`13`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_17` FOREIGN KEY (`14`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_18` FOREIGN KEY (`15`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_19` FOREIGN KEY (`16`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_2` FOREIGN KEY (`2`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_20` FOREIGN KEY (`17`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_21` FOREIGN KEY (`18`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_22` FOREIGN KEY (`19`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_23` FOREIGN KEY (`20`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_24` FOREIGN KEY (`21`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_25` FOREIGN KEY (`22`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_26` FOREIGN KEY (`23`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_27` FOREIGN KEY (`24`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_28` FOREIGN KEY (`25`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_29` FOREIGN KEY (`26`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_3` FOREIGN KEY (`3`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_30` FOREIGN KEY (`27`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_31` FOREIGN KEY (`28`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_32` FOREIGN KEY (`29`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_33` FOREIGN KEY (`30`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_34` FOREIGN KEY (`31`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_5` FOREIGN KEY (`4`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_6` FOREIGN KEY (`5`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_7` FOREIGN KEY (`6`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_8` FOREIGN KEY (`7`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `Schedule_ibfk_9` FOREIGN KEY (`8`) REFERENCES `doctor` (`Doctor_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
