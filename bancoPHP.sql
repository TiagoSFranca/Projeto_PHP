-- MySQL Script generated by MySQL Workbench
-- 11/21/16 15:44:27
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema projeto_pwsl
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema projeto_pwsl
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `projeto_pwsl` DEFAULT CHARACTER SET utf8 ;
USE `projeto_pwsl` ;

-- -----------------------------------------------------
-- Table `projeto_pwsl`.`acesso`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projeto_pwsl`.`acesso` (
  `ace_id` INT(11) NOT NULL AUTO_INCREMENT,
  `ace_descricao` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`ace_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `projeto_pwsl`.`usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projeto_pwsl`.`usuario` (
  `usu_id` INT(11) NOT NULL AUTO_INCREMENT,
  `usu_nome` VARCHAR(60) NOT NULL,
  `usu_login` VARCHAR(25) NOT NULL,
  `usu_senha` CHAR(32) NOT NULL,
  `usu_email` VARCHAR(50) NOT NULL,
  `usu_data_nascimento` DATE NOT NULL,
  `usu_sexo` CHAR(1) NOT NULL,
  `usu_data_cadastro` DATE NOT NULL,
  `ace_id` INT(11) NOT NULL,
  PRIMARY KEY (`usu_id`),
  INDEX `ace_id_idx` (`ace_id` ASC),
  CONSTRAINT `ace_id`
    FOREIGN KEY (`ace_id`)
    REFERENCES `projeto_pwsl`.`acesso` (`ace_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `projeto_pwsl`.`foto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projeto_pwsl`.`foto` (
  `foto_id` INT(11) NOT NULL AUTO_INCREMENT,
  `foto_nome` VARCHAR(32) NOT NULL,
  `usu_id` INT(11) NOT NULL,
  `foto_caminho` VARCHAR(100) NOT NULL,
  `foto_tag` VARCHAR(200) NULL DEFAULT NULL,
  `foto_data_upload` DATE NOT NULL,
  PRIMARY KEY (`foto_id`),
  INDEX `usu_id_idx` (`usu_id` ASC),
  CONSTRAINT `usu_id`
    FOREIGN KEY (`usu_id`)
    REFERENCES `projeto_pwsl`.`usuario` (`usu_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `projeto_pwsl`.`download`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projeto_pwsl`.`download` (
  `down_id` INT(11) NOT NULL AUTO_INCREMENT,
  `foto_id` INT(11) NOT NULL,
  `down_data` DATE NOT NULL,
  PRIMARY KEY (`down_id`),
  INDEX `foto_id_idx` (`foto_id` ASC),
  CONSTRAINT `foto_id`
    FOREIGN KEY (`foto_id`)
    REFERENCES `projeto_pwsl`.`foto` (`foto_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `projeto_pwsl`.`visualizacao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `projeto_pwsl`.`visualizacao` (
  `visu_id` INT(11) NOT NULL AUTO_INCREMENT,
  `visu_data` DATE NOT NULL,
  `foto_id` INT(11) NOT NULL,
  PRIMARY KEY (`visu_id`),
  INDEX `foto_id_idx` (`foto_id` ASC),
  CONSTRAINT `foto_visu`
    FOREIGN KEY (`foto_id`)
    REFERENCES `projeto_pwsl`.`foto` (`foto_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

insert into acesso(ace_descricao)values('Admin'),('Common User');
insert into usuario(usu_nome, usu_login, usu_senha, usu_email, usu_data_nascimento, usu_sexo, ace_id)
values('Admin 01', 'admin', md5('12345678'), 'admin@admin.com', '2016-07-07 00:00:00', 'M', 1),
('Usuario 01', 'user01', md5('12345678'), 'user@user.com', '2016-07-07 00:00:00', 'M', 2);

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
