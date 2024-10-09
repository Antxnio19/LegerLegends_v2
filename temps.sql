DROP TABLE IF EXISTS `user_eventlog`;
CREATE TABLE IF NOT EXISTS `user_eventlog` (
    AutoID CHAR(36) PRIMARY KEY,
    UserID INT,
    UserAcctType VARCHAR(50),
    DateANDTime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    AcctAffected VARCHAR(50),
    BeforeAffected VARCHAR(50),
    AfterAffected VARCHAR(50),
    STATUS VARCHAR(300)
)