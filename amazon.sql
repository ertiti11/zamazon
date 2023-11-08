CREATE DATABASE  IF NOT EXISTS `amazon` /*!40100 DEFAULT CHARACTER SET utf8mb3 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `amazon`;
-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: amazon
-- ------------------------------------------------------
-- Server version	8.0.35

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

--
-- Table structure for table `cestas`
--

DROP TABLE IF EXISTS `cestas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cestas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(8) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `precioTotal` float DEFAULT '9999.99',
  PRIMARY KEY (`id`),
  CONSTRAINT `cestas_chk_1` CHECK (((`precioTotal` >= 0) and (`precioTotal` <= 9999.99)))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cestas`
--

LOCK TABLES `cestas` WRITE;
/*!40000 ALTER TABLE `cestas` DISABLE KEYS */;
/*!40000 ALTER TABLE `cestas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `producto`
--

DROP TABLE IF EXISTS `producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `producto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre_producto` varchar(40) NOT NULL,
  `precio` float NOT NULL,
  `descripcion` text,
  `cantidad` int DEFAULT NULL,
  `imagen` text,
  PRIMARY KEY (`id`),
  CONSTRAINT `producto_chk_1` CHECK ((`precio` >= 0)),
  CONSTRAINT `producto_chk_2` CHECK ((`cantidad` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `producto`
--

LOCK TABLES `producto` WRITE;
/*!40000 ALTER TABLE `producto` DISABLE KEYS */;
INSERT INTO `producto` VALUES (1,'recoge cacas 3000',999,'recoge cacas como nadie, agachate y recogelas como se debe',213,'carpeta_destino/51FmkLVmmQL._AC_UF894,1000_QL80_.jpg'),(2,'recoge cacas 3000',999,'recoge cacas como nadie, agachate y recogelas como se debe',213,'./images/51FmkLVmmQL._AC_UF894,1000_QL80_.jpg'),(3,'el saca penes',123141,'sacate el pene',1233,'./images/265px-Olavinlinna2.jpg');
/*!40000 ALTER TABLE `producto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productoscestas`
--

DROP TABLE IF EXISTS `productoscestas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productoscestas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idCesta` int NOT NULL,
  `idProducto` int NOT NULL,
  `cantidad` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idCesta` (`idCesta`),
  KEY `idProducto` (`idProducto`),
  CONSTRAINT `productoscestas_ibfk_1` FOREIGN KEY (`idCesta`) REFERENCES `cestas` (`id`),
  CONSTRAINT `productoscestas_ibfk_2` FOREIGN KEY (`idProducto`) REFERENCES `producto` (`id`),
  CONSTRAINT `productoscestas_chk_1` CHECK ((`cantidad` <= 10))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productoscestas`
--

LOCK TABLES `productoscestas` WRITE;
/*!40000 ALTER TABLE `productoscestas` DISABLE KEYS */;
/*!40000 ALTER TABLE `productoscestas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(12) NOT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `usuarios_chk_1` CHECK ((length(`usuario`) between 4 and 12)),
  CONSTRAINT `usuarios_chk_2` CHECK ((length(`contrasena`) <= 255))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'titi','123456789','2003-01-22'),(2,'titi','123456789','2003-01-22'),(3,'adri','123456789','2003-01-22'),(4,'admin','123456789','1111-11-11'),(5,'admin','$2y$10$BFF2xH0cqO0PTdKIR.3PX.uA.7NzZZTPZyd2xS7hWOstx8UBgVpAS','1111-11-11'),(6,'test','$2y$10$gnzFkQR43Wr6o8.NrYr/bOqrVldU1qrFK7Yf9xJi429hdJB0UXdvC','0123-03-12');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-11-08 14:57:33
