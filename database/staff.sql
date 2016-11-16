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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `staff`
--

LOCK TABLES `staff` WRITE;
/*!40000 ALTER TABLE `staff` DISABLE KEYS */;
INSERT INTO `staff` VALUES (1,'f97c5d29941bfb1b2fdab0874906ab82','Patel Shaileshkumar Manubhai','Biochemistry','','1966-11-20'),(2,'b8a9f715dbb64fd5c56e7783c6820a61','Verma Nimesh Shivnath','General Surgery','','1967-01-01'),(3,'35d6d33467aae9a2e3dccb4b6b027878','Saxena Puneet','Biochemistry','','1968-11-08'),(4,'a87ff679a2f3e71d9181a67b7542122c','Tailor Piyush B','Biochemistry','','1972-02-02'),(5,'e4da3b7fbbce2345d7772b0674a318d5','','Anatomy','','1969-11-08'),(6,'1679091c5a880faf6fb5e6087eb1b2dc','Sarita Patel','Biochemistry','Assistant Professor','1999-11-13'),(7,'8f14e45fceea167a5a36dedd4bea2543',NULL,'','','0000-00-00');
/*!40000 ALTER TABLE `staff` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-11-15 23:17:46
