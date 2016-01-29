CREATE TABLE Hospital (
  hospital_id INT NOT NULL AUTO_INCREMENT,
  hospital_name VARCHAR(255),
  PRIMARY KEY (hospital_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE Mothers    ADD COLUMN hospital_id INT;
ALTER TABLE Scientists ADD COLUMN hospital_id INT;

