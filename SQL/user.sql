CREATE DATABASE IF NOT EXISTS sinox;
USE sinox;
DROP TABLE IF EXISTS user;
CREATE TABLE `user` (
  `userId` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `userName` varchar(50) NOT NULL UNIQUE,
  `email` varchar(255) NOT NULL UNIQUE,
  `password` varchar(150) DEFAULT NULL,
  `googleId` varchar(150) DEFAULT NULL UNIQUE,
  `createdDate` timestamp NOT NULL,
  `ruleAccepted` BOOLEAN NOT NULL,
  `verifiedEmail` BOOLEAN DEFAULT NULL,
  `emailVerificationCode` varchar(7) NULL UNIQUE,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS userSecurCode;
CREATE TABLE userSecurCode(
  id int UNSIGNED NOT NULL AUTO_INCREMENT,
  userId int(10) UNSIGNED NOT NULL,
  code varchar(25) NOT NULL,
  PRIMARY KEY (id,userId)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


ALTER TABLE userSecurCode 
ADD CONSTRAINT FK_userIdCode
 FOREIGN KEY (userId) REFERENCES user(userId) ON DELETE CASCADE;
