DROP TABLE IF EXISTS `user_eventlog`;

CREATE TABLE IF NOT EXISTS `user_eventlog` (
  `EventID` INT NOT NULL AUTO_INCREMENT,
  `UserId` INT NOT NULL,
  `Username` VARCHAR(250),
  `UserAcctType` VARCHAR(50) NOT NULL, 
  `AcctAffected` VARCHAR(100) NOT NULL,
  `BeforeAffected` VARCHAR(250) NOT NULL, 
  `AfterAffected` VARCHAR(250) NOT NULL, 
  `Event_Status` VARCHAR(100) NOT NULL,
  `DateANDTime` DATETIME NOT NULL, 
  PRIMARY KEY (`EventID`),
  KEY `UserId` (`UserId`)
)