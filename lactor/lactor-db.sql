-- MySQL dump 10.13  Distrib 5.5.25a, for Linux (x86_64)
--
-- Host: localhost    Database: lactor
-- ------------------------------------------------------
-- Server version	5.5.25a-log

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
-- Table structure for table `BreastfeedEntry`
--

DROP TABLE IF EXISTS `BreastfeedEntry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BreastfeedEntry` (
  `EntryId` int(11) NOT NULL,
  `BreastfeedingDuration` int(11) NOT NULL,
  `PumpingMethod` int(11) DEFAULT NULL,
  `InfantState` int(11) DEFAULT NULL,
  `MaternalProblems` int(11) DEFAULT NULL,
  `Latching` int(11) DEFAULT NULL,
  `Side` int(11) DEFAULT NULL,
  `PumpingAmount` int(11) DEFAULT NULL,
  PRIMARY KEY (`EntryId`),
  UNIQUE KEY `EntryId` (`EntryId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BreastfeedEntry`
--

LOCK TABLES `BreastfeedEntry` WRITE;
/*!40000 ALTER TABLE `BreastfeedEntry` DISABLE KEYS */;
INSERT INTO `BreastfeedEntry` VALUES (1,3,NULL,3,6,4,3,NULL),(2,3,2,NULL,NULL,NULL,NULL,3),(3,1,NULL,2,3,1,1,NULL),(4,2,2,NULL,NULL,NULL,NULL,3),(5,1,NULL,2,6,3,1,NULL),(6,1,NULL,3,1,2,1,NULL),(7,1,NULL,1,6,3,1,NULL),(8,1,NULL,4,6,3,2,NULL),(9,2,NULL,1,4,2,1,NULL),(10,3,NULL,4,2,2,3,NULL),(11,1,1,NULL,NULL,NULL,NULL,1),(12,1,NULL,4,6,2,1,NULL),(13,2,NULL,1,5,1,1,NULL),(14,2,1,NULL,NULL,NULL,NULL,6),(15,2,1,NULL,NULL,NULL,NULL,6),(16,3,1,NULL,NULL,NULL,NULL,7),(17,3,1,NULL,NULL,NULL,NULL,7),(18,3,1,NULL,NULL,NULL,NULL,7),(19,3,1,NULL,NULL,NULL,NULL,7),(20,3,1,NULL,NULL,NULL,NULL,7),(21,3,1,NULL,NULL,NULL,NULL,7),(22,3,1,NULL,NULL,NULL,NULL,7),(23,1,NULL,3,6,3,1,NULL),(24,3,NULL,1,2,1,3,NULL),(25,3,NULL,5,4,3,1,NULL),(26,3,1,NULL,NULL,NULL,NULL,6),(27,1,NULL,3,2,3,1,NULL),(28,2,NULL,3,6,4,1,NULL),(29,2,NULL,3,6,2,1,NULL),(30,2,NULL,4,6,3,1,NULL),(31,2,NULL,3,2,3,1,NULL),(32,2,NULL,3,2,3,1,NULL),(33,2,NULL,4,2,3,1,NULL),(34,1,NULL,3,6,2,1,NULL),(35,2,2,NULL,NULL,NULL,NULL,5),(36,2,NULL,4,6,3,1,NULL),(37,3,NULL,4,6,3,2,NULL),(38,3,NULL,3,6,4,2,NULL),(39,2,3,NULL,NULL,NULL,NULL,5),(40,1,NULL,4,6,3,1,NULL),(41,1,NULL,4,6,3,1,NULL),(42,1,NULL,4,6,3,1,NULL),(43,1,NULL,4,6,3,1,NULL),(44,1,NULL,4,6,3,1,NULL),(45,1,NULL,4,6,3,1,NULL),(46,1,NULL,4,6,3,1,NULL),(47,2,NULL,1,1,2,1,NULL),(48,1,NULL,3,6,2,1,NULL),(49,2,NULL,1,2,2,1,NULL),(50,1,3,NULL,NULL,NULL,NULL,8),(51,2,NULL,4,3,3,1,NULL),(52,1,NULL,3,6,3,1,NULL),(53,2,NULL,2,4,2,2,NULL),(54,3,NULL,4,6,3,1,NULL),(55,1,NULL,3,6,4,1,NULL),(56,3,NULL,4,6,2,2,NULL),(57,2,1,NULL,NULL,NULL,NULL,3),(58,2,2,NULL,NULL,NULL,NULL,4),(59,2,NULL,4,6,2,1,NULL),(60,3,2,NULL,NULL,NULL,NULL,6),(61,1,NULL,2,6,3,1,NULL),(62,1,NULL,3,3,1,1,NULL),(63,1,NULL,3,3,1,1,NULL),(64,1,NULL,2,2,2,1,NULL),(65,2,2,NULL,NULL,NULL,NULL,1),(66,1,1,NULL,NULL,NULL,NULL,1),(67,5,4,NULL,NULL,NULL,NULL,11),(68,2,NULL,2,1,2,1,NULL),(69,2,NULL,1,1,1,1,NULL),(70,2,NULL,5,5,2,1,NULL),(71,3,2,NULL,NULL,NULL,NULL,7);
/*!40000 ALTER TABLE `BreastfeedEntry` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `latch_trigger_2` AFTER UPDATE ON `BreastfeedEntry` FOR EACH ROW BEGIN

		DECLARE latch INTEGER;
		DECLARE infstate INTEGER;
		DECLARE lastnid INTEGER;
		DECLARE motherid INTEGER;

		SELECT D.mid INTO motherid FROM Diary D, BreastfeedEntry B WHERE D.EntryType = 1 AND D.EntryId = B.EntryId AND B.EntryId = NEW.EntryId;
		
		IF NEW.Latching != 1 AND NEW.Latching != 2 AND (OLD.Latching = 1 OR OLD.Latching = 2) THEN
			SELECT MAX(N.nid) INTO latch FROM Diary D, NotEntryId E, Notifications N WHERE D.EntryType = 1 AND D.EntryId = E.eid AND E.nid = N.nid AND E.eid = NEW.EntryId AND E.ntype = 1;
			UPDATE Notifications SET status = 8, astatus = 8 WHERE nid = latch;
		END IF;
		
		IF NEW.InfantState != 1 AND NEW.InfantState != 2 AND (OLD.InfantState = 1 OR OLD.InfantState = 2) THEN
			SELECT MAX(N.nid) INTO infstate FROM Diary D, NotEntryId E, Notifications N WHERE D.EntryType = 1 AND D.EntryId = E.eid AND E.nid = N.nid AND E.eid = NEW.EntryId AND E.ntype = 2;
			UPDATE Notifications SET status = 8, astatus = 8 WHERE nid = infstate;	
		END IF;

		IF (NEW.Latching = 1 OR NEW.Latching = 2) AND (OLD.Latching != 1 AND OLD.Latching != 2) THEN
			INSERT INTO Notifications (mid, status, ntype, NotificationIssued) VALUES (motherid, 0, 1, NOW());
			SELECT MAX(nid) INTO lastnid FROM Notifications;
			INSERT INTO NotEntryId VALUES (motherid, lastnid,  NEW.EntryId, 1, 1); 
		END IF;

		IF (NEW.InfantState = 1 OR NEW.InfantState = 2) AND (OLD.InfantState != 1 AND OLD.InfantState != 2) THEN
			INSERT INTO Notifications (mid, status, ntype, NotificationIssued) VALUES (motherid, 0, 2, NOW());
			SELECT MAX(nid) INTO lastnid FROM Notifications;
			INSERT INTO NotEntryId VALUES (motherid, lastnid,  NEW.EntryId, 1, 2); 
		END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Breastfeeding_Evaluation`
--

DROP TABLE IF EXISTS `Breastfeeding_Evaluation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Breastfeeding_Evaluation` (
  `mid` int(11) NOT NULL DEFAULT '0',
  `fillout` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `q1` tinyint(4) DEFAULT NULL,
  `q2` tinyint(4) DEFAULT NULL,
  `q3` tinyint(4) DEFAULT NULL,
  `q4` tinyint(4) DEFAULT NULL,
  `q5` tinyint(4) DEFAULT NULL,
  `q6` tinyint(4) DEFAULT NULL,
  `q7` tinyint(4) DEFAULT NULL,
  `q8` tinyint(4) DEFAULT NULL,
  `q9` tinyint(4) DEFAULT NULL,
  `q10` tinyint(4) DEFAULT NULL,
  `q11` tinyint(4) DEFAULT NULL,
  `q12` tinyint(4) DEFAULT NULL,
  `q13` tinyint(4) DEFAULT NULL,
  `q14` tinyint(4) DEFAULT NULL,
  `q15` tinyint(4) DEFAULT NULL,
  `q16` tinyint(4) DEFAULT NULL,
  `q17` tinyint(4) DEFAULT NULL,
  `q18` tinyint(4) DEFAULT NULL,
  `q19` tinyint(4) DEFAULT NULL,
  `q20` tinyint(4) DEFAULT NULL,
  `q21` tinyint(4) DEFAULT NULL,
  `q22` tinyint(4) DEFAULT NULL,
  `q23` tinyint(4) DEFAULT NULL,
  `q24` tinyint(4) DEFAULT NULL,
  `q25` tinyint(4) DEFAULT NULL,
  `q26` tinyint(4) DEFAULT NULL,
  `q27` tinyint(4) DEFAULT NULL,
  `q28` tinyint(4) DEFAULT NULL,
  `q29` tinyint(4) DEFAULT NULL,
  `q30` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`mid`,`fillout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Breastfeeding_Evaluation`
--

LOCK TABLES `Breastfeeding_Evaluation` WRITE;
/*!40000 ALTER TABLE `Breastfeeding_Evaluation` DISABLE KEYS */;
INSERT INTO `Breastfeeding_Evaluation` VALUES (110,'2012-08-09 00:26:18',-2,-1,0,1,2,-2,-1,0,1,2,-2,-1,0,1,2,-2,-1,0,1,2,-2,-1,0,1,2,-2,-1,0,1,2),(110,'2012-09-10 15:50:16',-1,-2,-2,-2,-2,-2,-1,-2,-2,-2,-1,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-1,-2,-1,-2,-2);
/*!40000 ALTER TABLE `Breastfeeding_Evaluation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Breastfeeding_Followup`
--

DROP TABLE IF EXISTS `Breastfeeding_Followup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Breastfeeding_Followup` (
  `mid` int(11) NOT NULL DEFAULT '0',
  `fillout` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `q1` text,
  `q2` text,
  `q3` text,
  `q4` text,
  `q5` text,
  `q6` text,
  `q7` text,
  `q8` text,
  `q9` text,
  PRIMARY KEY (`mid`,`fillout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Breastfeeding_Followup`
--

LOCK TABLES `Breastfeeding_Followup` WRITE;
/*!40000 ALTER TABLE `Breastfeeding_Followup` DISABLE KEYS */;
INSERT INTO `Breastfeeding_Followup` VALUES (110,'2012-08-09 01:00:42','First','3 months','over 9000!!!','yes','Herbs','Two times or less','Don\'t have enough milk','Exclusive breastfeeding (only breast milk, no other supplement with even water)','Inability to latch'),(110,'2012-08-09 01:01:52','Third','gesfasgsdgasdf','rfgassdfbvgfdsahgs','no','Others','More','dfasdgasdgsaf','No breastfeeding (exclusive formula)','fsadhgasdfasdsaf'),(110,'2012-08-29 15:44:25','First','3 months','8','no','Cereal','3-4 times','Going back to class/work','Partial breastfeeding, Medium:  50% breastfeeding','None'),(110,'2012-09-04 11:58:49','Second','6 months','3','no','Formula','3-4 times','Going back to class/work','Partial breastfeeding, Medium:  50% breastfeeding','Baby tires easily'),(110,'2012-09-10 15:41:11','Second','6 months','34','no','Formula','5-6 times','Going back to class/work','Predominant breastfeeding (Give herbs, liquid, no formula)','Sleepy baby'),(110,'2012-09-10 15:41:41','Second','6 months','23','no','Formula','3-4 times','Going back to class/work','Exclusive breastfeeding (only breast milk, no other supplement with even water)','Sleepy baby'),(110,'2012-09-10 15:43:17','Second','6 months','453','no','Formula','3-4 times','Going back to class/work','Partial breastfeeding, Low:  20-50% breastfeeding','324'),(110,'2012-09-10 15:44:21','Second','6 months','f3efeae','no','Cereal','5-6 times','Going back to class/work','Partial breastfeeding, Low:  20-50% breastfeeding','dafe'),(110,'2012-09-10 15:45:18','Second','6 months','54','no','Cereal','3-4 times','Going back to class/work','Partial breastfeeding, Low:  20-50% breastfeeding',''),(110,'2012-09-10 15:46:22','Second','6 months','3432','no','Cereal','5-6 times','Going back to class/work','Predominant breastfeeding (Give herbs, liquid, no formula)','Baby tires easily'),(110,'2013-02-13 16:24:25','Yes, all of the time','As much as I always could','As much as I ever did','Yes, most of the time','No, not at all','Yes, quite a lot','Yes, most of the time I haven\'t been able','Yes, most of the time','Yes, most of the time'),(110,'2013-02-13 16:52:47','Yes, all of the time','As much as I always could','As much as I ever did','Yes, most of the time','No, not at all','Yes, quite a lot','Yes, most of the time I haven\'t been able','Yes, most of the time','Yes, most of the time'),(110,'2013-02-13 17:13:01','Yes, all of the time','As much as I always could','As much as I ever did','Yes, most of the time','No, not at all','Yes, quite a lot','Yes, most of the time I haven\'t been able','Yes, most of the time','Yes, most of the time'),(110,'2013-02-13 17:14:19','Yes, all of the time','1','1','1','1','1','1','1','1'),(110,'2013-02-13 18:12:55','Yes, all of the time','1','1','1','1','1','1','1','1'),(110,'2013-02-13 18:14:33','Yes, all of the time','1','1','1','1','1','1','1','1'),(110,'2013-02-13 18:16:08','Yes, all of the time','1','1','1','1','1','1','1','1'),(110,'2013-02-13 18:26:43','Yes, all of the time','1','1','1','1','1','1','1','1'),(110,'2013-04-08 09:51:56','Second','6 months','5','no','','','','Exclusive breastfeeding (only breast milk, no other supplement with even water)','Inability to latch'),(110,'2013-04-08 09:52:21','Second','6 months','5','no','','','','Exclusive breastfeeding (only breast milk, no other supplement with even water)','Inability to latch'),(110,'2013-04-08 11:15:07','Second','6 months','6','no','','','','Partial breastfeeding, High:  50-80% breastfeeding','Baby tires easily');
/*!40000 ALTER TABLE `Breastfeeding_Followup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ControlledVocabulary`
--

DROP TABLE IF EXISTS `ControlledVocabulary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ControlledVocabulary` (
  `Attribute` varchar(50) NOT NULL,
  `TextValue` varchar(100) NOT NULL DEFAULT '',
  `NumValue` int(11) NOT NULL,
  PRIMARY KEY (`Attribute`,`TextValue`,`NumValue`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ControlledVocabulary`
--

LOCK TABLES `ControlledVocabulary` WRITE;
/*!40000 ALTER TABLE `ControlledVocabulary` DISABLE KEYS */;
INSERT INTO `ControlledVocabulary` VALUES ('Age','20-29',2),('Age','30-39',3),('Age','40 or more',4),('Age','<20',1),('Age','Choose One',0),('AgeFirstFeed','2-4 hours after birth',2),('AgeFirstFeed','6 hours after birth',3),('AgeFirstFeed','> 6 hours after birth',4),('AgeFirstFeed','Choose one',0),('AgeFirstFeed','Immediately after birth',1),('duration','1-2 Minutes',1),('duration','11-15 Minutes',4),('duration','3-4 Minutes',2),('duration','5-10 Minutes',3),('duration','> 15 Minutes',5),('duration','Choose one',0),('Education','Associate Degree',4),('Education','BA or BS degree',5),('Education','Choose One',0),('Education','Doctoral Degree',7),('Education','High school/GED',2),('Education','Less than high school',1),('Education','Masters Degree',6),('Education','Professional Degree',8),('Education','Some college',3),('Ethnicity','Choose One',0),('Ethnicity','Hispanic or Latino',1),('Ethnicity','NON Hispanic or Latino',2),('FirstPrimCare','After the first week of discharge',3),('FirstPrimCare','By the first week of discharge',2),('FirstPrimCare','Choose one',0),('FirstPrimCare','Within 48-72 hours',1),('FreqBreastExpr','> 5 hours',3),('FreqBreastExpr','Choose one',0),('FreqBreastExpr','Every 2-3 hours',1),('FreqBreastExpr','Every 4-5 hours',2),('gestate','34-35 weeks',2),('gestate','35-36 weeks',3),('gestate','36-37 weeks',4),('gestate','< 34 weeks',1),('gestate','> 37 weeks',5),('gestate','Choose one',0),('GestationalAge','AGA',1),('GestationalAge','Choose one',0),('GestationalAge','LGA',3),('GestationalAge','SGA',2),('HospFirstMonth','Choose one',0),('HospFirstMonth','No',2),('HospFirstMonth','Yes',1),('HouseIncome','$10,000 to < $24,999',2),('HouseIncome','$25,000 to $49,999',3),('HouseIncome','$50,000 or more',4),('HouseIncome','Choose One',0),('HouseIncome','Less than $10,000',1),('infant-state','Active alert',4),('infant-state','Choose one',0),('infant-state','Crying',5),('infant-state','Difficult to awake',1),('infant-state','Drowsy',2),('infant-state','Quiet and alert',3),('latching','Choose one',0),('latching','Latch correctly',3),('latching','Latch with nipple shield',4),('latching','Not at all',1),('latching','Slipping of the breast',2),('maternal-problems','Breast tissue is soft/no milk coming in',1),('maternal-problems','Choose one',0),('maternal-problems','Engorgement',4),('maternal-problems','Flat/inverted nipple',3),('maternal-problems','Mastitis',5),('maternal-problems','No problems',6),('maternal-problems','Sore nipple',2),('MHDP','Bleeding during pregnancy',2),('MHDP','Choose One',0),('MHDP','Gestational Diabetes',6),('MHDP','Lack or late prenatal care',4),('MHDP','Low maternal weight <50 kg',1),('MHDP','Other',7),('MHDP','PROM',5),('MHDP','Toxemia of Pregnancy',3),('MODel','Breech',3),('MODel','Choose One',0),('MODel','Section',4),('MODel','Vaginal',1),('MODel','Vaginal with assistance',2),('morb-type','Choose one',0),('morb-type','Decrease body temperature',2),('morb-type','Decrease in blood glucose',3),('morb-type','Dehydration',6),('morb-type','Difficult or trouble breathing',4),('morb-type','Infection',5),('morb-type','Jaundice',1),('morb-type','Weight Loss',7),('NeedExtraCare','Choose one',0),('NeedExtraCare','No',2),('NeedExtraCare','Yes',1),('NumberDiapers','1',1),('NumberDiapers','2',2),('NumberDiapers','3',3),('NumberDiapers','4',4),('NumberDiapers','5',5),('NumberDiapers','6',6),('NumberDiapers','> 6',7),('NumberDiapers','Choose one',0),('NumberTimes','1',1),('NumberTimes','2',2),('NumberTimes','3',3),('NumberTimes','4',4),('NumberTimes','5',5),('NumberTimes','> 5',6),('NumberTimes','Choose one',0),('Occupation','Cashier, Secretary, Laborer, Technical',3),('Occupation','Choose One',0),('Occupation','Homemaker',1),('Occupation','Other',4),('Occupation','Professional',2),('ounces','0 ounces',1),('ounces','1 ounce',2),('ounces','10 ounces',11),('ounces','11 ounces',12),('ounces','12 ounces',13),('ounces','13 ounces',14),('ounces','14 ounces',15),('ounces','15 ounces',16),('ounces','16 ounces',17),('ounces','2 ounces',3),('ounces','3 ounces',4),('ounces','4 ounces',5),('ounces','5 ounces',6),('ounces','6 ounces',7),('ounces','7 ounces',8),('ounces','8 ounces',9),('ounces','9 ounces',10),('ounces','Choose one',0),('out-s-color','Black/green',2),('out-s-color','Black/tarry meconium',1),('out-s-color','Choose one',0),('out-s-color','Yellow',3),('out-s-consistency','Choose one',0),('out-s-consistency','Formed',2),('out-s-consistency','Loose and seedy',1),('out-s-consistency','Watery',3),('out-u-color','Amber Yellow',1),('out-u-color','Choose one',0),('out-u-color','Dark Yellow',2),('out-u-saturation','Choose one',0),('out-u-saturation','Heavily wet',4),('out-u-saturation','Moderately wet',3),('out-u-saturation','Not wet at all',1),('out-u-saturation','Slightly wet',2),('Parity','Choose One',0),('Parity','First',1),('Parity','Fourth / More',4),('Parity','Second',2),('Parity','Third',3),('PBE','3-6 months',2),('PBE','7-12 months',3),('PBE','<3 months',1),('PBE','> 1 year',4),('PBE','Choose One',0),('PBE','No past breastfeeding experience',5),('POB','Choose One',0),('POB','History of pregnancy loss',1),('POB','Low birth weight infant',4),('POB','Others',5),('POB','Previous antepartum hemorrahage',3),('POB','Previous premature < 37 weeks',2),('pounds','10 pounds',10),('pounds','2 pounds',2),('pounds','3 pounds',3),('pounds','4 pounds',4),('pounds','5 pounds',5),('pounds','6 pounds',6),('pounds','7 pounds',7),('pounds','8 pounds',8),('pounds','9 pounds',9),('pounds','< 2 pounds',1),('pounds','> 10 pounds',11),('pounds','Choose one',0),('pumping-method','Choose one',0),('pumping-method','Double Electric Pump',3),('pumping-method','Hand Pump',1),('pumping-method','Manual Hand Pump',2),('pumping-method','Not applicable',4),('Race','American Indian / Alaskan Native',4),('Race','Asian',2),('Race','Black or African American',3),('Race','Choose One',0),('Race','More than one race',6),('Race','Native Hawaiian or Pacific Islander',1),('Race','White',5),('Residence','Apartment/Rental',3),('Residence','Choose One',0),('Residence','Owned',4),('Residence','Rural',1),('Residence','Urban',2),('Side','Both',3),('Side','Choose one',0),('Side','Left',1),('Side','Right',2),('sup-method','Bottle',1),('sup-method','Choose one',0),('sup-method','Cup',2),('sup-method','Spoon',4),('sup-method','Supplemental Set',3),('sup-type','Choose one',0),('sup-type','Expressed milk',1),('sup-type','Formula',3),('sup-type','Pasteurized human milk',2),('SystemFeedback','I felt very confident using the system:',9),('SystemFeedback','I found the system unnecessarily complex:',2),('SystemFeedback','I found the system very cumbersome to use:',8),('SystemFeedback','I found the various functions in this system were well integrated:',5),('SystemFeedback','I needed to learn a lot of the things before I could get going with this system:',10),('SystemFeedback','I think that I would like to use this system frequently:',1),('SystemFeedback','I think that I would need the support of a technical person to use this system:',4),('SystemFeedback','I thought the system was easy to use:',3),('SystemFeedback','I thought there was too much inconsistency in this system:',6),('SystemFeedback','I would imagine that most people would learn to use this system quickly:',7),('SystemPerception','How did it help?',1),('SystemPerception','How did the web-based monitoring help in overcoming your baby\'s health problems?',4),('SystemPerception','How much did the phone call from the lactation consultant help you in your breastfeeding experience?',6),('SystemPerception','How was your experience with this web-based monitoring?',5),('SystemPerception','Was the data entry a burden for you or overwhelming? Do you have any suggestions?',3),('SystemPerception','Was the data entry time consuming? Do you have any suggestions?',2),('SystemPerception','Would you recommend this web-based monitoring for a friend? Why and why not?',7),('TimesExtraCare','1-2 times',1),('TimesExtraCare','3-4 times',2),('TimesExtraCare','5-6 times',3),('TimesExtraCare','Choose one',0),('TimeStartBreast','6-12 hours after delivery',3),('TimeStartBreast','After the first day',4),('TimeStartBreast','Choose one',0),('TimeStartBreast','Within 1 hour after delivery',1),('TimeStartBreast','Within first 6 hours after delivery',2),('TotalAmount','1 ounce',1),('TotalAmount','10 ounces',10),('TotalAmount','2 ounces',2),('TotalAmount','3 ounces',3),('TotalAmount','4 ounces',4),('TotalAmount','5 ounces',5),('TotalAmount','6 ounces',6),('TotalAmount','7 ounces',7),('TotalAmount','8 ounces',8),('TotalAmount','9 ounces',9),('TotalAmount','> 10 ounces',11),('TotalAmount','Choose one',0),('TypeFirstDischarge','Breast milk \n	(banked)',2),('TypeFirstDischarge','Choose one',0),('TypeFirstDischarge','Formula',3),('TypeFirstDischarge','Mothers own milk',1);
/*!40000 ALTER TABLE `ControlledVocabulary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Diary`
--

DROP TABLE IF EXISTS `Diary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Diary` (
  `mid` int(11) NOT NULL DEFAULT '0',
  `Number` int(11) NOT NULL DEFAULT '0',
  `EntryDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `InputDate` datetime DEFAULT NULL,
  `EntryType` int(11) NOT NULL DEFAULT '0',
  `EntryId` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`mid`,`Number`,`EntryType`,`EntryId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Diary`
--

LOCK TABLES `Diary` WRITE;
/*!40000 ALTER TABLE `Diary` DISABLE KEYS */;
INSERT INTO `Diary` VALUES (110,1,'2011-06-15 09:00:00','2011-06-15 09:44:17',1,1),(110,2,'2011-06-15 09:00:00','2011-06-15 09:44:28',1,2),(110,123,'2013-02-25 09:15:00','2013-02-25 09:19:27',4,61),(110,4,'2011-06-15 09:00:00','2011-06-15 09:46:07',4,1),(110,5,'2011-06-15 09:00:00','2011-06-15 09:53:07',3,1),(110,6,'2011-06-15 09:00:00','2011-06-15 09:53:36',3,2),(110,7,'2011-06-15 09:00:00','2011-06-15 09:56:40',1,3),(110,8,'2011-06-15 09:00:00','2011-06-15 09:56:48',1,4),(110,122,'2013-02-07 17:00:00','2013-02-07 17:01:39',3,10),(110,10,'2011-06-15 09:00:00','2011-06-15 09:57:11',3,3),(110,11,'2011-06-15 09:00:00','2011-06-15 09:57:14',4,2),(110,12,'2011-07-06 12:00:00','2011-07-06 12:48:58',1,5),(110,13,'2011-07-06 12:00:00','2011-07-06 12:49:35',1,6),(110,14,'0000-00-00 00:00:00','2011-07-06 12:49:59',1,7),(110,15,'2013-02-04 11:05:00','2011-07-06 13:00:55',1,8),(110,16,'2013-02-04 11:15:00','2011-07-06 13:12:50',1,9),(110,17,'2011-07-06 15:45:00','2011-07-06 16:26:07',1,10),(110,121,'2013-02-05 08:55:00','2013-02-05 09:56:32',1,39),(110,120,'2013-02-04 01:25:00','2013-02-04 13:13:26',4,60),(110,119,'2013-02-04 11:00:00','2013-02-04 11:02:38',3,9),(110,118,'2013-02-04 11:25:00','2013-02-04 11:02:17',3,8),(110,22,'2011-07-06 16:40:00','2011-07-06 16:57:25',4,3),(110,23,'2011-07-06 16:55:00','2011-07-06 16:58:28',3,4),(110,117,'2013-02-04 11:15:00','2013-02-04 10:42:59',2,9),(110,25,'2011-07-13 08:50:00','2011-07-13 08:55:03',1,11),(110,26,'2011-07-13 08:55:00','2011-07-13 08:56:08',1,12),(110,116,'2013-02-04 11:00:00','2013-02-01 16:37:36',2,8),(110,28,'2011-07-13 08:55:00','2011-07-13 08:56:47',3,5),(110,29,'2011-07-13 08:55:00','2011-07-13 08:57:06',4,4),(110,30,'2011-07-18 10:15:00','2011-07-18 10:17:07',4,5),(110,31,'2012-08-09 11:00:00','2012-08-09 11:04:05',1,13),(110,32,'2012-08-09 11:00:00','2012-08-09 11:04:38',4,6),(110,33,'0000-00-00 00:00:00','2012-08-09 15:22:25',4,7),(110,34,'2013-02-04 11:00:00','2012-09-21 16:05:18',4,8),(110,35,'2013-02-04 12:30:00','2012-09-25 11:24:47',4,9),(110,36,'2012-09-25 11:40:00','2012-09-25 11:41:45',4,10),(110,37,'2012-09-25 11:40:00','2012-09-25 11:42:11',4,11),(110,38,'2012-09-25 11:40:00','2012-09-25 11:44:06',4,12),(110,39,'2012-09-25 11:40:00','2012-09-25 14:34:48',4,13),(110,40,'2012-09-25 14:35:00','2012-09-25 14:38:49',4,14),(110,41,'2012-09-25 14:35:00','2012-09-25 14:48:09',4,15),(110,42,'2012-09-25 14:45:00','2012-09-25 14:49:06',4,16),(110,43,'2012-09-25 14:45:00','2012-09-25 14:52:43',4,17),(110,44,'2012-09-25 14:45:00','2012-09-25 14:59:23',4,18),(110,45,'2012-09-25 14:55:00','2012-09-25 15:28:23',4,19),(110,46,'2012-09-26 13:30:00','2012-09-26 13:30:46',1,14),(110,47,'2012-09-26 13:30:00','2012-09-26 15:17:50',1,15),(110,48,'2012-09-26 17:30:00','2012-09-26 17:31:30',1,16),(110,49,'2012-09-26 17:30:00','2012-09-26 17:33:53',1,17),(110,50,'2012-09-26 17:30:00','2012-09-26 17:34:32',1,18),(110,51,'2012-09-26 17:30:00','2012-09-26 17:35:01',1,19),(110,52,'2012-09-26 17:30:00','2012-09-26 17:35:15',1,20),(110,53,'2012-09-26 17:30:00','2012-09-26 17:37:09',1,21),(110,54,'2012-09-26 17:30:00','2012-09-26 17:38:13',1,22),(110,55,'2012-09-27 12:45:00','2012-09-27 12:49:44',2,3),(110,56,'2012-09-28 12:50:00','2012-09-28 12:51:47',2,4),(110,57,'2012-09-28 16:30:00','2012-09-28 16:36:57',1,23),(112,1,'2012-10-02 11:00:00','2012-10-02 11:08:42',1,24),(110,58,'2012-10-08 11:20:00','2012-10-08 11:24:51',1,25),(110,59,'2012-10-08 11:25:00','2012-10-08 11:27:33',1,26),(110,60,'2012-10-08 11:25:00','2012-10-08 11:28:13',2,5),(110,61,'2012-10-08 11:25:00','2012-10-08 11:28:56',3,4),(110,62,'2012-10-08 11:25:00','2012-10-08 11:29:07',4,20),(110,63,'2012-10-08 12:00:00','2012-10-08 12:02:37',1,27),(110,64,'2012-10-08 12:00:00','2012-10-08 12:03:03',4,21),(110,65,'2012-10-08 12:05:00','2012-10-08 12:07:06',4,22),(110,66,'2012-10-08 12:05:00','2012-10-08 12:08:40',4,23),(110,67,'2012-10-08 12:05:00','2012-10-08 12:09:18',3,5),(110,68,'2012-10-08 12:10:00','2012-10-08 12:23:47',4,24),(110,69,'2012-10-08 12:20:00','2012-10-08 12:24:26',4,25),(110,70,'2012-10-08 12:25:00','2012-10-08 12:26:22',4,26),(110,71,'2012-10-08 12:25:00','2012-10-08 12:28:21',4,27),(110,72,'2012-10-08 12:30:00','2012-10-08 12:30:56',4,28),(110,73,'2012-10-08 12:30:00','2012-10-08 12:31:39',4,29),(110,74,'2012-10-08 12:40:00','2012-10-08 12:40:35',4,30),(110,75,'2012-10-08 12:40:00','2012-10-08 12:42:09',4,31),(110,76,'2012-10-08 12:55:00','2012-10-08 12:57:18',4,32),(110,77,'2012-10-08 13:00:00','2012-10-08 13:00:39',4,33),(110,78,'2012-10-08 13:00:00','2012-10-08 13:02:50',4,34),(110,79,'2012-10-08 13:00:00','2012-10-08 13:04:32',4,35),(110,80,'2012-10-08 13:05:00','2012-10-08 13:09:02',4,36),(110,81,'2013-01-29 09:00:00','2012-10-08 13:09:20',4,37),(111,1,'2013-01-31 10:35:00','2012-10-16 11:45:05',4,38),(111,2,'2012-10-16 11:45:00','2012-10-16 11:46:55',4,39),(110,82,'2012-10-16 12:15:00','2012-10-16 12:17:44',4,40),(110,83,'2012-10-16 12:15:00','2012-10-16 12:18:01',4,41),(110,84,'2012-10-16 12:15:00','2012-10-16 12:20:33',4,42),(111,3,'2012-10-16 14:10:00','2012-10-16 14:12:20',4,43),(110,85,'2012-10-16 16:30:00','2012-10-16 16:33:51',4,44),(110,86,'2012-10-16 16:30:00','2012-10-16 16:37:20',4,45),(110,87,'2012-10-16 16:40:00','2012-10-16 16:40:25',4,46),(110,88,'2012-10-25 12:45:00','2012-10-25 12:55:37',4,47),(110,89,'2012-10-25 12:55:00','2012-10-25 13:00:46',4,48),(110,90,'2012-10-25 13:00:00','2012-10-25 13:05:54',4,49),(110,91,'2012-10-25 13:05:00','2012-10-25 13:09:55',4,50),(110,92,'2012-10-25 13:05:00','2012-10-25 13:16:43',4,51),(110,93,'2012-10-25 13:15:00','2012-10-25 15:24:54',4,52),(110,94,'2012-10-25 15:20:00','2012-10-25 15:48:49',4,53),(110,95,'2012-10-25 16:15:00','2012-10-25 16:19:20',4,54),(110,96,'2012-10-29 12:35:00','2012-10-29 16:33:36',1,28),(110,97,'2012-11-02 11:30:00','2012-11-02 11:32:30',4,55),(110,98,'2012-11-02 11:10:00','2012-11-02 14:47:07',1,29),(110,99,'2012-11-02 14:45:00','2012-11-02 15:06:08',3,6),(110,100,'2012-11-02 14:45:00','2012-11-02 15:06:25',4,56),(110,101,'2012-11-06 00:30:00','2012-11-06 12:13:54',1,30),(110,102,'2012-12-04 15:45:00','2012-12-04 16:13:22',4,57),(110,103,'2012-12-13 13:55:00','2012-12-13 13:55:17',1,31),(110,104,'2012-12-13 13:55:00','2012-12-13 13:55:45',1,32),(110,105,'2012-12-13 13:55:00','2012-12-13 14:09:26',1,33),(110,106,'2013-01-24 11:55:00','2013-01-24 11:58:51',1,34),(110,107,'2013-01-24 12:20:00','2013-01-24 12:21:58',1,35),(110,108,'2013-01-24 12:20:00','2013-01-24 12:22:36',2,6),(110,109,'0000-00-00 00:00:00','2013-01-24 12:22:47',3,7),(110,110,'2013-01-24 12:20:00','2013-01-24 12:23:04',4,58),(110,111,'2013-01-24 12:20:00','2013-01-24 12:23:04',4,59),(110,112,'2013-01-28 17:30:00','2013-01-28 17:32:47',1,36),(110,113,'2013-01-29 09:00:00','2013-01-29 11:56:11',1,37),(110,114,'2013-01-31 10:35:00','2013-01-31 10:39:42',1,38),(110,115,'0000-00-00 00:00:00','2013-01-31 10:45:49',2,7),(110,124,'2013-03-22 23:30:00','2013-03-23 23:31:28',1,40),(110,125,'2013-03-22 23:30:00','2013-03-23 23:31:35',1,41),(110,126,'2013-03-22 23:30:00','2013-03-23 23:31:38',1,42),(110,127,'2013-03-22 23:30:00','2013-03-23 23:31:41',1,43),(110,128,'2013-03-22 23:30:00','2013-03-23 23:31:44',1,44),(110,129,'2013-03-22 23:30:00','2013-03-23 23:31:46',1,45),(110,130,'2013-03-22 23:30:00','2013-03-23 23:31:52',1,46),(110,131,'2013-03-22 23:30:00','2013-03-23 23:32:19',2,10),(110,132,'2013-03-22 23:30:00','2013-03-23 23:32:35',2,11),(110,133,'2013-03-22 23:30:00','2013-03-23 23:32:52',2,12),(110,134,'2013-03-22 23:30:00','2013-03-23 23:33:02',2,13),(110,135,'2013-03-22 23:30:00','2013-03-23 23:33:12',2,14),(110,136,'2013-03-22 23:30:00','2013-03-23 23:33:21',2,15),(110,137,'2013-03-22 23:30:00','2013-03-23 23:33:31',2,16),(110,138,'2013-03-22 00:00:00','2013-03-25 11:48:37',3,11),(110,139,'2013-03-22 00:00:00','2013-03-25 11:48:47',3,12),(110,140,'2013-03-22 00:00:00','2013-03-25 11:48:57',3,13),(110,141,'2013-03-22 00:00:00','2013-03-25 11:49:08',3,14),(110,142,'2013-03-22 00:00:00','2013-03-25 11:49:21',3,15),(110,143,'2013-05-07 14:30:00','2013-05-07 14:32:54',1,47),(110,144,'2013-05-07 15:30:00','2013-05-07 15:31:03',1,48),(110,145,'2013-05-21 12:45:00','2013-05-21 13:02:14',1,49),(110,146,'2013-05-21 13:00:00','2013-05-21 13:02:23',1,50),(110,147,'2013-05-21 13:00:00','2013-05-21 13:03:59',2,17),(110,148,'2013-05-21 13:00:00','2013-05-21 13:04:20',3,16),(110,149,'2013-05-21 13:00:00','2013-05-21 13:04:25',4,62),(110,150,'2013-06-03 22:15:00','2013-06-03 22:17:11',1,51),(110,151,'2013-07-15 10:40:00','2013-07-16 10:41:15',1,52),(110,152,'2013-07-15 10:40:00','2013-07-16 10:41:31',1,53),(110,153,'2013-07-15 23:15:00','2013-07-16 10:41:56',1,54),(110,154,'2013-07-16 11:15:00','2013-07-16 10:42:25',1,55),(110,155,'2013-07-16 11:15:00','2013-07-16 10:42:40',1,56),(110,156,'2013-07-16 10:55:00','2013-07-16 10:56:44',2,18),(110,157,'2013-07-16 10:55:00','2013-07-16 10:57:07',2,19),(110,158,'2013-07-16 10:55:00','2013-07-16 10:57:28',2,20),(110,159,'2013-07-15 10:55:00','2013-07-16 10:58:45',2,21),(110,160,'2013-07-15 11:10:00','2013-07-16 11:12:02',4,63),(110,161,'2013-07-15 11:10:00','2013-07-16 11:12:16',4,64),(110,162,'2013-07-15 11:10:00','2013-07-16 11:12:23',4,65),(110,163,'2013-07-16 11:10:00','2013-07-16 11:12:44',4,66),(110,164,'2013-07-16 11:10:00','2013-07-16 11:13:07',1,57),(110,165,'2013-07-15 11:10:00','2013-07-16 11:13:17',1,58),(110,166,'2013-07-16 11:10:00','2013-07-16 11:13:36',3,17),(110,167,'2013-07-16 11:10:00','2013-07-16 11:13:45',3,18),(110,168,'2013-07-15 11:10:00','2013-07-16 11:13:58',3,19),(110,169,'2013-08-30 11:15:00','2013-08-30 11:30:22',4,67),(110,170,'2013-09-17 11:00:00','2013-09-17 11:01:34',1,59),(110,171,'2013-09-17 11:00:00','2013-09-17 11:01:49',1,60),(110,172,'2013-09-17 11:00:00','2013-09-17 11:02:10',2,22),(110,173,'2013-09-17 11:00:00','2013-09-17 11:02:26',3,20),(110,174,'2013-09-17 11:00:00','2013-09-17 11:02:41',3,21),(110,175,'2013-09-17 11:00:00','2013-09-17 11:02:48',4,68),(110,176,'2013-10-10 12:55:00','2013-10-10 12:55:38',1,61),(110,177,'2013-10-30 16:15:00','2013-10-30 16:24:21',4,69),(110,178,'2013-10-31 14:10:00','2013-10-31 14:12:23',4,70),(110,179,'2013-10-31 14:10:00','2013-10-31 14:12:41',3,22),(110,180,'2013-11-08 12:15:00','2013-11-08 12:17:35',1,62),(110,181,'2013-11-08 12:15:00','2013-11-08 12:17:49',1,63),(110,182,'2013-11-08 12:15:00','2013-11-08 12:19:15',1,64),(110,183,'2013-11-08 12:15:00','2013-11-08 12:19:32',1,65),(110,184,'2013-11-08 12:15:00','2013-11-08 12:19:44',2,23),(110,185,'2013-11-08 12:15:00','2013-11-08 12:19:53',3,23),(110,186,'2013-11-08 12:15:00','2013-11-08 12:21:31',4,71),(110,187,'2014-01-09 05:00:00','2014-01-09 11:56:16',4,72),(110,188,'2014-01-08 02:30:00','2014-01-09 14:31:03',4,73),(110,189,'2014-01-16 23:35:00','2014-01-16 11:39:40',1,66),(110,190,'2014-01-16 23:40:00','2014-01-16 11:42:51',4,74),(110,191,'2014-01-16 23:40:00','2014-01-16 11:43:22',2,24),(110,192,'2014-01-16 23:40:00','2014-01-16 11:44:06',3,24),(110,193,'2014-01-16 23:40:00','2014-01-16 11:44:23',3,25),(110,194,'2014-01-16 04:55:00','2014-01-16 16:57:52',4,75),(110,195,'2014-01-16 04:55:00','2014-01-16 16:58:21',3,26),(110,196,'2014-01-16 04:55:00','2014-01-16 16:58:37',2,25),(110,197,'2014-01-16 04:55:00','2014-01-16 16:58:47',1,67),(110,198,'2014-01-16 05:00:00','2014-01-16 17:00:43',4,76),(110,199,'2014-01-16 07:05:00','2014-01-16 19:08:27',1,68),(110,200,'2014-01-16 07:05:00','2014-01-16 19:09:41',1,69),(110,201,'2014-01-16 07:10:00','2014-01-16 19:10:27',4,77),(110,202,'2014-01-15 07:10:00','2014-01-16 19:10:55',4,78),(110,203,'2014-01-16 07:15:00','2014-01-16 19:18:19',4,79),(110,204,'2014-01-16 19:35:00','2014-01-16 19:38:52',1,70),(110,205,'2014-01-16 19:35:00','2014-01-16 19:39:14',1,71),(110,206,'2014-01-16 19:35:00','2014-01-16 19:39:31',2,26),(110,207,'2014-01-16 19:35:00','2014-01-16 19:39:41',3,27),(110,208,'2014-01-16 19:35:00','2014-01-16 19:39:50',3,28),(110,209,'2014-01-16 19:35:00','2014-01-16 19:40:10',4,80);
/*!40000 ALTER TABLE `Diary` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = latin1 */ ;
/*!50003 SET character_set_results = latin1 */ ;
/*!50003 SET collation_connection  = latin1_swedish_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `latch_trigger_1` AFTER INSERT ON `Diary` FOR EACH ROW BEGIN
        IF NEW.EntryType in (1,4) THEN
                BEGIN
                DECLARE latch INTEGER;
                DECLARE infstate INTEGER;
                DECLARE lastnid INTEGER;
                DECLARE mproblem INTEGER;

                SELECT Latching INTO latch FROM Diary, BreastfeedEntry WHERE EntryType = 1 AND BreastfeedEntry.EntryId = NEW.EntryId AND BreastfeedEntry.EntryId = Diary.EntryId;
                IF latch = 1 OR latch = 2 THEN
                        INSERT INTO Notifications (mid, status, ntype, NotificationIssued) VALUES (NEW.mid, 0, 1, NOW());
                        SELECT MAX(nid) INTO lastnid FROM Notifications;
                        INSERT INTO NotEntryId VALUES (NEW.mid, lastnid,  NEW.EntryId, 1, 1);
                 END IF;
                IF latch = 4 THEN
                        INSERT INTO Notifications (mid, status, ntype, NotificationIssued) VALUES (NEW.mid, 0, 7, NOW());
                        SELECT MAX(nid) INTO lastnid FROM Notifications;
                        INSERT INTO NotEntryId VALUES (NEW.mid, lastnid,  NEW.EntryId, 1, 1);
                 END IF;

                SELECT InfantState INTO infstate FROM Diary, BreastfeedEntry WHERE EntryType = 1 AND BreastfeedEntry.EntryId = NEW.EntryId AND BreastfeedEntry.EntryId = Diary.EntryId;
                IF infstate = 1 OR infstate = 2 THEN
                         INSERT INTO Notifications (mid, status, ntype, NotificationIssued) VALUES (NEW.mid, 0, 2, NOW());
                        SELECT MAX(nid) INTO lastnid FROM Notifications;
                        INSERT INTO NotEntryId VALUES (NEW.mid, lastnid,  NEW.EntryId, 1, 2);
                 END IF;

                SELECT MaternalProblems INTO mproblem FROM Diary, BreastfeedEntry WHERE EntryType = 1 AND BreastfeedEntry.EntryId = NEW.EntryId AND BreastfeedEntry.EntryId = Diary.EntryId;
                 IF mproblem = 4 THEN
                        INSERT INTO Notifications (mid, status, ntype, NotificationIssued) VALUES (NEW.mid, 0, 4, NOW());
                        SELECT MAX(nid) INTO lastnid FROM Notifications;
                         INSERT INTO NotEntryId VALUES (NEW.mid, lastnid,  NEW.EntryId, 1, 4);
                END IF;
                SELECT Type INTO mproblem FROM Diary, MorbidityEntry WHERE EntryType = 4 AND MorbidityEntry.EntryId = NEW.EntryId AND MorbidityEntry.EntryId = Diary.EntryId;
                IF mproblem = 1 THEN
                        INSERT INTO Notifications (mid, status, ntype, NotificationIssued) VALUES (NEW.mid, 0, 3, NOW());
                        SELECT MAX(nid) INTO lastnid FROM Notifications;
                        INSERT INTO NotEntryId VALUES (NEW.mid, lastnid,  NEW.EntryId, 1, 3);
                END IF;
                IF mproblem = 2 THEN
                        INSERT INTO Notifications (mid, status, ntype, NotificationIssued) VALUES (NEW.mid, 0, 5, NOW());
                        SELECT MAX(nid) INTO lastnid FROM Notifications;
                        INSERT INTO NotEntryId VALUES (NEW.mid, lastnid,  NEW.EntryId, 1, 5);
                END IF;
                END;
        END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `Hospital`
--

DROP TABLE IF EXISTS `Hospital`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Hospital` (
  `hospital_id` int(11) NOT NULL AUTO_INCREMENT,
  `hospital_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`hospital_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Hospital`
--

LOCK TABLES `Hospital` WRITE;
/*!40000 ALTER TABLE `Hospital` DISABLE KEYS */;
INSERT INTO `Hospital` VALUES (1,'Wookie University Hospital'),(2,'Doctors R Us');
/*!40000 ALTER TABLE `Hospital` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Inbox`
--

DROP TABLE IF EXISTS `Inbox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Inbox` (
  `EntryId` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(4096) NOT NULL,
  `messageDate` datetime NOT NULL,
  `senderId` int(11) NOT NULL,
  `recipientId` int(11) NOT NULL,
  `metadata` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`EntryId`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Inbox`
--

LOCK TABLES `Inbox` WRITE;
/*!40000 ALTER TABLE `Inbox` DISABLE KEYS */;
INSERT INTO `Inbox` VALUES (2,'Test Message','2013-09-06 08:54:34',110,31,25),(3,'Test Message','2013-09-06 08:57:06',110,31,25),(4,'This is a test of the emergency broadcast system.','2013-09-06 09:01:00',110,31,25),(5,'This is a test of the emergency broadcast system.','2013-09-06 09:01:24',110,31,25),(6,'Testing, 1, 2, 3.','2013-09-06 09:01:50',110,31,25),(7,'Testing 1, 2, 3, 4.','2013-09-06 09:02:32',110,31,41),(8,'Final test','2013-09-06 11:17:23',110,31,41),(24,'Is this thing on%3F','2014-02-10 15:56:38',110,31,25),(23,'Is this thing on%3F','2014-02-10 15:54:39',110,31,25),(22,'Is this thing on%3F','2014-02-10 15:54:19',110,31,25),(21,'Testing','2013-12-05 17:04:14',110,31,25),(15,'fgfsghdnjter ul7i8dc687k y y567u5 4qy ','2013-09-24 16:39:14',110,31,41),(16,'fsyh4765uh4w5','2013-09-24 16:39:36',31,110,38),(17,'fsyh4765uh4w5','2013-09-24 16:39:40',31,110,38),(18,'fsyh4765uh4w5','2013-09-24 16:40:17',31,110,38),(19,'hyer67eb54 5 574 868 6 8  6828746','2013-09-24 16:40:33',110,31,41),(20,'!!!!!!!!!!!!!!!!!!!!!!!!!!!1','2013-09-24 16:40:51',31,110,38),(25,'Hello%3F','2014-02-10 15:57:06',110,31,25),(26,'Hello%3F','2014-02-10 15:58:52',110,31,25),(27,'What man%3F','2014-02-10 15:59:55',110,31,25),(28,'What man%3F','2014-02-10 16:00:03',110,31,25),(29,'Testing this thing','2014-02-10 16:00:51',110,31,25),(30,'Last one%3F','2014-02-10 16:07:55',110,31,25),(31,'Special Char Test ?&##=','2014-02-10 16:11:33',110,31,25),(32,'??==##','2014-02-10 16:12:22',110,31,25),(33,'\"\"\'\'\"\"','2014-02-10 16:12:47',110,31,25),(34,'I\'m a little teapot.','2014-02-10 16:25:35',110,31,25),(35,'Hello','2014-02-28 11:03:54',110,31,41);
/*!40000 ALTER TABLE `Inbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `InfantProfile`
--

DROP TABLE IF EXISTS `InfantProfile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `InfantProfile` (
  `mid` int(11) NOT NULL DEFAULT '0',
  `cid` int(11) NOT NULL DEFAULT '0',
  `InfantInitials` varchar(8) DEFAULT NULL,
  `GestationalAge` int(11) DEFAULT NULL,
  `DOB` datetime DEFAULT NULL,
  `BirthWeight` varchar(8) DEFAULT NULL,
  `DOD` datetime DEFAULT NULL,
  `DischargeWeight` varchar(8) DEFAULT NULL,
  `TypeFirstBreast` int(11) DEFAULT NULL,
  `AgeFirstFeed` int(11) DEFAULT NULL,
  `TimeStartBreast` int(11) DEFAULT NULL,
  `FreqBreastExpr` int(11) DEFAULT NULL,
  `ChildMorb` int(11) DEFAULT NULL,
  `FirstPrimCare` int(11) DEFAULT NULL,
  `NeedExtraCare` int(11) DEFAULT NULL,
  `TimesExtraCare` int(11) DEFAULT NULL,
  `HospFirstMonth` int(11) DEFAULT NULL,
  `AppropAge` int(11) DEFAULT NULL,
  PRIMARY KEY (`mid`,`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `InfantProfile`
--

LOCK TABLES `InfantProfile` WRITE;
/*!40000 ALTER TABLE `InfantProfile` DISABLE KEYS */;
INSERT INTO `InfantProfile` VALUES (110,1,'R.P.',4,'2011-07-04 00:00:00','6 6','2011-07-05 00:00:00','6 15',1,2,1,1,NULL,1,1,1,1,1),(113,1,'F.H',3,'0000-00-00 00:00:00','6 4','0000-00-00 00:00:00','6 6',1,1,1,2,NULL,1,1,2,1,3),(111,1,'G.H.',1,'2013-05-02 00:00:00','2 2','2013-05-15 00:00:00','2 4',1,3,3,2,NULL,2,1,1,1,1);
/*!40000 ALTER TABLE `InfantProfile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `LoginMonitor`
--

DROP TABLE IF EXISTS `LoginMonitor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LoginMonitor` (
  `mid` int(11) DEFAULT NULL,
  `session` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `LoginMonitor`
--

LOCK TABLES `LoginMonitor` WRITE;
/*!40000 ALTER TABLE `LoginMonitor` DISABLE KEYS */;
/*!40000 ALTER TABLE `LoginMonitor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MorbidityEntry`
--

DROP TABLE IF EXISTS `MorbidityEntry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MorbidityEntry` (
  `EntryId` int(11) NOT NULL DEFAULT '0',
  `Type` int(11) DEFAULT NULL,
  PRIMARY KEY (`EntryId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MorbidityEntry`
--

LOCK TABLES `MorbidityEntry` WRITE;
/*!40000 ALTER TABLE `MorbidityEntry` DISABLE KEYS */;
INSERT INTO `MorbidityEntry` VALUES (1,6),(2,3),(3,5),(4,6),(5,5),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,6),(21,5),(22,2),(23,4),(24,5),(25,4),(26,2),(27,5),(28,4),(29,3),(30,5),(31,4),(32,5),(33,2),(34,3),(35,3),(36,2),(37,4),(38,1),(39,1),(40,1),(41,1),(42,1),(43,1),(44,1),(45,1),(46,1),(47,1),(48,1),(49,1),(50,1),(51,1),(52,1),(53,1),(54,1),(55,7),(56,7),(57,1),(58,5),(59,5),(60,5),(61,7),(62,2),(63,5),(64,3),(65,2),(66,2),(67,0),(68,6),(69,6),(70,2),(71,6),(72,3),(73,6),(74,5),(75,3),(76,5),(77,3),(78,1),(79,7),(80,4);
/*!40000 ALTER TABLE `MorbidityEntry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MotherInfo`
--

DROP TABLE IF EXISTS `MotherInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MotherInfo` (
  `mid` int(11) NOT NULL DEFAULT '0',
  `Name` varchar(50) DEFAULT NULL,
  `Address` varchar(50) DEFAULT NULL,
  `Age` int(11) DEFAULT NULL,
  `Ethnicity` int(11) DEFAULT NULL,
  `Race` int(11) DEFAULT NULL,
  `Education` int(11) DEFAULT NULL,
  `HouseIncome` int(11) DEFAULT NULL,
  `Occupation` int(11) DEFAULT NULL,
  `Residence` int(11) DEFAULT NULL,
  `Parity` int(11) DEFAULT NULL,
  `POH` int(11) DEFAULT NULL,
  `MHDP` int(11) DEFAULT NULL,
  `MethodOfDelivery` int(11) DEFAULT NULL,
  `PBE` int(11) DEFAULT NULL,
  `Phone` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MotherInfo`
--

LOCK TABLES `MotherInfo` WRITE;
/*!40000 ALTER TABLE `MotherInfo` DISABLE KEYS */;
INSERT INTO `MotherInfo` VALUES (111,'Bob R Anne','None',1,2,5,3,2,2,2,1,22111,2111111,1,5,'555-555-5555'),(112,'Mother Goose','Not Specified',1,1,1,1,1,1,1,1,11111,1111111,1,1,'555-555-5555'),(113,'Mother Hen','Not Specified',1,1,1,1,1,1,1,1,11111,1111111,1,1,'555-555-5555'),(114,'Julia Goulia','Not Specified',1,1,1,1,1,1,1,1,21211,2221111,1,1,'555-555-5555'),(129,'Bork Bork',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `MotherInfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Mothers`
--

DROP TABLE IF EXISTS `Mothers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Mothers` (
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `loginstep` int(11) DEFAULT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `actions_required` int(10) unsigned DEFAULT NULL,
  `study_id` int(11) DEFAULT '0',
  `actions_completed` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`mid`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=130 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Mothers`
--

LOCK TABLES `Mothers` WRITE;
/*!40000 ALTER TABLE `Mothers` DISABLE KEYS */;
INSERT INTO `Mothers` VALUES ('tmcgrew@purdue.edu','0163a239c5026195c16ca08f9e4fdcec33d4fb31c48b2ef1284a449dc3541a2d',110,0,NULL,0,0,8192),('blahblahbobo@mailinator.com','8f5dabb83317aec2dd5291447c94e2cc',111,5,NULL,0,0,0),('mother1-dru@mailinator.com','8f5dabb83317aec2dd5291447c94e2cc',112,5,2,0,0,0),('mother2-dru@mailinator.com','8f5dabb83317aec2dd5291447c94e2cc',113,5,2,0,0,0),('mother1-wuh@mailinator.com','8f5dabb83317aec2dd5291447c94e2cc',114,5,1,0,0,0),('mother2-wuh@mailinator.com','8f5dabb83317aec2dd5291447c94e2cc',115,5,1,0,0,0),('mother3-wuh@mailinator.com','8f5dabb83317aec2dd5291447c94e2cc',116,1,0,0,0,0),('mother3-dru@mailinator.com','8f5dabb83317aec2dd5291447c94e2cc',117,1,0,0,0,0),('mother4-dru@mailinator.com','8f5dabb83317aec2dd5291447c94e2cc',120,1,2,0,0,0),('mother4-wuh@mailinator.com','8f5dabb83317aec2dd5291447c94e2cc',121,1,1,0,0,0),('mother5-dru@mailinator.com','9307701e9acece19885ee69dfa0171ca0d74283dda9e3f1cfe5c430e69110caa',122,3,2,0,0,0),('mother5-wuh@mailinator.com','de74918411c977d39d5732d5db9e73033893c36f7f4c535c2bc886b21f5fc083',123,1,1,0,0,0),('mother6-wuh@mailinator.com','32f2ccbda426f0982058f6f972e6ba9481a59d79b29d7a831b7d7070f49069f0',124,1,1,0,0,0),('mother6-dru@mailinator.com','8e71ab5088cf76386f16a0a0fb1094c4ebc834a59da93ba1b3532eabe5431bdc',125,1,2,0,0,0),('mother7-dru@mailinator.com','fb8bef1be8ce8b08ca93df4b01e14d1172970398fad3c8802b95c4567dbb6199',126,1,2,0,0,0),('mother7-wuh@mailinator.com','95502d694e0aa25ee2d6839043cf46bacf940e96d77ead88a9dde0187c44ec26',127,1,1,0,0,0),('test@blah.com','0163a239c5026195c16ca08f9e4fdcec33d4fb31c48b2ef1284a449dc3541a2d',128,0,2,0,0,0),('borkbork@mailinator.com','bea53b8421e282fe59409148ab1a5c0558b25cfd288a3261027a5938a5388035',129,0,2,4112,0,0);
/*!40000 ALTER TABLE `Mothers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Mothers_Scientists`
--

DROP TABLE IF EXISTS `Mothers_Scientists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Mothers_Scientists` (
  `mid` int(11) DEFAULT NULL,
  `sid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Mothers_Scientists`
--

LOCK TABLES `Mothers_Scientists` WRITE;
/*!40000 ALTER TABLE `Mothers_Scientists` DISABLE KEYS */;
INSERT INTO `Mothers_Scientists` VALUES (111,31),(117,31),(110,31),(116,31),(118,31),(110,31),(116,31),(112,33),(113,33),(121,31),(120,31),(119,31),(114,36),(115,36),(122,31),(123,31),(124,31),(125,31),(126,31),(127,31),(0,31),(0,31),(0,31),(0,31),(0,31),(0,31),(128,31),(129,31),(129,31),(129,31);
/*!40000 ALTER TABLE `Mothers_Scientists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NotEntryId`
--

DROP TABLE IF EXISTS `NotEntryId`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NotEntryId` (
  `mid` int(11) DEFAULT NULL,
  `nid` int(11) DEFAULT NULL,
  `eid` int(11) DEFAULT NULL,
  `etype` int(11) DEFAULT NULL,
  `ntype` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `NotEntryId`
--

LOCK TABLES `NotEntryId` WRITE;
/*!40000 ALTER TABLE `NotEntryId` DISABLE KEYS */;
INSERT INTO `NotEntryId` VALUES (110,1,2,1,1),(110,2,2,1,2),(110,3,3,1,1),(110,4,3,1,2),(110,5,4,1,1),(110,6,4,1,2),(110,7,5,1,1),(110,8,5,1,2),(110,9,6,1,1),(110,10,6,1,2),(110,11,7,1,1),(110,12,7,1,2),(110,13,8,1,1),(110,14,8,1,2),(110,15,9,1,1),(110,16,9,1,2),(110,17,10,1,1),(110,18,10,1,2),(110,1,3,1,1),(110,2,3,1,2),(110,3,5,1,2),(110,4,6,1,1),(110,5,6,1,3),(110,6,7,1,2),(110,7,8,1,1),(110,8,8,1,2),(110,9,8,1,3),(110,10,9,1,1),(110,11,9,1,2),(110,12,10,1,1),(110,13,3,1,1),(110,14,3,1,2),(110,15,12,1,1),(110,16,5,1,2),(110,3,15,1,3),(110,4,16,1,3),(110,5,17,1,3),(110,6,18,1,3),(110,7,19,1,3),(110,8,14,1,3),(110,9,15,1,3),(110,10,16,1,3),(110,11,17,1,3),(110,12,18,1,3),(110,13,19,1,3),(112,13,24,1,1),(112,13,24,1,2),(110,14,25,1,4),(110,15,24,1,1),(110,16,24,1,2),(110,17,25,1,4),(111,17,38,1,3),(111,17,39,1,3),(110,18,40,1,3),(110,19,41,1,3),(110,20,42,1,3),(111,20,43,1,3),(110,21,44,1,3),(110,22,45,1,3),(110,23,46,1,3),(110,24,47,1,3),(110,25,48,1,3),(110,26,49,1,3),(110,27,50,1,3),(110,28,51,1,3),(110,29,52,1,3),(110,30,53,1,3),(110,31,54,1,3),(110,37,28,1,1),(110,44,29,1,1),(110,47,57,1,3),(110,48,33,1,5),(110,50,34,1,1),(110,52,36,1,5),(110,53,38,1,3),(110,60,39,1,3),(110,63,40,1,3),(110,64,41,1,3),(110,65,42,1,3),(110,66,43,1,3),(110,67,44,1,3),(110,68,45,1,3),(110,69,46,1,3),(110,86,47,1,1),(110,87,47,1,2),(110,88,47,1,3),(110,89,48,1,1),(110,90,48,1,3),(110,92,49,1,1),(110,93,49,1,2),(110,94,49,1,3),(110,95,50,1,3),(110,96,62,1,5),(110,98,51,1,3),(110,100,52,1,3),(110,101,53,1,1),(110,102,53,1,2),(110,103,53,1,4),(110,104,53,1,3),(110,105,54,1,3),(110,106,55,1,1),(110,107,56,1,1),(110,108,65,1,5),(110,109,66,1,5),(110,110,57,1,3),(110,112,59,1,1),(110,114,61,1,2),(110,116,70,1,5),(110,117,62,1,1),(110,118,62,1,5),(110,119,63,1,1),(110,120,64,1,1),(110,121,64,1,2),(110,122,65,1,5),(110,124,66,1,5),(110,125,68,1,1),(110,126,68,1,2),(110,127,69,1,1),(110,128,69,1,2),(110,129,78,1,3),(110,130,70,1,1),(110,131,70,1,5);
/*!40000 ALTER TABLE `NotEntryId` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NotificationDescription`
--

DROP TABLE IF EXISTS `NotificationDescription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NotificationDescription` (
  `ntype` int(11) DEFAULT NULL,
  `Problem` text,
  `Description` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `NotificationDescription`
--

LOCK TABLES `NotificationDescription` WRITE;
/*!40000 ALTER TABLE `NotificationDescription` DISABLE KEYS */;
INSERT INTO `NotificationDescription` VALUES (1,'Latching',''),(2,'Baby Sleepy',''),(3,'Jaundice',''),(4,'Engorgement',''),(5,'Sore Nipple',''),(6,'Insufficient Breastfeeding Entries',''),(7,'Nipple Shield',''),(8,'Sufficient Breastfeeding Entries',''),(9,'Sufficient Output Entries',''),(10,'Sufficient Notification Reading','');
/*!40000 ALTER TABLE `NotificationDescription` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Notifications`
--

DROP TABLE IF EXISTS `Notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Notifications` (
  `nid` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `ntype` int(11) DEFAULT NULL,
  `NotificationIssued` datetime DEFAULT NULL,
  `astatus` int(11) DEFAULT '1',
  PRIMARY KEY (`mid`,`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Notifications`
--

LOCK TABLES `Notifications` WRITE;
/*!40000 ALTER TABLE `Notifications` DISABLE KEYS */;
INSERT INTO `Notifications` VALUES (1,110,2,1,'2011-06-15 09:56:40',1),(2,110,2,2,'2011-06-15 09:56:40',1),(3,110,2,3,'2012-09-25 14:48:09',1),(4,110,2,3,'2012-09-25 14:49:06',1),(5,110,2,3,'2012-09-25 14:52:43',1),(6,110,2,3,'2012-09-25 14:59:23',1),(7,110,2,3,'2012-09-25 15:28:23',1),(8,110,2,3,'2012-09-26 13:30:46',1),(9,110,2,3,'2012-09-26 15:17:50',1),(10,110,2,3,'2012-09-26 17:31:30',1),(11,110,2,3,'2012-09-26 17:33:53',1),(12,110,2,3,'2012-09-26 17:34:32',1),(13,110,8,3,'2012-09-26 17:35:01',8),(1,112,2,1,'2012-10-02 11:08:42',1),(2,112,2,2,'2012-10-02 11:08:42',1),(14,110,8,4,'2012-10-08 11:24:51',8),(15,110,2,1,'2012-10-08 12:23:47',1),(16,110,2,2,'2012-10-08 12:23:47',1),(17,110,2,4,'2012-10-08 12:24:26',1),(1,111,1,3,'2012-10-16 11:45:05',1),(2,111,1,3,'2012-10-16 11:46:55',1),(18,110,2,3,'2012-10-16 12:17:44',1),(19,110,2,3,'2012-10-16 12:18:01',1),(20,110,2,3,'2012-10-16 12:20:33',1),(3,111,1,3,'2012-10-16 14:12:20',1),(21,110,2,3,'2012-10-16 16:33:51',1),(22,110,2,3,'2012-10-16 16:37:20',1),(23,110,2,3,'2012-10-16 16:40:25',1),(24,110,2,3,'2012-10-25 12:55:37',1),(25,110,2,3,'2012-10-25 13:00:46',1),(26,110,2,3,'2012-10-25 13:05:54',1),(27,110,2,3,'2012-10-25 13:09:55',1),(28,110,2,3,'2012-10-25 13:16:43',1),(29,110,2,3,'2012-10-25 15:24:54',1),(30,110,2,3,'2012-10-25 15:48:49',1),(31,110,2,3,'2012-10-25 16:19:20',1),(60,110,2,3,'2013-02-05 09:56:32',1),(59,110,2,6,'2013-01-31 00:00:00',1),(58,110,2,6,'2011-07-06 00:00:00',1),(57,110,2,6,'0000-00-00 00:00:00',1),(56,110,2,6,'2013-01-29 00:00:00',1),(55,110,2,6,'2011-06-15 00:00:00',1),(54,110,2,6,'2013-01-28 00:00:00',1),(53,110,2,3,'2013-01-31 10:39:42',1),(52,110,2,5,'2013-01-28 17:32:47',1),(51,110,2,6,'2013-01-24 00:00:00',1),(50,110,2,1,'2013-01-25 13:38:30',1),(49,110,2,6,'2012-12-13 00:00:00',1),(48,110,2,5,'2012-12-13 14:09:26',1),(47,110,2,3,'2012-12-04 16:13:22',1),(46,110,2,6,'2012-11-06 00:00:00',1),(45,110,2,6,'2012-11-02 00:00:00',1),(44,110,2,1,'2012-11-02 14:47:07',1),(43,110,2,6,'2012-10-29 00:00:00',1),(37,110,2,7,'2012-10-29 16:33:36',1),(42,110,2,6,'2012-10-08 00:00:00',1),(3,112,1,6,'2012-10-02 00:00:00',1),(41,110,2,6,'2012-09-28 00:00:00',1),(40,110,2,6,'2012-09-27 00:00:00',1),(39,110,2,6,'2012-08-09 00:00:00',1),(38,110,2,6,'2011-07-13 00:00:00',1),(61,110,2,6,'2013-02-04 00:00:00',1),(62,110,2,6,'2013-02-05 00:00:00',1),(84,110,2,9,'2013-03-22 00:00:00',1),(83,110,2,9,'2013-02-04 00:00:00',1),(82,110,2,9,'2012-10-08 00:00:00',1),(81,110,2,9,'2011-06-15 00:00:00',1),(79,110,2,8,'2012-09-26 00:00:00',1),(80,110,2,8,'2013-03-22 00:00:00',1),(77,110,2,8,'2012-09-26 00:00:00',1),(70,110,2,8,'2012-09-26 00:00:00',1),(71,110,2,8,'2012-09-26 00:00:00',1),(72,110,2,8,'2012-09-26 00:00:00',1),(73,110,2,8,'2012-09-26 00:00:00',1),(74,110,2,8,'2012-09-26 00:00:00',1),(75,110,2,8,'2012-09-26 00:00:00',1),(76,110,2,8,'2012-09-26 00:00:00',1),(85,110,2,10,'2013-03-22 00:00:00',1),(86,110,2,1,'2013-05-07 14:32:54',1),(87,110,2,2,'2013-05-07 14:32:54',1),(88,110,2,3,'2013-05-07 14:32:54',1),(89,110,2,1,'2013-05-07 15:31:03',1),(90,110,2,3,'2013-05-07 15:31:03',1),(91,110,2,6,'2013-05-07 00:00:00',1),(92,110,2,1,'2013-05-21 13:02:14',1),(93,110,2,2,'2013-05-21 13:02:14',1),(94,110,2,3,'2013-05-21 13:02:14',1),(95,110,2,3,'2013-05-21 13:02:23',1),(96,110,2,5,'2013-05-21 13:04:25',1),(97,110,2,6,'2013-05-21 00:00:00',1),(98,110,2,3,'2013-06-03 22:17:11',1),(99,110,2,6,'2013-06-03 00:00:00',1),(100,110,2,3,'2013-07-16 10:41:15',1),(101,110,2,1,'2013-07-16 10:41:31',1),(102,110,1,2,'2013-07-16 10:41:31',1),(103,110,1,4,'2013-07-16 10:41:31',1),(104,110,1,3,'2013-07-16 10:41:31',1),(105,110,1,3,'2013-07-16 10:41:56',1),(106,110,1,7,'2013-07-16 10:42:25',1),(107,110,1,1,'2013-07-16 10:42:40',1),(108,110,1,5,'2013-07-16 11:12:23',1),(109,110,1,5,'2013-07-16 11:12:44',1),(110,110,1,3,'2013-07-16 11:13:07',1),(111,110,2,6,'2013-07-15 00:00:00',1),(112,110,1,1,'2013-09-17 11:01:34',1),(113,110,1,6,'2013-09-17 00:00:00',1),(114,110,1,2,'2013-10-10 12:55:38',1),(115,110,1,6,'2013-10-10 00:00:00',1),(116,110,1,5,'2013-10-31 14:12:23',1),(117,110,2,1,'2013-11-08 12:17:35',1),(118,110,1,5,'2013-11-08 12:17:35',1),(119,110,2,1,'2013-11-08 12:17:49',1),(120,110,2,1,'2013-11-08 12:19:15',1),(121,110,2,2,'2013-11-08 12:19:15',1),(122,110,2,5,'2013-11-08 12:19:32',1),(123,110,2,6,'2013-11-08 00:00:00',1),(124,110,1,5,'2014-01-16 11:39:40',1),(125,110,1,1,'2014-01-16 19:08:27',1),(126,110,1,2,'2014-01-16 19:08:27',1),(127,110,2,1,'2014-01-16 19:09:41',1),(128,110,1,2,'2014-01-16 19:09:41',1),(129,110,2,3,'2014-01-16 19:10:55',1),(130,110,1,1,'2014-01-16 19:38:52',1),(131,110,1,5,'2014-01-16 19:38:52',1);
/*!40000 ALTER TABLE `Notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NotificationsInfo`
--

DROP TABLE IF EXISTS `NotificationsInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NotificationsInfo` (
  `ntype` int(11) NOT NULL DEFAULT '0',
  `description` text,
  PRIMARY KEY (`ntype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `NotificationsInfo`
--

LOCK TABLES `NotificationsInfo` WRITE;
/*!40000 ALTER TABLE `NotificationsInfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `NotificationsInfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `OutputEntry`
--

DROP TABLE IF EXISTS `OutputEntry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OutputEntry` (
  `EntryId` int(11) NOT NULL DEFAULT '0',
  `UrineColor` int(11) DEFAULT NULL,
  `UrineSaturation` int(11) DEFAULT NULL,
  `StoolColor` int(11) DEFAULT NULL,
  `StoolConsistency` int(11) DEFAULT NULL,
  `NumberDiapers` int(11) DEFAULT NULL,
  PRIMARY KEY (`EntryId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `OutputEntry`
--

LOCK TABLES `OutputEntry` WRITE;
/*!40000 ALTER TABLE `OutputEntry` DISABLE KEYS */;
INSERT INTO `OutputEntry` VALUES (1,0,0,2,2,2),(2,1,1,0,0,3),(3,1,1,2,2,2),(4,1,1,0,0,4),(5,2,2,0,0,2),(6,1,1,0,0,2),(7,1,1,0,0,3),(8,1,1,0,0,2),(9,0,0,3,1,4),(10,1,3,0,0,3),(11,1,1,0,0,1),(12,1,2,0,0,1),(13,0,0,1,2,1),(14,0,0,2,3,1),(15,0,0,2,1,2),(16,2,2,0,0,3),(17,2,1,0,0,2),(18,1,2,0,0,1),(19,0,0,1,3,2),(20,1,1,0,0,4),(21,0,0,2,2,1),(22,1,1,0,0,2),(23,1,1,0,0,2),(24,1,4,0,0,1),(25,0,0,2,2,1),(26,1,2,0,0,5),(27,1,3,0,0,3),(28,0,0,1,2,3);
/*!40000 ALTER TABLE `OutputEntry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Postnatal_Depression`
--

DROP TABLE IF EXISTS `Postnatal_Depression`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Postnatal_Depression` (
  `mid` int(11) NOT NULL DEFAULT '0',
  `fillout` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `q1` tinyint(4) DEFAULT NULL,
  `q2` tinyint(4) DEFAULT NULL,
  `q3` tinyint(4) DEFAULT NULL,
  `q4` tinyint(4) DEFAULT NULL,
  `q5` tinyint(4) DEFAULT NULL,
  `q6` tinyint(4) DEFAULT NULL,
  `q7` tinyint(4) DEFAULT NULL,
  `q8` tinyint(4) DEFAULT NULL,
  `q9` tinyint(4) DEFAULT NULL,
  `q10` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`mid`,`fillout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Postnatal_Depression`
--

LOCK TABLES `Postnatal_Depression` WRITE;
/*!40000 ALTER TABLE `Postnatal_Depression` DISABLE KEYS */;
INSERT INTO `Postnatal_Depression` VALUES (110,'2013-03-05 14:01:34',2,2,2,3,4,2,3,2,1,1),(110,'2013-02-13 18:30:39',1,2,3,4,1,2,3,4,1,2),(110,'2013-02-13 18:31:13',4,3,2,1,4,3,2,1,4,3),(110,'2013-02-13 18:31:34',2,2,2,2,2,2,2,2,2,2),(110,'2013-02-13 18:32:01',3,3,3,3,3,3,3,3,3,3),(110,'2013-02-13 18:32:23',4,4,4,4,4,4,4,4,4,4);
/*!40000 ALTER TABLE `Postnatal_Depression` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ScienceDownloads`
--

DROP TABLE IF EXISTS `ScienceDownloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ScienceDownloads` (
  `sid` int(11) DEFAULT NULL,
  `DownloadTime` datetime DEFAULT NULL,
  `Mothers` text,
  `BeginDate` datetime DEFAULT NULL,
  `EndDate` datetime DEFAULT NULL,
  `Screen` int(11) DEFAULT NULL,
  `Parameters` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ScienceDownloads`
--

LOCK TABLES `ScienceDownloads` WRITE;
/*!40000 ALTER TABLE `ScienceDownloads` DISABLE KEYS */;
INSERT INTO `ScienceDownloads` VALUES (31,'2011-06-20 09:33:08','','2011-05-02 00:00:00','2011-06-20 00:00:00',1,'0000'),(31,'2011-06-20 10:22:47','','2011-06-20 00:00:00','2011-06-20 00:00:00',1,'1111'),(31,'2011-06-20 10:23:56','','2011-06-01 00:00:00','2011-06-20 00:00:00',1,'1111'),(31,'2011-06-20 10:25:58','','2011-06-01 00:00:00','2011-06-20 00:00:00',1,'1111'),(31,'2011-06-20 10:27:20','','2011-06-01 00:00:00','2011-06-20 00:00:00',1,'1111'),(31,'2011-06-20 10:30:04','','2011-06-20 00:00:00','2011-06-20 00:00:00',1,'1111'),(31,'2011-06-20 11:13:17','','2011-06-01 00:00:00','2011-06-20 00:00:00',2,''),(31,'2011-06-21 10:11:56','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1111'),(31,'2011-06-21 10:14:51','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1111'),(31,'2011-06-21 10:16:40','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1000'),(31,'2011-06-21 10:19:50','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1000'),(31,'2011-06-21 10:20:36','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1000'),(31,'2011-06-21 10:23:23','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1000'),(31,'2011-06-21 10:24:33','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1000'),(31,'2011-06-21 10:25:23','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1000'),(31,'2011-06-21 10:27:02','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1000'),(31,'2011-06-21 10:29:48','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1000'),(31,'2011-06-21 10:30:00','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1111'),(31,'2011-06-21 10:33:05','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1111'),(31,'2011-06-21 10:33:56','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1111'),(31,'2011-06-21 10:35:36','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1111'),(31,'2011-06-21 10:36:41','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1111'),(31,'2011-06-21 10:38:29','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1111'),(31,'2011-06-21 10:42:14','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1111'),(31,'2011-06-21 10:44:37','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1111'),(31,'2011-06-21 10:45:15','','2011-06-01 00:00:00','2011-06-21 00:00:00',1,'1111'),(31,'2013-05-07 03:06:00','','2013-01-01 00:00:00','2013-05-07 00:00:00',1,'0000');
/*!40000 ALTER TABLE `ScienceDownloads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ScienceView`
--

DROP TABLE IF EXISTS `ScienceView`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ScienceView` (
  `sid` int(11) DEFAULT NULL,
  `ViewTime` datetime DEFAULT NULL,
  `mid` int(11) DEFAULT NULL,
  `BeginDate` datetime DEFAULT NULL,
  `EndDate` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ScienceView`
--

LOCK TABLES `ScienceView` WRITE;
/*!40000 ALTER TABLE `ScienceView` DISABLE KEYS */;
INSERT INTO `ScienceView` VALUES (116,'2013-02-25 09:18:55',110,'2013-02-18 00:00:00','2013-02-25 23:59:59'),(116,'2013-02-25 09:19:30',110,'2013-02-18 00:00:00','2013-02-25 23:59:59');
/*!40000 ALTER TABLE `ScienceView` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ScienceWrite`
--

DROP TABLE IF EXISTS `ScienceWrite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ScienceWrite` (
  `sid` int(11) DEFAULT NULL,
  `Time` datetime DEFAULT NULL,
  `mid` int(11) DEFAULT NULL,
  `WriteType` int(11) DEFAULT NULL,
  `EntryType` int(11) DEFAULT NULL,
  `EntryId` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ScienceWrite`
--

LOCK TABLES `ScienceWrite` WRITE;
/*!40000 ALTER TABLE `ScienceWrite` DISABLE KEYS */;
INSERT INTO `ScienceWrite` VALUES (116,'2013-02-25 09:19:27',110,1,4,61);
/*!40000 ALTER TABLE `ScienceWrite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Scientists`
--

DROP TABLE IF EXISTS `Scientists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Scientists` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(60) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `loginstep` int(11) DEFAULT NULL,
  `admin` int(1) DEFAULT '0',
  `hospital_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Scientists`
--

LOCK TABLES `Scientists` WRITE;
/*!40000 ALTER TABLE `Scientists` DISABLE KEYS */;
INSERT INTO `Scientists` VALUES (31,'tmcgrew@purdue.edu','73eeb42549b8b8d26f05099e650ebe84c9adc5619f57b91de28f58b7084d4924',0,2,NULL,'Thomas McGrew'),(33,'scientist-dru@mailinator.com','8f5dabb83317aec2dd5291447c94e2cc',0,0,2,NULL),(34,'admin-dru@mailinator.com','8f5dabb83317aec2dd5291447c94e2cc',0,1,2,NULL),(35,'admin-wuh@mailinator.com','ff665ea8f6b83761284d3bf13d48abae',0,1,1,NULL),(36,'scientist-wuh@mailinator.com','8f5dabb83317aec2dd5291447c94e2cc',0,0,1,NULL),(40,'borkbork@mailinator.com','3ce8bd8f3fe79e163abe1498c7ed078b82b8c04258109122f69475cfa488975e',1,0,2,'Bork Bork'),(41,'goughes@purdue.edu','64723ff703ec4318e8687aec40a155670d92a64a194be7804e9b0df0d9e76425',0,2,2,'Erik Gough'),(42,'zhan1371@purdue.edu','ab3c8791ebe42e9651051e625a8782e5',0,2,2,'Xiaoyuan Zhang');
/*!40000 ALTER TABLE `Scientists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Self_Efficacy_Survey`
--

DROP TABLE IF EXISTS `Self_Efficacy_Survey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Self_Efficacy_Survey` (
  `mid` int(11) NOT NULL DEFAULT '0',
  `fillout` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `q1` tinyint(4) DEFAULT NULL,
  `q2` tinyint(4) DEFAULT NULL,
  `q3` tinyint(4) DEFAULT NULL,
  `q4` tinyint(4) DEFAULT NULL,
  `q5` tinyint(4) DEFAULT NULL,
  `q6` tinyint(4) DEFAULT NULL,
  `q7` tinyint(4) DEFAULT NULL,
  `q8` tinyint(4) DEFAULT NULL,
  `q9` tinyint(4) DEFAULT NULL,
  `q10` tinyint(4) DEFAULT NULL,
  `q11` tinyint(4) DEFAULT NULL,
  `q12` tinyint(4) DEFAULT NULL,
  `q13` tinyint(4) DEFAULT NULL,
  `q14` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`mid`,`fillout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Self_Efficacy_Survey`
--

LOCK TABLES `Self_Efficacy_Survey` WRITE;
/*!40000 ALTER TABLE `Self_Efficacy_Survey` DISABLE KEYS */;
INSERT INTO `Self_Efficacy_Survey` VALUES (110,'2012-08-09 00:43:45',-2,-1,0,1,2,-2,-1,0,1,2,-2,-1,0,1),(110,'2012-09-10 15:46:44',-2,-2,-2,-2,-2,-1,-2,-2,-2,-2,-2,-2,-2,-2),(110,'2012-09-10 15:47:10',-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2),(110,'2012-09-10 15:48:16',-2,-2,-2,-2,-2,-2,-2,-2,-1,-2,-1,-2,-2,-2);
/*!40000 ALTER TABLE `Self_Efficacy_Survey` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SupplementEntry`
--

DROP TABLE IF EXISTS `SupplementEntry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SupplementEntry` (
  `EntryId` int(11) NOT NULL DEFAULT '0',
  `SupType` int(11) DEFAULT NULL,
  `SupMethod` int(11) DEFAULT NULL,
  `NumberDiapers` int(11) DEFAULT NULL,
  `TotalAmount` int(11) DEFAULT NULL,
  `NumberTimes` int(11) DEFAULT NULL,
  PRIMARY KEY (`EntryId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SupplementEntry`
--

LOCK TABLES `SupplementEntry` WRITE;
/*!40000 ALTER TABLE `SupplementEntry` DISABLE KEYS */;
INSERT INTO `SupplementEntry` VALUES (1,1,3,NULL,3,3),(2,2,2,NULL,1,2),(3,2,1,NULL,2,2),(4,2,2,NULL,5,4),(5,2,2,NULL,5,4),(6,2,2,NULL,4,4),(7,1,2,NULL,4,3),(8,3,2,NULL,2,3),(9,2,3,NULL,3,5),(10,3,1,NULL,4,1),(11,3,1,NULL,3,1),(12,1,1,NULL,1,1),(13,1,2,NULL,3,1),(14,2,2,NULL,2,1),(15,3,1,NULL,2,2),(16,1,1,NULL,3,1),(17,2,2,NULL,7,2),(18,1,1,NULL,4,2),(19,2,4,NULL,4,2),(20,3,3,NULL,6,5),(21,1,1,NULL,3,3),(22,1,2,NULL,6,3),(23,1,1,NULL,1,1),(24,1,2,NULL,2,2),(25,1,2,NULL,10,3),(26,1,1,NULL,7,3);
/*!40000 ALTER TABLE `SupplementEntry` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SystemFeedback`
--

DROP TABLE IF EXISTS `SystemFeedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SystemFeedback` (
  `mid` int(11) NOT NULL DEFAULT '0',
  `fillout` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `q1` int(11) DEFAULT NULL,
  `q2` int(11) DEFAULT NULL,
  `q3` int(11) DEFAULT NULL,
  `q4` int(11) DEFAULT NULL,
  `q5` int(11) DEFAULT NULL,
  `q6` int(11) DEFAULT NULL,
  `q7` int(11) DEFAULT NULL,
  `q8` int(11) DEFAULT NULL,
  `q9` int(11) DEFAULT NULL,
  `q10` int(11) DEFAULT NULL,
  PRIMARY KEY (`mid`,`fillout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SystemFeedback`
--

LOCK TABLES `SystemFeedback` WRITE;
/*!40000 ALTER TABLE `SystemFeedback` DISABLE KEYS */;
INSERT INTO `SystemFeedback` VALUES (110,'2012-09-04 11:59:04',3,3,3,3,3,3,3,3,3,3),(110,'2012-09-04 12:25:53',3,3,3,3,3,3,3,3,3,3),(110,'2012-09-05 11:37:09',3,3,3,3,3,3,3,3,3,3),(110,'2012-09-05 11:37:34',3,3,3,3,3,3,3,3,3,3),(110,'2012-09-10 12:25:02',3,3,3,3,3,3,3,3,3,3),(110,'2012-09-10 12:25:34',3,3,3,3,3,3,3,3,3,3),(110,'2012-09-10 12:53:52',3,3,3,3,3,3,3,3,3,3),(110,'2012-09-10 12:55:11',3,3,3,3,3,3,3,3,3,3),(110,'2012-09-10 12:55:26',3,3,3,3,3,3,3,3,3,3),(110,'2012-09-10 12:56:02',3,3,3,3,3,3,3,3,3,3),(110,'2012-09-10 12:56:21',3,3,3,3,3,3,3,3,3,3),(110,'2012-09-10 12:58:22',1,1,1,1,1,1,1,1,1,1),(110,'2012-09-10 13:00:37',3,3,3,3,3,3,3,3,3,3),(110,'2012-09-10 15:37:08',1,3,1,1,1,1,1,1,1,1),(110,'2012-09-10 15:37:24',1,1,2,1,1,1,1,1,1,1),(110,'2012-09-10 15:38:22',1,1,1,1,1,1,1,1,1,1),(110,'2012-09-10 15:39:18',1,1,1,1,1,1,1,1,1,1),(110,'2012-09-10 15:40:13',1,1,1,1,1,1,1,1,1,1),(110,'2012-09-10 16:48:44',1,1,1,1,1,1,1,1,2,1);
/*!40000 ALTER TABLE `SystemFeedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SystemPerception`
--

DROP TABLE IF EXISTS `SystemPerception`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SystemPerception` (
  `mid` int(11) NOT NULL DEFAULT '0',
  `fillout` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `q1` text,
  `q2` text,
  `q3` text,
  `q4` text,
  `q5` text,
  `q6` text,
  `q7` text,
  PRIMARY KEY (`mid`,`fillout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SystemPerception`
--

LOCK TABLES `SystemPerception` WRITE;
/*!40000 ALTER TABLE `SystemPerception` DISABLE KEYS */;
INSERT INTO `SystemPerception` VALUES (110,'2012-09-04 11:59:22','asdgas','gsgfr','asegwaerfadsa','sdyhasrf','dsayherasgyr','etyryhzegfdg','etrgsfdgrewb'),(110,'2012-09-04 12:26:45','hadfs','hdafsdfa','hfdasdf','hfdhazfas','fgasdfzgasdfd','asfhasdfs','hfdasdags'),(110,'2012-09-05 11:37:21','32524','34632452','46245345342','5724565','5673456','83567634','875467245'),(110,'2012-09-05 11:37:51','634547766','6345635','6534576465','5623454165345','75363454','562547543656','754563424154'),(110,'2012-09-10 12:25:14','dsfasd','gasdfsdf','afsdfasd','fsdfasd','ghasdfsdaf','gsdfadsdf','ryhaefdsfa'),(110,'2012-09-10 12:25:52','fhagrfds','gsdfasdfas','vafeaetwa','dsfaghgwerfd','gsgrefsea','dgsafdhgsdfadsg','yregfdzefdZvcvsd'),(110,'2012-09-10 16:48:57','fgasdjf','jkj;','jkj','fhdufii','fhabw','fudih79','f89rf3q'),(110,'2013-03-05 14:02:19','gfsyreahrag','fhadfagbadfagrehtrj','','htrkraefkartjjrgzj','tryjZAYsdhtzfdhetjhtrtjtrjt','5jtrjzrtyjgZDZHvchrejrtykj','jtrjuaxhfgaurjrthudf');
/*!40000 ALTER TABLE `SystemPerception` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Weight`
--

DROP TABLE IF EXISTS `Weight`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Weight` (
  `EntryId` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  `EntryDate` datetime DEFAULT NULL,
  `InputDate` datetime DEFAULT NULL,
  PRIMARY KEY (`EntryId`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Weight`
--

LOCK TABLES `Weight` WRITE;
/*!40000 ALTER TABLE `Weight` DISABLE KEYS */;
INSERT INTO `Weight` VALUES (3,110,128,'2013-08-30 13:20:00','2013-08-30 13:26:20'),(4,110,131,'2013-08-30 13:20:00','2013-08-30 13:26:25'),(5,110,106,'2013-08-29 13:20:00','2013-08-30 13:26:35'),(6,110,128,'2013-10-31 11:55:00','2013-10-31 11:56:06'),(7,110,128,'2013-10-30 12:05:00','2013-10-31 12:05:50'),(8,110,128,'2013-10-30 12:05:00','2013-10-31 12:08:33'),(9,110,96,'2013-10-30 12:10:00','2013-10-31 12:11:15'),(10,110,112,'2013-10-31 14:00:00','2013-10-31 14:02:56'),(11,110,112,'2013-10-31 12:30:00','2013-10-31 14:03:58'),(12,110,128,'2013-10-31 14:10:00','2013-10-31 14:12:10'),(13,110,134,'2013-11-08 12:15:00','2013-11-08 12:21:25'),(14,110,128,'2014-01-16 23:40:00','2014-01-16 11:42:43'),(15,110,136,'2014-01-16 04:55:00','2014-01-16 16:57:56'),(16,110,520,'2014-01-16 07:15:00','2014-01-16 19:16:02'),(17,110,520,'2014-01-16 07:15:00','2014-01-16 19:21:36'),(18,110,8,'2014-01-16 19:35:00','2014-01-16 19:35:31'),(19,110,248,'2014-01-16 19:35:00','2014-01-16 19:40:04');
/*!40000 ALTER TABLE `Weight` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-03-16 10:22:19
