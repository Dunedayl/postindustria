CREATE  TABLE IF NOT EXISTS `Users` (
  `id` INT  AUTO_INCREMENT ,
  `firstname` VARCHAR(150) NOT NULL ,
  `lastname` VARCHAR(150) NOT NULL ,
  `email` VARCHAR(255),
  `password` VARCHAR(255),
  PRIMARY KEY (`id`) );

CREATE  TABLE IF NOT EXISTS `Sessions` (
  `id` INT  AUTO_INCREMENT,
  `sessionId` VARCHAR(225) NOT NULL,
  `userId` int NOT NULL,
  `expiry_date` datetime,
  FOREIGN KEY (userId) REFERENCES Users(id),
  PRIMARY KEY (`id`) );
  