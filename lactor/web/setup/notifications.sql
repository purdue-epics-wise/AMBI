DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 */ TRIGGER `latch_trigger_2` AFTER UPDATE ON `BreastfeedEntry` FOR EACH ROW BEGIN

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
END ;;

/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`localhost` */ /*!50003 */ TRIGGER `latch_trigger_1` AFTER INSERT ON `Diary` FOR EACH ROW BEGIN
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
END ;;

DELIMITER ;

