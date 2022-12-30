-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema brogram
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema brogram
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `brogram` DEFAULT CHARACTER SET utf8mb4 ;
USE `brogram` ;

-- -----------------------------------------------------
-- Table `brogram`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `brogram`.`users` (
  `usrId` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(60) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `firstName` VARCHAR(45) NOT NULL,
  `lastName` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`usrId`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `brogram`.`followers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `brogram`.`followers` (
  `usrId` INT(11) NOT NULL,
  `friendId` INT(11) NOT NULL,
  PRIMARY KEY (`usrId`, `friendId`),
  INDEX `fk_followers_users1_idx` (`friendId` ASC),
  CONSTRAINT `fk_followers_users`
    FOREIGN KEY (`usrId`)
    REFERENCES `brogram`.`users` (`usrId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_followers_users1`
    FOREIGN KEY (`friendId`)
    REFERENCES `brogram`.`users` (`usrId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `brogram`.`login_attempts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `brogram`.`login_attempts` (
  `usrId` INT(11) NOT NULL,
  `time` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`usrId`),
  CONSTRAINT `fk_login_attempts_users2`
    FOREIGN KEY (`usrId`)
    REFERENCES `brogram`.`users` (`usrId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `brogram`.`posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `brogram`.`posts` (
  `postId` INT(11) NOT NULL AUTO_INCREMENT,
  `usrId` INT(11) NOT NULL,
  `title` VARCHAR(50) NOT NULL,
  `caption` VARCHAR(255) NOT NULL,
  `image` VARCHAR(45) NOT NULL,
  `location` VARCHAR(45) NOT NULL DEFAULT NULL,
  `creationDate` DATETIME NOT NULL DEFAULT NOW(),
  `eventDate` DATETIME NOT NULL,
  `likes` INT NOT NULL,
  PRIMARY KEY (`postId`),
  INDEX `fk_posts_users1_idx` (`usrId` ASC),
  CONSTRAINT `fk_posts_users1`
    FOREIGN KEY (`usrId`)
    REFERENCES `brogram`.`users` (`usrId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `brogram`.`comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `brogram`.`comments` (
  `commentId` INT NOT NULL AUTO_INCREMENT,
  `postId` INT(11) NOT NULL,
  `author` INT(11) NOT NULL,
  `date` DATETIME NULL,
  `content` VARCHAR(255) NULL,
  PRIMARY KEY (`commentId`),
  INDEX `fk_comments_users1_idx` (`author` ASC),
  CONSTRAINT `fk_comments_posts1`
    FOREIGN KEY (`postId`)
    REFERENCES `brogram`.`posts` (`postId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_comments_users1`
    FOREIGN KEY (`author`)
    REFERENCES `brogram`.`users` (`usrId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `brogram`.`likes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `brogram`.`likes` (
  `likeId` INT NOT NULL AUTO_INCREMENT,
  `usrId` INT(11) NOT NULL,
  `postId` INT(11) NOT NULL,
  INDEX `fk_likes_posts1_idx` (`postId` ASC),
  PRIMARY KEY (`likeId`),
  CONSTRAINT `fk_likes_users1`
    FOREIGN KEY (`usrId`)
    REFERENCES `brogram`.`users` (`usrId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_likes_posts1`
    FOREIGN KEY (`postId`)
    REFERENCES `brogram`.`posts` (`postId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `brogram`.`notifications`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `brogram`.`notifications` (
  `notificationId` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  `forUser` INT(11) NOT NULL,
  `entityId` VARCHAR(45) NOT NULL,
  `read` TINYINT(1) NOT NULL DEFAULT 0,
  `time` DATETIME NULL DEFAULT NOW(),
  PRIMARY KEY (`notificationId`),
  INDEX `fk_notifications_users1_idx` (`forUser` ASC),
  CONSTRAINT `fk_notifications_users1`
    FOREIGN KEY (`forUser`)
    REFERENCES `brogram`.`users` (`usrId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `brogram`.`participations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `brogram`.`participations` (
  `usrId` INT(11) NOT NULL,
  `eventId` INT NOT NULL,
  INDEX `fk_participations_users1_idx` (`usrId` ASC),
  INDEX `fk_participations_events2_idx` (`eventId` ASC),
  PRIMARY KEY (`usrId`, `eventId`),
  CONSTRAINT `fk_participations_users1`
    FOREIGN KEY (`usrId`)
    REFERENCES `brogram`.`users` (`usrId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_participations_events2`
    FOREIGN KEY (`eventId`)
    REFERENCES `brogram`.`events` (`eventId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `brogram`.`events`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `brogram`.`events` (
  `eventId` INT NOT NULL AUTO_INCREMENT,
  `postId` INT(11) NOT NULL,
  `participants` INT NULL DEFAULT 0,
  PRIMARY KEY (`eventId`),
  INDEX `fk_events_posts2_idx` (`postId` ASC),
  CONSTRAINT `fk_events_posts2`
    FOREIGN KEY (`postId`)
    REFERENCES `brogram`.`posts` (`postId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `brogram`.`participations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `brogram`.`participations` (
  `usrId` INT(11) NOT NULL,
  `eventId` INT NOT NULL,
  INDEX `fk_participations_users1_idx` (`usrId` ASC),
  INDEX `fk_participations_events2_idx` (`eventId` ASC),
  PRIMARY KEY (`usrId`, `eventId`),
  CONSTRAINT `fk_participations_users1`
    FOREIGN KEY (`usrId`)
    REFERENCES `brogram`.`users` (`usrId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_participations_events2`
    FOREIGN KEY (`eventId`)
    REFERENCES `brogram`.`events` (`eventId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `brogram`.`participations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `brogram`.`participations` (
  `usrId` INT(11) NOT NULL,
  `eventId` INT NOT NULL,
  INDEX `fk_participations_users1_idx` (`usrId` ASC),
  INDEX `fk_participations_events2_idx` (`eventId` ASC),
  PRIMARY KEY (`usrId`, `eventId`),
  CONSTRAINT `fk_participations_users1`
    FOREIGN KEY (`usrId`)
    REFERENCES `brogram`.`users` (`usrId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_participations_events2`
    FOREIGN KEY (`eventId`)
    REFERENCES `brogram`.`events` (`eventId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

-- Creating a user that is capable of making only SELECT, INSERT and UPDATE
-- operations, so that no one is able to delete the DB except the administrator.
CREATE USER IF NOT EXISTS 'sec_user'@'localhost' IDENTIFIED BY 'eKcGZr59zAa2BEWU';
GRANT SELECT, INSERT, UPDATE ON `brogram`.* TO 'sec_user'@'localhost';


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
