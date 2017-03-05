-- MySQL dump 10.13  Distrib 5.5.44, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: staff
-- ------------------------------------------------------
-- Server version	5.5.44-0+deb7u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `appointment_type`
--

DROP TABLE IF EXISTS `appointment_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointment_type` (
  `appointment_type` varchar(100) NOT NULL,
  PRIMARY KEY (`appointment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `department` (
  `department` varchar(200) NOT NULL,
  `code` varchar(30) NOT NULL,
  PRIMARY KEY (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `departmental_exam`
--

DROP TABLE IF EXISTS `departmental_exam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departmental_exam` (
  `staff_id` bigint(20) NOT NULL,
  `cccplus` varchar(10) NOT NULL,
  `gujarati` varchar(10) NOT NULL,
  `hindi` varchar(10) NOT NULL,
  PRIMARY KEY (`staff_id`),
  CONSTRAINT `sid_ex` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `designation_type`
--

DROP TABLE IF EXISTS `designation_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `designation_type` (
  `designation_type` varchar(50) NOT NULL,
  PRIMARY KEY (`designation_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `institute`
--

DROP TABLE IF EXISTS `institute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `institute` (
  `institute` varchar(200) NOT NULL,
  PRIMARY KEY (`institute`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `leavee`
--

DROP TABLE IF EXISTS `leavee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leavee` (
  `application_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `staff_id` bigint(20) NOT NULL,
  `nature` varchar(200) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `prefix` varchar(200) NOT NULL,
  `postfix` varchar(200) NOT NULL,
  `reason` varchar(400) NOT NULL,
  `application_date` date NOT NULL,
  PRIMARY KEY (`application_id`),
  UNIQUE KEY `sid_leave` (`staff_id`),
  CONSTRAINT `leavee_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mci`
--

DROP TABLE IF EXISTS `mci`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mci` (
  `staff_id` bigint(20) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`staff_id`,`date`),
  CONSTRAINT `mci_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `met`
--

DROP TABLE IF EXISTS `met`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `met` (
  `id` bigint(20) NOT NULL,
  `center` varchar(100) NOT NULL,
  `place` varchar(100) NOT NULL,
  `observer` varchar(100) NOT NULL,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `met_ibfk_1` FOREIGN KEY (`id`) REFERENCES `staff` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `office_staff`
--

DROP TABLE IF EXISTS `office_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `office_staff` (
  `id` bigint(20) NOT NULL,
  `fullname` varchar(300) NOT NULL,
  `office` varchar(200) NOT NULL,
  `catagory` varchar(100) NOT NULL,
  `password` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pan`
--

DROP TABLE IF EXISTS `pan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pan` (
  `staff_id` bigint(20) NOT NULL,
  `pan` varchar(20) NOT NULL,
  `attachment` mediumblob NOT NULL,
  `attachment_filename` varchar(300) NOT NULL,
  PRIMARY KEY (`staff_id`),
  CONSTRAINT `pan_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `photo`
--

DROP TABLE IF EXISTS `photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photo` (
  `id` bigint(20) NOT NULL,
  `proof_type` varchar(30) NOT NULL,
  `proof_number` varchar(30) NOT NULL,
  `proof_issued_by` varchar(100) NOT NULL,
  `photo_id` mediumblob NOT NULL,
  `photo_id_filename` varchar(300) NOT NULL,
  `photo` mediumblob NOT NULL,
  `photo_filename` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`id`) REFERENCES `staff` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `photo_id_proof_type`
--

DROP TABLE IF EXISTS `photo_id_proof_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photo_id_proof_type` (
  `photo_id_proof_type` varchar(30) NOT NULL,
  PRIMARY KEY (`photo_id_proof_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `publication`
--

DROP TABLE IF EXISTS `publication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `publication` (
  `staff_id` bigint(20) NOT NULL,
  `international` int(11) NOT NULL,
  `national` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  PRIMARY KEY (`staff_id`),
  CONSTRAINT `publication_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `qualification`
--

DROP TABLE IF EXISTS `qualification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qualification` (
  `qualification_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `staff_id` bigint(20) NOT NULL,
  `qualification` varchar(50) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `college` varchar(200) NOT NULL,
  `university` varchar(200) NOT NULL,
  `year` int(11) NOT NULL,
  `registration_number` varchar(100) NOT NULL,
  `registration_date` date DEFAULT NULL,
  `medical_council` varchar(100) NOT NULL,
  PRIMARY KEY (`qualification_id`),
  KEY `sidq` (`staff_id`),
  CONSTRAINT `qualification_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=681 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `qualification_attachment`
--

DROP TABLE IF EXISTS `qualification_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qualification_attachment` (
  `attachment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `qualification_id` bigint(20) NOT NULL,
  `type` varchar(100) NOT NULL,
  `attachment` mediumblob NOT NULL,
  `attachment_filename` mediumblob NOT NULL,
  PRIMARY KEY (`attachment_id`),
  KEY `qid_qa` (`qualification_id`),
  CONSTRAINT `qualification_attachment_ibfk_1` FOREIGN KEY (`qualification_id`) REFERENCES `qualification` (`qualification_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=765 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `qualification_degree`
--

DROP TABLE IF EXISTS `qualification_degree`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qualification_degree` (
  `qualification_degree` varchar(200) NOT NULL,
  PRIMARY KEY (`qualification_degree`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `residencial_address_proof`
--

DROP TABLE IF EXISTS `residencial_address_proof`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `residencial_address_proof` (
  `id` bigint(20) NOT NULL,
  `proof` mediumblob NOT NULL,
  `filename` varchar(300) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `residencial_address_proof_ibfk_1` FOREIGN KEY (`id`) REFERENCES `staff` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff` (
  `id` bigint(20) NOT NULL,
  `password` varchar(200) NOT NULL DEFAULT '',
  `fullname` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `residencial_address` varchar(1000) NOT NULL,
  `residencial_phone` varchar(15) NOT NULL,
  `office_phone` varchar(15) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `catagory` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff.visible`
--

DROP TABLE IF EXISTS `staff.visible`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff.visible` (
  `id` bigint(20) NOT NULL,
  `password` varchar(200) NOT NULL DEFAULT '',
  `fullname` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `residencial_address` varchar(1000) NOT NULL,
  `residencial_phone` varchar(15) NOT NULL,
  `office_phone` varchar(15) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff_movement`
--

DROP TABLE IF EXISTS `staff_movement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_movement` (
  `movement_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `staff_id` bigint(20) NOT NULL,
  `institute` varchar(300) NOT NULL,
  `department` varchar(300) NOT NULL,
  `post` varchar(300) NOT NULL,
  `from_date` date DEFAULT NULL,
  `from_time` varchar(20) NOT NULL,
  `to_date` date DEFAULT NULL,
  `to_time` varchar(20) NOT NULL,
  `type` varchar(100) NOT NULL,
  PRIMARY KEY (`movement_id`),
  KEY `sidsm` (`staff_id`),
  CONSTRAINT `staff_movement_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1266 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `staff_movement_attachment`
--

DROP TABLE IF EXISTS `staff_movement_attachment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_movement_attachment` (
  `attachment_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `movement_id` bigint(20) NOT NULL,
  `type` varchar(200) NOT NULL,
  `attachment` mediumblob NOT NULL,
  `attachment_filename` varchar(300) NOT NULL,
  PRIMARY KEY (`attachment_id`),
  KEY `movement_id` (`movement_id`),
  CONSTRAINT `staff_movement_attachment_ibfk_1` FOREIGN KEY (`movement_id`) REFERENCES `staff_movement` (`movement_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=431 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `view_data`
--

DROP TABLE IF EXISTS `view_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `view_data` (
  `id` int(11) NOT NULL,
  `info` varchar(50) NOT NULL,
  `sql` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-03-05 16:40:39
