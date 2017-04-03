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
(2,	'Lang:',	'Editácia jazykov',	5,	'ikonky/AzulLustre_icons/Webfolder.png'),
(3,	'Slider:',	'Editácia slider-u',	4,	'ikonky/AzulLustre_icons/Imagenes.png'),
(4,	'User:',	'Editácia členov',	5,	'ikonky/AzulLustre_icons/Fuentes.png'),
(5,	'Verzie:',	'Verzie webu',	4,	'ikonky/AzulLustre_icons/URL_historial.png'),
(6,	'Udaje:',	'Údaje webu',	4,	'ikonky/AzulLustre_icons/Admin.png');

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

INSERT INTO `clanok_komponenty` (`id`, `id_hlavne_menu`, `spec_nazov`, `parametre`) VALUES
(1,	1,	'tableOfUsers',	NULL),
(2,	6,	'tableOfUsers',	NULL);

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
(1,	1,	'<p>Úvodná stránka súťaže.</p>',	''),
(2,	1,	'<p>\n	T&aacute;to str&aacute;nka obsahuje pravidl&aacute; s&uacute;ťaže, ktor&eacute; s&uacute; tu nap&iacute;san&eacute;.</p>\n<p>\n	Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus quis lectus metus, at posuere neque. Sed pharetra nibh eget orci convallis at posuere leo convallis. Sed blandit augue vitae augue scelerisque bibendum. Vivamus sit amet libero turpis, non venenatis urna. In blandit, odio convallis suscipit venenatis.</p>\n<p>\n	Ante ipsum cursus augue, et mollis nunc diam eget sapien. Nulla facilisi. Etiam feugiat imperdiet rhoncus. Sed suscipit bibendum enim, sed volutpat tortor malesuada non. Morbi fringilla dui non purus porttitor mattis. Suspendisse quis vulputate risus. Phasellus erat velit, sagittis sed varius volutpat, placerat nec urna.</p>\n<p>\n	Nam eu metus vitae dolor fringilla feugiat. Nulla facilisi. Etiam enim metus, luctus in adipiscing at, consectetur quis sapien. Duis imperdiet egestas ligula, quis hendrerit ipsum ullamcorper et. Phasellus id tristique orci. Proin consequat mi at felis scelerisque ullamcorper. Etiam tempus, felis vel eleifend porta, velit.</p>\n',	'Pripravili sme pre Vás návod ako pridávať príspevky do súťaže.'),
(3,	1,	'<p>\n	Objav&nbsp;pr&iacute;rodn&eacute; prostredie mestsk&yacute;ch lesov, kr&aacute;su a&nbsp;rozmanitosť pr&iacute;rody a&nbsp;hist&oacute;rie. Zaznamenaj r&ocirc;znou formou zauj&iacute;mavosti &uacute;zemia, pom&ocirc;ž k&nbsp;vytvoreniu nov&yacute;ch poznatkov o&nbsp;&uacute;zem&iacute; a <strong>vyhraj zauj&iacute;mav&eacute; ceny</strong>.&nbsp;S&uacute;ťaž je určen&aacute; žiakom &scaron;k&ocirc;l na &uacute;zem&iacute; mesta Poprad a jeho občanov, ktor&iacute; sa do s&uacute;ťaže zaregistruj&uacute; na str&aacute;ne sutaz.lesypoprad.sk. S&uacute;ťaž potrv&aacute; v obdob&iacute; od&nbsp;od 7.4. - do 30. 9 2017.&nbsp;S&uacute;ťaže sa m&ocirc;žu z&uacute;častniť aj t&iacute;my žiakov z rovnakej &scaron;koly, ktor&iacute; sa do s&uacute;ťaže prihl&aacute;sia spoločne.</p>\n',	''),
(4,	1,	'',	'Tabuľka súťažiacich s aktuálnym počtom príspevkov. Pozrite sa ako na tom ste...'),
(5,	1,	'<p>\n	<strong>Čl&aacute;nok 1: &Uacute;vodn&eacute; ustanovenie</strong></p>\n<p>\n	Tento &scaron;tat&uacute;t určuje pravidl&aacute;&nbsp; s&uacute;ťaže s&nbsp;n&aacute;zvom &bdquo;Pozn&aacute;vaj mestsk&eacute; lesy Poprad&ldquo; pre &scaron;koly a&nbsp;občanov mesta Poprad.</p>\n<p>\n	<strong>Čl&aacute;nok 2: Cieľ s&uacute;ťaže</strong></p>\n<p>\n	Cieľom s&uacute;ťaže je:&nbsp;podporiť z&aacute;ujem o&nbsp;pr&iacute;rodn&eacute; prostredie mestsk&yacute;ch lesov, jeho živ&uacute; a&nbsp;neživ&uacute; zložku, kr&aacute;su a&nbsp;rozmanitosť pr&iacute;rody a&nbsp;hist&oacute;rie, podnietiť r&ocirc;zne formy zaznamen&aacute;vania zauj&iacute;mavost&iacute; &uacute;zemia a&nbsp;napom&ocirc;cť tak k&nbsp;vytv&aacute;rania poznatkov o&nbsp;predmetnom &uacute;zem&iacute;.</p>\n<p>\n	<strong>Čl&aacute;nok 3: Vyhlasovateľ a&nbsp;organiz&aacute;tor s&uacute;ťaže</strong></p>\n<p>\n	1. Vyhlasovateľom s&uacute;ťaže s&uacute; Mestsk&eacute; lesy, s.r.o. Poprad.</p>\n<p>\n	2. Organizačn&yacute;m a&nbsp;odborn&yacute;m garantom s&uacute;ťaže je Mestsk&yacute; &uacute;rad Poprad, oddelenie strategick&eacute;ho rozvojov&eacute;ho manažmentu.</p>\n<p>\n	<strong>Čl&aacute;nok 4: Pravidl&aacute;&nbsp;s&uacute;ťaže</strong></p>\n<p>\n	1. S&uacute;ťaž je určen&aacute;&nbsp; žiakom &scaron;k&ocirc;l na &uacute;zem&iacute; mesta Poprad a&nbsp;jeho občanov, ktor&iacute; sa do s&uacute;ťaže zaregistruj&uacute; na str&aacute;ne www.lesypoprad.sk a&nbsp;tak potvrdia, že bud&uacute; v&nbsp;obdob&iacute; od 7.4. - &nbsp;do 30. 9 2017 zaznamen&aacute;vať n&aacute;jden&eacute; pr&iacute;rodn&eacute; zložky prostredia a&nbsp;bud&uacute; ich prezentovať r&ocirc;znou formou.. S&uacute;ťaže sa m&ocirc;žu z&uacute;častniť aj t&iacute;my žiakov z rovnakej &scaron;koly, ktor&iacute; sa do s&uacute;ťaže prihl&aacute;sia spoločne.</p>\n<p>\n	2. Každ&yacute; s&uacute;ťažiaci sa m&ocirc;že do s&uacute;ťaže zaregistrovať iba 1x. Opakovan&aacute; registr&aacute;cia bude považovan&aacute; za poru&scaron;enie t&yacute;chto pravidiel. V&scaron;etky t&iacute;my, v&nbsp;ktor&yacute;ch je tak&yacute;to s&uacute;ťažiaci zaregistrovan&yacute;, sa nebud&uacute; m&ocirc;cť zaradiť do zlosovania o&nbsp;ceny.</p>\n<p>\n	3. Vyplnen&iacute;m prihlasovacieho formul&aacute;ra každ&yacute; s&uacute;ťažiaci potvrdzuje, že bude re&scaron;pektovať pravidl&aacute; s&uacute;ťaže.</p>\n<p>\n	4. Pozn&aacute;vanie&nbsp; n&aacute;jden&yacute;ch pr&iacute;rodn&iacute;n (druhy rastl&iacute;n, živoč&iacute;chov, nerastov), sa m&ocirc;že vykon&aacute;vať v&yacute;lučne prostredn&iacute;ctvom fotoapar&aacute;tu, telef&oacute;nu a&nbsp;ich zaznamen&aacute;vanie cez internetov&eacute; aplik&aacute;cie alebo r&ocirc;zne poč&iacute;tačov&eacute; spracovanie. &nbsp;&nbsp;&nbsp;Ku každ&eacute;mu n&aacute;jden&eacute;mu druhu je potrebn&eacute; nap&iacute;sať jeho meno slovensk&eacute; a&nbsp;latinsk&eacute; a&nbsp;taktiež miesto v&yacute;skytu na mape.</p>\n<p>\n	Je zak&aacute;zan&eacute; tieto trhať , zbierať, usmrcovať.</p>\n<p>\n	Jednotliv&eacute; druhy sa obrazovo nasn&iacute;maj&uacute; a po&scaron;l&uacute; na str&aacute;nku mestsk&yacute;ch lesov na to určen&uacute;. Miesto v&yacute;skytu sa zaznamen&aacute; cez gps s&uacute;radice a&nbsp;tieto sa vložia do google mapy.</p>\n<p>\n	Vlastn&eacute; &uacute;zemie Mestsk&yacute;ch lesov Poprad je zobrazen&eacute; na internetovej str&aacute;nke s&uacute;ťaže.</p>\n<p>\n	b) umelecky zachyten&eacute; prostredie mestsk&yacute;ch lesov (fotografia, film, v&yacute;tvarn&aacute; pr&aacute;ca),</p>\n<p>\n	<strong>Pozn&aacute;vanie mestsk&yacute;ch lesov touto formou je zachytenie určitej časti &nbsp;mestsk&yacute;ch lesov cez r&ocirc;zne formy umeleck&eacute;ho&nbsp; prejavu a&nbsp;to fotografia, film, v&yacute;tvarn&aacute; pr&aacute;ca.</strong></p>\n<p>\n	<strong>Tieto sa po&scaron;l&uacute; na str&aacute;nku pozn&aacute;vaj lesypoprad , film jeho zaslan&iacute;m na pam&auml;tovom m&eacute;dia na s&iacute;dlo spoločnosti.</strong></p>\n<p>\n	&nbsp;</p>\n<p>\n	<strong>Čl&aacute;nok 5: Realiz&aacute;cia a term&iacute;ny s&uacute;ťaže</strong></p>\n<p>\n	1. Term&iacute;n začatia s&uacute;ťaže je 7. apr&iacute;l 2017.</p>\n<p>\n	2. S&uacute;ťažiaci vypln&iacute; registračn&yacute; formul&aacute;ra v&nbsp;term&iacute;ne najnesk&ocirc;r do 18. apr&iacute;la 2017 na pr&iacute;slu&scaron;nej webovej str&aacute;nke s&uacute;ťaže&nbsp;<a href=\"http://www.lesypoprad.sk/\" target=\"_blank\">www.lesypoprad.sk</a></p>\n<p>\n	Povinn&eacute; &uacute;daje prihlasovacieho formul&aacute;ra&nbsp; s&uacute;:</p>\n<p>\n	1. meno a&nbsp;priezvisko jednotlivca</p>\n<p>\n	2. bydlisko</p>\n<p>\n	3. e-mail, telef&oacute;n</p>\n<p>\n	4. n&aacute;zov &scaron;koly,</p>\n<p>\n	5. potvrdenie podmienok pre registr&aacute;ciu do s&uacute;ťaže,</p>\n<p>\n	6. potvrdenie s&uacute;hlasu s&nbsp;pravidlami s&uacute;ťaže,</p>\n<p>\n	7. S&uacute;ťažiaci t&iacute;m:</p>\n<p>\n	8. meno poverenej osoby zodpovednej za t&iacute;mov&uacute;&nbsp; skupinu,</p>\n<p>\n	9. kontaktn&eacute; &uacute;daje koordin&aacute;tora.</p>\n<p>\n	v&nbsp;term&iacute;ne najnesk&ocirc;r do 5.5.2017 sa zaregistruje na prihlasovacom online formul&aacute;ri uverejnenom na pr&iacute;slu&scaron;nej webovej str&aacute;nke s&uacute;ťaže s&nbsp;uveden&iacute;m požadovan&yacute;ch povinn&yacute;ch &uacute;dajov</p>\n<p>\n	3. Term&iacute;n uz&aacute;vierky s&uacute;ťaže je 31.5.2017.</p>\n<p>\n	4. &Scaron;tat&uacute;t s&uacute;ťaže je zverejnen&yacute; na webovom s&iacute;dle&nbsp;<a href=\"http://www.lesypoprad.sk/\" target=\"_blank\">www.lesypoprad.sk</a>&nbsp;(v z&aacute;ložke s&uacute;ťaž pozn&aacute;vaj mestsk&eacute; lesy)</p>\n<p>\n	5. Term&iacute;n a miesto konania sl&aacute;vnostn&eacute;ho vyhodnotenia bude uverejnen&yacute; na webovej str&aacute;nke s&uacute;ťaže,</p>\n<p>\n	<strong>Čl&aacute;nok 6: S&uacute;ťažn&eacute; kateg&oacute;rie</strong></p>\n<p>\n	1. Vyhodnocovať sa bud&uacute; tri s&uacute;ťažn&eacute; kateg&oacute;rie :</p>\n<p>\n	a) počet n&aacute;jden&yacute;ch pr&iacute;rodn&iacute;n (druhy rastl&iacute;n, živoč&iacute;chov, nerastov),</p>\n<p>\n	b) umelecky zachyten&eacute; prostredie mestsk&yacute;ch lesov (fotografia, film, v&yacute;tvarn&aacute; pr&aacute;ca),</p>\n<p>\n	c) &scaron;kola &nbsp;v ktorej sa zapojilo najviac s&uacute;ťažiacich (vyhodnocuje sa na z&aacute;klade počtu prihl&aacute;sen&yacute;ch žiakov z&nbsp;jednej &scaron;koly)..</p>\n<p>\n	<strong>Čl&aacute;nok 7: Osobitn&eacute; ustanovenia</strong></p>\n<p>\n	1. Kontakty na s&uacute;ťažiacich a ved&uacute;cich t&iacute;mov (e-mail, telef&oacute;n) zverejnen&eacute; nebud&uacute; a&nbsp;sl&uacute;žia len pre potreby organiz&aacute;tora s&uacute;ťaže.</p>\n<p>\n	<strong>Čl&aacute;nok 8: Z&aacute;verečn&eacute; ustanovenia</strong></p>\n<p>\n	1. Tento &scaron;tat&uacute;t je schv&aacute;len&yacute; vyhlasovateľom s&uacute;ťaže.</p>\n<p>\n	2. &Scaron;tat&uacute;t je zverejnen&yacute; na <a href=\"sutaz.lesypoprad.sk/\">sutaz.lesypoprad.sk</a></p>\n<p>\n	3. Tento &scaron;tat&uacute;t nadob&uacute;da &uacute;činnosť dňom jeho podp&iacute;sania.</p>\n<p>\n	&nbsp;</p>\n',	'');

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
  `id_dokumenty_kategoria` int(11) DEFAULT NULL COMMENT 'Kategória dokumentov',
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
  `lat` float NOT NULL COMMENT 'sirka',
  `lng` float NOT NULL COMMENT 'vyska',
  PRIMARY KEY (`id`),
  UNIQUE KEY `spec_nazov` (`spec_nazov`),
  KEY `id_user_profiles` (`id_user_profiles`),
  KEY `id_registracia` (`id_registracia`),
  KEY `id_hlavne_menu` (`id_hlavne_menu`),
  KEY `id_dokumenty_kategoria` (`id_dokumenty_kategoria`),
  CONSTRAINT `dokumenty_ibfk_2` FOREIGN KEY (`id_user_profiles`) REFERENCES `user_profiles` (`id`),
  CONSTRAINT `dokumenty_ibfk_3` FOREIGN KEY (`id_hlavne_menu`) REFERENCES `hlavne_menu` (`id`),
  CONSTRAINT `dokumenty_ibfk_4` FOREIGN KEY (`id_dokumenty_kategoria`) REFERENCES `dokumenty_kategoria` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `dokumenty_kategoria`;
CREATE TABLE `dokumenty_kategoria` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `nazov` varchar(30) COLLATE utf8_bin NOT NULL COMMENT 'Názov kategórie',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Kategórie dokumentov';

INSERT INTO `dokumenty_kategoria` (`id`, `nazov`) VALUES
(1,	'Poznávanie druhov'),
(2,	'Vizuálne dielo');

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
(7,	'dokumenty',	NULL,	'Dokumenty',	'Vkladanie dokumentov do stránky',	0,	0,	0),
(8,	'my',	NULL,	'My',	'Vypísanie profilu a správu príloh',	1,	0,	1);

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
(1,	'home',	1,	0,	NULL,	1,	0,	NULL,	3,	1,	0,	1,	0,	0,	0,	NULL,	'Homepage:',	'',	NULL,	0,	'2017-03-28 07:25:26',	NULL,	0,	NULL,	1),
(2,	'ako-sutazit',	1,	0,	NULL,	1,	0,	NULL,	2,	2,	0,	2,	0,	0,	0,	'mlpp_sutaz_title_ako',	NULL,	'',	'nu9ivn96fe0chhi.png',	0,	'2017-04-03 07:05:28',	NULL,	0,	NULL,	1),
(4,	'moje-prispevky',	1,	1,	NULL,	8,	0,	NULL,	1,	3,	0,	2,	0,	0,	0,	NULL,	NULL,	'',	NULL,	0,	'2017-03-20 09:12:14',	NULL,	0,	NULL,	1),
(5,	'najdi-odfot-posli-a-vyhraj',	2,	0,	NULL,	1,	0,	NULL,	2,	1,	0,	0,	0,	0,	0,	'mlpp_sutaz_title',	NULL,	'',	NULL,	0,	'2017-04-03 07:05:46',	NULL,	0,	NULL,	1),
(6,	'zoznam-sutaziacich',	1,	0,	NULL,	1,	0,	NULL,	1,	4,	0,	0,	0,	0,	0,	'mlpp_sutaz_title_ako',	NULL,	'',	'2eyckipuwu169de.png',	0,	'2017-03-31 09:11:02',	NULL,	0,	NULL,	1),
(7,	'o-sutazi',	1,	0,	NULL,	1,	0,	NULL,	2,	5,	0,	0,	0,	0,	0,	NULL,	NULL,	'',	NULL,	0,	'2017-04-03 07:04:06',	NULL,	0,	NULL,	1);

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
(1,	1,	1,	1,	'Úvod',	'',	'Mestské Lesy Poprad - Úvodná stránka'),
(2,	1,	2,	2,	'Ako súťažiť',	'',	'Mestské lesy Poprad - Ako súťažiť'),
(4,	1,	4,	NULL,	'Moje príspevky',	'',	'Moje príspevky'),
(5,	1,	5,	3,	'Nájdi, odfoť, pošli a vyhraj.',	'',	'Nájdi, odfoť, pošli a vyhraj.'),
(6,	1,	6,	4,	'Zoznam súťažiacich',	'',	'Zoznam súťažiacich'),
(7,	1,	7,	5,	'Štatút súťaže',	'',	'Štatút súťaže');

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

DROP TABLE IF EXISTS `skoly`;
CREATE TABLE `skoly` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `nazov` varchar(50) COLLATE utf8_bin NOT NULL COMMENT 'Názov školy',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Zoznam škôl';

INSERT INTO `skoly` (`id`, `nazov`) VALUES
(1,	'Cirkevné gymnázium P. U. Olivu'),
(2,	'Gymnázium Kukučínova'),
(3,	'Obchodná akadémia'),
(4,	'SOŠ elektrotechnická'),
(5,	'SOŠ technická'),
(6,	'SOŠ, Okružná'),
(7,	'Spojená škola, Dominika Tatarku'),
(8,	'Spojená škola, Partizánska2'),
(9,	'Spojená škola, Ulica mládeže'),
(10,	'Spojená škola, Letná ulica'),
(11,	'Stredná priemyselná škola'),
(12,	'Stredná zdravotnícka škola'),
(13,	'SSOŠ Tatranská akadémia'),
(14,	'Súkromná SOŠ, ulica SNP'),
(15,	'Súkromné gymnázium, Letná ulica'),
(16,	'Súkromné gymnázium, Rovná'),
(17,	'Súkromná ZŠ, Rovná'),
(18,	'ZŠ, Dostojevského ulica'),
(19,	'ZŠ, Francisciho ulica'),
(20,	'ZŠ, Jarná ulica'),
(21,	'ZŠ, Komenského ulica'),
(22,	'ZŠ, Koperníkova ulica, PP-Matejovce'),
(23,	'ZŠ, Štefana Mnoheľa'),
(24,	'ZŠ, Tajovského ulica'),
(25,	'ZŠ, Ulica Fraňa Kráľa'),
(26,	'ZŠ, Vagonárska ulica');

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
(1,	5,	NULL,	1,	'titulka-sk',	'Objav mestské lesy',	'Názov zobrazený v titulke'),
(2,	4,	NULL,	1,	'titulka_2-sk',	'Poprad',	'Druhá časť titulky pre jazyk: sk'),
(3,	4,	NULL,	1,	'titulka_citat_enable',	'0',	'Povolenie zobrazenia citátu'),
(4,	4,	NULL,	1,	'titulka_citat_podpis',	'Nájdi, odfoť, pošli a vyhraj.',	'Podpis pod citát na titulke'),
(5,	4,	NULL,	1,	'titulka_citat-sk',	'Objav prírodné prostredie mestských lesov, krásu a rozmanitosť prírody a histórie. Zaznamenaj rôznou formou zaujímavosti územia, pomôž k vytvoreniu nových poznatkov o území a vyhraj zaujímavé ceny. Súťaž je určená žiakom škôl na území mesta Poprad a jeho',	'Text citátu, ktorý sa zobrazí na titulke pre jazyk: sk'),
(6,	5,	NULL,	1,	'keywords-sk',	'Mestské lesy Poprad, Turistika, oddych, ochrana životného prostredia, súťaž, objavovanie',	'Kľúčové slová'),
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
(19,	4,	1,	1,	'clanok_hlavicka',	'1',	'Nastavuje, ktoré hodnoty sa zobrazia v hlavičke článku Front modulu. Výsledok je súčet čísel.[1=Dátum, 2=Zadávateľ, 4=Počet zobrazení]'),
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
(3,	'jozue',	'$2a$08$42jtlR2mq4RA95ozlGuu5uDaZ234kysLxEskByf/Tiy0szwORIz8u',	'jozue@anigraph.eu',	1,	0,	NULL,	NULL,	NULL,	NULL,	NULL,	'',	'0000-00-00 00:00:00',	'2017-03-31 13:20:02'),
(4,	'franto',	'$2a$08$PhVN909ty4HWA5PuJhrciOpqVpQvK.gG7g6iPhgfvPvYIoCTFsbEO',	'sutaz@echo-msz.eu',	1,	0,	NULL,	NULL,	NULL,	NULL,	NULL,	'',	'0000-00-00 00:00:00',	'2017-03-31 10:19:45'),
(5,	'jozue',	'$2a$08$hewgPenLjC1Kh7UuJNOT4u3eG5ADcxmYFRxrAo5EaHBPMBdI4l12K',	'jozue@anigraph.eu',	1,	0,	NULL,	NULL,	NULL,	NULL,	NULL,	'',	'0000-00-00 00:00:00',	'2017-03-31 13:18:17');

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
(1,	1,	'2017-03-20 08:04:07'),
(2,	1,	'2017-03-20 09:46:59'),
(3,	1,	'2017-03-20 10:49:31'),
(4,	1,	'2017-03-23 09:17:18'),
(5,	1,	'2017-03-27 11:00:19'),
(6,	1,	'2017-03-28 08:20:10'),
(7,	1,	'2017-03-28 08:50:41'),
(8,	1,	'2017-03-28 09:32:53'),
(9,	1,	'2017-03-28 12:37:03'),
(10,	1,	'2017-03-30 08:53:24'),
(11,	1,	'2017-03-30 09:55:18'),
(12,	1,	'2017-03-31 08:15:47'),
(13,	1,	'2017-03-31 08:33:07'),
(14,	1,	'2017-03-31 11:10:46'),
(15,	4,	'2017-03-31 12:19:56'),
(16,	1,	'2017-03-31 12:41:16'),
(17,	3,	'2017-03-31 15:20:27'),
(18,	3,	'2017-03-31 21:31:53'),
(19,	1,	'2017-04-03 08:21:30'),
(20,	1,	'2017-04-03 08:57:12'),
(21,	4,	'2017-04-03 09:17:54');

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
  `adresa` text COLLATE utf8_bin COMMENT 'Adresa',
  `id_skoly` int(11) DEFAULT NULL COMMENT 'Škola',
  `id_user_team` int(11) DEFAULT NULL COMMENT 'Údaje tímu',
  PRIMARY KEY (`id`),
  KEY `user_id` (`id_users`),
  KEY `id_registracia` (`id_registracia`),
  KEY `id_skoly` (`id_skoly`),
  KEY `id_user_team` (`id_user_team`),
  CONSTRAINT `user_profiles_ibfk_2` FOREIGN KEY (`id_users`) REFERENCES `users` (`id`),
  CONSTRAINT `user_profiles_ibfk_3` FOREIGN KEY (`id_registracia`) REFERENCES `registracia` (`id`),
  CONSTRAINT `user_profiles_ibfk_4` FOREIGN KEY (`id_skoly`) REFERENCES `skoly` (`id`),
  CONSTRAINT `user_profiles_ibfk_5` FOREIGN KEY (`id_user_team`) REFERENCES `user_team` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

INSERT INTO `user_profiles` (`id`, `id_users`, `id_registracia`, `meno`, `priezvisko`, `rok`, `telefon`, `poznamka`, `pocet_pr`, `pohl`, `prihlas_teraz`, `prihlas_predtym`, `avatar_25`, `avatar_75`, `foto`, `news`, `created`, `modified`, `adresa`, `id_skoly`, `id_user_team`) VALUES
(1,	1,	5,	'Peter',	'VOJTECH',	NULL,	NULL,	'Administrátor',	17,	'M',	'2017-04-03 08:57:12',	'2017-04-03 08:21:30',	'files/1/4ixm9oy1y04ehzcas1c47yn13_25.jpg',	'files/1/4ixm9oy1y04ehzcas1c47yn13_75.jpg',	NULL,	'A',	'2013-01-03 11:17:32',	'2017-03-20 10:49:21',	NULL,	NULL,	NULL),
(2,	2,	4,	'Róbert',	'DULA',	NULL,	NULL,	NULL,	0,	'M',	NULL,	NULL,	NULL,	NULL,	NULL,	'A',	'2017-02-13 08:38:27',	'2017-02-13 08:38:27',	NULL,	NULL,	NULL),
(3,	3,	4,	'Jozef',	'PETRENČÍK',	NULL,	NULL,	NULL,	2,	'M',	'2017-03-31 21:31:53',	'2017-03-31 15:20:27',	NULL,	NULL,	NULL,	'A',	'2017-02-13 08:54:07',	'2017-02-13 08:54:07',	NULL,	NULL,	NULL),
(4,	4,	1,	'Franto',	'Vojtech',	7,	NULL,	'',	2,	'Z',	'2017-04-03 09:17:55',	'2017-03-31 12:19:56',	'files/4/z0aj423coonb7ztazfbg2ri4n_25.jpg',	'files/4/z0aj423coonb7ztazfbg2ri4n_75.jpg',	NULL,	'A',	'2017-03-31 12:17:03',	'2017-03-31 12:38:25',	'sdfdfafa',	23,	NULL),
(5,	5,	1,	'Jozef',	'Petrenčík',	42,	NULL,	NULL,	0,	'Z',	NULL,	NULL,	NULL,	NULL,	NULL,	'A',	'2017-03-31 14:26:31',	'2017-03-31 15:18:17',	'',	5,	NULL);

DROP TABLE IF EXISTS `user_team`;
CREATE TABLE `user_team` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '[A]Index',
  `nazov` varchar(30) COLLATE utf8_bin NOT NULL COMMENT 'Názov tímu',
  `pocet` int(11) NOT NULL DEFAULT '2' COMMENT 'Počet členov',
  `clenovia` text COLLATE utf8_bin NOT NULL COMMENT 'Mená členov tímu',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


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
(1,	1,	'0.1.',	NULL,	'Východzia verzia',	'2017-02-13 08:03:32'),
(2,	1,	'0.1.1',	'Registracia',	'<p>\n	Refaktoring prihlasovacieho a registračn&eacute;ho formul&aacute;ra.</p>\n',	'2017-03-31 10:42:56');

-- 2017-04-03 09:26:38
