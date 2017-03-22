-- Adminer 4.3.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `odkaz` varchar(50) COLLATE utf8_bin NOT NULL COMMENT 'Odkaz',
  `nazov` varchar(100) COLLATE utf8_bin NOT NULL COMMENT 'Názov položky',
  `id_registracia` int(11) NOT NULL DEFAULT '4' COMMENT 'Min. úroveň registrácie',
  `avatar` varchar(200) COLLATE utf8_bin DEFAULT NULL COMMENT 'Odkaz na avatar aj s relatívnou cestou od adresára www',
  PRIMARY KEY (`id`),
  KEY `id_registracia` (`id_registracia`),
  CONSTRAINT `admin_menu_ibfk_1` FOREIGN KEY (`id_registracia`) REFERENCES `registracia` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Administračné menu';

INSERT INTO `admin_menu` (`id`, `odkaz`, `nazov`, `id_registracia`, `avatar`) VALUES
(1,	'Homepage:',	'Úvod',	3,	'ikonky/AzulLustre_icons/Cerrada.png'),
(2,	'Lang:',	'Editácia jazykov',	4,	'ikonky/AzulLustre_icons/Webfolder.png'),
(3,	'Slider:',	'Editácia slider-u',	4,	'ikonky/AzulLustre_icons/Imagenes.png'),
(4,	'User:',	'Editácia členov',	5,	'ikonky/AzulLustre_icons/Fuentes.png'),
(5,	'Verzie:',	'Verzie webu',	4,	'ikonky/AzulLustre_icons/URL_historial.png'),
(6,	'Udaje:',	'Údaje webu',	4,	'ikonky/AzulLustre_icons/Admin.png'),
(7,	'Oznam:',	'Aktuality(oznamy)',	4,	'ikonky/AzulLustre_icons/Documentos_azul.png');

DROP TABLE IF EXISTS `clanok_komponenty`;
CREATE TABLE `clanok_komponenty` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `id_hlavne_menu` int(11) NOT NULL COMMENT 'Id hl. menu, ktorému je komponenta pripojená',
  `spec_nazov` varchar(30) COLLATE utf8_bin DEFAULT NULL COMMENT 'Špecifický názov komponenty',
  `parametre` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT 'Parametre komponenty',
  PRIMARY KEY (`id`),
  KEY `id_clanok` (`id_hlavne_menu`),
  CONSTRAINT `clanok_komponenty_ibfk_3` FOREIGN KEY (`id_hlavne_menu`) REFERENCES `hlavne_menu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Zoznam komponent, ktoré sú priradené k článku';


DROP TABLE IF EXISTS `clanok_lang`;
CREATE TABLE `clanok_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `id_lang` int(11) NOT NULL DEFAULT '1' COMMENT 'Id jazyka',
  `text` text COLLATE utf8_bin,
  `anotacia` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'Anotácia článku v danom jazyku',
  PRIMARY KEY (`id`),
  KEY `id_lang` (`id_lang`),
  CONSTRAINT `clanok_lang_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `lang` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Jazyková mutácia článku';

INSERT INTO `clanok_lang` (`id`, `id_lang`, `text`, `anotacia`) VALUES
(1,	1,	'<p>Na tomto mieste budú pravidlá súťaže</p>',	NULL),
(2,	1,	'<p>Pravidlá súťaže</p>',	'');

DROP TABLE IF EXISTS `dlzka_novinky`;
CREATE TABLE `dlzka_novinky` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `nazov` varchar(30) COLLATE utf8_bin NOT NULL COMMENT 'Zobrazený názov',
  `dlzka` int(11) NOT NULL COMMENT 'Počet dní, v ktorých je novinka',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tabuľka pre hodnoty dĺžky noviniek';

INSERT INTO `dlzka_novinky` (`id`, `nazov`, `dlzka`) VALUES
(1,	'Nesleduje sa',	0),
(2,	'Deň',	1),
(3,	'Týždeň',	7),
(4,	'Mesiac(30 dní)',	30),
(5,	'Štvrť roka(91 dní)',	91),
(6,	'Pol roka(182 dní)',	182),
(7,	'Rok',	365);

DROP TABLE IF EXISTS `dokumenty`;
CREATE TABLE `dokumenty` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_hlavne_menu` int(11) NOT NULL DEFAULT '1' COMMENT 'Id položky hl. menu ku ktorej patrí',
  `id_user_profiles` int(11) NOT NULL DEFAULT '0' COMMENT 'Kto pridal dokument',
  `id_registracia` int(11) NOT NULL DEFAULT '0' COMMENT 'Úroveň registrácie',
  `znacka` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT 'Značka súboru pre vloženie do textu',
  `nazov` varchar(50) COLLATE utf8_bin NOT NULL COMMENT 'Názov titulku pre daný dokument',
  `pripona` varchar(20) COLLATE utf8_bin NOT NULL COMMENT 'Prípona súboru',
  `spec_nazov` varchar(50) COLLATE utf8_bin NOT NULL COMMENT 'Špecifický názov dokumentu pre URL',
  `popis` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'Popis dokumentu',
  `subor` varchar(255) COLLATE utf8_bin NOT NULL COMMENT 'Názov súboru s relatívnou cestou',
  `thumb` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'Názov súboru thumb pre obrázky a iné ',
  `zmena` datetime NOT NULL COMMENT 'Dátum uloženia alebo opravy - časová pečiatka',
  `zobraz_v_texte` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Zobrazenie obrázku v texte',
  `pocitadlo` int(11) NOT NULL DEFAULT '0' COMMENT 'Počítadlo stiahnutí',
  `lat` float DEFAULT NULL COMMENT 'latitude',
  `lng` float DEFAULT NULL COMMENT 'longnitude',
  PRIMARY KEY (`id`),
  UNIQUE KEY `spec_nazov` (`spec_nazov`),
  KEY `id_user_profiles` (`id_user_profiles`),
  KEY `id_registracia` (`id_registracia`),
  KEY `id_hlavne_menu` (`id_hlavne_menu`),
  CONSTRAINT `dokumenty_ibfk_2` FOREIGN KEY (`id_user_profiles`) REFERENCES `user_profiles` (`id`),
  CONSTRAINT `dokumenty_ibfk_3` FOREIGN KEY (`id_hlavne_menu`) REFERENCES `hlavne_menu` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `druh`;
CREATE TABLE `druh` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Id položiek',
  `druh` varchar(20) COLLATE utf8_bin NOT NULL COMMENT 'Názov druhu stredného stĺpca',
  `modul` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT 'Názov špecifického modulu ak NULL vždy',
  `presenter` varchar(30) COLLATE utf8_bin NOT NULL COMMENT 'Názov prezenteru pre Nette',
  `popis` varchar(255) COLLATE utf8_bin DEFAULT 'Popis' COMMENT 'Popis bloku',
  `povolene` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Ak 1 tak daná položka je povolená',
  `je_spec_naz` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Ak 1 tak daný druh potrebuje špecif. názov',
  `robots` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Ak 1 tak je povolené indexovanie daného druhu',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `druh` (`id`, `druh`, `modul`, `presenter`, `popis`, `povolene`, `je_spec_naz`, `robots`) VALUES
(1,	'clanky',	NULL,	'Clanky',	'Články - Stredná časť je ako článok, alebo je sub-menu',	1,	1,	1),
(3,	'menupol',	NULL,	'Menu',	'Položka menu - nerobí nič, len zobrazí všetky položky, ktoré sú v nej zaradené',	1,	1,	1),
(5,	'oznam',	NULL,	'Oznam',	'Vypísanie oznamov',	1,	0,	1),
(7,	'dokumenty',	NULL,	'Dokumenty',	'Vkladanie dokumentov do stránky',	0,	0,	0),
(8,	'my',	NULL,	'My',	'Vkladanie fotiek',	1,	0,	0);

DROP TABLE IF EXISTS `hlavicka`;
CREATE TABLE `hlavicka` (
  `id` int(11) NOT NULL COMMENT '[A]Index',
  `nazov` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT 'Veľká' COMMENT 'Zobrazený názov pre daný typ hlavičky',
  `pripona` varchar(10) COLLATE utf8_bin DEFAULT NULL COMMENT 'Prípona názvu súborov',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `hlavicka` (`id`, `nazov`, `pripona`) VALUES
(0,	'Nerozhoduje',	' '),
(1,	'Veľká',	'normal'),
(2,	'Malá',	'small');

DROP TABLE IF EXISTS `hlavne_menu`;
CREATE TABLE `hlavne_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[5]Id položky hlavného menu',
  `spec_nazov` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT 'Názov položky menu pre URL',
  `id_hlavne_menu_cast` int(11) NOT NULL DEFAULT '1' COMMENT '[5]Ku ktorej časti hl. menu patrí položka',
  `id_registracia` int(11) NOT NULL DEFAULT '0' COMMENT '[4]Min úroveň registrácie pre zobrazenie',
  `id_ikonka` int(11) DEFAULT NULL COMMENT '[4]Názov súboru ikonky aj s koncovkou',
  `id_druh` int(11) NOT NULL DEFAULT '1' COMMENT '[5]Výber druhu priradenej položky. Ak 1 tak je možné priradiť článok v náväznosti na tab. druh',
  `uroven` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Úroveň položky menu',
  `id_nadradenej` int(11) DEFAULT NULL COMMENT 'Id nadradenej položky menu z tejto tabuľky ',
  `id_user_profiles` int(11) NOT NULL DEFAULT '1' COMMENT 'Id užívateľa, ktorý položku zadal ',
  `poradie` int(11) NOT NULL DEFAULT '1' COMMENT 'Poradie v zobrazení',
  `poradie_podclankov` int(11) NOT NULL DEFAULT '0' COMMENT 'Poradie podčlánkov ak sú: 0 - od 1-9, 1 - od 9-1',
  `id_hlavicka` int(11) NOT NULL DEFAULT '0' COMMENT '[5]Druh hlavičky podľa tabuľky hlavicka. 1 - velka',
  `povol_pridanie` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Povolenie pridávania podčlánkov pre nevlastníkov',
  `zvyrazni` tinyint(4) NOT NULL DEFAULT '0' COMMENT '[5]Zvýraznenie položky menu pri pridaní obsahu',
  `pocitadlo` int(11) NOT NULL DEFAULT '0' COMMENT '[R]Počítadlo kliknutí na položku',
  `nazov_ul_sub` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT '[5]Názov pomocnej triedy ul-elsementu sub menu',
  `absolutna` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT 'Absolútna adresa',
  `ikonka` varchar(30) COLLATE utf8_bin DEFAULT NULL COMMENT 'Názov css ikonky',
  `avatar` varchar(300) COLLATE utf8_bin DEFAULT NULL COMMENT 'Názov a cesta k titulnému obrázku',
  `komentar` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Povolenie komentárov',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Posledná zmena',
  `datum_platnosti` date DEFAULT NULL COMMENT 'Platnosť',
  `aktualny_projekt` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Označenie aktuálneho projektu',
  `redirect_id` int(11) DEFAULT NULL COMMENT 'Id článku na ktorý sa má presmerovať',
  `id_dlzka_novinky` int(11) NOT NULL DEFAULT '1' COMMENT 'Do kedy je to novinka',
  PRIMARY KEY (`id`),
  KEY `id_reg` (`id_registracia`),
  KEY `druh` (`id_druh`),
  KEY `id_ikonka` (`id_ikonka`),
  KEY `id_hlavicka` (`id_hlavicka`),
  KEY `id_hlavne_menu_cast` (`id_hlavne_menu_cast`),
  KEY `id_user_profiles` (`id_user_profiles`),
  KEY `id_dlzka_novinky` (`id_dlzka_novinky`),
  CONSTRAINT `hlavne_menu_ibfk_1` FOREIGN KEY (`id_registracia`) REFERENCES `registracia` (`id`),
  CONSTRAINT `hlavne_menu_ibfk_2` FOREIGN KEY (`id_ikonka`) REFERENCES `ikonka` (`id`),
  CONSTRAINT `hlavne_menu_ibfk_4` FOREIGN KEY (`id_hlavicka`) REFERENCES `hlavicka` (`id`),
  CONSTRAINT `hlavne_menu_ibfk_5` FOREIGN KEY (`id_hlavne_menu_cast`) REFERENCES `hlavne_menu_cast` (`id`),
  CONSTRAINT `hlavne_menu_ibfk_6` FOREIGN KEY (`id_druh`) REFERENCES `druh` (`id`),
  CONSTRAINT `hlavne_menu_ibfk_7` FOREIGN KEY (`id_user_profiles`) REFERENCES `user_profiles` (`id`),
  CONSTRAINT `hlavne_menu_ibfk_8` FOREIGN KEY (`id_dlzka_novinky`) REFERENCES `dlzka_novinky` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Položky HLAVNÉHO menu';

INSERT INTO `hlavne_menu` (`id`, `spec_nazov`, `id_hlavne_menu_cast`, `id_registracia`, `id_ikonka`, `id_druh`, `uroven`, `id_nadradenej`, `id_user_profiles`, `poradie`, `poradie_podclankov`, `id_hlavicka`, `povol_pridanie`, `zvyrazni`, `pocitadlo`, `nazov_ul_sub`, `absolutna`, `ikonka`, `avatar`, `komentar`, `modified`, `datum_platnosti`, `aktualny_projekt`, `redirect_id`, `id_dlzka_novinky`) VALUES
(1,	'home',	1,	0,	NULL,	1,	0,	NULL,	3,	1,	0,	0,	0,	0,	0,	NULL,	NULL,	NULL,	NULL,	0,	'2017-02-16 07:54:07',	NULL,	0,	NULL,	1),
(2,	'pravidla',	1,	0,	NULL,	1,	0,	NULL,	1,	2,	0,	2,	0,	0,	0,	NULL,	NULL,	NULL,	NULL,	0,	'2017-03-22 05:43:04',	NULL,	0,	NULL,	1),
(3,	'moje-fotky',	1,	1,	NULL,	8,	0,	NULL,	1,	3,	0,	2,	0,	0,	0,	NULL,	NULL,	NULL,	NULL,	0,	'2017-03-22 05:43:04',	NULL,	0,	NULL,	1);

DROP TABLE IF EXISTS `hlavne_menu_cast`;
CREATE TABLE `hlavne_menu_cast` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `nazov` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT 'Časť' COMMENT 'Názov časti',
  `id_registracia` int(11) NOT NULL DEFAULT '5' COMMENT 'Úroveň registrácie pre editáciu',
  `mapa_stranky` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Ak 1 tak je časť zahrnutá do mapy',
  PRIMARY KEY (`id`),
  KEY `id_registracia` (`id_registracia`),
  CONSTRAINT `hlavne_menu_cast_ibfk_1` FOREIGN KEY (`id_registracia`) REFERENCES `registracia` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Časti hlavného menu';

INSERT INTO `hlavne_menu_cast` (`id`, `nazov`, `id_registracia`, `mapa_stranky`) VALUES
(1,	'Hlavná ponuka',	4,	1),
(2,	'Druhá časť',	4,	1);

DROP TABLE IF EXISTS `hlavne_menu_lang`;
CREATE TABLE `hlavne_menu_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `id_lang` int(11) NOT NULL DEFAULT '1' COMMENT 'Id Jazyka',
  `id_hlavne_menu` int(11) NOT NULL COMMENT 'Id hlavného menu, ku ktorému patrí',
  `id_clanok_lang` int(11) DEFAULT NULL COMMENT 'Id jazka článku ak ho má',
  `nazov` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT 'Názov položky pre daný jazyk',
  `h1part2` varchar(100) COLLATE utf8_bin DEFAULT NULL COMMENT 'Druhá časť názvu pre daný jazyk',
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'Popis položky pre daný jazyk',
  PRIMARY KEY (`id`),
  KEY `id_hlavne_menu` (`id_hlavne_menu`),
  KEY `id_lang` (`id_lang`),
  KEY `id_clanok_lang` (`id_clanok_lang`),
  CONSTRAINT `hlavne_menu_lang_ibfk_1` FOREIGN KEY (`id_hlavne_menu`) REFERENCES `hlavne_menu` (`id`),
  CONSTRAINT `hlavne_menu_lang_ibfk_2` FOREIGN KEY (`id_lang`) REFERENCES `lang` (`id`),
  CONSTRAINT `hlavne_menu_lang_ibfk_3` FOREIGN KEY (`id_clanok_lang`) REFERENCES `clanok_lang` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Popis položiek hlavného menu pre iný jazyk';

INSERT INTO `hlavne_menu_lang` (`id`, `id_lang`, `id_hlavne_menu`, `id_clanok_lang`, `nazov`, `h1part2`, `description`) VALUES
(1,	1,	1,	1,	'home',	NULL,	'Mestské Lesy Poprad - Úvodná stránka a pravidlá'),
(2,	1,	2,	2,	'Pravidlá',	'',	'Pravidlá'),
(3,	1,	3,	NULL,	'Môje fotky',	'',	'Môje fotky');

DROP TABLE IF EXISTS `ikonka`;
CREATE TABLE `ikonka` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `nazov` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT 'ikonka' COMMENT 'Kmeňová časť názvu súboru ikonky',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Ikonky';

INSERT INTO `ikonka` (`id`, `nazov`) VALUES
(0,	'---'),
(1,	'info'),
(2,	'kniha'),
(3,	'kvietok'),
(4,	'lienka'),
(5,	'list_ceruza'),
(6,	'list'),
(7,	'listok'),
(8,	'lupa'),
(9,	'pocasie'),
(10,	'slnko'),
(11,	'smerovnik'),
(12,	'topanka'),
(13,	'vykricnik');

DROP TABLE IF EXISTS `komponenty`;
CREATE TABLE `komponenty` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `nazov` varchar(30) COLLATE utf8_bin NOT NULL COMMENT 'Názov použitej komponenty',
  `parametre` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'Názov parametrov oddelený čiarkou',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Zoznam použiteľných komponent';


DROP TABLE IF EXISTS `lang`;
CREATE TABLE `lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `skratka` varchar(3) COLLATE utf8_bin NOT NULL DEFAULT 'sk' COMMENT 'Skratka jazyka',
  `nazov` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT 'Slovenčina' COMMENT 'Miestny názov jazyka',
  `nazov_en` varchar(15) COLLATE utf8_bin NOT NULL DEFAULT 'Slovak' COMMENT 'Anglický názov jazyka',
  `prijaty` tinyint(4) DEFAULT NULL COMMENT 'Ak je > 0 jazyk je možné použiť na Frond',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Jazyky pre web';

INSERT INTO `lang` (`id`, `skratka`, `nazov`, `nazov_en`, `prijaty`) VALUES
(1,	'sk',	'Slovenčina',	'Slovak',	1);

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `text` text COLLATE utf8_bin NOT NULL COMMENT 'Text novinky',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Dátum novinky',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `oznam`;
CREATE TABLE `oznam` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_profiles` int(11) NOT NULL DEFAULT '0' COMMENT 'Id člena, ktorý zadal oznam',
  `id_registracia` int(11) NOT NULL DEFAULT '0' COMMENT 'Úroveň registrácie pre zobrazenie',
  `id_ikonka` int(11) NOT NULL DEFAULT '0' COMMENT 'Id použitej ikonky',
  `datum_platnosti` date NOT NULL DEFAULT '0000-00-00' COMMENT 'Dátum platnosti',
  `datum_zadania` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Dátum zadania oznamu',
  `nazov` varchar(50) DEFAULT NULL COMMENT 'Názov oznamu',
  `text` text COMMENT 'Text oznamu',
  PRIMARY KEY (`id`),
  KEY `id_user_profiles` (`id_user_profiles`),
  KEY `id_registracia` (`id_registracia`),
  KEY `id_ikonka` (`id_ikonka`),
  CONSTRAINT `oznam_ibfk_1` FOREIGN KEY (`id_user_profiles`) REFERENCES `user_profiles` (`id`),
  CONSTRAINT `oznam_ibfk_2` FOREIGN KEY (`id_registracia`) REFERENCES `registracia` (`id`),
  CONSTRAINT `oznam_ibfk_3` FOREIGN KEY (`id_ikonka`) REFERENCES `ikonka` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `oznam_volba`;
CREATE TABLE `oznam_volba` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `volba` varchar(30) COLLATE utf8_bin NOT NULL COMMENT 'Popis voľby',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Volby pre potvrdenie účasti';

INSERT INTO `oznam_volba` (`id`, `volba`) VALUES
(1,	'Áno'),
(2,	'Asi áno'),
(3,	'50 na 50'),
(4,	'Asi nie'),
(5,	'Nie');

DROP TABLE IF EXISTS `registracia`;
CREATE TABLE `registracia` (
  `id` int(11) NOT NULL COMMENT '[A]Index',
  `role` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT 'guest' COMMENT 'Názov pre ACL',
  `nazov` varchar(30) COLLATE utf8_bin NOT NULL DEFAULT 'Registracia cez web' COMMENT 'Názov úrovne registrácie',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Úrovne registrácie a ich názvy';

INSERT INTO `registracia` (`id`, `role`, `nazov`) VALUES
(0,	'guest',	'Bez registrácie'),
(1,	'register',	'Registrácia cez web'),
(2,	'pasivny',	'Registrovaný člen'),
(3,	'aktivny',	'Aktívny člen'),
(4,	'spravca',	'Správca obsahu'),
(5,	'admin',	'Administrátor');

DROP TABLE IF EXISTS `slider`;
CREATE TABLE `slider` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `poradie` int(11) NOT NULL DEFAULT '1' COMMENT 'Určuje poradie obrázkov v slidery',
  `nadpis` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT 'Nadpis obrázku',
  `popis` varchar(150) COLLATE utf8_bin NOT NULL DEFAULT 'Popis obrázku' COMMENT 'Popis obrázku slideru vypisovaný v dolnej časti',
  `subor` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '*.jpg' COMMENT 'Názov obrázku slideru aj s relatívnou cestou',
  `zobrazenie` varchar(20) COLLATE utf8_bin DEFAULT NULL COMMENT 'Kedy sa obrázok zobrazí',
  `id_hlavne_menu` int(11) DEFAULT NULL COMMENT 'Odkaz na položku hlavného menu',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Popis obrázkou slideru aj s názvami súborov';

INSERT INTO `slider` (`id`, `poradie`, `nadpis`, `popis`, `subor`, `zobrazenie`, `id_hlavne_menu`) VALUES
(1,	1,	NULL,	'',	'01_obora.jpg',	'',	NULL),
(2,	1,	NULL,	'',	'02_mravenisko.jpg',	'',	NULL),
(3,	1,	NULL,	'',	'03_ml.jpg',	'',	NULL),
(4,	1,	NULL,	'',	'04_poprad.jpg',	'',	NULL),
(5,	1,	NULL,	'',	'05_tatry.jpg',	'',	NULL),
(6,	1,	NULL,	'',	'06_preslop.jpg',	'',	NULL),
(7,	1,	NULL,	'',	'07_obloha.jpg',	'',	NULL),
(8,	1,	NULL,	'',	'08_preslop.jpg',	'',	NULL),
(9,	1,	NULL,	'',	'09_pavilon.jpg',	'',	NULL);

DROP TABLE IF EXISTS `udaje`;
CREATE TABLE `udaje` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `id_registracia` int(11) NOT NULL DEFAULT '5' COMMENT 'Aká úroveň môže danú hodnotu editovať.',
  `id_druh` int(11) DEFAULT NULL COMMENT 'Druhová skupina pre nastavenia',
  `id_udaje_typ` int(11) NOT NULL DEFAULT '1' COMMENT 'Typ input-u',
  `nazov` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT 'nazov' COMMENT 'Názov prvku',
  `text` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT 'Definícia' COMMENT 'Hodnota prvku',
  `comment` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'Komentár k hodnote',
  PRIMARY KEY (`id`),
  KEY `id_registracia` (`id_registracia`),
  KEY `id_druh` (`id_druh`),
  KEY `id_udaje_typ` (`id_udaje_typ`),
  CONSTRAINT `udaje_ibfk_1` FOREIGN KEY (`id_registracia`) REFERENCES `registracia` (`id`),
  CONSTRAINT `udaje_ibfk_2` FOREIGN KEY (`id_druh`) REFERENCES `druh` (`id`),
  CONSTRAINT `udaje_ibfk_3` FOREIGN KEY (`id_udaje_typ`) REFERENCES `udaje_typ` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tabuľka na uschovanie základných údajov o stránke';

INSERT INTO `udaje` (`id`, `id_registracia`, `id_druh`, `id_udaje_typ`, `nazov`, `text`, `comment`) VALUES
(1,	5,	NULL,	1,	'titulka-sk',	'Mestské lesy Poprad',	'Názov zobrazený v titulke'),
(2,	4,	NULL,	1,	'titulka_2-sk',	'Súťaž',	'Druhá časť titulky pre jazyk: sk'),
(3,	4,	NULL,	1,	'titulka_citat_enable',	'0',	'Povolenie zobrazenia citátu'),
(4,	4,	NULL,	1,	'titulka_citat_podpis',	'',	'Podpis pod citát na titulke'),
(5,	4,	NULL,	1,	'titulka_citat-sk',	'',	'Text citátu, ktorý sa zobrazí na titulke pre jazyk: sk'),
(6,	5,	NULL,	1,	'keywords-sk',	'Mestské lesy Poprad, Turistika, oddych, ochrana životného prostredia',	'Kľúčové slová'),
(7,	5,	NULL,	1,	'autor',	'Ing. Peter VOJTECH ml., Mgr. Jozef PETRENČÍK',	'Autor stránky'),
(8,	4,	NULL,	1,	'log_out-sk',	'Odhlás sa...',	'Text pre odkaz na odhlásenie sa'),
(9,	4,	NULL,	1,	'log_in-sk',	'Prihlás sa',	'Text pre odkaz na prihlásenie sa'),
(10,	4,	NULL,	1,	'forgot_password-sk',	'Zabudnuté heslo?',	'Text pre odkaz na zabudnuté heslo'),
(11,	4,	NULL,	1,	'register-sk',	'Registrácia',	'Text pre odkaz na registráciu'),
(12,	4,	NULL,	1,	'last_update-sk',	'Posledná aktualizácia',	'Text pre odkaz na poslednú aktualizáciu'),
(13,	4,	NULL,	1,	'spravca-sk',	'Správca obsahu',	'Text pre odkaz na správcu'),
(14,	4,	NULL,	1,	'copy',	'MLPP',	'Text, ktorý sa vypíše za znakom copyright-u'),
(15,	4,	NULL,	1,	'no_exzist-sk',	'To čo hľadáte nie je ešte v tomto jazyku vytvorené!',	'Text ak položka v danom jazyku neexzistuje pre jazyk:sk'),
(16,	4,	NULL,	1,	'nazov_uvod-sk',	'Úvod',	'Text pre odkaz na východziu stránku pre jazyk:sk'),
(17,	5,	NULL,	3,	'komentare',	'0',	'Globálne povolenie komentárov'),
(18,	4,	NULL,	3,	'registracia_enabled',	'1',	'Globálne registrácie(ak 1 tak áno, ak 0 tak nie)'),
(19,	4,	1,	1,	'clanok_hlavicka',	'3',	'Nastavuje, ktoré hodnoty sa zobrazia v hlavičke článku Front modulu. Výsledok je súčet čísel.[1=Dátum, 2=Zadávateľ, 4=Počet zobrazení]'),
(21,	4,	5,	3,	'oznam_komentare',	'0',	'Povolenie komentárov k aktualitám(oznamom).'),
(22,	5,	5,	2,	'oznam_usporiadanie',	'1',	'Usporiadanie aktualít podľa dátumu platnosti. [1=od najstaršieho; 0=od najmladšieho]'),
(23,	4,	5,	3,	'oznam_ucast',	'0',	'Povolenie potvrdenia účasti.'),
(24,	5,	5,	1,	'oznam_prva_stranka',	'0',	'Id stránky, ktorá sa zobrazí ako 1. po načítaní webu'),
(25,	4,	5,	3,	'oznam_title_image_en',	'1',	'Povolenie pridávania titulného obrázku k oznamu. Ak je zakázané používajú sa ikonky.'),
(26,	4,	8,	1,	'max_pocet_foto',	'5',	'Maximálny počet fotiek pre jedného užívateľa. Ak je 0 tak neobmedzený.');

DROP TABLE IF EXISTS `udaje_typ`;
CREATE TABLE `udaje_typ` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `nazov` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT 'text' COMMENT 'Typ input-u pre danú položku',
  `comment` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT 'Text' COMMENT 'Popis navonok',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Typy prvkov pre tabuľku udaje';

INSERT INTO `udaje_typ` (`id`, `nazov`, `comment`) VALUES
(1,	'text',	'Text'),
(2,	'radio',	'Vyber jednu možnosť'),
(3,	'checkbox',	'Áno alebo nie');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '1',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `ban_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `new_password_key` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_password_requested` datetime DEFAULT NULL,
  `new_email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `new_email_key` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `users` (`id`, `username`, `password`, `email`, `activated`, `banned`, `ban_reason`, `new_password_key`, `new_password_requested`, `new_email`, `new_email_key`, `last_ip`, `created`, `modified`) VALUES
(1,	'petov',	'$2a$08$pWTgI.3Vkx.1GsoyX.ov7O7/YyN3P/pispAAgYQJdUG6V7LFt8oNq',	'petak23@gmail.com',	1,	0,	NULL,	NULL,	NULL,	NULL,	NULL,	'217.12.48.22',	'0000-00-00 00:00:00',	'2014-12-08 06:48:37'),
(2,	'robo',	'$2a$08$pyutyDEVhMzj0EgyZ6K5Z.7IJklZPQo9l0avi2bqv8xlK2MGErGIi',	'dula.robert@mail.t-com.sk',	1,	0,	NULL,	NULL,	NULL,	NULL,	NULL,	'',	'0000-00-00 00:00:00',	'2017-02-13 08:38:27'),
(3,	'jozue',	'$2a$08$2AkVBGbpNKkHppPC89TNqO4I7ZSiHDD/UVhNQecVVaqHB5VU1pvFS',	'jozue@anigraph.eu',	1,	0,	NULL,	NULL,	NULL,	NULL,	NULL,	'',	'0000-00-00 00:00:00',	'2017-02-13 08:54:07');

DROP TABLE IF EXISTS `user_prihlasenie`;
CREATE TABLE `user_prihlasenie` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `id_user_profiles` int(11) NOT NULL COMMENT 'Id člena, ktorý sa prihlásil',
  `prihlasenie_datum` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Dátum a čas prihlásenia',
  PRIMARY KEY (`id`),
  KEY `id_user_profiles` (`id_user_profiles`),
  CONSTRAINT `user_prihlasenie_ibfk_1` FOREIGN KEY (`id_user_profiles`) REFERENCES `user_profiles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Evidencia prihlásenia užívateľov';

INSERT INTO `user_prihlasenie` (`id`, `id_user_profiles`, `prihlasenie_datum`) VALUES
(1,	1,	'2017-03-22 06:38:10');

DROP TABLE IF EXISTS `user_profiles`;
CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_users` int(11) NOT NULL COMMENT 'Id v tabuľke users',
  `id_registracia` int(11) NOT NULL DEFAULT '0' COMMENT 'Úroveň registrácie',
  `meno` varchar(50) COLLATE utf8_bin NOT NULL COMMENT 'Meno',
  `priezvisko` varchar(50) COLLATE utf8_bin NOT NULL COMMENT 'Priezvisko',
  `rok` int(11) DEFAULT NULL COMMENT 'Rok narodenia',
  `telefon` varchar(30) COLLATE utf8_bin DEFAULT NULL COMMENT 'Telefón',
  `poznamka` varchar(255) COLLATE utf8_bin DEFAULT NULL COMMENT 'Poznámka',
  `pocet_pr` int(11) NOT NULL DEFAULT '0' COMMENT 'Počet prihlásení',
  `pohl` enum('Z','M') COLLATE utf8_bin NOT NULL DEFAULT 'Z' COMMENT 'Pohlavie',
  `prihlas_teraz` datetime DEFAULT NULL COMMENT 'Posledné prihlásenie',
  `prihlas_predtym` datetime DEFAULT NULL COMMENT 'Predposledné prihlásenie',
  `avatar_25` varchar(200) COLLATE utf8_bin DEFAULT NULL COMMENT 'Cesta k avatarovi veľkosti 25x25',
  `avatar_75` varchar(200) COLLATE utf8_bin DEFAULT NULL COMMENT 'Cesta k avatarovi veľkosti 75x75',
  `foto` varchar(50) COLLATE utf8_bin DEFAULT NULL COMMENT 'Názov fotky člena',
  `news` enum('A','N') COLLATE utf8_bin NOT NULL DEFAULT 'A' COMMENT 'Posielanie info emailou',
  `created` datetime DEFAULT NULL COMMENT 'Dátum vytvorenia člena',
  `modified` datetime DEFAULT NULL COMMENT 'Posledná zmena',
  PRIMARY KEY (`id`),
  KEY `user_id` (`id_users`),
  KEY `id_registracia` (`id_registracia`),
  CONSTRAINT `user_profiles_ibfk_2` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`),
  CONSTRAINT `user_profiles_ibfk_3` FOREIGN KEY (`id_registracia`) REFERENCES `registracia` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `user_profiles` (`id`, `id_users`, `id_registracia`, `meno`, `priezvisko`, `rok`, `telefon`, `poznamka`, `pocet_pr`, `pohl`, `prihlas_teraz`, `prihlas_predtym`, `avatar_25`, `avatar_75`, `foto`, `news`, `created`, `modified`) VALUES
(1,	1,	5,	'Peter',	'VOJTECH',	NULL,	NULL,	'Administrátor',	1,	'M',	'2017-03-22 06:38:11',	NULL,	'files/1/evjv4xbig05stnnihm3hbtj7f_25.jpg',	'files/1/evjv4xbig05stnnihm3hbtj7f_75.jpg',	NULL,	'A',	'2013-01-03 11:17:32',	'2017-03-22 06:43:42'),
(2,	2,	4,	'Róbert',	'DULA',	NULL,	NULL,	NULL,	0,	'M',	NULL,	NULL,	NULL,	NULL,	NULL,	'A',	'2017-02-13 08:38:27',	'2017-02-13 08:38:27'),
(3,	3,	4,	'Jozef',	'PETRENČÍK',	NULL,	NULL,	NULL,	0,	'M',	NULL,	NULL,	NULL,	NULL,	NULL,	'A',	'2017-02-13 08:54:07',	'2017-02-13 08:54:07');

DROP TABLE IF EXISTS `verzie`;
CREATE TABLE `verzie` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `id_user_profiles` int(11) NOT NULL DEFAULT '1' COMMENT 'Id člena, ktorý zadal verziu',
  `cislo` varchar(10) COLLATE utf8_bin NOT NULL DEFAULT '',
  `subory` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `text` text COLLATE utf8_bin,
  `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cislo` (`cislo`),
  KEY `datum` (`datum`),
  KEY `id_clena` (`id_user_profiles`),
  CONSTRAINT `verzie_ibfk_1` FOREIGN KEY (`id_user_profiles`) REFERENCES `user_profiles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `verzie` (`id`, `id_user_profiles`, `cislo`, `subory`, `text`, `datum`) VALUES
(1,	1,	'0.1.',	NULL,	'Východzia verzia',	'2017-02-13 08:03:32');

-- 2017-03-22 16:48:40
