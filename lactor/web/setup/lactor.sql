-- MySQL dump 10.11
--
-- Host: localhost    Database: Breast1
-- ------------------------------------------------------
-- Server version	5.0.90-log

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
  `PumpingMethod` int(11) default NULL,
  `InfantState` int(11) default NULL,
  `MaternalProblems` int(11) default NULL,
  `Latching` int(11) default NULL,
  `Side` int(11) default NULL,
  `PumpingAmount` int(11) default NULL,
  PRIMARY KEY  (`EntryId`),
  UNIQUE KEY `EntryId` (`EntryId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `latch_trigger_2` AFTER UPDATE ON `BreastfeedEntry` FOR EACH ROW BEGIN

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
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `ControlledVocabulary`
--

DROP TABLE IF EXISTS `ControlledVocabulary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ControlledVocabulary` (
  `Attribute` varchar(50) NOT NULL,
  `TextValue` varchar(100) NOT NULL default '',
  `NumValue` int(11) NOT NULL,
  PRIMARY KEY  (`Attribute`,`TextValue`,`NumValue`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Dumping data for table `ControlledVocabulary`
--

LOCK TABLES `ControlledVocabulary` WRITE;
/*!40000 ALTER TABLE `ControlledVocabulary` DISABLE KEYS */;
INSERT INTO `ControlledVocabulary` VALUES ('Age','20-29',2),('Age','30-39',3),('Age','40 or more',4),('Age','<20',1),('Age','Choose One',0),('AgeFirstFeed','2-4 hours after birth',2),('AgeFirstFeed','6 hours after birth',3),('AgeFirstFeed','> 6 hours after birth',4),('AgeFirstFeed','Choose one',0),('AgeFirstFeed','Immediately after birth',1),('duration','1-2 Minutes',1),('duration','11-15 Minutes',4),('duration','3-4 Minutes',2),('duration','5-10 Minutes',3),('duration','> 15 Minutes',5),('duration','Choose one',0),('Education','Associate Degree',4),('Education','BA or BS degree',5),('Education','Choose One',0),('Education','Doctoral Degree',7),('Education','High school/GED',2),('Education','Less than high school',1),('Education','Masters Degree',6),('Education','Professional Degree',8),('Education','Some college',3),('Ethnicity','Choose One',0),('Ethnicity','Hispanic or Latino',1),('Ethnicity','NON Hispanic or Latino',2),('FirstPrimCare','After the first week of discharge',3),('FirstPrimCare','By the first week of discharge',2),('FirstPrimCare','Choose one',0),('FirstPrimCare','Within 48-72 hours',1),('FreqBreastExpr','> 5 hours',3),('FreqBreastExpr','Choose one',0),('FreqBreastExpr','Every 2-3 hours',1),('FreqBreastExpr','Every 4-5 hours',2),('gestate','34-35 weeks',2),('gestate','35-36 weeks',3),('gestate','36-37 weeks',4),('gestate','< 34 weeks',1),('gestate','> 37 weeks',5),('gestate','Choose one',0),('GestationalAge','AGA',1),('GestationalAge','Choose one',0),('GestationalAge','LGA',3),('GestationalAge','SGA',2),('HospFirstMonth','Choose one',0),('HospFirstMonth','No',2),('HospFirstMonth','Yes',1),('HouseIncome','$10,000 to < $24,999',2),('HouseIncome','$25,000 to $49,999',3),('HouseIncome','$50,000 or more',4),('HouseIncome','Choose One',0),('HouseIncome','Less than $10,000',1),('infant-state','Active alert',4),('infant-state','Choose one',0),('infant-state','Crying',5),('infant-state','Difficult to awake',1),('infant-state','Drowsy',2),('infant-state','Quiet and alert',3),('latching','Choose one',0),('latching','Latch correctly',3),('latching','Latch with nipple shield',4),('latching','Not at all',1),('latching','Slipping of the breast',2),('maternal-problems','Breast tissue is soft/no milk coming in',1),('maternal-problems','Choose one',0),('maternal-problems','Engorgement',4),('maternal-problems','Flat/inverted nipple',3),('maternal-problems','Mastitis',5),('maternal-problems','No problems',6),('maternal-problems','Sore nipple',2),('MHDP','Bleeding during pregnancy',2),('MHDP','Choose One',0),('MHDP','Gestational Diabetes',6),('MHDP','Lack or late prenatal care',4),('MHDP','Low maternal weight <50 kg',1),('MHDP','Other',7),('MHDP','PROM',5),('MHDP','Toxemia of Pregnancy',3),('MODel','Breech',3),('MODel','Choose One',0),('MODel','Section',4),('MODel','Vaginal',1),('MODel','Vaginal with assistance',2),('morb-type','Choose one',0),('morb-type','Decrease body temperature',2),('morb-type','Decrease in blood glucose',3),('morb-type','Dehydration',6),('morb-type','Weight Loss',7),('morb-type','Difficult or trouble breathing',4),('morb-type','Infection',5),('morb-type','Jaundice',1),('NeedExtraCare','Choose one',0),('NeedExtraCare','No',2),('NeedExtraCare','Yes',1),('NumberDiapers','1',1),('NumberDiapers','2',2),('NumberDiapers','3',3),('NumberDiapers','4',4),('NumberDiapers','5',5),('NumberDiapers','6',6),('NumberDiapers','> 6',7),('NumberDiapers','Choose one',0),('NumberTimes','1',1),('NumberTimes','2',2),('NumberTimes','3',3),('NumberTimes','4',4),('NumberTimes','5',5),('NumberTimes','> 5',6),('NumberTimes','Choose one',0),('Occupation','Cashier, Secretary, Laborer, Technical',3),('Occupation','Choose One',0),('Occupation','Homemaker',1),('Occupation','Other',4),('Occupation','Professional',2),('ounces','0 ounces',1),('ounces','1 ounce',2),('ounces','10 ounces',11),('ounces','11 ounces',12),('ounces','12 ounces',13),('ounces','13 ounces',14),('ounces','14 ounces',15),('ounces','15 ounces',16),('ounces','16 ounces',17),('ounces','2 ounces',3),('ounces','3 ounces',4),('ounces','4 ounces',5),('ounces','5 ounces',6),('ounces','6 ounces',7),('ounces','7 ounces',8),('ounces','8 ounces',9),('ounces','9 ounces',10),('ounces','Choose one',0),('out-s-color','Black/green',2),('out-s-color','Black/tarry meconium',1),('out-s-color','Choose one',0),('out-s-color','Yellow',3),('out-s-consistency','Choose one',0),('out-s-consistency','Formed',2),('out-s-consistency','Loose and seedy',1),('out-s-consistency','Watery',3),('out-u-color','Amber Yellow',1),('out-u-color','Choose one',0),('out-u-color','Dark Yellow',2),('out-u-saturation','Choose one',0),('out-u-saturation','Heavily wet',4),('out-u-saturation','Moderately wet',3),('out-u-saturation','Not wet at all',1),('out-u-saturation','Slightly wet',2),('Parity','Choose One',0),('Parity','First',1),('Parity','Fourth / More',4),('Parity','Second',2),('Parity','Third',3),('PBE','3-6 months',2),('PBE','7-12 months',3),('PBE','<3 months',1),('PBE','> 1 year',4),('PBE','Choose One',0),('PBE','No past breastfeeding experience',5),('POB','Choose One',0),('POB','History of pregnancy loss',1),('POB','Low birth weight infant',4),('POB','Others',5),('POB','Previous antepartum hemorrahage',3),('POB','Previous premature < 37 weeks',2),('pounds','10 pounds',10),('pounds','2 pounds',2),('pounds','3 pounds',3),('pounds','4 pounds',4),('pounds','5 pounds',5),('pounds','6 pounds',6),('pounds','7 pounds',7),('pounds','8 pounds',8),('pounds','9 pounds',9),('pounds','< 2 pounds',1),('pounds','> 10 pounds',11),('pounds','Choose one',0),('pumping-method','Choose one',0),('pumping-method','Double Electric Pump',3),('pumping-method','Hand Pump',1),('pumping-method','Manual Hand Pump',2),('pumping-method','Not applicable',4),('Race','American Indian / Alaskan Native',4),('Race','Asian',2),('Race','Black or African American',3),('Race','Choose One',0),('Race','More than one race',6),('Race','Native Hawaiian or Pacific Islander',1),('Race','White',5),('Residence','Apartment/Rental',3),('Residence','Choose One',0),('Residence','Owned',4),('Residence','Rural',1),('Residence','Urban',2),('Side','Both',3),('Side','Choose one',0),('Side','Left',1),('Side','Right',2),('sup-method','Bottle',1),('sup-method','Choose one',0),('sup-method','Cup',2),('sup-method','Spoon',4),('sup-method','Supplemental Set',3),('sup-type','Choose one',0),('sup-type','Expressed milk',1),('sup-type','Formula',3),('sup-type','Pasteurized human milk',2),('SystemFeedback','I felt very confident using the system:',9),('SystemFeedback','I found the system unnecessarily complex:',2),('SystemFeedback','I found the system very cumbersome to use:',8),('SystemFeedback','I found the various functions in this system were well integrated:',5),('SystemFeedback','I needed to learn a lot of the things before I could get going with this system:',10),('SystemFeedback','I think that I would like to use this system frequently:',1),('SystemFeedback','I think that I would need the support of a technical person to use this system:',4),('SystemFeedback','I thought the system was easy to use:',3),('SystemFeedback','I thought there was too much inconsistency in this system:',6),('SystemFeedback','I would imagine that most people would learn to use this system quickly:',7),('SystemPerception','How did it help?',1),('SystemPerception','How did the web-based monitoring help in overcoming your baby\'s health problems?',4),('SystemPerception','How much did the phone call from the lactation consultant help you in your breastfeeding experience?',6),('SystemPerception','How was your experience with this web-based monitoring?',5),('SystemPerception','Was the data entry a burden for you or overwhelming? Do you have any suggestions?',3),('SystemPerception','Was the data entry time consuming? Do you have any suggestions?',2),('SystemPerception','Would you recommend this web-based monitoring for a friend? Why and why not?',7),('TimesExtraCare','1-2 times',1),('TimesExtraCare','3-4 times',2),('TimesExtraCare','5-6 times',3),('TimesExtraCare','Choose one',0),('TimeStartBreast','6-12 hours after delivery',3),('TimeStartBreast','After the first day',4),('TimeStartBreast','Choose one',0),('TimeStartBreast','Within 1 hour after delivery',1),('TimeStartBreast','Within first 6 hours after delivery',2),('TotalAmount','1 ounce',1),('TotalAmount','10 ounces',10),('TotalAmount','2 ounces',2),('TotalAmount','3 ounces',3),('TotalAmount','4 ounces',4),('TotalAmount','5 ounces',5),('TotalAmount','6 ounces',6),('TotalAmount','7 ounces',7),('TotalAmount','8 ounces',8),('TotalAmount','9 ounces',9),('TotalAmount','> 10 ounces',11),('TotalAmount','Choose one',0),('TypeFirstDischarge','Breast milk (banked)',2),('TypeFirstDischarge','Choose one',0),('TypeFirstDischarge','Formula',3),('TypeFirstDischarge','Mothers own milk',1);
/*!40000 ALTER TABLE `ControlledVocabulary` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

--
-- Table structure for table `Diary`
--

DROP TABLE IF EXISTS `Diary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Diary` (
  `mid` int(11) NOT NULL default '0',
  `Number` int(11) NOT NULL default '0',
  `EntryDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `InputDate` datetime default NULL,
  `EntryType` int(11) NOT NULL default '0',
  `EntryId` int(11) NOT NULL default '0',
  PRIMARY KEY  (`mid`,`Number`,`EntryType`,`EntryId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 TRIGGER `latch_trigger_1` AFTER INSERT ON `Diary` FOR EACH ROW BEGIN
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

                END;
        END IF;
END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Table structure for table `InfantProfile`
--

DROP TABLE IF EXISTS `InfantProfile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `InfantProfile` (
  `mid` int(11) NOT NULL default '0',
  `cid` int(11) NOT NULL default '0',
  `InfantInitials` varchar(8) default NULL,
  `GestationalAge` int(11) default NULL,
  `DOB` datetime default NULL,
  `BirthWeight` varchar(8) default NULL,
  `DOD` datetime default NULL,
  `DischargeWeight` varchar(8) default NULL,
  `TypeFirstBreast` int(11) default NULL,
  `AgeFirstFeed` int(11) default NULL,
  `TimeStartBreast` int(11) default NULL,
  `FreqBreastExpr` int(11) default NULL,
  `ChildMorb` int(11) default NULL,
  `FirstPrimCare` int(11) default NULL,
  `NeedExtraCare` int(11) default NULL,
  `TimesExtraCare` int(11) default NULL,
  `HospFirstMonth` int(11) default NULL,
  `AppropAge` int(11) default NULL,
  PRIMARY KEY  (`mid`,`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `LoginMonitor`
--

DROP TABLE IF EXISTS `LoginMonitor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `LoginMonitor` (
  `mid` int(11) default NULL,
  `session` datetime default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MorbidityEntry`
--

DROP TABLE IF EXISTS `MorbidityEntry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MorbidityEntry` (
  `EntryId` int(11) NOT NULL default '0',
  `Type` int(11) default NULL,
  PRIMARY KEY  (`EntryId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `MotherInfo`
--

DROP TABLE IF EXISTS `MotherInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MotherInfo` (
  `mid` int(11) NOT NULL default '0',
  `Name` varchar(50) default NULL,
  `Address` varchar(50) default NULL,
  `Age` int(11) default NULL,
  `Ethnicity` int(11) default NULL,
  `Race` int(11) default NULL,
  `Education` int(11) default NULL,
  `HouseIncome` int(11) default NULL,
  `Occupation` int(11) default NULL,
  `Residence` int(11) default NULL,
  `Parity` int(11) default NULL,
  `POH` int(11) default NULL,
  `MHDP` int(11) default NULL,
  `MethodOfDelivery` int(11) default NULL,
  `PBE` int(11) default NULL,
  `Phone` varchar(50) default NULL,
  PRIMARY KEY  (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Mothers`
--

DROP TABLE IF EXISTS `Mothers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Mothers` (
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mid` int(11) NOT NULL auto_increment,
  `loginstep` int(11) default NULL,
  PRIMARY KEY  (`mid`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=110 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Mothers_Scientists`
--

DROP TABLE IF EXISTS `Mothers_Scientists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Mothers_Scientists` (
  `mid` int(11) default NULL,
  `sid` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `NotEntryId`
--

DROP TABLE IF EXISTS `NotEntryId`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NotEntryId` (
  `mid` int(11) default NULL,
  `nid` int(11) default NULL,
  `eid` int(11) default NULL,
  `etype` int(11) default NULL,
  `ntype` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `NotificationDescription`
--

DROP TABLE IF EXISTS `NotificationDescription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NotificationDescription` (
  `ntype` int(11) default NULL,
  `Problem` text,
  `Description` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `NotificationDescription`
--

LOCK TABLES `NotificationDescription` WRITE;
/*!40000 ALTER TABLE `NotificationDescription` DISABLE KEYS */;
INSERT INTO `NotificationDescription` VALUES (1,'Latching',''),(2,'Baby Sleepy',''),(3,'Insufficient Breastfeed Entries',''),(4,'Engorgement','');
/*!40000 ALTER TABLE `NotificationDescription` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

--
-- Table structure for table `Notifications`
--

DROP TABLE IF EXISTS `Notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Notifications` (
  `nid` int(11) NOT NULL auto_increment,
  `mid` int(11) NOT NULL,
  `status` int(11) default NULL,
  `ntype` int(11) default NULL,
  `NotificationIssued` datetime default NULL,
  `astatus` int(11) default '1',
  PRIMARY KEY  (`mid`,`nid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `NotificationsInfo`
--

DROP TABLE IF EXISTS `NotificationsInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NotificationsInfo` (
  `ntype` int(11) NOT NULL default '0',
  `description` text,
  PRIMARY KEY  (`ntype`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `OutputEntry`
--

DROP TABLE IF EXISTS `OutputEntry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `OutputEntry` (
  `EntryId` int(11) NOT NULL default '0',
  `UrineColor` int(11) default NULL,
  `UrineSaturation` int(11) default NULL,
  `StoolColor` int(11) default NULL,
  `StoolConsistency` int(11) default NULL,
  `NumberDiapers` int(11) default NULL,
  PRIMARY KEY  (`EntryId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ScienceDownloads`
--

DROP TABLE IF EXISTS `ScienceDownloads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ScienceDownloads` (
  `sid` int(11) default NULL,
  `DownloadTime` datetime default NULL,
  `Mothers` text,
  `BeginDate` datetime default NULL,
  `EndDate` datetime default NULL,
  `Screen` int(11) default NULL,
  `Parameters` text
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ScienceView`
--

DROP TABLE IF EXISTS `ScienceView`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ScienceView` (
  `sid` int(11) default NULL,
  `ViewTime` datetime default NULL,
  `mid` int(11) default NULL,
  `BeginDate` datetime default NULL,
  `EndDate` datetime default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ScienceWrite`
--

DROP TABLE IF EXISTS `ScienceWrite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ScienceWrite` (
  `sid` int(11) default NULL,
  `Time` datetime default NULL,
  `mid` int(11) default NULL,
  `WriteType` int(11) default NULL,
  `EntryType` int(11) default NULL,
  `EntryId` int(11) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Scientists`
--

DROP TABLE IF EXISTS `Scientists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Scientists` (
  `sid` int(11) NOT NULL auto_increment,
  `email` varchar(60) default NULL,
  `password` varchar(200) default NULL,
  `loginstep` int(11) default NULL,
  PRIMARY KEY  (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SupplementEntry`
--

DROP TABLE IF EXISTS `SupplementEntry`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SupplementEntry` (
  `EntryId` int(11) NOT NULL default '0',
  `SupType` int(11) default NULL,
  `SupMethod` int(11) default NULL,
  `NumberDiapers` int(11) default NULL,
  `TotalAmount` int(11) default NULL,
  `NumberTimes` int(11) default NULL,
  PRIMARY KEY  (`EntryId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SystemFeedback`
--

DROP TABLE IF EXISTS `SystemFeedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SystemFeedback` (
  `mid` int(11) NOT NULL default '0',
  `fillout` datetime NOT NULL default '0000-00-00 00:00:00',
  `q1` int(11) default NULL,
  `q2` int(11) default NULL,
  `q3` int(11) default NULL,
  `q4` int(11) default NULL,
  `q5` int(11) default NULL,
  `q6` int(11) default NULL,
  `q7` int(11) default NULL,
  `q8` int(11) default NULL,
  `q9` int(11) default NULL,
  `q10` int(11) default NULL,
  PRIMARY KEY  (`mid`,`fillout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SystemPerception`
--

DROP TABLE IF EXISTS `SystemPerception`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SystemPerception` (
  `mid` int(11) NOT NULL default '0',
  `fillout` datetime NOT NULL default '0000-00-00 00:00:00',
  `q1` text,
  `q2` text,
  `q3` text,
  `q4` text,
  `q5` text,
  `q6` text,
  `q7` text,
  PRIMARY KEY  (`mid`,`fillout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-06-02 11:44:36

-- Things added later...

DROP TABLE IF EXISTS `Postnatal_Depression`;
CREATE TABLE `Postnatal_Depression` (
  `mid` int(11) NOT NULL default '0',
  `fillout` datetime NOT NULL default '0000-00-00 00:00:00',
  `q1` tinyint,
  `q2` tinyint,
  `q3` tinyint,
  `q4` tinyint,
  `q5` tinyint,
  `q6` tinyint,
  `q7` tinyint,
  `q8` tinyint,
  `q9` tinyint,
  `q10` tinyint,
  PRIMARY KEY  (`mid`,`fillout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

UPDATE NotificationDescription SET Problem="Jaundice" where ntype=3;

INSERT INTO NotificationDescription VALUES
  (6, 'Insufficient Breastfeeding Entries', ''),
  (7, 'Nipple Shield', ''),
  (8, 'Sufficient Breastfeeding Entries', ''),
  (9, 'Sufficient Output Entries', ''),
  (10, 'Sufficient Notification Reading', '');
  

DROP TABLE IF EXISTS `Weight`;

CREATE TABLE `Weight` (
  `EntryId` int NOT NULL AUTO_INCREMENT,
  `mid` int NOT NULL,
  `weight` int NOT NULL,
  `EntryDate` datetime NOT NULL,
  `InputDate` datetime NOT NULL,
  PRIMARY KEY (`EntryId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Inbox`;

CREATE TABLE `Inbox` (
  `EntryId` int NOT NULL AUTO_INCREMENT,
  `message` varchar(4096) NOT NULL,
  `messageDate` datetime NOT NULL,
  `senderId` int NOT NULL,
  `recipientId` int NOT NULL,
  `metadata` int NOT NULL default 0,
  PRIMARY KEY (`EntryId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


