-- MySQL dump 10.19  Distrib 10.3.38-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: lebongeek
-- ------------------------------------------------------
-- Server version	10.3.38-MariaDB-0ubuntu0.20.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ad`
--

DROP TABLE IF EXISTS `ad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `price` int(11) NOT NULL,
  `state` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_77E0ED58A76ED395` (`user_id`),
  KEY `IDX_77E0ED5812469DE2` (`category_id`),
  CONSTRAINT `FK_77E0ED5812469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `FK_77E0ED58A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ad`
--

LOCK TABLES `ad` WRITE;
/*!40000 ALTER TABLE `ad` DISABLE KEYS */;
INSERT INTO `ad` VALUES (3,8,3,'Carte Pokémon Rare','Y\'en a pas 10 comme elle, vous devriez vraiment l\'acheter',3999,1,'Marseille','2023-12-21 11:06:20',NULL),(5,10,13,'Jeu Horreur à Arkham','Extension du jeu de plateau Horreur à Arkham',20,2,'Paris','2023-12-21 11:13:05',NULL),(7,6,21,'Borne de jeu d\'arcade','Borne d\'arcade des années 80, entièrement fonctionnel',950,3,'Paris','2023-12-21 11:14:17',NULL),(14,14,4,'Hulk le mec vert','C\'est Hulk, il est vert ',98,2,'Montpellier','2024-01-04 15:59:45',NULL),(15,28,6,'Le titre change','oui',50,1,'Mtp','2024-01-17 11:02:20','2024-01-17 11:19:31'),(16,43,4,'Figurine Spiderman','Une super figurine Spiderman, très collector !',59,1,'Paris','2024-01-27 14:32:46',NULL),(17,43,1,'Jeu The Last Of Us 2 sur PS4','Un magnifique jeu que tout le monde doit faire au moins une fois dans la vie.',30,2,'Paris','2024-01-27 14:36:35',NULL);
/*!40000 ALTER TABLE `ad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name_address` varchar(255) DEFAULT NULL,
  `street_number` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D4E6F81A76ED395` (`user_id`),
  CONSTRAINT `FK_D4E6F81A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_64C19C15E237E06` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (1,'jeux videos','https://i.postimg.cc/PJ1BkZMt/Jeux-videos.png','jeux-videos'),(2,'jeu de rôle','https://i.postimg.cc/xTSw-3x0q/Jeu-de-role.png','jeu-de-role'),(3,'cartes à jouer et à collectionner','https://i.postimg.cc/7PT4VJ0t/Cartes-jouer-et-collectionner.png','cartes-a-jouer-et-a-collectionner'),(4,'figurines','https://i.postimg.cc/cJHy8vdm/Figurines-et-statuettes.png','figurines'),(5,'comics','https://i.postimg.cc/1tkLTMwC/Comics.png','comics'),(6,'mangas','https://i.postimg.cc/KYVVkQqG/Mangas.png','mangas'),(7,'bandes dessinées','https://i.postimg.cc/C5DVL9wp/Bande-dessin-e.png','bandes-dessinees'),(8,'cosplay','https://i.postimg.cc/hG5F8Kjq/Cosplay.png','cosplay'),(9,'goodies (mugs, objets publicitaires…)','https://i.postimg.cc/RV7BrrPc/Goodies.png','goodies-mugs-objets-publicitaires'),(10,'dvd/blu-ray','https://i.postimg.cc/tTZK28Zn/DVD-Bluray.png','dvd-blu-ray'),(11,'cd/vinyles','https://i.postimg.cc/t49Hprf8/CD-Vinyles.png','cd-vinyles'),(12,'jeu de rôle gn (larping)','https://i.postimg.cc/kM8r83Pk/Jeu-de-role-GN-LARPING.png','jeu-de-role-gn-larping'),(13,'jeux de plateau','https://i.postimg.cc/Z5wXZJ6y/Jeux-de-plateau.png','jeux-de-plateau'),(14,'jouets','https://i.postimg.cc/mrhJgKSp/Jouets.png','jouets'),(15,'livres et manuels','https://i.postimg.cc/Hs4fZwxz/Livres-et-manuels.png','livres-et-manuels'),(16,'maquettes et modélisme','https://i.postimg.cc/Hn0PNmpn/Maquettes-et-mod-lisme.png','maquettes-et-modelisme'),(17,'matériel de streaming et gaming','https://i.postimg.cc/HxyNQ5RQ/Materiel-de-streaming-et-gaming.png','materiel-de-streaming-et-gaming'),(18,'objets connectés','https://i.postimg.cc/x8V4g8VB/Objets-connect-s.png','objets-connectes'),(19,'gadgets et technologie','https://i.postimg.cc/L6RW1JT1/Technologie.png','gadgets-et-technologie'),(20,'maison mobilier et déco','https://i.postimg.cc/bwy4KZSh/Maison-mobilier-et-d-co.png','maison-mobilier-et-deco'),(21,'retrogaming','https://i.postimg.cc/02fBd9WH/Retrogaming.png','retrogaming'),(22,'mode et accessoires','https://i.postimg.cc/pVK7rfDv/Mode-et-accessoires.png','mode-et-accessoires'),(23,'evènements geek','https://i.postimg.cc/2STN6QNy/Evenements-Geek.png','evenements-geek'),(24,'créations originales','https://i.postimg.cc/ncbJwV1F/Cr-ations-originales.png','creations-originales');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('DoctrineMigrations\\Version20231221095041','2023-12-21 10:50:49',146);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `ad_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `year` varchar(4) DEFAULT NULL,
  `serial_number` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_D34A04ADA76ED395` (`user_id`),
  KEY `IDX_D34A04AD12469DE2` (`category_id`),
  KEY `IDX_D34A04AD4F34D596` (`ad_id`),
  CONSTRAINT `FK_D34A04AD12469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `FK_D34A04AD4F34D596` FOREIGN KEY (`ad_id`) REFERENCES `ad` (`id`),
  CONSTRAINT `FK_D34A04ADA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (2,11,4,NULL,'Figurine Deadpool','65840d2ab62bc.jpeg','2018','223365329952102','2023-12-21 11:00:47',NULL),(3,6,4,NULL,'Figurine spiderman ','65840d7a77b1c.jpeg','1988','020850412','2023-12-21 11:03:38',NULL),(4,14,4,14,'Figurine Hulk ','65840dcae43a6.jpeg','1988','020450250','2023-12-21 11:04:58',NULL),(5,7,3,3,'Carte Pokémon','65840e3a95fbb.jpeg','1999','65566332523','2023-12-21 11:05:03',NULL),(6,14,5,NULL,'Comic Batman - 1. La cour des hiboux','65840f2f60e90.jpeg','1999','0520505052','2023-12-21 11:10:55',NULL),(7,10,13,5,'Jeu Horreur à Arkham','65840fb0e5208.jpeg','2022','','2023-12-21 11:13:04',NULL),(9,6,21,7,'Borne de jeu d\'arcade','65b514a9efe05.jpeg','1981','056056205','2023-12-21 11:14:16','2024-01-27 15:35:21'),(10,14,4,NULL,'Figurine Chtulhu  - Myth Statue ','6584124e1eba1.jpeg','2021','','2023-12-21 11:24:13',NULL),(11,14,4,NULL,'Figurine Marvel','658416c3b7877.jpeg','1998','06545152','2023-12-21 11:43:15',NULL),(12,11,5,NULL,'BD Marvel','','1988','5152145','2023-12-21 11:45:41',NULL),(17,10,4,NULL,'Statue Cthulhu de 32cm','6584400932739.jpeg','1988','','2023-12-21 14:39:20',NULL),(24,13,2,NULL,'Duis quia earum qui ','6584562672c03.jpeg','2000','1516','2023-12-21 15:44:40','2023-12-21 16:13:42'),(26,15,2,NULL,'Dolor maxime molesti','659561ae6b14b.jpeg','1999','156156511455','2024-01-03 14:31:25',NULL),(27,22,4,NULL,'Figurine de ouf','65981ef5221e9.jpeg','1958','0151512','2024-01-05 16:23:31',NULL),(28,28,6,15,'L\'annonce','65a931a7e2fd3.png','2024','051515','2024-01-17 11:02:20','2024-01-17 11:19:31'),(29,28,4,NULL,'C bébé yoda','65a95c70517d6.png','2024','524526','2024-01-17 11:03:36','2024-01-17 11:12:42'),(30,43,4,16,'Figurine Spiderman','65b505fe447ad.jpeg','2017','144554151','2024-01-27 14:32:46',NULL),(31,43,1,17,'Jeu The Last Of Us 2 sur PS4','65b506e2d40ca.jpeg','2020','','2024-01-27 14:36:34',NULL),(32,45,4,NULL,'Figurine deadpool','65b53383aafe9.jpeg','2014','2564545665','2024-01-27 17:46:59',NULL);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `firstname` varchar(65) NOT NULL,
  `lastname` varchar(65) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `description` longtext DEFAULT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `password` varchar(255) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  UNIQUE KEY `UNIQ_8D93D6496B01BC5B` (`phone_number`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (6,'Amael','Amael','Rosales','avatar-null.jpg','658439f99b05a.jpeg','amael.rosales@gmail.com','0264511205','Mon chien ne mord pas, swear to god','2023-12-21 10:56:13',NULL,'$2y$13$so6WXZeIPZ5lqz3/AflzIebOnULwnmC/x4/Z23SzjP/X8rfpEwc1m','[\"ROLE_USER\"]'),(7,'Matthieu','Matthieu','Le Floch','avatar-null.jpg','banner-null.png','matthieu-lf@oclock.io','0770077007','Je n\'ai pas de description','2023-12-21 10:56:17',NULL,'$2y$13$8lYLlDKJWEQZRiNeFTbe1eHYfhBxAQVnMEtjDhnwrpWStB70UhGhW','[\"ROLE_USER\"]'),(8,'Amgad','Amgad','Gaafr','avatar-null.jpg','banner-null.png','amgadgaafr@oclock.io','0660066006','Je n\'ai pas de description','2023-12-21 10:56:43',NULL,'$2y$13$g95ozXvIBznf/4ZtzmE2KOD.WMVlfzYF1eHEY0BWfndjyurlDVGaK','[\"ROLE_USER\"]'),(9,'xxx__OliverLeGeekos___xxx','Olivier','Ziolkowski','65840ef9eca52.jpeg','http://placehold.it/500x500','olivier@gmail.com','0689485775','Je suis OIivier. J\'adore les jeux vidéos. Ouic\'estça','2023-12-21 10:57:20',NULL,'$2y$13$ZGXE2TuDufdTK3JDEvrceODqFjk.viAAWNpYQbMv8MCn7NUAeEXEm','[\"ROLE_USER\"]'),(10,'Abrassax','Lay','Abrassax','6584406a1b27e.png','6584406a4b136.jpeg','abrassax@gmail.com','0678854125','\"Je sentis alors me mordre au coeur cette obscure terreur qui ne me quittera plus jusqu\'à ce, que moi aussi, je sois au repos \"par accident\" ou d\'une autre manière.\"','2023-12-21 11:03:36',NULL,'$2y$13$FBQbnJ8Ss7vPpSrA8spksePzFLDsLhQXpL4AsZBM7Db3B9MB/S4gG','[\"ROLE_USER\"]'),(11,'Julien','Julien','Levasseur','658417a128675.jpeg','http://placehold.it/500x500','julien@gmail.com','594857562','J\'ai une description incroyable c\'est fou','2023-12-21 11:37:56',NULL,'$2y$13$LDcvhWZSjsBeoK4qFn4MDud55RVw58r/sbccy/bm6fyFOmTyuOgba','[\"ROLE_USER\"]'),(13,'Amael2','Amael','Rosales','65843b34a5ca6.jpeg','banner-null.png','amael2.rosales@gmail.com','202084105485','Je n\'ai pas de description','2023-12-21 14:16:00',NULL,'$2y$13$9OF/LBDpDh2TL1PwMxYCzue5aD3h64jhmALxFouGX0b1nJc6rQNxa','[\"ROLE_USER\"]'),(14,'Yesman','Yesman','challenge','http://placehold.it/300x300','banner-null.png','yesman@gmail.com','05871524550','Je n\'ai pas de description','2024-01-03 14:22:54',NULL,'$2y$13$iSbgj.huFmVmV4q5w4r1yu4wieucIBitlmN3P3NjyrCF0KJVdg7IK','[\"ROLE_USER\"]'),(15,'Amael3','Amael3','Rosales','6595618d265af.jpeg','6595618d6e98e.jpeg','amael3.rosales@gmail.com','14850250805','Je n\'ai pas de description','2024-01-03 14:29:50',NULL,'$2y$13$4ca9YSasUD65pKYt0tNN..ziLBrAwAATwthxj9B6b.8CljmHGB00i','[\"ROLE_USER\"]'),(16,'clafolie','CFOU','oui','avatar-null.jpg','banner-null.png','clafolie@gmail.com','058481526','Je n\'ai pas de description','2024-01-05 10:45:15',NULL,'$2y$13$HOYyY3uPmU2iWHsGluUECur7hFV9KdHRKMJZePUPZYTcxZcuoMU7S','[\"ROLE_USER\"]'),(17,'cmoi','cmoilemec','là','avatar-null.jpg','banner-null.png','cmoi@gmail.com','058451269','Je n\'ai pas de description','2024-01-05 10:47:12',NULL,'$2y$13$WnXdclA8lOggYTfMBcqhPOwOqm0ovt9S/da8dR5yuWzAXV/7p8sY.','[\"ROLE_USER\"]'),(18,'lemeclo','lemeclo','NomdeFamille','http://placehold.it/300x300','http://placehold.it/500x500','lenouveau@gmail.com','0541872685','C\'est l\'heure du test','2024-01-05 13:08:36',NULL,'$2y$13$781h4k5rChgb4UCzIeEgKOnnDTPk4EG0NuzOXOlRr0DuU5UGzBB4y','[\"ROLE_USER\"]'),(19,'encoreun','encoreun','cmoi','avatar-null.jpg','banner-null.png','encore@gmail.com','051487596','J\'ai maintenant une description oui','2024-01-05 13:55:30',NULL,'$2y$13$6mWPrt8qGTIRDUqI/t5bu.nCLpBwxCM0nWg0nSyuYWVFXrqEAi.vi','[\"ROLE_USER\"]'),(20,'anotherone','another','one','avatar-null.jpg','banner-null.png','another@g.com','054781562','Description de fou','2024-01-05 14:00:41',NULL,'$2y$13$xLB0urQReRitj2uZUbNgceck3cp4wJaV3Du6V8K9Uruye0PKV5H9.','[\"ROLE_USER\"]'),(21,'lepseudo','uhuii','uhhfef','avatar-null.jpg','banner-null.png','uwu@mail.com','05721485945','Description oui','2024-01-05 14:35:29',NULL,'$2y$13$06BxmxV28NrFFQNMVISySOtRDT3dux5WKpENtrl7yH.D.L5oMS.hG','[\"ROLE_USER\"]'),(22,'lesimages','die','ing','65981d964821a.jpeg','banner-null.png','gr@gmail.com','051521565','Yop c ','2024-01-05 15:25:43',NULL,'$2y$13$CswzU6EVyIJn1kP87InY5.SHgNRTmWEBW.ypaHUXK/ijefWfBo0Le','[\"ROLE_USER\"]'),(24,'Amgad2','Amgad2','Gaafr','avatar-null.jpg','banner-null.png','amgadgaafr2@oclock.io','06600660062','Je n\'ai pas de description','2024-01-17 10:22:29',NULL,'$2y$13$5NILqFUQMwCe2HUT1f3dzecx4NmBur2ATAqZiVwVjr.0oa840RexG','[\"ROLE_USER\"]'),(26,'Cestt4','Gregtttt','Leftttt','avatar-null.jpg','banner-null.png','matttt4@oclock.com','8106249223','Je n\'ai pas de description','2024-01-17 10:26:03',NULL,'$2y$13$2aMHjpi.1ECYxqwWL2Au3uGWDCDzeRXLv37RpdLpKAvGBqJdFF87q','[\"ROLE_USER\"]'),(28,'Julienlenouveau','ju','lienlien','65a7a893dbecb.jpeg','banner-null.png','ju@g.com','0451256952','La description','2024-01-17 10:54:12',NULL,'$2y$13$1ORBnFuSdZT.eDe1oTYp3OUG92rK3DG7Wn0kJj84JpaUMyiThw.ua','[\"ROLE_USER\"]'),(41,'anotherone22','Amgad2','Gaafr','avatar-null.jpg','banner-null.png','amael223.rosales@gmail.com','067885412225','Je n\'ai pas de description','2024-01-18 13:37:05',NULL,'$2y$13$WkzMq15n5JVQQ68zgx8lsOYvg.oen4rrhRWHVK5ZskNhAEHAvdcWi','[\"ROLE_USER\"]'),(42,'Hacker','jul','o','65a92ea3ded38.jpeg','banner-null.png','hack@icloud.com','5241524526','Je n\'ai pas de description','2024-01-18 14:49:06',NULL,'$2y$13$kmWZ9pdXWEs5apmsKy0o2Oa0/vyt.gX.0MkzQfref5.fylNDl7Y2S','[\"ROLE_USER\"]'),(43,'MMM','mmm','mmm','avatar-null.jpg','banner-null.png','mmm@mmm.fr','123456321','Je n\'ai pas de description','2024-01-27 14:19:26',NULL,'$2y$13$xtiwmptSP/1HcPf/jfzbPOdfEsmkj5dctUGk6Q.7C7DOHzbo78nsW','[\"ROLE_USER\"]'),(44,'amgad3','amgad','gaafr','avatar-null.jpg','banner-null.png','sdfdsf@df.fr','5545466','Je n\'ai pas de description','2024-01-27 15:17:53',NULL,'$2y$13$t770ESXKxyDG7k5kFxNaj./l6tCLEQua351BH4uHmmhLU/c10aa52','[\"ROLE_USER\"]'),(45,'youpi','youpi','youpi','65b533309cc7b.jpeg','banner-null.png','youpi@hh.fr','255454','Je n\'ai pas de description','2024-01-27 17:44:55',NULL,'$2y$13$FeNnj.rAJ6cFJ/ItKpHSzeTc7E43x7uYHaNddwu7n0.ne4.jOsWDa','[\"ROLE_USER\"]'),(46,'anotherone222','Amgad2','Gaafr','avatar-null.jpg','banner-null.png','amael2234.rosales@gmail.com','0678812225','Je n\'ai pas de description','2024-01-28 20:04:58',NULL,'$2y$13$30QaIRlKhdnUZ3h/zdZi4eX560TbK39xHHaOga.7znbTK9ppXJ88K','[\"ROLE_USER\"]'),(47,'anotherone2222','Amgad2','Gaafr','avatar-null.jpg','banner-null.png','amael22324.rosales@gmail.com','06782812225','Je n\'ai pas de description','2024-01-28 20:07:15',NULL,'$2y$13$W5DcqtmxHXEyyrR4mCU/eO77gKCz8sbB4UrbJzf6odkpLBwZorreC','[\"ROLE_USER\"]'),(48,'anotherone22222','Amgad2','Gaafr','avatar-null.jpg','banner-null.png','amael223224.rosales@gmail.com','067828122225','Je n\'ai pas de description','2024-01-28 20:10:50',NULL,'$2y$13$a8PrFh5cJ2Fa4b7lUzFNs.gr8zabedEoFxhz99YTxoHJI.l92yOdu','[\"ROLE_USER\"]'),(49,'tata','Gregtttt','Leftttt','',NULL,'mattttt4@oclock.com','81065552492233','Je veux une descriptionnnnn','2024-02-01 15:58:57',NULL,'$2y$13$AacSTeobPSaRI9hLzaT1W.q0zH4SnJztWuJ/ZD.bFw9BmddSZyHjO','[\"ROLE_USER\"]'),(51,'tgtg','Amgad2','Gaafr','avatar-null.jpg','banner-null.png','dfdsf@ho.fr','162165465','Je n\'ai pas de description','2024-02-03 10:49:50',NULL,'$2y$13$Y6irKE3LohyGqLJT8r7AzeLyLqGdMvSSm1eNHi.66FznoO7ZW8zrW','[\"ROLE_USER\"]'),(52,'tgtgd','Amgad2','Gaafr','avatar-null.jpg','banner-null.png','dfdsdf@ho.fr','1621265465','Je n\'ai pas de description','2024-02-03 10:51:36',NULL,'$2y$13$r0Y1/wpVv5.iEvJcGsCdju4ETkIWXLjQY0SERSRL.sHP.DU6gE./O','[\"ROLE_USER\"]'),(53,'tgtgsd','Amgad2','Gaafr','avatar-null.jpg','banner-null.png','dfdssdf@ho.fr','16231265465','Je n\'ai pas de description','2024-02-03 10:57:22',NULL,'$2y$13$CISY14YRrqiLDvQvjQV0Hu69DzXKv0fzNSOxndIFvSJFDQD3exE7q','[\"ROLE_USER\"]'),(54,'tgtgsqd','Amgad2','Gaafr','avatar-null.jpg','banner-null.png','dfdssdqf@ho.fr','162312365465','Je n\'ai pas de description','2024-02-03 11:00:46',NULL,'$2y$13$zENkqyvHyj8A.XkZclQE4eXzEHlJDBTE4eGNMA.KuSvRQZ1QPI35C','[\"ROLE_USER\"]'),(55,'tgtgssqd','Amgad2','Gaafr','avatar-null.jpg','banner-null.png','dfdssdsqf@ho.fr','1624312365465','Je n\'ai pas de description','2024-02-03 11:01:33',NULL,'$2y$13$TqVfeSGibgCL7qFVFLZrleN/z3UC2LgkO/bS3caJOO151sle9EfVO','[\"ROLE_USER\"]'),(56,'tatatata','Gregtttt','Leftttt','avatar-null.jpg','banner-null.png','dfsqf@ho.fr','552492233','Je veux une descriptionnnnn','2024-02-03 11:07:13',NULL,'$2y$13$UaUKeQpi0l8lE9NI4as4weoKVO/RAxRFbJoZO7Jr4hOMNm3.MqVFu','[\"ROLE_USER\"]'),(57,'fdf','Amgad2','Gaafr','avatar-null.jpg','banner-null.png','dfsvvqf@ho.fr','156525456','Je n\'ai pas de description','2024-02-03 11:13:29',NULL,'$2y$13$xNabXM7OiYjpsDUkL5oYeeCK8blZ08CvcYaq77dYHto3nBasG0yH2','[\"ROLE_USER\"]'),(58,'fdvf','Amgad2','Gaafr','avatar-null.jpg','banner-null.png','dfsvvvqf@ho.fr','1546525456','Je n\'ai pas de description','2024-02-03 11:16:43',NULL,'$2y$13$KPVssAcyQENzAVf5btwtJu.eenVVCKWs4C6mfOAdaAjES9.dmUM22','[\"ROLE_USER\"]'),(59,'tatatatsad','Gregtttt','Leftttt','avatar-null.jpg','banner-null.png','dfsvvvsqf@ho.fr','55243292233','Je veux une descriptionnnnn','2024-02-03 11:22:05',NULL,'$2y$13$fhmgWPIpwcQiDBbYI.1v2.Ghf4yrxbypv/HZgKZ.9R5ztfyoyN6O.','[\"ROLE_USER\"]'),(60,'fdvsssf','Amgad2','Gaafr','avatar-null.jpg','banner-null.png','dfsvvvssqf@ho.fr','154652265456','Je n\'ai pas de description','2024-02-03 11:41:25',NULL,'$2y$13$SfJTA2pMV1lkjZy/4I1oWOZrAQoY2FcymqPMALv/miW79nbabLgne','[\"ROLE_USER\"]');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-02-05 17:24:25
