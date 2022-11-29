-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
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
  `usrId` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`usrId`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `brogram`.`login_attempts` (
  `usrId` INT(11) NOT NULL,
  `time` VARCHAR(30) NOT NULL
) ENGINE InnoDB;

-- Creating a user that is capable of making only SELECT, INSERT and UPDATE
-- operations, so that no one is able to delete the DB except the administrator.

CREATE USER IF NOT EXISTS 'sec_user'@'localhost' IDENTIFIED BY 'eKcGZr59zAa2BEWU';
GRANT SELECT, INSERT, UPDATE ON `brogram`.* TO 'sec_user'@'localhost';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;



