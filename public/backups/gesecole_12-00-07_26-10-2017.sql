-- Generation time: Thu, 26 Oct 2017 12:00:07 +0100
-- Host: localhost
-- DB name: gesecole
/*!40030 SET NAMES UTF8 */;
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP TABLE IF EXISTS `bulletin_genere`;
CREATE TABLE `bulletin_genere` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_classe` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `classe`;
CREATE TABLE `classe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `nom_anglais` varchar(200) NOT NULL,
  `id_niveau` int(11) NOT NULL,
  `id_cycle` int(11) NOT NULL,
  `capacite` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `classe` VALUES ('1','IG','IG1','IG1','2','1','500'),
('2','IG','Informatique de Gestion 3e année','Computer science for management 3rd year','2','2','500'); 


DROP TABLE IF EXISTS `classe_matiere`;
CREATE TABLE `classe_matiere` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cycle` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `id_matiere` int(11) NOT NULL,
  `coef` int(11) NOT NULL DEFAULT '1',
  `max` int(11) DEFAULT '20',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

INSERT INTO `classe_matiere` VALUES ('9','2','2','4','4','100'),
('10','2','2','1','4','100'),
('7','2','2','2','4','100'),
('13','2','2','3','5','100'),
('12','2','2','5','3','100'),
('14','2','2','6','3','100'),
('15','2','2','7','3','100'),
('16','2','2','8','3','100'),
('17','2','2','9','3','100'),
('18','2','2','10','4','100'),
('19','2','2','11','4','100'),
('20','1','1','1','1','100'),
('21','1','1','2','1','100'),
('22','1','1','3','1','100'); 


DROP TABLE IF EXISTS `cycle`;
CREATE TABLE `cycle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `cycle` VALUES ('1','BTS'),
('2','LICENCE'); 


DROP TABLE IF EXISTS `decaissement`;
CREATE TABLE `decaissement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_personnel` int(11) NOT NULL,
  `motif` varchar(2000) NOT NULL,
  `montant` float NOT NULL,
  `date` date NOT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `decaissement` VALUES ('1','1','10 sacs de ciment à 5000 francs le sacs','50000','2017-10-20','2017-2018'); 


DROP TABLE IF EXISTS `discipline`;
CREATE TABLE `discipline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_eleve` int(11) NOT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  `abscence_inj` int(11) NOT NULL DEFAULT '0',
  `abscence_just` int(11) NOT NULL DEFAULT '0',
  `retard` int(11) NOT NULL DEFAULT '0',
  `avertissement` int(11) NOT NULL DEFAULT '0',
  `blame` int(11) NOT NULL DEFAULT '0',
  `exclusion` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `discipline` VALUES ('1','14','2017-2018','0','0','0','0','0','0'); 


DROP TABLE IF EXISTS `discipline_personnel`;
CREATE TABLE `discipline_personnel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `heure_arrive` varchar(50) DEFAULT NULL,
  `id_personnel` int(11) NOT NULL,
  `type` int(1) NOT NULL,
  `date_d` date NOT NULL,
  `nombre` int(11) DEFAULT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `dispense`;
CREATE TABLE `dispense` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_personnel` int(11) NOT NULL,
  `id_matiere` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `jour` int(11) NOT NULL,
  `id_horaire` int(11) NOT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO `dispense` VALUES ('1','1','2','1','1','1','2017-2018'),
('2','1','1','2','2','1','2017-2018'),
('3','1','1','2','2','2','2017-2018'),
('5','2','10','2','3','4','2017-2018'),
('6','2','10','2','3','5','2017-2018'),
('8','2','9','1','4','2','2017-2018'),
('9','1','6','2','1','4','2017-2018'); 


DROP TABLE IF EXISTS `eleve`;
CREATE TABLE `eleve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `date_naiss` date NOT NULL,
  `lieu_naiss` varchar(100) NOT NULL,
  `sexe` varchar(1) NOT NULL,
  `nationalite` varchar(100) NOT NULL,
  `quartier` varchar(100) NOT NULL,
  `lien_tuteur` varchar(100) DEFAULT NULL,
  `matricule` varchar(20) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `tel` varchar(200) DEFAULT NULL,
  `status_matrimonial` varchar(100) DEFAULT NULL,
  `region_origine` varchar(200) DEFAULT NULL,
  `departement_origine` varchar(200) DEFAULT NULL,
  `diplome_preinscription` varchar(200) DEFAULT NULL,
  `annee_obtention` varchar(10) DEFAULT NULL,
  `pays_obtention` varchar(100) DEFAULT NULL,
  `dernier_etablissement` varchar(200) DEFAULT NULL,
  `annee_sortie` varchar(200) DEFAULT NULL,
  `diplome_obtenu` varchar(200) DEFAULT NULL,
  `premiere_langue` varchar(100) DEFAULT NULL,
  `deuxieme_langue` varchar(100) DEFAULT NULL,
  `autre_langue` varchar(100) DEFAULT NULL,
  `profession` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `matricule` (`matricule`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

INSERT INTO `eleve` VALUES ('1','Mankon','Junior','2004-05-03','Bertoua','M','Tchadien','Yaoundé','Oncle','17MP00001',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
('9','Tagne Fonkou','Hervé','1990-02-04','Bertoua','M','Camerounais','Ekounou','Tante','17IA-IG0009',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
('8','MAMADOU','Moulaye','1997-02-02','Adamoua','M','Camerounais','Essos','Frère','17IA-IG0008',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
('10','DOUMBE','Kevin Willy Benoit Junior','1995-02-02','Littoral','M','Camerounais','Santa Barbara','','17IA-IG0010',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
('11','Tchakounte','Marceline','1992-07-06','Douala','F','Camerounaise','Tongolo','Oncle','17MP00011',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
('12','Douanla ','Estelle','1991-01-01','Yaoundé','F','Camerounaise','messassi','Oncle','17IA-IG0012',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
('13','Siewe','Anais Landry','1992-03-03','Douala','M','Cameroun','Yaoundé','Frère','17IA-IG0013',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),
('14','Chinda melong cyrille joel',NULL,'1995-02-02','Yaoundé','M','Camerounais','Messassi','','17IA-IG0014','chindajoel@gmail.com','696873627/650373203',NULL,'Ouest','Mbouda','Licence en informatique','2015','Cameroun','Faculté des sciences UYI','2017','MASTER I','francais','Anglais','','aucune'); 


DROP TABLE IF EXISTS `fonction`;
CREATE TABLE `fonction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `fonction` VALUES ('1','Enseignant'); 


DROP TABLE IF EXISTS `fonction_personnel`;
CREATE TABLE `fonction_personnel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_fonction` int(11) NOT NULL,
  `id_personnel` int(11) NOT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `fonction_personnel` VALUES ('1','1','1','2017-2018'); 


DROP TABLE IF EXISTS `heure_realise`;
CREATE TABLE `heure_realise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_personnel` int(11) NOT NULL,
  `nombre` int(11) NOT NULL DEFAULT '0',
  `date_heure_realisee` date NOT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

INSERT INTO `heure_realise` VALUES ('17','1','2','2017-10-18','2017-2018'),
('2','1','0','2017-10-17','2017-2018'),
('6','1','1','2017-10-18','2017-2018'),
('7','1','1','2017-10-18','2017-2018'),
('8','1','1','2017-10-18','2017-2018'),
('9','1','1','2017-10-18','2017-2018'),
('10','1','1','2017-10-18','2017-2018'),
('11','1','5','2017-10-18','2017-2018'),
('12','1','7','2017-10-18','2017-2018'),
('13','1','9','2017-10-18','2017-2018'),
('14','1','1','2017-10-18','2017-2018'),
('15','1','2','2017-10-18','2017-2018'),
('16','1','3','2017-10-18','2017-2018'),
('18','2','5','2017-10-19','2017-2018'); 


DROP TABLE IF EXISTS `horaire`;
CREATE TABLE `horaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `intervalle` varchar(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `ordre` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

INSERT INTO `horaire` VALUES ('1','7h30-8h30','0','1'),
('2','8h30-9h30','0','2'),
('3','9h30-10h','1','3'),
('4','11h-12h','0','5'),
('5','10h00-11h00','0','4'); 


DROP TABLE IF EXISTS `inscription`;
CREATE TABLE `inscription` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_inscription` date NOT NULL,
  `id_eleve` int(11) NOT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `id_cycle` int(11) NOT NULL,
  `montant` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `inscription` VALUES ('1','2017-10-04','14','2017-2018','2','1','25000'),
('2','2017-10-06','12','2017-2018','2','2','25000'),
('3','2017-10-10','8','2017-2018','2','2','15000'),
('4','2017-10-10','13','2017-2018','2','2','15000'),
('5','2017-10-10','10','2017-2018','2','2','50000'),
('6','2017-10-22','9','2017-2018','2','2','50000'); 


DROP TABLE IF EXISTS `matiere`;
CREATE TABLE `matiere` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupe` int(11) NOT NULL DEFAULT '0',
  `nom` varchar(100) NOT NULL,
  `nom_anglais` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

INSERT INTO `matiere` VALUES ('1','0','Algèbre','Algebra'),
('2','0','Géométrie','Geometric'),
('3','0','Philosophie','Philosophy'),
('4','0','Economie','Economic'),
('5','0','Réseau','Network'),
('6','0','Langue française','French language'),
('7','0','Anglais','English'),
('8','0','Education civic','Civic Education'),
('9','0','Droit','Right'),
('10','0','Communication','Communication'),
('11','0','Marketing','Marketing'),
('12','0','Publicité','Pub'); 


DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



DROP TABLE IF EXISTS `moyenne_classe`;
CREATE TABLE `moyenne_classe` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_classe` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  `moyenne` float NOT NULL,
  `max` float NOT NULL,
  `min` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `moyenne_eleve`;
CREATE TABLE `moyenne_eleve` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_eleve` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `moyenne` float NOT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `moyenne_matiere`;
CREATE TABLE `moyenne_matiere` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_matiere` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `max` float NOT NULL,
  `min` float NOT NULL,
  `moyenne` float NOT NULL,
  `sequence` int(11) NOT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `niveau`;
CREATE TABLE `niveau` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(200) NOT NULL,
  `nom_anglais` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `niveau` VALUES ('2','Commercial et gestion','Commercial and management'); 


DROP TABLE IF EXISTS `note`;
CREATE TABLE `note` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_matiere` int(11) NOT NULL,
  `id_eleve` int(11) NOT NULL,
  `note` float NOT NULL,
  `sequence` int(11) NOT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=utf8;

INSERT INTO `note` VALUES ('1','1','12','20','1','2017-2018'),
('2','1','12','51','3','2017-2018'),
('3','1','10','18','1','2017-2018'),
('4','1','8','13.5','1','2017-2018'),
('5','1','13','23.5','1','2017-2018'),
('6','1','10','37','3','2017-2018'),
('7','1','8','23.5','3','2017-2018'),
('8','1','13','63','3','2017-2018'),
('9','1','8','53','5','2017-2018'),
('10','7','12','25','1','2017-2018'),
('11','7','10','14.5','1','2017-2018'),
('12','7','8','17','1','2017-2018'),
('13','7','13','19','1','2017-2018'),
('14','7','12','58','3','2017-2018'),
('15','7','10','47','3','2017-2018'),
('16','7','8','33','3','2017-2018'),
('17','7','13','40','3','2017-2018'),
('18','10','12','17','1','2017-2018'),
('19','10','10','28','1','2017-2018'),
('20','10','8','21','1','2017-2018'),
('21','10','13','19','1','2017-2018'),
('22','10','12','63','3','2017-2018'),
('23','10','10','47','3','2017-2018'),
('24','10','8','48','3','2017-2018'),
('25','10','13','52','3','2017-2018'),
('26','9','12','17','1','2017-2018'),
('27','9','10','11','1','2017-2018'),
('28','9','8','13','1','2017-2018'),
('29','9','13','15.5','1','2017-2018'),
('30','9','12','39','3','2017-2018'),
('31','9','10','32','3','2017-2018'),
('32','9','8','43','3','2017-2018'),
('33','9','13','37','3','2017-2018'),
('34','4','12','26','1','2017-2018'),
('35','4','10','21','1','2017-2018'),
('36','4','8','19','1','2017-2018'),
('37','4','13','23.5','1','2017-2018'),
('38','4','12','48','3','2017-2018'),
('39','4','10','52','3','2017-2018'),
('40','4','8','57','3','2017-2018'),
('41','4','13','43','3','2017-2018'),
('42','8','12','12','1','2017-2018'),
('43','8','10','16','1','2017-2018'),
('44','8','8','9','1','2017-2018'),
('45','8','13','10','1','2017-2018'),
('46','8','12','48','3','2017-2018'),
('47','8','10','32','3','2017-2018'),
('48','8','8','25','3','2017-2018'),
('49','8','13','13','3','2017-2018'),
('50','8','8','37.75','5','2017-2018'),
('51','8','13','41.5','5','2017-2018'),
('52','5','12','26','2','2017-2018'),
('53','5','10','20','2','2017-2018'),
('54','5','8','19.5','2','2017-2018'),
('55','5','13','28.5','2','2017-2018'),
('56','5','12','53','3','2017-2018'),
('57','5','10','49.75','3','2017-2018'),
('58','5','8','38.5','3','2017-2018'),
('59','5','13','64.5','3','2017-2018'),
('60','1','12','20','2','2017-2018'),
('61','1','10','17','2','2017-2018'),
('62','1','8','23','2','2017-2018'),
('63','1','13','16','2','2017-2018'),
('64','1','12','40.5','4','2017-2018'),
('65','1','10','34','4','2017-2018'),
('66','1','8','49','4','2017-2018'),
('67','1','13','36.5','4','2017-2018'),
('68','5','12','58','4','2017-2018'),
('69','5','10','50','4','2017-2018'),
('70','5','8','42','4','2017-2018'),
('71','5','13','67.5','4','2017-2018'),
('72','5','12','25','1','2017-2018'),
('73','5','10','23','1','2017-2018'),
('74','5','8','19','1','2017-2018'),
('75','5','13','28.5','1','2017-2018'),
('76','3','12','17','2','2017-2018'),
('77','3','10','11','2','2017-2018'),
('78','3','8','15','2','2017-2018'),
('79','3','13','16.5','2','2017-2018'),
('80','3','12','39','3','2017-2018'),
('81','3','10','26','3','2017-2018'),
('82','3','8','34.5','3','2017-2018'),
('83','3','13','35','3','2017-2018'),
('84','3','12','55','5','2017-2018'),
('85','3','10','63','5','2017-2018'),
('86','3','8','49','5','2017-2018'),
('87','3','13','58','5','2017-2018'),
('88','3','12','45','6','2017-2018'),
('89','3','10','53','6','2017-2018'),
('90','3','8','50','6','2017-2018'),
('91','3','13','60','6','2017-2018'),
('92','2','12','20','2','2017-2018'),
('93','2','10','20','2','2017-2018'),
('94','2','8','25','2','2017-2018'),
('95','2','13','20','2','2017-2018'),
('96','2','12','55.5','4','2017-2018'),
('97','2','10','43','4','2017-2018'),
('98','2','8','48','4','2017-2018'),
('99','2','13','49.75','4','2017-2018'); 


DROP TABLE IF EXISTS `parametres`;
CREATE TABLE `parametres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `valeur` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

INSERT INTO `parametres` VALUES ('1','nom_etablissement','INTITUT SUPERIEUR AZIMUT'),
('2','nom_directeur','Munkam herve'),
('3','annee_scolaire','2017-2018'),
('4','initiales_etablissement','IA'),
('5','telephone','698589589-689587478'),
('6','adresse','Nkolfulu'),
('7','web',''),
('8','email',''),
('9','configuration','OK'); 


DROP TABLE IF EXISTS `parent`;
CREATE TABLE `parent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(200) DEFAULT NULL,
  `profession` varchar(100) DEFAULT NULL,
  `ville` varchar(100) DEFAULT NULL,
  `quartier` varchar(100) DEFAULT NULL,
  `tel1` varchar(15) DEFAULT NULL,
  `tel2` varchar(15) DEFAULT NULL,
  `type` int(11) NOT NULL,
  `id_eleve` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `payement`;
CREATE TABLE `payement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_eleve` int(11) NOT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  `date_payement` date NOT NULL,
  `montant` float NOT NULL,
  `motif` varchar(100) NOT NULL,
  `numero` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

INSERT INTO `payement` VALUES ('1','14','2017-2018','2017-10-04','25000','Inscription','00000001'),
('2','14','2017-2018','2017-10-04','98000','1ere tranche','00000002'),
('3','12','2017-2018','2017-10-06','25000','Inscription','00000003'),
('4','8','2017-2018','2017-10-10','15000','Inscription','00000004'),
('5','13','2017-2018','2017-10-10','15000','Inscription','00000005'),
('6','10','2017-2018','2017-10-10','50000','Inscription','00000006'),
('7','14','2017-2018','2017-10-22','59000','scolarite','00000007'),
('8','14','2017-2018','2017-10-22','77000','scolarite','00000008'),
('9','12','2017-2018','2017-10-22','215000','scolarite','00000009'),
('10','13','2017-2018','2017-10-22','135000','scolarite','00000010'),
('11','9','2017-2018','2017-10-22','50000','Inscription','00000011'); 


DROP TABLE IF EXISTS `payement_salaire`;
CREATE TABLE `payement_salaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_personnel` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `montant` float NOT NULL,
  `date_p` date NOT NULL,
  `numero` varchar(20) NOT NULL,
  `mois` int(11) NOT NULL,
  `heure_realise` int(11) NOT NULL,
  `primes` float NOT NULL DEFAULT '0',
  `acomptes` float NOT NULL DEFAULT '0',
  `cnss` float NOT NULL DEFAULT '0',
  `cas_sociales` float NOT NULL DEFAULT '0',
  `assurances` float NOT NULL DEFAULT '0',
  `autre_retenues` float NOT NULL DEFAULT '0',
  `taux` float NOT NULL DEFAULT '0',
  `total` float NOT NULL DEFAULT '0',
  `total_retenu` float NOT NULL DEFAULT '0',
  `net_a_payer` float NOT NULL DEFAULT '0',
  `annee_scolaire` varchar(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `payement_salaire` VALUES ('1','1','1','65000','2017-10-15','00000001','10','4','0','0','0','0','0','0','0','65000','0','65000','2017-2018'),
('2','1','1','292500','2017-10-18','00000002','10','35','0','0','0','0','0','0','6500','292500','0','292500','2017-2018'); 


DROP TABLE IF EXISTS `personnel`;
CREATE TABLE `personnel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) DEFAULT NULL,
  `nom` varchar(200) NOT NULL,
  `date_naiss` date NOT NULL,
  `lieu_naiss` varchar(100) NOT NULL,
  `diplome` varchar(100) NOT NULL,
  `sexe` varchar(1) NOT NULL,
  `date_entree` date NOT NULL,
  `tel1` varchar(15) NOT NULL,
  `tel2` varchar(15) NOT NULL,
  `adresse` varchar(100) NOT NULL,
  `type` int(11) DEFAULT '0',
  `matieres` varchar(2000) DEFAULT NULL,
  `autre_information` varchar(2000) DEFAULT NULL,
  `matricule` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `personnel` VALUES ('1','Kamgang@yahoo.fr','Kamgang','1984-02-02','Yaoundé','DIPES','H','2017-10-07','698747856','','Emana','0','Réseaux-philosophie-Architecture','a enseigner a Hintel superior institute','17IA001'),
('2','maigarilaeticia@yahoo.fr','Maigari Laeticia','1981-02-02','Buea','Master II - Management','F','2017-10-18','678858954','','Etoudi','0','Management','','17IA002'); 


DROP TABLE IF EXISTS `salaire`;
CREATE TABLE `salaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_personnel` int(11) NOT NULL,
  `type` int(1) NOT NULL DEFAULT '0',
  `montant` float DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_personnel` (`id_personnel`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `salaire` VALUES ('1','1','0','65000'); 


DROP TABLE IF EXISTS `scolarite`;
CREATE TABLE `scolarite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_cycle` int(11) NOT NULL,
  `tranche1` float DEFAULT '0',
  `tranche2` float DEFAULT '0',
  `tranche3` float DEFAULT '0',
  `tranche4` float DEFAULT '0',
  `tranche5` int(11) NOT NULL DEFAULT '0',
  `total` float NOT NULL DEFAULT '0',
  `id_niveau` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

INSERT INTO `scolarite` VALUES ('1','1','97000','97000','97000','0','0','291000','2'),
('2','2','105000','105000','105000','0','0','315000','2'); 


DROP TABLE IF EXISTS `taux`;
CREATE TABLE `taux` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_personnel` int(11) NOT NULL,
  `taux` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `taux` VALUES ('1','1','6500'); 


DROP TABLE IF EXISTS `titulaire`;
CREATE TABLE `titulaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_personnel` int(11) NOT NULL,
  `id_classe` int(11) NOT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `trimestre`;
CREATE TABLE `trimestre` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `seq1` int(11) NOT NULL,
  `seq2` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `fullname` varchar(200) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `remember_token` varchar(100) NOT NULL,
  `role` float NOT NULL DEFAULT '20',
  `active` int(1) NOT NULL DEFAULT '0',
  `id_personnel` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `users` VALUES ('1','tamanois','tamanois',NULL,'$2y$10$SswXQVoERqPmUHzdMGlky.o7nTw1ySt3J4ZyHBi8nd/mAdUX2rt0q','wu6YPOo3J9JVOS7N5nUNeGPlCWgdcoUUCclqIRVETTqSSYSsqD058K60blFh','0','1',NULL,'2017-10-03 15:10:55','2017-10-15 07:19:07'),
('2','admin','Munkan herve',NULL,'$2y$10$now9QiOYly7PG6zCtJyck.GqCZw1.MOV46EwjrHEDHFO/HlOIPxP6','','17','0',NULL,'2017-10-03 15:10:55','2017-10-15 06:49:23'),
('3','kamgamg','kamgang',NULL,'$2y$10$muqWmIqrbebjeApCutv7oOQucxfPhV2fIZLuYulg6YNiR/hnlfjO.','idEeymwRqsJvrCIRqmSBgxY4juIzmxrAS9fEmanYGO7MZSsC7yiDioucBcF4','18.5','1','1','2017-10-15 07:18:13','2017-10-15 07:18:57'); 


DROP TABLE IF EXISTS `verrounote`;
CREATE TABLE `verrounote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sequence` int(11) NOT NULL,
  `annee_scolaire` varchar(9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

INSERT INTO `verrounote` VALUES ('11','1','2017-2018'),
('12','4','2017-2018'); 




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

