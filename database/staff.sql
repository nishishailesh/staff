-- MySQL dump 10.13  Distrib 5.5.39, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: staff_blank
-- ------------------------------------------------------
-- Server version	5.5.39-1

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
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `department` (
  `department` varchar(30) NOT NULL,
  `code` varchar(30) NOT NULL,
  PRIMARY KEY (`department`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` VALUES ('Anatomy',''),('Anesthesiology',''),('Biochemistry',''),('Community Medicine',''),('Dematology',''),('Dentistry',''),('Emergency Medicine',''),('ENT',''),('Forensic Medicine',''),('General Surgery',''),('Immunohematology and Blood Tra',''),('Medicine',''),('Microbiology',''),('Obstetrics and Gynacology',''),('Opthalmology',''),('Orthopaedics',''),('Paediatrics',''),('Pathology',''),('Pharmacology',''),('Physiology',''),('Plastic Surgery',''),('Psychiatry',''),('Radiology',''),('Respiratory Medicine','');
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `designation_type`
--

LOCK TABLES `designation_type` WRITE;
/*!40000 ALTER TABLE `designation_type` DISABLE KEYS */;
INSERT INTO `designation_type` VALUES ('Assistant Professor'),('Associate Professor'),('Dean'),('Medical Superintendent'),('Professor'),('Tutor');
/*!40000 ALTER TABLE `designation_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photo`
--

DROP TABLE IF EXISTS `photo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photo` (
  `id` int(11) NOT NULL,
  `proof_type` varchar(30) NOT NULL,
  `proof_number` varchar(30) NOT NULL,
  `proof_issued_by` varchar(100) NOT NULL,
  `photo_id` mediumblob NOT NULL,
  `photo_id_filename` varchar(300) NOT NULL,
  `photo` mediumblob NOT NULL,
  `photo_filename` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photo`
--

LOCK TABLES `photo` WRITE;
/*!40000 ALTER TABLE `photo` DISABLE KEYS */;
/*!40000 ALTER TABLE `photo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photo_id_proof_type`
--

DROP TABLE IF EXISTS `photo_id_proof_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photo_id_proof_type` (
  `photo_id_proof_type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photo_id_proof_type`
--

LOCK TABLES `photo_id_proof_type` WRITE;
/*!40000 ALTER TABLE `photo_id_proof_type` DISABLE KEYS */;
INSERT INTO `photo_id_proof_type` VALUES ('Passport'),('PAN Card'),('Voter ID'),('Aadhar Card');
/*!40000 ALTER TABLE `photo_id_proof_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(200) NOT NULL DEFAULT '',
  `fullname` varchar(100) DEFAULT NULL,
  `department` varchar(50) NOT NULL,
  `designation` varchar(50) NOT NULL,
  `dob` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `staff_movement`
--

DROP TABLE IF EXISTS `staff_movement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `staff_movement` (
  `id` int(11) NOT NULL,
  `effective_date` date NOT NULL,
  `effective_time` varchar(15) NOT NULL,
  `current` int(11) NOT NULL,
  `order_detail` varchar(400) NOT NULL,
  `order_copy` mediumblob NOT NULL,
  `order_copy_filename` varchar(300) NOT NULL,
  `from_institute` varchar(100) NOT NULL,
  `to_institute` varchar(100) NOT NULL,
  `from_dept` varchar(100) NOT NULL,
  `to_dept` varchar(100) NOT NULL,
  `from_post` varchar(100) NOT NULL,
  `to_post` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `movement` int(11) NOT NULL,
  PRIMARY KEY (`id`,`effective_date`,`effective_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_movement`
--

LOCK TABLES `staff_movement` WRITE;
/*!40000 ALTER TABLE `staff_movement` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff_movement` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-11-19 11:33:18
