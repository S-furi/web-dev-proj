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


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

