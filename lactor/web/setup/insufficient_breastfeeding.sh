#!/bin/bash

# This script will check the database and add any daily notifications that are
# necessary.
# It should be added to the server's daily cron tasks.

LACTOR_ROOT="/srv/http";

# insert a notification for sufficient reading
NTYPE=10
echo "
INSERT Notifications( mid,status,ntype,NotificationIssued ) 
SELECT mid, 0, $NTYPE, DATE(TIMESTAMPADD(DAY, -2, CURDATE()))
  FROM 
    (SELECT DISTINCT mid 
      FROM Notifications 
      WHERE status=2 AND 
        -- check notifications for 2 days ago to see if they have any read
        DATE(NotificationIssued) = DATE(TIMESTAMPADD(DAY, -2, CURDATE())) AND 
        -- make sure they have no unread notifications
        mid NOT IN (SELECT DISTINCT mid FROM Notifications WHERE status=1) AND
        -- make sure a notification has not already been issued
        mid NOT IN (SELECT mid FROM Notifications WHERE ntype=$NTYPE AND 
          DATE(NotificationIssued) = DATE(TIMESTAMPADD(DAY, -2, CURDATE())))
    ) a;" | \
mysql -u root --database=lactor

# insert a notification for insufficient breastfeeding where applicable;
NTYPE=6
echo "
INSERT Notifications( mid,status,ntype,NotificationIssued ) 
SELECT mid, 0, $NTYPE, date 
  FROM 
    (SELECT 
        Diary.mid, nid, ntype, DATE(EntryDate) AS date, COUNT(*) AS entries 
      FROM Diary 
      LEFT JOIN Notifications ON 
        DATE(EntryDate)=DATE(NotificationIssued) AND ntype=$NTYPE
      WHERE 
        EntryType in (1,2) AND 
        EntryDate < TIMESTAMPADD(DAY,-2,CURDATE()) 
      GROUP BY DATE(EntryDate)
    ) a 
    -- ntype will be null if a notification for this has not already been issued
    WHERE entries < 6 AND ntype IS NULL;" | \
mysql -u root --database=lactor

# insert a notification for sufficient breastfeeding where applicable;
NTYPE=8
echo "
INSERT Notifications( mid,status,ntype,NotificationIssued ) 
SELECT mid, 0, $NTYPE, date 
  FROM 
    (SELECT 
        Diary.mid, nid, ntype, DATE(EntryDate) AS date, COUNT(*) AS entries 
      FROM Diary 
      LEFT JOIN Notifications ON 
        DATE(EntryDate)=DATE(NotificationIssued) AND ntype=$NTYPE
      WHERE 
        EntryType in (1,2) AND 
        EntryDate < TIMESTAMPADD(DAY,-2,CURDATE()) 
      GROUP BY DATE(EntryDate)
    ) a 
    -- ntype will be null if a notification for this has not already been issued
    WHERE entries >= 6 AND ntype IS NULL;" | \
mysql -u root --database=lactor

# insert a notification for sufficient output where applicable;
NTYPE=9
echo "
INSERT Notifications( mid,status,ntype,NotificationIssued ) 
SELECT mid, 0, $NTYPE, date 
  FROM 
    (SELECT a.mid, nid, ntype, DATE(date) as date, SUM(NumberDiapers) as entries 
      FROM  
        (SELECT EntryId, Diary.mid, nid, ntype, DATE(EntryDate) AS date 
          FROM Diary 
          LEFT JOIN Notifications 
          ON DATE(EntryDate)=DATE(NotificationIssued) AND ntype=9 
          WHERE EntryType = 3 AND EntryDate < TIMESTAMPADD(DAY,-2,CURDATE())) a, 
        OutputEntry b WHERE a.EntryId=b.EntryId GROUP BY DATE(date) 
    -- ntype will be null if a notification for this has not already been issued
    ) a WHERE entries >= 6 AND ntype IS NULL;" | \
mysql -u root --database=lactor

# process any new notifications;
for i in \
  $(echo "select distinct mid from lactor.Notifications where status=0;" | \
  mysql -u root | tail -n +2); do

  php -r "include_once('$LACTOR_ROOT/includes/general.php');
          include_once('$LACTOR_ROOT/includes/mail.include.php');
          include_once('$LACTOR_ROOT/includes/db.php');
          db_connect();
          processNotifications($i);"
done;

