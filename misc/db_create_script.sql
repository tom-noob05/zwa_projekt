SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';


-- -----------------------------------------------------
-- Table `sql7805635`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sql7805635`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `jmeno` VARCHAR(45) NOT NULL,
  `prijmeni` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `role_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `sql7805635`.`user_roles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sql7805635`.`user_roles` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sql7805635`.`offers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sql7805635`.`offers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `description` TEXT NOT NULL,
  `price` FLOAT NOT NULL,
  `status` VARCHAR(20) NOT NULL,
  `condition` VARCHAR(20) NOT NULL,
  `seller_id` INT NOT NULL,
  `category_id` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sql7805635`.`categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sql7805635`.`categories` (
  `id` INT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `parent_id` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sql7805635`.`bought_offers`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `sql7805635`.`bought_offers` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `offer_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `bought_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `sql7805635`.`listed_offers`
-- -----------------------------------------------------
-- CREATE TABLE IF NOT EXISTS `sql7805635`.`listed_offers` (
--   `id` INT NOT NULL AUTO_INCREMENT,
--   `seller_id` INT NOT NULL,
--   `offer_id` INT NOT NULL,
--   PRIMARY KEY (`id`))
-- ENGINE = InnoDB
-- DEFAULT CHARACTER SET = utf8mb4;
                                  -- tahle tabulka neni potreba, ale necham ji tu

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;










