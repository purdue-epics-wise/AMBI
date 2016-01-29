
ALTER TABLE Mothers ADD COLUMN actions_required INT UNSIGNED NOT NULL DEFAULT 0;

DROP TABLE IF EXISTS `Breastfeeding_Followup`;
CREATE TABLE `Breastfeeding_Followup` (
  `mid` int(11) NOT NULL default '0',
  `fillout` datetime NOT NULL default '0000-00-00 00:00:00',
  `q1` text,
  `q2` text,
  `q3` text,
  `q4` text,
  `q5` text,
  `q6` text,
  `q7` text,
  `q8` text,
  `q9` text,
  PRIMARY KEY  (`mid`,`fillout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Self_Efficacy_Survey`;
CREATE TABLE `Self_Efficacy_Survey` (
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
  `q11` tinyint,
  `q12` tinyint,
  `q13` tinyint,
  `q14` tinyint,
  PRIMARY KEY  (`mid`,`fillout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Breastfeeding_Evaluation`;
CREATE TABLE `Breastfeeding_Evaluation` (
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
  `q11` tinyint,
  `q12` tinyint,
  `q13` tinyint,
  `q14` tinyint,
  `q15` tinyint,
  `q16` tinyint,
  `q17` tinyint,
  `q18` tinyint,
  `q19` tinyint,
  `q20` tinyint,
  `q21` tinyint,
  `q22` tinyint,
  `q23` tinyint,
  `q24` tinyint,
  `q25` tinyint,
  `q26` tinyint,
  `q27` tinyint,
  `q28` tinyint,
  `q29` tinyint,
  `q30` tinyint,
  PRIMARY KEY  (`mid`,`fillout`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
