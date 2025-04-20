DROP TABLE IF EXISTS user;
CREATE TABLE `user` (
  `userId` int(10) UNSIGNED NOT NULL,
  `userName` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(150) DEFAULT NULL,
  `googleId` varchar(150) DEFAULT NULL,
  `createdDate` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
