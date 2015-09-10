-- MySQL dump 10.13  Distrib 5.5.43, for debian-linux-gnu (x86_64)
--
-- Host: db4free.net    Database: grp55twdb
-- ------------------------------------------------------
-- Server version	5.6.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Appartenenza`
--

DROP TABLE IF EXISTS `Appartenenza`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Appartenenza` (
  `idCentro` int(11) DEFAULT NULL,
  `idTecnico` int(11) DEFAULT NULL,
  UNIQUE KEY `index3` (`idCentro`,`idTecnico`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Appartenenza`
--

LOCK TABLES `Appartenenza` WRITE;
/*!40000 ALTER TABLE `Appartenenza` DISABLE KEYS */;
/*!40000 ALTER TABLE `Appartenenza` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Categorie`
--

DROP TABLE IF EXISTS `Categorie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Categorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(45) NOT NULL,
  `Tipo` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Categorie`
--

LOCK TABLES `Categorie` WRITE;
/*!40000 ALTER TABLE `Categorie` DISABLE KEYS */;
INSERT INTO `Categorie` VALUES (2,'Utilitarie','Auto'),(3,'Roadster','Moto'),(4,'Berline','Auto'),(5,'Executive','Auto'),(6,'SUV','Auto'),(7,'Sportive','Moto'),(8,'Urban Mobility','Moto'),(9,'Enduro','Moto');
/*!40000 ALTER TABLE `Categorie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CentriAssistenza`
--

DROP TABLE IF EXISTS `CentriAssistenza`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CentriAssistenza` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(45) NOT NULL,
  `Indirizzo` varchar(250) NOT NULL,
  `Latitudine` varchar(15) DEFAULT NULL,
  `Longitudine` varchar(15) DEFAULT NULL,
  `Telefono` varchar(20) DEFAULT NULL,
  `Mobile` varchar(20) DEFAULT NULL,
  `Fax` varchar(20) DEFAULT NULL,
  `Skype` varchar(30) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Sito` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CentriAssistenza`
--

LOCK TABLES `CentriAssistenza` WRITE;
/*!40000 ALTER TABLE `CentriAssistenza` DISABLE KEYS */;
INSERT INTO `CentriAssistenza` VALUES (9,'CAPPELLO ANTONIO','Via Arceviese, 10 A,  60019 SENIGALLIA','43.679349','13.186988','+390717926033','','','','cappello.antonio@assistenzabmw.it',NULL),(10,'STILE SRL','Via I Maggio, 124,  60131 ANCONA','43.5503435','13.5099147','+390712912511','','','','stilesrl@assistenzabmw.it','http://www.car-point.bmw.it');
/*!40000 ALTER TABLE `CentriAssistenza` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Componenti`
--

DROP TABLE IF EXISTS `Componenti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Componenti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(45) DEFAULT NULL,
  `Descrizione` varchar(45) DEFAULT NULL,
  `Foto` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Componenti`
--

LOCK TABLES `Componenti` WRITE;
/*!40000 ALTER TABLE `Componenti` DISABLE KEYS */;
INSERT INTO `Componenti` VALUES (1,'Motore','Motore di una macchina',NULL),(3,'Freni','Freni bmw','f10m_carbonkeramik_brakes_a0161324_p.JPG.resource.1234.type2.jpg'),(4,'Cinghia distribuzione','Descrizione cinghia distribuzione','Cinghia-di-distribuzione-auto.jpg'),(5,'Tergicristalli','Descrizione tergicristalli','windscreen-wiper.jpg.resource.1381498927128.jpg');
/*!40000 ALTER TABLE `Componenti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Composizione`
--

DROP TABLE IF EXISTS `Composizione`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Composizione` (
  `idProdotto` int(11) DEFAULT NULL,
  `idComponente` int(11) DEFAULT NULL,
  KEY `Prodotto_idx` (`idProdotto`),
  KEY `Componente_idx` (`idComponente`),
  CONSTRAINT `Componente` FOREIGN KEY (`idComponente`) REFERENCES `Componenti` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Prodotto` FOREIGN KEY (`idProdotto`) REFERENCES `Prodotti` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Composizione`
--

LOCK TABLES `Composizione` WRITE;
/*!40000 ALTER TABLE `Composizione` DISABLE KEYS */;
INSERT INTO `Composizione` VALUES (9,1),(13,1),(13,3),(13,4),(15,1),(15,3),(15,4),(16,1),(17,3),(3,1),(3,3),(3,4),(3,5),(8,4),(8,5),(2,1),(2,5);
/*!40000 ALTER TABLE `Composizione` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `FAQ`
--

DROP TABLE IF EXISTS `FAQ`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `FAQ` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Domanda` text,
  `Risposta` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `FAQ`
--

LOCK TABLES `FAQ` WRITE;
/*!40000 ALTER TABLE `FAQ` DISABLE KEYS */;
INSERT INTO `FAQ` VALUES (1,'Faq di prova 1 domandadsfasda','Faq di prova 1 rispostaasdasda'),(2,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean porta elit ac risus pharetra, ac posuere magna mollis. Maecenas finibus orci et gravida porttitor. Pellentesque ut nulla nec orci vehicula.','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur ut tortor interdum, suscipit ligula in, mattis erat. Cras pharetra, orci quis interdum dapibus, justo ipsum laoreet orci, at vulputate tortor eros sed libero. Proin auctor nunc sit amet dolor tempus tincidunt. Suspendisse purus urna, consequat sit amet enim at, commodo aliquam nisl. Praesent ultricies quam id neque faucibus egestas. Praesent nec metus quis magna egestas volutpat quis elementum orci. Integer sit amet dictum massa. Suspendisse lectus ante, ultricies vitae nunc at, cursus molestie odio. Aliquam euismod dui neque, sit amet placerat odio euismod a. Pellentesque ut volutpat urna, ut ullamcorper nisl. Suspendisse potenti. Duis varius id lorem in tempor. Curabitur a accumsan enim. Suspendisse consectetur neque id diam malesuada elementum. Nunc tempus porta nisl. Nam sodales metus sit amet erat laoreet, id interdum felis lobortis.');
/*!40000 ALTER TABLE `FAQ` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Malfunzionamenti`
--

DROP TABLE IF EXISTS `Malfunzionamenti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Malfunzionamenti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Malfunzionamento` text NOT NULL,
  `Soluzione` text NOT NULL,
  `Nome` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Malfunzionamenti`
--

LOCK TABLES `Malfunzionamenti` WRITE;
/*!40000 ALTER TABLE `Malfunzionamenti` DISABLE KEYS */;
INSERT INTO `Malfunzionamenti` VALUES (1,'Malfunzionamento1 aggiorn','Soluzione1 aggiorn','malf1'),(2,'Malffff2','Solllll2','malf2'),(3,'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque fermentum purus id ante blandit cursus. Vivamus ullamcorper efficitur justo in fringilla. Etiam quis condimentum elit, nec dapibus velit. Nullam malesuada odio et sapien varius egestas. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aenean commodo in risus tristique porta. Sed vel dolor mauris. Cras posuere urna mi, ut porttitor purus fermentum ac. Vestibulum lobortis finibus scelerisque. Cras ante augue, commodo eget ex id, aliquam vehicula odio. Aenean a metus et sem blandit lacinia eget nec velit. Nunc malesuada ultrices turpis, nec porttitor libero posuere id.','Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque fermentum purus id ante blandit cursus. Vivamus ullamcorper efficitur justo in fringilla. Etiam quis condimentum elit, nec dapibus velit. Nullam malesuada odio et sapien varius egestas. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aenean commodo in risus tristique porta. Sed vel dolor mauris. Cras posuere urna mi, ut porttitor purus fermentum ac. Vestibulum lobortis finibus scelerisque. Cras ante augue, commodo eget ex id, aliquam vehicula odio. Aenean a metus et sem blandit lacinia eget nec velit. Nunc malesuada ultrices turpis, nec porttitor libero posuere id.','lorem ipsum'),(4,'\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"','\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"','Rottura motore');
/*!40000 ALTER TABLE `Malfunzionamenti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MalfunzionamentiProdotti`
--

DROP TABLE IF EXISTS `MalfunzionamentiProdotti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MalfunzionamentiProdotti` (
  `idProdotto` int(11) NOT NULL,
  `idMalfunzionamento` int(11) NOT NULL,
  KEY `index1` (`idProdotto`,`idMalfunzionamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MalfunzionamentiProdotti`
--

LOCK TABLES `MalfunzionamentiProdotti` WRITE;
/*!40000 ALTER TABLE `MalfunzionamentiProdotti` DISABLE KEYS */;
INSERT INTO `MalfunzionamentiProdotti` VALUES (8,1),(9,1),(9,2);
/*!40000 ALTER TABLE `MalfunzionamentiProdotti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NTBU`
--

DROP TABLE IF EXISTS `NTBU`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NTBU` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(60) NOT NULL,
  `Descrizione` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `NTBU`
--

LOCK TABLES `NTBU` WRITE;
/*!40000 ALTER TABLE `NTBU` DISABLE KEYS */;
INSERT INTO `NTBU` VALUES (1,'Cambiare olioo','Le vetture dotate di motore a benzina necessitano del cambio olio allâ€™incirca ogni 15.000 Km, mentre quelle diesel possono solitamente percorrere  30.000 km prima di intervenire con la sostituzione.');
/*!40000 ALTER TABLE `NTBU` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `NTBUProdotti`
--

DROP TABLE IF EXISTS `NTBUProdotti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `NTBUProdotti` (
  `idNTBU` int(11) DEFAULT NULL,
  `idProdotto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `NTBUProdotti`
--

LOCK TABLES `NTBUProdotti` WRITE;
/*!40000 ALTER TABLE `NTBUProdotti` DISABLE KEYS */;
INSERT INTO `NTBUProdotti` VALUES (1,17),(1,13),(1,8),(1,2);
/*!40000 ALTER TABLE `NTBUProdotti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Prodotti`
--

DROP TABLE IF EXISTS `Prodotti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Prodotti` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(45) DEFAULT NULL,
  `DescrizioneBreve` varchar(250) DEFAULT NULL,
  `DescrizioneLunga` text,
  `Foto` text,
  `idCategoria` int(11) DEFAULT NULL,
  `Cilindrata` int(11) DEFAULT NULL,
  `Potenza` int(11) DEFAULT NULL,
  `MassaVuoto` int(11) DEFAULT NULL,
  `VelocitaMax` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Categoria_idx` (`idCategoria`),
  CONSTRAINT `Categoria` FOREIGN KEY (`idCategoria`) REFERENCES `Categorie` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Prodotti`
--

LOCK TABLES `Prodotti` WRITE;
/*!40000 ALTER TABLE `Prodotti` DISABLE KEYS */;
INSERT INTO `Prodotti` VALUES (2,'M3','BMW M3 Berlina: una sportiva high performance a quattro porte.','descsdfasda','',4,2979,431,1520,250),(3,'520d GT','dasdasd','dasdasdasd','',5,1995,184,1990,215),(7,'Prodotto1','DescBreve p1','DescLunga p2',NULL,NULL,1000,2000,3000,200),(8,'Prod2','Desceb2','asdasd',NULL,NULL,200,12,45,542),(9,'730d','Varianti della quinta serie della BMW Serie 7, autovettura di punta (ammiraglia)della casa automobilistica tedesca BMW','I gruppi ottici anteriori riprendono i motivi stilistici dei fari della E65 post-restyling. Molto lineare e filante anche la fiancata oltre al frontale, con una linea di cintura molto marcata. I gruppi ottici posteriori sono piÃ¹ arrotondati rispetto la serie precedente.\r\n\r\nI livelli di allestimento sono tre: Eletta, Futura ed Eccelsa. Quest\'ultima Ã¨ la piÃ¹ ricca e lussuosa, ma giÃ  la Eletta, possiede una dotazione di serie pressochÃ© infinita, e che comprende per esempio il Driving Dynamic Control (con cui si puÃ² scegliere fra piÃ¹ impostazioni di guida, da quella piÃ¹ confortevole a quella piÃ¹ sportiva), il Dynamic Damper Control (che gestisce i quattro ammortizzatori indipendentemente l\'uno dall\'altro), il CBC, il volante multifunzionale in pelle, il cambio automatico Steptronic a 6 rapporti con comandi al volante, il climatizzatore bi-zona, il controllo di stabilitÃ , l\'Integral Active Steering (gestisce lo sterzo alle ruote posteriori a seconda della velocitÃ , in modo da coadiuvare il lavoro di sterzo di quelle anteriori) e il Brake Energy Regeneration. Quest\'ultimo, giÃ  presente su altri modelli della Casa, rientra nell\'ambito del progetto EfficientDynamics varato dalla BMW per equipaggiare tutti i suoi modelli con tutti i possibili ritrovati tecnologici volti all\'abbattimento dei consumi e dell\'inquinamento e al minor spreco energetico. Tra gli altri accessori di serie vi Ã¨ un sofisticato impianto stereo, la telecamera posteriore per i parcheggi, l\'impianto lavafari, il sensore di perdita pressione degli pneumatici ed il computer di bordo con display da 10\"25.\r\n\r\nDal punto di vista telaistico, la nuova Serie 7 monta sospensioni con struttura in lega di alluminio. Oltre all\'avantreno a quadrilateri trasversali, va segnalato il nuovo retrotreno a V con bracci multipli, una soluzione brevettata dalla stessa BMW. Le versioni a passo lungo, perÃ², montano di serie un retrotreno diverso, di tipo pneumatico ed autolivellante in funzione del carico della vettura.','4-00036270.JPG',5,2993,190,1840,250),(10,'530d Sedan','Realizzata sulla base dellâ€™ammiraglia Serie 7, rispetto al vecchio modello ha una linea piÃ¹ classica ma anche piÃ¹ slanciata e filante, che ne mitiga le imponenti dimensioni','Realizzata sulla base dellâ€™ammiraglia Serie 7, rispetto al vecchio modello ha una linea piÃ¹ classica ma anche piÃ¹ slanciata e filante, che ne mitiga le imponenti dimensioni; tuttavia lâ€™abitabilitÃ  Ã¨ solo discreta, e il divano ospita comodamente solo due persone. Notevoli le qualitÃ  di guida: fra le curve la nuova BMW Serie 5 si muove con sorprendente agilitÃ , e la maneggevolezza viene ulteriormente esaltata se si aggiungono costosi optional tecnologici come le sospensioni elettroniche o le quattro ruote sterzanti. Si deve pagare a parte anche il cambio automatico a 8 marce, ideale complemento del generoso sei cilindri turbodiesel.','model_5series_sedan_530d.jpg',4,2993,180,1720,248),(12,'C Evolution Elettrico','Scooterone','Powered by three lithium ion battery modules plucked from BMW\'s i3 city car concept (each of which incorporates 12 cells), the c Evolution produces the equivalent of 14.75 hp continuously, or a peak of 46.93 horsepower from the swingarm-mounted motor. Interestingly, the die-cast aluminum battery housing acts as a load bearing chassis element, helping keep the bike\'s center of gravity low while also offering protection for the batteries in the event of a crash. A continuously variable transmission directs power to the rear wheel via a belt drive, because the engine\'s quiet operation highlighted the noise created by a traditional chain.','swap.jpg',8,600,14,265,100),(13,'F15 - X5 xDrive35i','La sigla BMW F15 identifica la terza generazione della X5, un\'autovettura SUV di Segmento E prodotta a partire dal 2013 dalla Casa automobilistica tedesca BMW.','La terza generazione del grosso SUV della Casa dell\'Elica viene svelata nel maggio del 2013 con la diffusione in rete delle prima foto ufficiali. La vettura ha fatto il suo ingresso nel listino tedesco a partire al luglio dello stesso anno, mentre nel listino italiano Ã¨ entrata a partire dal mese di settembre. Sempre a settembre, e piÃ¹ precisamente tra il 12 settembre ed il 22, si Ã¨ avuta la presentazione della vettura al Salone di Francoforte.',NULL,4,2979,264,2030,235),(14,'F15 - X5 xDrive35i','La sigla BMW F15 identifica la terza generazione della X5, un\'autovettura SUV di Segmento E prodotta a partire dal 2013 dalla Casa automobilistica tedesca BMW.','La terza generazione del grosso SUV della Casa dell\'Elica viene svelata nel maggio del 2013 con la diffusione in rete delle prima foto ufficiali. La vettura ha fatto il suo ingresso nel listino tedesco a partire al luglio dello stesso anno, mentre nel listino italiano Ã¨ entrata a partire dal mese di settembre. Sempre a settembre, e piÃ¹ precisamente tra il 12 settembre ed il 22, si Ã¨ avuta la presentazione della vettura al Salone di Francoforte.',NULL,4,2979,264,2030,235),(15,'F15 - X5 xDrive35i','La sigla BMW F15 identifica la terza generazione della X5, un\'autovettura SUV di Segmento E prodotta a partire dal 2013 dalla Casa automobilistica tedesca BMW.','La terza generazione del grosso SUV della Casa dell\'Elica viene svelata nel maggio del 2013 con la diffusione in rete delle prima foto ufficiali. La vettura ha fatto il suo ingresso nel listino tedesco a partire al luglio dello stesso anno, mentre nel listino italiano Ã¨ entrata a partire dal mese di settembre. Sempre a settembre, e piÃ¹ precisamente tra il 12 settembre ed il 22, si Ã¨ avuta la presentazione della vettura al Salone di Francoforte.',NULL,4,2979,264,2030,235),(16,'M3','asdasd','asada',NULL,4,111,11,11,11),(17,'asdasd','fasda','asdasda',NULL,9,111,111,11,11);
/*!40000 ALTER TABLE `Prodotti` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `StaffCategorie`
--

DROP TABLE IF EXISTS `StaffCategorie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `StaffCategorie` (
  `idUtente` int(11) DEFAULT NULL,
  `idCategoria` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `StaffCategorie`
--

LOCK TABLES `StaffCategorie` WRITE;
/*!40000 ALTER TABLE `StaffCategorie` DISABLE KEYS */;
INSERT INTO `StaffCategorie` VALUES (12,4),(12,5),(12,3),(12,6),(12,8),(4,3),(4,2);
/*!40000 ALTER TABLE `StaffCategorie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TopCategorie`
--

DROP TABLE IF EXISTS `TopCategorie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TopCategorie` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idTopCategorie_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TopCategorie`
--

LOCK TABLES `TopCategorie` WRITE;
/*!40000 ALTER TABLE `TopCategorie` DISABLE KEYS */;
INSERT INTO `TopCategorie` VALUES (1,'Auto'),(2,'Moto');
/*!40000 ALTER TABLE `TopCategorie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Utenti`
--

DROP TABLE IF EXISTS `Utenti`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Utenti` (
  `idUtenti` int(11) NOT NULL AUTO_INCREMENT,
  `Nome` varchar(45) NOT NULL,
  `Cognome` varchar(45) NOT NULL,
  `Email` varchar(60) NOT NULL,
  `Ruolo` varchar(15) DEFAULT NULL,
  `Username` varchar(25) NOT NULL,
  `Password` varchar(25) NOT NULL,
  PRIMARY KEY (`idUtenti`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Utenti`
--

LOCK TABLES `Utenti` WRITE;
/*!40000 ALTER TABLE `Utenti` DISABLE KEYS */;
INSERT INTO `Utenti` VALUES (1,'admiNome','adminCong','admin@aaa.it','admin','admin','admin'),(2,'Alessandro','Caprarelli','aasd@asda.it','admin','alec93','fede'),(4,'StaffNom','StaffCogn','asdasd@gasd.it','staff','staff','staff'),(7,'tecniaa','sadasd','ddasd@fasd.it','tecnico','tecnicodiioo','asdasda'),(8,'Tec','C','asda@asda.it','tecnico','tecnico','tecnico'),(9,'Tecnico3','Cogn Tecn3','tec3@tec.it','tecnico','tecnico3','tecnico3'),(10,'Staff2','Staff2','staff2@staff.it','staff','staff2','staff2'),(12,'Staff3','Staff3','staff3@staff.it','staff','staff3','staff3');
/*!40000 ALTER TABLE `Utenti` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-04 17:52:31
