CREATE DATABASE  IF NOT EXISTS `santafe` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `santafe`;
-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: localhost    Database: santafe
-- ------------------------------------------------------
-- Server version	9.5.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '2b9f1c4c-f49b-11f0-9172-18c04da8b6b8:1-625';

--
-- Table structure for table `cita`
--

DROP TABLE IF EXISTS `cita`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cita` (
  `id_cita` int NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` varchar(15) NOT NULL,
  `id_usuario` int NOT NULL,
  `id_medico` int NOT NULL,
  `id_servicio` int NOT NULL,
  `id_especialidad` int NOT NULL,
  `id_estado` int NOT NULL,
  PRIMARY KEY (`id_cita`),
  KEY `especialidad` (`id_especialidad`),
  KEY `estado_cita` (`id_estado`),
  KEY `servicio` (`id_servicio`),
  KEY `usuario` (`id_usuario`),
  KEY `medico` (`id_medico`),
  CONSTRAINT `especialidad` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `estado_cita` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `medico` FOREIGN KEY (`id_medico`) REFERENCES `usuario` (`id_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `servicio` FOREIGN KEY (`id_servicio`) REFERENCES `servicio` (`id_servicio`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cita`
--

LOCK TABLES `cita` WRITE;
/*!40000 ALTER TABLE `cita` DISABLE KEYS */;
INSERT INTO `cita` VALUES (1,'2024-01-15','09:00',23,3,1,1,5),(2,'2024-03-10','14:00',23,7,6,3,5),(3,'2024-06-20','10:00',23,15,5,2,5),(4,'2024-02-18','08:00',24,8,7,4,5),(5,'2024-05-22','11:00',24,12,5,2,5),(6,'2024-08-14','16:00 ',24,4,1,1,4),(7,'2024-03-25','10:00',25,5,11,8,5),(8,'2024-07-10','09:00',25,14,5,2,5),(9,'2024-11-08','15:00',25,18,9,6,3),(10,'2024-01-30','10:00',26,9,1,1,5),(11,'2024-04-15','14:00 ',26,16,6,3,5),(12,'2024-09-20','08:00',26,22,13,10,5),(13,'2024-02-12','11:00',27,6,8,5,5),(14,'2024-06-18','16:00',27,13,9,6,5),(15,'2024-10-25','09:00',27,19,13,10,4),(16,'2024-03-08','13:00',28,10,4,1,5),(17,'2024-07-22','15:00',28,17,9,6,5),(18,'2024-12-05','10:00',28,11,8,5,5),(19,'2024-04-10','08:00',29,20,12,9,5),(20,'2024-08-28','14:00',29,3,1,1,5),(21,'2024-11-12','09:00',29,15,11,8,4),(22,'2024-05-14','09:00',30,21,10,7,5),(23,'2024-09-05','11:00',30,8,7,4,5),(24,'2024-12-18','16:00',30,16,6,3,5),(25,'2025-01-20','08:00 ',31,12,5,2,3),(26,'2025-03-15','10:00',31,7,6,3,3),(27,'2025-05-22','14:00',31,19,13,10,3),(28,'2025-02-10','09:00',32,5,11,8,3),(29,'2025-04-18','16:00',32,14,5,2,3),(30,'2025-07-25','11:00',32,22,13,10,3),(31,'2025-01-28','15:00',33,18,9,6,3),(32,'2025-03-30','08:00',33,6,8,5,3),(33,'2025-06-12','16:00',33,13,9,6,3),(34,'2025-02-25','10:00',34,17,9,6,3),(35,'2025-04-08','08:00',34,10,4,1,3),(36,'2025-08-14','11:00',34,20,12,9,3),(37,'2025-03-12','09:00',35,11,8,5,3),(38,'2025-05-20','11:00',35,21,10,7,3),(39,'2025-09-18','15:00',35,4,1,1,3),(40,'2025-01-15','11:00',36,9,1,1,3),(41,'2025-04-22','08:00',36,15,11,8,3),(42,'2025-07-30','14:00',36,16,6,3,3),(43,'2024-01-08','10:00',24,3,1,1,5),(44,'2024-02-22','15:00',25,8,7,4,5),(45,'2024-03-18','11:00',26,12,5,2,5),(46,'2024-04-25','16:00',27,17,9,6,5),(47,'2024-05-30','11:00',28,21,10,7,5),(48,'2024-06-14','9:00',29,6,8,5,5),(49,'2024-07-08','11:00',30,13,9,6,5),(50,'2025-08-31','10:00',23,13,9,6,3),(51,'2025-09-22','11:00',24,5,11,8,3),(52,'2025-10-18','11:00',25,20,12,9,4),(53,'2025-11-25','16:00',26,22,13,10,3),(54,'2025-12-12','08:00',27,14,5,2,3),(55,'2025-08-28','15:00',23,21,2,10,4),(56,'2025-08-27','10:00',23,20,12,9,3),(61,'2025-08-29','10:00',36,5,4,1,3),(62,'2025-08-29','14:00',36,5,4,1,3);
/*!40000 ALTER TABLE `cita` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `enfermedad`
--

DROP TABLE IF EXISTS `enfermedad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `enfermedad` (
  `id_enfermedad` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_enfermedad`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enfermedad`
--

LOCK TABLES `enfermedad` WRITE;
/*!40000 ALTER TABLE `enfermedad` DISABLE KEYS */;
INSERT INTO `enfermedad` VALUES (1,'Gripe'),(2,'Diabetes'),(3,'Hipertensión'),(4,'Asma'),(5,'COVID-19'),(6,'Artritis'),(7,'Migraña'),(8,'Bronquitis'),(9,'Gastritis'),(10,'Anemia'),(11,'Hepatitis A'),(12,'Hepatitis B'),(13,'Tétanos'),(14,'Tosferina'),(15,'Sarampión'),(16,'Rubéola'),(17,'Varicela'),(18,'Rotavirus'),(19,'Neumococo'),(20,'Meningitis'),(21,'VPH');
/*!40000 ALTER TABLE `enfermedad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `especialidad`
--

DROP TABLE IF EXISTS `especialidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `especialidad` (
  `id_especialidad` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_especialidad`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `especialidad`
--

LOCK TABLES `especialidad` WRITE;
/*!40000 ALTER TABLE `especialidad` DISABLE KEYS */;
INSERT INTO `especialidad` VALUES (1,'Pediatría'),(2,'Medicina Interna'),(3,'Cardiología'),(4,'Neumología'),(5,'Gastroenterología'),(6,'Neurología'),(7,'Reumatología'),(8,'Endocrinología'),(9,'Medicina General'),(10,'Psicología');
/*!40000 ALTER TABLE `especialidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `esquema_vacunacion`
--

DROP TABLE IF EXISTS `esquema_vacunacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `esquema_vacunacion` (
  `id_esquema` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id_esquema`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `esquema_vacunacion`
--

LOCK TABLES `esquema_vacunacion` WRITE;
/*!40000 ALTER TABLE `esquema_vacunacion` DISABLE KEYS */;
INSERT INTO `esquema_vacunacion` VALUES (1,'Única dosis'),(2,'Dosis inicial'),(3,'Dosis refuerzo'),(4,'Dosis anual'),(5,'Dosis cada 10 años'),(6,'Primera dosis'),(7,'Segunda dosis'),(8,'Tercera dosis');
/*!40000 ALTER TABLE `esquema_vacunacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado`
--

DROP TABLE IF EXISTS `estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado` (
  `id_estado` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado`
--

LOCK TABLES `estado` WRITE;
/*!40000 ALTER TABLE `estado` DISABLE KEYS */;
INSERT INTO `estado` VALUES (1,'Activo'),(2,'Inactivo'),(3,'Pendiente'),(4,'Suspendida'),(5,'Completada');
/*!40000 ALTER TABLE `estado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado_civil`
--

DROP TABLE IF EXISTS `estado_civil`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado_civil` (
  `id_estado_civil` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id_estado_civil`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado_civil`
--

LOCK TABLES `estado_civil` WRITE;
/*!40000 ALTER TABLE `estado_civil` DISABLE KEYS */;
INSERT INTO `estado_civil` VALUES (1,'Soltero'),(2,'Casado'),(3,'Divorciado'),(4,'Viudo'),(5,'Unión libre'),(6,'Separado');
/*!40000 ALTER TABLE `estado_civil` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expediente`
--

DROP TABLE IF EXISTS `expediente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expediente` (
  `id_expediente` int NOT NULL AUTO_INCREMENT,
  `id_usuario` int NOT NULL,
  `peso` varchar(10) DEFAULT NULL,
  `altura` varchar(10) DEFAULT NULL,
  `tipo_sangre` varchar(5) DEFAULT NULL,
  `enfermedades` text,
  `alergias` text,
  `cirugias` text,
  PRIMARY KEY (`id_expediente`),
  UNIQUE KEY `unique_usuario` (`id_usuario`),
  CONSTRAINT `expediente_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expediente`
--

LOCK TABLES `expediente` WRITE;
/*!40000 ALTER TABLE `expediente` DISABLE KEYS */;
INSERT INTO `expediente` VALUES (1,23,'72kg','1.75m','O+','Hipertensión','Ninguna','Apendicectomía'),(2,24,'80kg','1.80m','A+','Diabetes tipo 2','Penicilina','Ninguna'),(3,25,'68kg','1.70m','B-','Ninguna','Mariscos','Fractura pierna'),(4,26,'85kg','1.78m','AB+','Asma','Ninguna','Colecistectomía'),(5,27,'60kg','1.65m','O-','Ninguna','Polvo','Cesárea'),(6,28,'70kg','1.72m','A-','Migrañas','Ninguna','Ninguna'),(7,29,'58kg','1.60m','O+','Hipotiroidismo','Gluten','Ninguna'),(8,30,'62kg','1.63m','B+','Ninguna','Polen','Apendicectomía'),(9,31,'75kg','1.82m','O+','Colesterol alto','Ninguna','Ninguna'),(10,32,'90kg','1.85m','AB-','Hipertensión','Ninguna','Hernia'),(11,33,'78kg','1.77m','A+','Ninguna','Ninguna','Ninguna'),(12,34,'55kg','1.62m','O-','Asma','Ácaros','Ninguna'),(13,35,'82kg','1.74m','B+','Artritis','Ninguna','Bypass coronario'),(14,36,'68kg','1.68m','O+','Ninguna','Lácteos','Ninguna');
/*!40000 ALTER TABLE `expediente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forma_farmaceutica`
--

DROP TABLE IF EXISTS `forma_farmaceutica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forma_farmaceutica` (
  `id_forma_farmaceutica` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_forma_farmaceutica`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forma_farmaceutica`
--

LOCK TABLES `forma_farmaceutica` WRITE;
/*!40000 ALTER TABLE `forma_farmaceutica` DISABLE KEYS */;
INSERT INTO `forma_farmaceutica` VALUES (1,'Tableta'),(2,'Cápsula'),(3,'Jarabe'),(4,'Inyectable'),(5,'Crema'),(6,'Supositorio'),(7,'Spray nasal');
/*!40000 ALTER TABLE `forma_farmaceutica` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `genero`
--

DROP TABLE IF EXISTS `genero`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `genero` (
  `id_genero` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id_genero`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genero`
--

LOCK TABLES `genero` WRITE;
/*!40000 ALTER TABLE `genero` DISABLE KEYS */;
INSERT INTO `genero` VALUES (1,'Masculino'),(2,'Femenino'),(3,'No binario'),(4,'Prefiere no decirlo'),(5,'Otro');
/*!40000 ALTER TABLE `genero` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo_terapeutico`
--

DROP TABLE IF EXISTS `grupo_terapeutico`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupo_terapeutico` (
  `id_grupo_farmaceutico` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_grupo_farmaceutico`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_terapeutico`
--

LOCK TABLES `grupo_terapeutico` WRITE;
/*!40000 ALTER TABLE `grupo_terapeutico` DISABLE KEYS */;
INSERT INTO `grupo_terapeutico` VALUES (1,'Analgésicos'),(2,'Antibióticos'),(3,'Antiinflamatorios'),(4,'Antihipertensivos'),(5,'Antidiabéticos'),(6,'Antivirales'),(7,'Broncodilatadores'),(8,'Antidepresivos'),(9,'Antialérgicos'),(10,'Gastroprotectores');
/*!40000 ALTER TABLE `grupo_terapeutico` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medicamento`
--

DROP TABLE IF EXISTS `medicamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medicamento` (
  `id_medicamento` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `id_forma_farmaceutica` int NOT NULL,
  `id_grupo_terapeutico` int NOT NULL,
  `id_via_administracion` int NOT NULL,
  `id_estado` int NOT NULL,
  PRIMARY KEY (`id_medicamento`),
  KEY `forma_farmaceutica` (`id_forma_farmaceutica`),
  KEY `grupo_terapeutico` (`id_grupo_terapeutico`),
  KEY `via_administracion_medicamento` (`id_via_administracion`),
  KEY `estado_medicmento` (`id_estado`),
  CONSTRAINT `estado_medicmento` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `forma_farmaceutica` FOREIGN KEY (`id_forma_farmaceutica`) REFERENCES `forma_farmaceutica` (`id_forma_farmaceutica`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `grupo_terapeutico` FOREIGN KEY (`id_grupo_terapeutico`) REFERENCES `grupo_terapeutico` (`id_grupo_farmaceutico`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `via_administracion` FOREIGN KEY (`id_via_administracion`) REFERENCES `via_administracion` (`id_via_administracion`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medicamento`
--

LOCK TABLES `medicamento` WRITE;
/*!40000 ALTER TABLE `medicamento` DISABLE KEYS */;
INSERT INTO `medicamento` VALUES (1,'Paracetamol 500mg',1,1,1,1),(2,'Ibuprofeno 400mg',1,1,1,1),(3,'Aspirina 100mg',1,1,1,1),(4,'Diclofenaco gel',5,1,5,1),(5,'Amoxicilina 500mg',2,2,1,1),(6,'Ciprofloxacino 250mg',1,2,1,1),(7,'Penicilina G',4,2,2,1),(8,'Eritromicina pomada',5,2,5,1),(9,'Prednisolona 5mg',1,3,1,1),(10,'Dexametasona',4,3,2,1),(11,'Hidrocortisona crema',5,3,5,1),(12,'Enalapril 10mg',1,4,1,1),(13,'Losartán 50mg',1,4,1,1),(14,'Amlodipino 5mg',1,4,1,1),(15,'Metformina 850mg',1,5,1,1),(16,'Glibenclamida 5mg',1,5,1,1),(17,'Insulina NPH',4,5,4,1),(18,'Aciclovir 400mg',1,6,1,1),(19,'Aciclovir crema',5,6,5,1),(20,'Salbutamol inhalador',7,7,6,1),(21,'Teofilina 200mg',1,7,1,1),(22,'Sertralina 50mg',1,8,1,1),(23,'Fluoxetina 20mg',2,8,1,1),(24,'Loratadina 10mg',1,9,1,1),(25,'Cetirizina jarabe',3,9,1,1),(26,'Beclometasona spray',7,9,9,1),(27,'Omeprazol 20mg',2,10,1,1),(28,'Ranitidina 150mg',1,10,1,1),(29,'Sucralfato suspensión',3,10,1,1);
/*!40000 ALTER TABLE `medicamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medicamento_paciente`
--

DROP TABLE IF EXISTS `medicamento_paciente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medicamento_paciente` (
  `id_medicamento_paciente` int NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(100) NOT NULL,
  `fecha_preescripcion` date NOT NULL,
  `tiempo_tratamiento` varchar(100) NOT NULL,
  `indicaciones` varchar(250) NOT NULL,
  `id_estado` int NOT NULL,
  `id_medicamento` int NOT NULL,
  `id_paciente` int NOT NULL,
  PRIMARY KEY (`id_medicamento_paciente`),
  KEY `estado_medicmento_paciente` (`id_estado`),
  KEY `medicamento_paciente` (`id_medicamento`),
  KEY `medicamento_usuario` (`id_paciente`),
  CONSTRAINT `estado_medicmento_paciente` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `medicamento_paciente` FOREIGN KEY (`id_medicamento`) REFERENCES `medicamento` (`id_medicamento`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `medicamento_usuario` FOREIGN KEY (`id_paciente`) REFERENCES `usuario` (`id_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medicamento_paciente`
--

LOCK TABLES `medicamento_paciente` WRITE;
/*!40000 ALTER TABLE `medicamento_paciente` DISABLE KEYS */;
INSERT INTO `medicamento_paciente` VALUES (1,'José Ramírez Quesada','2025-08-19','5 días cada 8 horas','Tomar con alimentos para dolor de cabeza y fiebre',1,1,23),(2,'José Ramírez Quesada','2024-03-10','7 días cada 12 horas','Tratamiento antibiótico para infección respiratoria',2,5,23),(3,'Miguel Sandoval Torres','2024-02-20','3 días cada 6 horas','Para dolor muscular y inflamación',2,2,24),(4,'Miguel Sandoval Torres','2024-05-15','Tratamiento crónico diario','Control de hipertensión arterial',1,12,24),(5,'Carlos Méndez Brenes','2024-01-25','Según necesidad','Prevención cardiovascular, tomar con agua',1,3,25),(6,'Carlos Méndez Brenes','2024-06-20','Tratamiento crónico','Control de diabetes tipo 2',1,15,25),(7,'Fernando Castro Elizondo','2024-03-12','Aplicar 3 veces al día','Gel antiinflamatorio para lesión deportiva',2,4,26),(8,'Fernando Castro Elizondo','2024-04-18','10 días cada 8 horas','Antibiótico para infección bacteriana',2,7,26),(9,'María Córdoba Salas','2024-02-14','5 días cada 12 horas','Antibiótico para infección urinaria',2,6,27),(10,'María Córdoba Salas','2024-07-22','Según prescripción médica','Corticoide para proceso inflamatorio',2,9,27),(11,'Ana Montero Villalobos','2025-08-17','Aplicar 2 veces al día','Antibiótico tópico para infección cutánea',1,8,28),(12,'Ana Montero Villalobos','2024-08-15','Tratamiento crónico','Control de hipertensión arterial',1,13,28),(13,'Lucía Araya Fonseca','2024-04-12','Según indicación médica','Corticoide inyectable para alergia severa',2,10,29),(14,'Lucía Araya Fonseca','2024-09-10','Tratamiento crónico','Control de diabetes con dieta',1,16,29),(15,'Patricia Morales Jiménez','2024-05-18','Aplicar según necesidad','Crema corticoide para dermatitis',2,11,30),(16,'Patricia Morales Jiménez','2024-10-25','5 días cada 24 horas','Antiviral para herpes labial',2,18,30),(17,'Alex Rivera Campos','2024-06-14','Una vez al día','Control de presión arterial',1,14,31),(18,'Alex Rivera Campos','2024-11-08','Tratamiento crónico','Broncodilatador para asma',1,21,31),(19,'Sam Delgado Núñez','2024-07-20','Según indicación endocrina','Insulina para diabetes tipo 1',1,17,32),(20,'Sam Delgado Núñez','2024-12-15','Tratamiento crónico','Antidepresivo para depresión mayor',2,22,32),(21,'Diego Chacón Madrigal','2024-08-25','Aplicar según necesidad','Crema antiviral para herpes',2,19,33),(22,'Diego Chacón Madrigal','2025-01-12','Tratamiento crónico','Antidepresivo ISRS para ansiedad',1,23,33),(23,'Sofía Picado Rojas','2024-09-30','Inhalador según necesidad','Broncodilatador para asma bronquial',1,20,34),(24,'Sofía Picado Rojas','2025-02-18','Tratamiento crónico','Antihistamínico para rinitis alérgica',1,24,34),(25,'Eduardo Blanco Cordero','2024-10-15','Según indicación médica','Antihistamínico para urticaria crónica',1,25,35),(26,'Eduardo Blanco Cordero','2025-03-22','Según necesidad','Analgésico para dolor articular',2,1,35),(27,'Elena Vargas Trejos','2024-11-28','3 días cada 8 horas','Antiinflamatorio para dolor menstrual',2,2,36),(28,'Elena Vargas Trejos','2025-04-10','7 días cada 12 horas','Antibiótico para cistitis recurrente',1,6,36);
/*!40000 ALTER TABLE `medicamento_paciente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medico_especialidad`
--

DROP TABLE IF EXISTS `medico_especialidad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `medico_especialidad` (
  `id_medico_especialidad` int NOT NULL AUTO_INCREMENT,
  `id_medico` int NOT NULL,
  `id_especialidad` int NOT NULL,
  `id_estado` int NOT NULL,
  PRIMARY KEY (`id_medico_especialidad`),
  KEY `medico_especialidad` (`id_medico`),
  KEY `especialidad_medico` (`id_especialidad`),
  KEY `medico_especialidad_estado` (`id_estado`),
  CONSTRAINT `especialidad_medico` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidad` (`id_especialidad`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `medico_especialidad` FOREIGN KEY (`id_medico`) REFERENCES `usuario` (`id_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `medico_especialidad_estado` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medico_especialidad`
--

LOCK TABLES `medico_especialidad` WRITE;
/*!40000 ALTER TABLE `medico_especialidad` DISABLE KEYS */;
INSERT INTO `medico_especialidad` VALUES (1,3,2,1),(2,4,2,1),(3,5,1,1),(4,6,1,1),(5,7,3,1),(6,8,3,1),(7,9,4,1),(8,10,4,1),(9,11,5,1),(10,12,5,1),(11,13,6,1),(12,14,6,1),(13,15,7,1),(14,16,7,1),(15,17,8,1),(16,18,8,1),(17,19,9,1),(18,20,9,1),(19,21,10,1),(20,22,10,1);
/*!40000 ALTER TABLE `medico_especialidad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rol` (
  `id_rol` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `id_estado` int NOT NULL,
  PRIMARY KEY (`id_rol`),
  KEY `estado_rol` (`id_estado`),
  CONSTRAINT `estado_rol` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'Administrador','Administrador del sistema con acceso completo a todas las funcionalidades.',1),(2,'Medico','Médico profesional.',1),(3,'Paciente','Usuario paciente.',1),(4,'Secretaria','Secretaria de la clínica',2);
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `servicio`
--

DROP TABLE IF EXISTS `servicio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `servicio` (
  `id_servicio` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_servicio`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `servicio`
--

LOCK TABLES `servicio` WRITE;
/*!40000 ALTER TABLE `servicio` DISABLE KEYS */;
INSERT INTO `servicio` VALUES (1,'Emergencias'),(2,'Hospitalización'),(3,'Cuidados Intensivos'),(4,'Pediatría'),(5,'Medicina Interna'),(6,'Cardiología'),(7,'Neumología'),(8,'Gastroenterología'),(9,'Neurología'),(10,'Reumatología'),(11,'Endocrinología'),(12,'Medicina General'),(13,'Psicología');
/*!40000 ALTER TABLE `servicio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `cedula_usuario` varchar(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidos` varchar(100) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `direccion` varchar(200) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `id_genero` int DEFAULT NULL,
  `id_estado_civil` int DEFAULT NULL,
  `id_rol` int NOT NULL,
  `id_estado` int NOT NULL,
  PRIMARY KEY (`id_usuario`),
  KEY `estado_usuario` (`id_estado`),
  KEY `estado_civil` (`id_estado_civil`),
  KEY `genero` (`id_genero`),
  KEY `rol` (`id_rol`),
  CONSTRAINT `estado_civil` FOREIGN KEY (`id_estado_civil`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `estado_usuario` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `genero` FOREIGN KEY (`id_genero`) REFERENCES `genero` (`id_genero`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `rol` FOREIGN KEY (`id_rol`) REFERENCES `rol` (`id_rol`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'123456789','Carlos','Rodríguez Pérez','carlos.admin@hospital.com','22345678',NULL,'San José, Costa Rica','admin123',NULL,NULL,1,1),(2,'987654321','María','González López','maria.admin@hospital.com','22876543',NULL,'Cartago, Costa Rica','admin456',NULL,NULL,1,1),(3,'456789123','Juan','Hernández Mora','juan.hernandez@hospital.com','88234567',NULL,'San José, Escazú','medico123',NULL,NULL,2,1),(4,'789123456','Ana','Vargas Solano','ana.vargas@hospital.com','87654321',NULL,'Cartago, Centro','medico456',NULL,NULL,2,1),(5,'321654987','Dr. Roberto','Jiménez Castro','roberto.jimenez@hospital.com','89123456',NULL,'Alajuela, Centro','medico789',NULL,NULL,2,1),(6,'654987321','Dra. Carmen','Rojas Vega','carmen.rojas@hospital.com','88765432',NULL,'Heredia, San Pablo','medico321',NULL,NULL,2,1),(7,'147258369','Dr. Luis','Fernández Chacón','luis.fernandez@hospital.com','88147258',NULL,'San José, Santa Ana','cardio123',NULL,NULL,2,1),(8,'963852741','Dra. Silvia','Ramírez Gutiérrez','silvia.ramirez@hospital.com','87963852',NULL,'Cartago, Paraíso','cardio456',NULL,NULL,2,1),(9,'258147963','Dr. Mario','Castillo Vega','mario.castillo@hospital.com','89258147',NULL,'Alajuela, San Carlos','neumo123',NULL,NULL,2,1),(10,'741963852','Dra. Patricia','Moreno Sánchez','patricia.moreno@hospital.com','88741963',NULL,'Puntarenas, Esparza','neumo456',NULL,NULL,2,1),(11,'369258147','Dr. Carlos','Delgado Pérez','carlos.delgado@hospital.com','87369258',NULL,'Heredia, Flores','gastro123',NULL,NULL,2,1),(12,'852741963','Dra. Mónica','Arias Campos','monica.arias@hospital.com','89852741',NULL,'Guanacaste, Nicoya','gastro456',NULL,NULL,2,1),(13,'147852963','Dr. Francisco','Solano Miranda','francisco.solano@hospital.com','88147852',NULL,'San José, Moravia','neuro123',NULL,NULL,2,1),(14,'963741852','Dra. Rebeca','Quesada Torres','rebeca.quesada@hospital.com','87963741',NULL,'Limón, Siquirres','neuro456',NULL,NULL,2,1),(15,'258963741','Dr. Gerardo','Brenes Elizondo','gerardo.brenes@hospital.com','89258963',NULL,'Cartago, Turrialba','reuma123',NULL,NULL,2,1),(16,'741852963','Dra. Verónica','Madrigal Rojas','veronica.madrigal@hospital.com','88741852',NULL,'San José, Guadalupe','reuma456',NULL,NULL,2,1),(17,'369741852','Dr. Rafael','Cordero Villalobos','rafael.cordero@hospital.com','87369741',NULL,'Alajuela, Grecia','endo123',NULL,NULL,2,1),(18,'852963741','Dra. Alejandra','Salas Bonilla','alejandra.salas@hospital.com','89852963',NULL,'Heredia, San Rafael','endo456',NULL,NULL,2,1),(19,'147963741','Dr. Esteban','Picado Castro','esteban.picado@hospital.com','88147963',NULL,'Puntarenas, Quepos','general123',NULL,NULL,2,1),(20,'963852147','Dra. Karla','Núñez Fallas','karla.nunez@hospital.com','87963852',NULL,'Guanacaste, Santa Cruz','general456',NULL,NULL,2,1),(21,'258741369','Dr. Daniel','Trejos Monge','daniel.trejos@hospital.com','89258741',NULL,'San José, Pavas','psico123',NULL,NULL,2,1),(22,'741369258','Dra. Adriana','Chaves Alpízar','adriana.chaves@hospital.com','88741369',NULL,'Cartago, La Unión','psico456',NULL,NULL,2,1),(23,'111222333','José','Ramírez Quesada','jose.ramirez@email.com','88111222','1985-03-15','Cartago, Paraíso','paciente123',1,2,3,1),(24,'444555666','Miguel','Sandoval Torres','miguel.sandoval@email.com','87333444','1978-11-22','San José, Desamparados','paciente456',1,1,3,1),(25,'777888999','Carlos','Méndez Brenes','carlos.mendez@email.com','89555666','1992-07-08','Alajuela, San Ramón','paciente789',1,3,3,1),(26,'101112131','Fernando','Castro Elizondo','fernando.castro@email.com','88777888','1965-12-03','Puntarenas, Centro','paciente321',1,4,3,1),(27,'222333444','María','Córdoba Salas','maria.cordoba@email.com','88222333','1990-08-25','Cartago, La Unión','paciente654',2,2,3,1),(28,'555666777','Ana','Montero Villalobos','ana.montero@email.com','87444555','1975-01-18','Heredia, Barva','paciente987',2,1,3,1),(29,'888999000','Lucía','Araya Fonseca','lucia.araya@email.com','89666777','1988-09-12','Guanacaste, Liberia','paciente147',2,5,3,1),(30,'141516171','Patricia','Morales Jiménez','patricia.morales@email.com','88888999','1995-04-30','Limón, Puerto Viejo','paciente246',2,3,3,1),(31,'181920212','Alex','Rivera Campos','alex.rivera@email.com','89000111','1993-06-14','San José, Tibás','paciente369',3,1,3,1),(32,'223242526','Sam','Delgado Núñez','sam.delgado@email.com','88123456','1987-10-07','Cartago, Turrialba','paciente741',4,2,3,1),(33,'272829303','Diego','Chacón Madrigal','diego.chacon@email.com','87987654','2000-02-20','Alajuela, Atenas','paciente852',1,1,3,1),(34,'313233343','Sofía','Picado Rojas','sofia.picado@email.com','89234567','1999-12-10','Heredia, Santo Domingo','paciente745',2,2,3,1),(35,'353637383','Eduardo','Blanco Cordero','eduardo.blanco@email.com','88345678','1950-05-25','San José, Curridabat','paciente413',1,4,3,1),(36,'394041424','Elena','Vargas Trejos','elena.vargas@email.com','87456789','1948-03-08','Cartago, Oreamuno','paciente185',2,4,3,1),(37,'305550982','Kenneth','Gómez Calderón','kenneth.gomez@gmail.com','647589314','2004-11-30','Cartago, El Alto, San Rafael','admin747',1,2,1,1);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacuna`
--

DROP TABLE IF EXISTS `vacuna`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vacuna` (
  `id_vacuna` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `id_enfermedad` int NOT NULL,
  `id_esquema_vacunacion` int NOT NULL,
  `id_via_administracion` int NOT NULL,
  `id_estado` int NOT NULL,
  PRIMARY KEY (`id_vacuna`),
  KEY `enfermedad` (`id_enfermedad`),
  KEY `esquema_vacunacion` (`id_esquema_vacunacion`),
  KEY `estado_vacuna` (`id_estado`),
  KEY `via_administracion_vacuna` (`id_via_administracion`),
  CONSTRAINT `enfermedad` FOREIGN KEY (`id_enfermedad`) REFERENCES `enfermedad` (`id_enfermedad`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `esquema_vacunacion` FOREIGN KEY (`id_esquema_vacunacion`) REFERENCES `esquema_vacunacion` (`id_esquema`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `estado_vacuna` FOREIGN KEY (`id_estado`) REFERENCES `estado` (`id_estado`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `via_administracion_vacuna` FOREIGN KEY (`id_via_administracion`) REFERENCES `via_administracion` (`id_via_administracion`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacuna`
--

LOCK TABLES `vacuna` WRITE;
/*!40000 ALTER TABLE `vacuna` DISABLE KEYS */;
INSERT INTO `vacuna` VALUES (1,'Vacuna Antigripal Trivalente',1,1,1,1),(2,'Vacuna Antigripal Anual',1,4,3,1),(3,'FluMist Nasal',1,1,9,1),(4,'Pfizer-BioNTech COVID-19',5,2,3,1),(5,'Moderna COVID-19',5,2,3,1),(6,'Refuerzo COVID-19',5,3,3,1),(7,'Johnson & Johnson COVID-19',5,1,3,1),(8,'Havrix Hepatitis A',11,2,3,1),(9,'Vaqta Hepatitis A',11,3,3,1),(10,'Engerix-B Hepatitis B',12,2,3,1),(11,'Recombivax HB',12,3,3,1),(12,'Twinrix (Hepatitis A y B)',12,5,3,1),(13,'Toxoide Tetánico',13,5,3,1),(14,'Td (Tétanos-Difteria)',13,5,3,1),(15,'Tdap',13,6,3,1),(16,'DTaP',14,6,3,1),(17,'Tdap Tosferina',14,7,3,1),(18,'MMR (Triple Viral)',15,6,4,1),(19,'MMR Segunda dosis',15,7,4,1),(20,'MMR Rubéola',16,6,4,1),(21,'Vacuna Rubéola',16,1,4,1),(22,'Varivax',17,6,4,1),(23,'Varicela Segunda dosis',17,7,4,1),(24,'RotaTeq',18,6,1,1),(25,'Rotarix',18,7,1,1),(26,'Rotavirus Tercera',18,8,1,1),(27,'Prevnar 13',19,6,3,1),(28,'Pneumovax 23',19,1,3,1),(29,'Menactra',20,6,3,1),(30,'Bexsero',20,7,3,1),(31,'Gardasil 9',21,6,3,1),(32,'Gardasil Segunda',21,7,3,1),(33,'Cervarix',21,8,3,1);
/*!40000 ALTER TABLE `vacuna` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vacuna_paciente`
--

DROP TABLE IF EXISTS `vacuna_paciente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vacuna_paciente` (
  `id_vacuna_paciente` int NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(150) NOT NULL,
  `fecha_vacunacion` date NOT NULL,
  `tiempo_tratamiento` varchar(100) NOT NULL,
  `dosis` varchar(50) NOT NULL,
  `descripcion` varchar(150) NOT NULL,
  `id_usuario` int NOT NULL,
  `id_vacuna` int NOT NULL,
  PRIMARY KEY (`id_vacuna_paciente`),
  KEY `usuario_vacuna` (`id_usuario`),
  KEY `vacuna_paciente` (`id_vacuna`),
  CONSTRAINT `usuario_vacuna` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `vacuna_paciente` FOREIGN KEY (`id_vacuna`) REFERENCES `vacuna` (`id_vacuna`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vacuna_paciente`
--

LOCK TABLES `vacuna_paciente` WRITE;
/*!40000 ALTER TABLE `vacuna_paciente` DISABLE KEYS */;
INSERT INTO `vacuna_paciente` VALUES (1,'José Ramírez Quesada','2024-01-15','1 dosis','0.5ml','Primera dosis Pfizer-BioNTech COVID-19',23,4),(2,'José Ramírez Quesada','2024-06-10','1 dosis','0.6ml','Vacuna MMR (Triple Viral)',23,18),(3,'Miguel Sandoval Torres','2024-02-20','1 dosis','0.5ml','Vacuna Moderna COVID-19',24,5),(4,'Miguel Sandoval Torres','2024-07-15','3 dosis','1.0ml','Vacuna Havrix Hepatitis A',24,8),(5,'Carlos Méndez Brenes','2024-03-10','1 dosis','0.5ml','Refuerzo COVID-19',25,6),(6,'Carlos Méndez Brenes','2024-08-05','1 dosis','0.5ml','Vacuna Prevnar 13',25,27),(7,'Fernando Castro Elizondo','2024-01-25','1 dosis','0.5ml','Johnson & Johnson COVID-19',26,7),(8,'Fernando Castro Elizondo','2024-09-12','1 dosis','0.5ml','Vacuna Tdap',26,15),(9,'María Córdoba Salas','2024-02-14','1 dosis','1.0ml','Vacuna Vaqta Hepatitis A',27,9),(10,'María Córdoba Salas','2024-10-08','1 dosis','0.5ml','Vacuna Rubéola',27,21),(11,'Ana Montero Villalobos','2024-03-22','1 dosis','1.0ml','Vacuna Engerix-B Hepatitis B',28,10),(12,'Ana Montero Villalobos','2024-11-15','1 dosis','0.5ml','Vacuna Varivax',28,22),(13,'Lucía Araya Fonseca','2024-04-18','1 dosis','1.0ml','Vacuna Recombivax HB',29,11),(14,'Lucía Araya Fonseca','2024-12-20','2 dosis','0.5ml','Varicela Segunda dosis',29,23),(15,'Patricia Morales Jiménez','2024-05-12','1 dosis','1.0ml','Vacuna Twinrix (Hepatitis A y B)',30,12),(16,'Patricia Morales Jiménez','2025-01-10','1 dosis','0.5ml','Vacuna RotaTeq',30,24),(17,'Alex Rivera Campos','2024-06-08','1 dosis','0.5ml','Vacuna Tóxoide Tetánico',31,13),(18,'Alex Rivera Campos','2025-02-14','1 dosis','2.0ml','Vacuna Rotarix',31,25),(19,'Sam Delgado Núñez','2024-07-25','1 dosis','0.5ml','Vacuna Td (Tétanos-Difteria)',32,14),(20,'Sam Delgado Núñez','2025-03-18','1 dosis','2.0ml','Vacuna Rotavirus Tercera',32,26),(21,'Diego Chacón Madrigal','2024-08-14','1 dosis','0.5ml','Vacuna DTaP',33,16),(22,'Diego Chacón Madrigal','2025-04-22','1 dosis','0.6ml','Vacuna Pneumovax 23',33,28),(23,'Sofía Picado Rojas','2024-09-30','1 dosis','0.5ml','Vacuna Tdap Tosferina',34,17),(24,'Sofía Picado Rojas','2025-05-16','1 dosis','0.5ml','Vacuna Menactra',34,29),(25,'Eduardo Blanco Cordero','2024-10-12','2 dosis','0.5ml','Vacuna MMR Segunda dosis',35,19),(26,'Eduardo Blanco Cordero','2025-06-20','1 dosis','0.5ml','Vacuna Bexsero',35,30),(27,'Elena Vargas Trejos','2024-11-28','1 dosis','0.5ml','Vacuna MMR Rubéola',36,20),(28,'Elena Vargas Trejos','2025-07-08','1 dosis','0.5ml','Vacuna Gardasil 9',36,31);
/*!40000 ALTER TABLE `vacuna_paciente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `via_administracion`
--

DROP TABLE IF EXISTS `via_administracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `via_administracion` (
  `id_via_administracion` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`id_via_administracion`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `via_administracion`
--

LOCK TABLES `via_administracion` WRITE;
/*!40000 ALTER TABLE `via_administracion` DISABLE KEYS */;
INSERT INTO `via_administracion` VALUES (1,'Oral'),(2,'Intravenosa'),(3,'Intramuscular'),(4,'Subcutánea'),(5,'Tópica'),(6,'Inhalatoria'),(7,'Sublingual'),(8,'Intradérmica'),(9,'Intranasal');
/*!40000 ALTER TABLE `via_administracion` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-10 19:33:06
