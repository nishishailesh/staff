-- MySQL dump 10.13  Distrib 5.5.39, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: staff
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
-- Dumping data for table `appointment_type`
--

LOCK TABLES `appointment_type` WRITE;
/*!40000 ALTER TABLE `appointment_type` DISABLE KEYS */;
INSERT INTO `appointment_type` VALUES ('Adhoc'),('Contract'),('GPSC'),('Other'),('Out Source'),('Promotion');
/*!40000 ALTER TABLE `appointment_type` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `department`
--

LOCK TABLES `department` WRITE;
/*!40000 ALTER TABLE `department` DISABLE KEYS */;
INSERT INTO `department` VALUES (' N/A',''),('Anatomy',''),('Anesthesiology',''),('Biochemistry',''),('Community Medicine',''),('Dematology',''),('Dentistry',''),('Emergency Medicine',''),('Forensic Medicine',''),('General Surgery',''),('Immunohematology and Blood Transfusion',''),('Medicine',''),('Microbiology',''),('Obstetrics and Gynacology',''),('Opthalmology',''),('Orthopaedics',''),('Otorhinolaryngiology',''),('Paediatrics',''),('Pathology',''),('Pharmacology',''),('Physiology',''),('Plastic Surgery',''),('Psychiatry',''),('Radiology',''),('Respiratory Medicine','');
/*!40000 ALTER TABLE `department` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `departmental_exam`
--

LOCK TABLES `departmental_exam` WRITE;
/*!40000 ALTER TABLE `departmental_exam` DISABLE KEYS */;
/*!40000 ALTER TABLE `departmental_exam` ENABLE KEYS */;
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
INSERT INTO `designation_type` VALUES ('Assistant Professor'),('Associate Professor'),('Dean'),('Junior Resident'),('Medical Superintendent'),('Professor'),('Senior Resident'),('Tutor');
/*!40000 ALTER TABLE `designation_type` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `institute`
--

LOCK TABLES `institute` WRITE;
/*!40000 ALTER TABLE `institute` DISABLE KEYS */;
INSERT INTO `institute` VALUES ('Government Medical Collge Surat'),('Medical college Vadodara');
/*!40000 ALTER TABLE `institute` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leave`
--

DROP TABLE IF EXISTS `leave`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `leave` (
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
  CONSTRAINT `leave_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leave`
--

LOCK TABLES `leave` WRITE;
/*!40000 ALTER TABLE `leave` DISABLE KEYS */;
/*!40000 ALTER TABLE `leave` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `mci`
--

LOCK TABLES `mci` WRITE;
/*!40000 ALTER TABLE `mci` DISABLE KEYS */;
/*!40000 ALTER TABLE `mci` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `met`
--

LOCK TABLES `met` WRITE;
/*!40000 ALTER TABLE `met` DISABLE KEYS */;
/*!40000 ALTER TABLE `met` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `pan`
--

LOCK TABLES `pan` WRITE;
/*!40000 ALTER TABLE `pan` DISABLE KEYS */;
/*!40000 ALTER TABLE `pan` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `publication`
--

LOCK TABLES `publication` WRITE;
/*!40000 ALTER TABLE `publication` DISABLE KEYS */;
/*!40000 ALTER TABLE `publication` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qualification`
--

LOCK TABLES `qualification` WRITE;
/*!40000 ALTER TABLE `qualification` DISABLE KEYS */;
/*!40000 ALTER TABLE `qualification` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qualification_attachment`
--

LOCK TABLES `qualification_attachment` WRITE;
/*!40000 ALTER TABLE `qualification_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `qualification_attachment` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `qualification_degree`
--

LOCK TABLES `qualification_degree` WRITE;
/*!40000 ALTER TABLE `qualification_degree` DISABLE KEYS */;
INSERT INTO `qualification_degree` VALUES ('Diploma'),('DM'),('DNB'),('MBBS'),('MCh'),('MD'),('MS');
/*!40000 ALTER TABLE `qualification_degree` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Dumping data for table `residencial_address_proof`
--

LOCK TABLES `residencial_address_proof` WRITE;
/*!40000 ALTER TABLE `residencial_address_proof` DISABLE KEYS */;
/*!40000 ALTER TABLE `residencial_address_proof` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_movement`
--

LOCK TABLES `staff_movement` WRITE;
/*!40000 ALTER TABLE `staff_movement` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff_movement` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff_movement_attachment`
--

LOCK TABLES `staff_movement_attachment` WRITE;
/*!40000 ALTER TABLE `staff_movement_attachment` DISABLE KEYS */;
/*!40000 ALTER TABLE `staff_movement_attachment` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-12-06 12:09:19
