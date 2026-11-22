-- WorldCup2026 数据库初始化脚本，目标为空数据库。
-- 包含三个模块的表结构、Yii 迁移历史、后台角色权限及 2026 世界杯正式赛事数据。
-- 赛事来源见各记录的 source_url 字段。数据库账号及网站账号由部署者在本地创建。


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
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_assignment` (
  `item_name` varchar(64) NOT NULL,
  `user_id` varchar(64) NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  KEY `idx-auth_assignment-user_id` (`user_id`),
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `auth_assignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_assignment` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item` (
  `name` varchar(64) NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text DEFAULT NULL,
  `rule_name` varchar(64) DEFAULT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `auth_item` DISABLE KEYS */;
INSERT INTO `auth_item` VALUES ('admin',1,'系统管理员',NULL,NULL,1795366311,1795366311);
INSERT INTO `auth_item` VALUES ('manageFootball',2,'管理世界杯球队与赛事',NULL,NULL,1795366311,1795366311);
INSERT INTO `auth_item` VALUES ('manageNews',2,'管理世界杯新闻分类、标签和文章',NULL,NULL,1795366311,1795366311);
INSERT INTO `auth_item` VALUES ('managePortal',2,'管理团队门户与团队成员',NULL,NULL,1795366311,1795366311);
/*!40000 ALTER TABLE `auth_item` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_item_child` (
  `parent` varchar(64) NOT NULL,
  `child` varchar(64) NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `auth_item_child` DISABLE KEYS */;
INSERT INTO `auth_item_child` VALUES ('admin','manageFootball');
INSERT INTO `auth_item_child` VALUES ('admin','manageNews');
INSERT INTO `auth_item_child` VALUES ('admin','managePortal');
/*!40000 ALTER TABLE `auth_item_child` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auth_rule` (
  `name` varchar(64) NOT NULL,
  `data` blob DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `auth_rule` DISABLE KEYS */;
/*!40000 ALTER TABLE `auth_rule` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `migration` DISABLE KEYS */;
INSERT INTO `migration` VALUES ('m000000_000000_base',1795366309);
INSERT INTO `migration` VALUES ('m130524_201442_init',1795366309);
INSERT INTO `migration` VALUES ('m140506_102106_rbac_init',1795366310);
INSERT INTO `migration` VALUES ('m170907_052038_rbac_add_index_on_auth_assignment_user_id',1795366310);
INSERT INTO `migration` VALUES ('m180523_151638_rbac_updates_indexes_without_prefix',1795366310);
INSERT INTO `migration` VALUES ('m190124_110200_add_verification_token_column_to_user_table',1795366309);
INSERT INTO `migration` VALUES ('m200409_110543_rbac_update_mssql_trigger',1795366310);
INSERT INTO `migration` VALUES ('m261119_190100_create_portal_tables',1795366310);
INSERT INTO `migration` VALUES ('m261120_233000_create_football_tables',1795366310);
INSERT INTO `migration` VALUES ('m261122_230000_create_news_tables',1795366310);
/*!40000 ALTER TABLE `migration` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 10,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `verification_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `password_reset_token` (`password_reset_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `user` DISABLE KEYS */;
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wc_football_match` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `home_team_id` bigint(20) unsigned NOT NULL,
  `away_team_id` bigint(20) unsigned NOT NULL,
  `stadium_id` bigint(20) unsigned DEFAULT NULL,
  `match_time` datetime NOT NULL,
  `stage` varchar(30) NOT NULL,
  `home_score` int(11) unsigned DEFAULT NULL,
  `away_score` int(11) unsigned DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `result_note` varchar(255) DEFAULT NULL,
  `source_match_key` varchar(80) DEFAULT NULL,
  `source_url` varchar(500) DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `source_match_key` (`source_match_key`),
  KEY `idx-football-match-time` (`match_time`),
  KEY `fk-football-match-home` (`home_team_id`),
  KEY `fk-football-match-away` (`away_team_id`),
  KEY `fk-football-match-stadium` (`stadium_id`),
  CONSTRAINT `fk-football-match-away` FOREIGN KEY (`away_team_id`) REFERENCES `wc_football_team` (`id`),
  CONSTRAINT `fk-football-match-home` FOREIGN KEY (`home_team_id`) REFERENCES `wc_football_team` (`id`),
  CONSTRAINT `fk-football-match-stadium` FOREIGN KEY (`stadium_id`) REFERENCES `wc_football_stadium` (`id`),
  CONSTRAINT `ck-football-match-teams` CHECK (`home_team_id` <> `away_team_id`)
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `wc_football_match` DISABLE KEYS */;
INSERT INTO `wc_football_match` VALUES (1,1,2,1,'2026-06-11 19:00:00','小组赛 A组',2,0,2,NULL,'FWC26-M001','https://fdp.fifa.org/assetspublic/ce281/r12452/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (2,3,4,2,'2026-06-12 02:00:00','小组赛 A组',2,1,2,NULL,'FWC26-M002','https://fdp.fifa.org/assetspublic/ce281/r12450/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (3,5,6,3,'2026-06-12 19:00:00','小组赛 B组',1,1,2,NULL,'FWC26-M003','https://fdp.fifa.org/assetspublic/ce281/r12458/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (4,13,14,11,'2026-06-13 01:00:00','小组赛 D组',4,1,2,NULL,'FWC26-M004','https://fdp.fifa.org/assetspublic/ce281/r12467/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (5,11,12,7,'2026-06-14 01:00:00','小组赛 C组',0,1,2,NULL,'FWC26-M005','https://fdp.fifa.org/assetspublic/ce281/r12462/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (6,15,16,5,'2026-06-14 04:00:00','小组赛 D组',2,0,2,NULL,'FWC26-M006','https://fdp.fifa.org/assetspublic/ce281/r12472/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (7,9,10,13,'2026-06-13 22:00:00','小组赛 C组',1,1,2,NULL,'FWC26-M007','https://fdp.fifa.org/assetspublic/ce281/r12465/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (8,7,8,15,'2026-06-13 19:00:00','小组赛 B组',1,1,2,NULL,'FWC26-M008','https://fdp.fifa.org/assetspublic/ce281/r12456/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (9,19,20,14,'2026-06-14 23:00:00','小组赛 E组',1,0,2,NULL,'FWC26-M009','https://fdp.fifa.org/assetspublic/ce281/r12476/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (10,17,18,9,'2026-06-14 17:00:00','小组赛 E组',7,1,2,NULL,'FWC26-M010','https://fdp.fifa.org/assetspublic/ce281/r12473/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (11,21,22,8,'2026-06-14 20:00:00','小组赛 F组',2,2,2,NULL,'FWC26-M011','https://fdp.fifa.org/assetspublic/ce281/r12480/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (12,23,24,6,'2026-06-15 02:00:00','小组赛 F组',5,1,2,NULL,'FWC26-M012','https://fdp.fifa.org/assetspublic/ce281/r12484/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (13,31,32,12,'2026-06-15 22:00:00','小组赛 H组',1,1,2,NULL,'FWC26-M013','https://fdp.fifa.org/assetspublic/ce281/r12495/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (14,29,30,4,'2026-06-15 16:00:00','小组赛 H组',0,0,2,NULL,'FWC26-M014','https://fdp.fifa.org/assetspublic/ce281/r12492/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (15,27,28,11,'2026-06-16 01:00:00','小组赛 G组',2,2,2,NULL,'FWC26-M015','https://fdp.fifa.org/assetspublic/ce281/r12485/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (16,25,26,16,'2026-06-15 19:00:00','小组赛 G组',1,1,2,NULL,'FWC26-M016','https://fdp.fifa.org/assetspublic/ce281/r12488/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (17,33,34,13,'2026-06-16 19:00:00','小组赛 I组',3,1,2,NULL,'FWC26-M017','https://fdp.fifa.org/assetspublic/ce281/r12499/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (18,35,36,7,'2026-06-16 22:00:00','小组赛 I组',1,4,2,NULL,'FWC26-M018','https://fdp.fifa.org/assetspublic/ce281/r12497/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (19,37,38,10,'2026-06-17 01:00:00','小组赛 J组',3,0,2,NULL,'FWC26-M019','https://fdp.fifa.org/assetspublic/ce281/r12506/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (20,39,40,15,'2026-06-17 04:00:00','小组赛 J组',3,1,2,NULL,'FWC26-M020','https://fdp.fifa.org/assetspublic/ce281/r12508/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (21,47,48,3,'2026-06-17 23:00:00','小组赛 L组',1,0,2,NULL,'FWC26-M021','https://fdp.fifa.org/assetspublic/ce281/r12519/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (22,45,46,8,'2026-06-17 20:00:00','小组赛 L组',4,2,2,NULL,'FWC26-M022','https://fdp.fifa.org/assetspublic/ce281/r12516/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (23,41,42,9,'2026-06-17 17:00:00','小组赛 K组',1,1,2,NULL,'FWC26-M023','https://fdp.fifa.org/assetspublic/ce281/r12511/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (24,43,44,1,'2026-06-18 02:00:00','小组赛 K组',1,3,2,NULL,'FWC26-M024','https://fdp.fifa.org/assetspublic/ce281/r12513/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (25,4,2,4,'2026-06-18 16:00:00','小组赛 A组',1,1,2,NULL,'FWC26-M025','https://fdp.fifa.org/assetspublic/ce281/r12449/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (26,8,6,11,'2026-06-18 19:00:00','小组赛 B组',4,1,2,NULL,'FWC26-M026','https://fdp.fifa.org/assetspublic/ce281/r12455/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (27,5,7,5,'2026-06-18 22:00:00','小组赛 B组',6,0,2,NULL,'FWC26-M027','https://fdp.fifa.org/assetspublic/ce281/r12459/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (28,1,3,2,'2026-06-19 01:00:00','小组赛 A组',1,0,2,NULL,'FWC26-M028','https://fdp.fifa.org/assetspublic/ce281/r12451/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (29,9,11,14,'2026-06-20 00:30:00','小组赛 C组',3,0,2,NULL,'FWC26-M029','https://fdp.fifa.org/assetspublic/ce281/r12466/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (30,12,10,7,'2026-06-19 22:00:00','小组赛 C组',0,1,2,NULL,'FWC26-M030','https://fdp.fifa.org/assetspublic/ce281/r12463/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (31,16,14,15,'2026-06-20 03:00:00','小组赛 D组',0,1,2,NULL,'FWC26-M031','https://fdp.fifa.org/assetspublic/ce281/r12470/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (32,13,15,16,'2026-06-19 19:00:00','小组赛 D组',2,0,2,NULL,'FWC26-M032','https://fdp.fifa.org/assetspublic/ce281/r12471/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (33,17,19,3,'2026-06-20 20:00:00','小组赛 E组',2,1,2,NULL,'FWC26-M033','https://fdp.fifa.org/assetspublic/ce281/r12478/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (34,20,18,10,'2026-06-21 00:00:00','小组赛 E组',0,0,2,NULL,'FWC26-M034','https://fdp.fifa.org/assetspublic/ce281/r12474/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (35,21,23,9,'2026-06-20 17:00:00','小组赛 F组',5,1,2,NULL,'FWC26-M035','https://fdp.fifa.org/assetspublic/ce281/r12481/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (36,24,22,6,'2026-06-21 04:00:00','小组赛 F组',0,4,2,NULL,'FWC26-M036','https://fdp.fifa.org/assetspublic/ce281/r12483/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (37,32,30,12,'2026-06-21 22:00:00','小组赛 H组',2,2,2,NULL,'FWC26-M037','https://fdp.fifa.org/assetspublic/ce281/r12496/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (38,29,31,4,'2026-06-21 16:00:00','小组赛 H组',4,0,2,NULL,'FWC26-M038','https://fdp.fifa.org/assetspublic/ce281/r12491/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (39,25,27,11,'2026-06-21 19:00:00','小组赛 G组',0,0,2,NULL,'FWC26-M039','https://fdp.fifa.org/assetspublic/ce281/r12486/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (40,28,26,5,'2026-06-22 01:00:00','小组赛 G组',1,3,2,NULL,'FWC26-M040','https://fdp.fifa.org/assetspublic/ce281/r12489/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (41,36,34,13,'2026-06-23 00:00:00','小组赛 I组',3,2,2,NULL,'FWC26-M041','https://fdp.fifa.org/assetspublic/ce281/r12500/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (42,33,35,14,'2026-06-22 21:00:00','小组赛 I组',3,0,2,NULL,'FWC26-M042','https://fdp.fifa.org/assetspublic/ce281/r12501/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (43,37,39,8,'2026-06-22 17:00:00','小组赛 J组',2,0,2,NULL,'FWC26-M043','https://fdp.fifa.org/assetspublic/ce281/r12504/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (44,40,38,15,'2026-06-23 03:00:00','小组赛 J组',1,2,2,NULL,'FWC26-M044','https://fdp.fifa.org/assetspublic/ce281/r12507/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (45,45,47,7,'2026-06-23 20:00:00','小组赛 L组',0,0,2,NULL,'FWC26-M045','https://fdp.fifa.org/assetspublic/ce281/r12515/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (46,48,46,3,'2026-06-23 23:00:00','小组赛 L组',0,1,2,NULL,'FWC26-M046','https://fdp.fifa.org/assetspublic/ce281/r12520/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (47,41,43,9,'2026-06-23 17:00:00','小组赛 K组',5,0,2,NULL,'FWC26-M047','https://fdp.fifa.org/assetspublic/ce281/r12512/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (48,44,42,2,'2026-06-24 02:00:00','小组赛 K组',1,0,2,NULL,'FWC26-M048','https://fdp.fifa.org/assetspublic/ce281/r12510/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (49,12,9,12,'2026-06-24 22:00:00','小组赛 C组',0,3,2,NULL,'FWC26-M049','https://fdp.fifa.org/assetspublic/ce281/r12464/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (50,10,11,4,'2026-06-24 22:00:00','小组赛 C组',4,2,2,NULL,'FWC26-M050','https://fdp.fifa.org/assetspublic/ce281/r12461/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (51,8,5,5,'2026-06-24 19:00:00','小组赛 B组',2,1,2,NULL,'FWC26-M051','https://fdp.fifa.org/assetspublic/ce281/r12460/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (52,6,7,16,'2026-06-24 19:00:00','小组赛 B组',3,1,2,NULL,'FWC26-M052','https://fdp.fifa.org/assetspublic/ce281/r12457/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (53,4,1,1,'2026-06-25 01:00:00','小组赛 A组',0,3,2,NULL,'FWC26-M053','https://fdp.fifa.org/assetspublic/ce281/r12453/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (54,2,3,6,'2026-06-25 01:00:00','小组赛 A组',1,0,2,NULL,'FWC26-M054','https://fdp.fifa.org/assetspublic/ce281/r12454/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (55,18,19,14,'2026-06-25 20:00:00','小组赛 E组',0,2,2,NULL,'FWC26-M055','https://fdp.fifa.org/assetspublic/ce281/r12477/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (56,20,17,13,'2026-06-25 20:00:00','小组赛 E组',2,1,2,NULL,'FWC26-M056','https://fdp.fifa.org/assetspublic/ce281/r12475/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (57,22,23,8,'2026-06-25 23:00:00','小组赛 F组',1,1,2,NULL,'FWC26-M057','https://fdp.fifa.org/assetspublic/ce281/r12479/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (58,24,21,10,'2026-06-25 23:00:00','小组赛 F组',1,3,2,NULL,'FWC26-M058','https://fdp.fifa.org/assetspublic/ce281/r12482/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (59,16,13,11,'2026-06-26 02:00:00','小组赛 D组',3,2,2,NULL,'FWC26-M059','https://fdp.fifa.org/assetspublic/ce281/r12468/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (60,14,15,15,'2026-06-26 02:00:00','小组赛 D组',0,0,2,NULL,'FWC26-M060','https://fdp.fifa.org/assetspublic/ce281/r12469/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (61,36,33,7,'2026-06-26 19:00:00','小组赛 I组',1,4,2,NULL,'FWC26-M061','https://fdp.fifa.org/assetspublic/ce281/r12498/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (62,34,35,3,'2026-06-26 19:00:00','小组赛 I组',5,0,2,NULL,'FWC26-M062','https://fdp.fifa.org/assetspublic/ce281/r12502/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (63,26,27,16,'2026-06-27 03:00:00','小组赛 G组',1,1,2,NULL,'FWC26-M063','https://fdp.fifa.org/assetspublic/ce281/r12487/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (64,28,25,5,'2026-06-27 03:00:00','小组赛 G组',1,5,2,NULL,'FWC26-M064','https://fdp.fifa.org/assetspublic/ce281/r12490/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (65,30,31,9,'2026-06-27 00:00:00','小组赛 H组',0,0,2,NULL,'FWC26-M065','https://fdp.fifa.org/assetspublic/ce281/r12494/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (66,32,29,2,'2026-06-27 00:00:00','小组赛 H组',0,1,2,NULL,'FWC26-M066','https://fdp.fifa.org/assetspublic/ce281/r12493/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (67,48,45,13,'2026-06-27 21:00:00','小组赛 L组',0,2,2,NULL,'FWC26-M067','https://fdp.fifa.org/assetspublic/ce281/r12517/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (68,46,47,14,'2026-06-27 21:00:00','小组赛 L组',2,1,2,NULL,'FWC26-M068','https://fdp.fifa.org/assetspublic/ce281/r12518/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (69,38,39,10,'2026-06-28 02:00:00','小组赛 J组',3,3,2,NULL,'FWC26-M069','https://fdp.fifa.org/assetspublic/ce281/r12505/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (70,40,37,8,'2026-06-28 02:00:00','小组赛 J组',1,3,2,NULL,'FWC26-M070','https://fdp.fifa.org/assetspublic/ce281/r12503/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (71,44,41,12,'2026-06-27 23:30:00','小组赛 K组',0,0,2,NULL,'FWC26-M071','https://fdp.fifa.org/assetspublic/ce281/r12514/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (72,42,43,4,'2026-06-27 23:30:00','小组赛 K组',3,1,2,NULL,'FWC26-M072','https://fdp.fifa.org/assetspublic/ce281/r12509/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (73,2,5,11,'2026-06-28 19:00:00','32强赛',0,1,2,NULL,'FWC26-M073','https://fdp.fifa.org/assetspublic/ce281/r12527/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (74,17,14,7,'2026-06-29 20:30:00','32强赛',1,1,2,'点球大战 3–4','FWC26-M074','https://fdp.fifa.org/assetspublic/ce281/r12522/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (75,21,10,6,'2026-06-30 01:00:00','32强赛',1,1,2,'点球大战 2–3','FWC26-M075','https://fdp.fifa.org/assetspublic/ce281/r12531/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (76,9,22,9,'2026-06-29 17:00:00','32强赛',2,1,2,NULL,'FWC26-M076','https://fdp.fifa.org/assetspublic/ce281/r12525/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (77,33,23,13,'2026-06-30 21:00:00','32强赛',3,0,2,NULL,'FWC26-M077','https://fdp.fifa.org/assetspublic/ce281/r12532/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (78,19,36,8,'2026-06-30 17:00:00','32强赛',1,2,2,NULL,'FWC26-M078','https://fdp.fifa.org/assetspublic/ce281/r12523/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (79,1,20,1,'2026-07-01 01:00:00','32强赛',2,0,2,NULL,'FWC26-M079','https://fdp.fifa.org/assetspublic/ce281/r12529/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (80,45,42,4,'2026-07-01 16:00:00','32强赛',2,1,2,NULL,'FWC26-M080','https://fdp.fifa.org/assetspublic/ce281/r12521/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (81,13,6,15,'2026-07-02 00:00:00','32强赛',2,0,2,NULL,'FWC26-M081','https://fdp.fifa.org/assetspublic/ce281/r12533/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (82,25,34,16,'2026-07-01 20:00:00','32强赛',3,2,2,'加时赛决出胜负','FWC26-M082','https://fdp.fifa.org/assetspublic/ce281/r12534/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (83,41,46,3,'2026-07-02 23:00:00','32强赛',2,1,2,NULL,'FWC26-M083','https://fdp.fifa.org/assetspublic/ce281/r12535/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (84,29,39,11,'2026-07-02 19:00:00','32强赛',3,0,2,NULL,'FWC26-M084','https://fdp.fifa.org/assetspublic/ce281/r12528/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (85,8,38,5,'2026-07-03 03:00:00','32强赛',2,0,2,NULL,'FWC26-M085','https://fdp.fifa.org/assetspublic/ce281/r12536/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (86,37,30,12,'2026-07-03 22:00:00','32强赛',3,2,2,'加时赛决出胜负','FWC26-M086','https://fdp.fifa.org/assetspublic/ce281/r12530/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (87,44,47,10,'2026-07-04 01:30:00','32强赛',1,0,2,NULL,'FWC26-M087','https://fdp.fifa.org/assetspublic/ce281/r12526/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (88,15,26,8,'2026-07-03 18:00:00','32强赛',1,1,2,'点球大战 2–4','FWC26-M088','https://fdp.fifa.org/assetspublic/ce281/r12524/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (89,14,33,14,'2026-07-04 21:00:00','16强赛',0,1,2,NULL,'FWC26-M089','https://fdp.fifa.org/assetspublic/ce281/r12542/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (90,5,10,9,'2026-07-04 17:00:00','16强赛',0,3,2,NULL,'FWC26-M090','https://fdp.fifa.org/assetspublic/ce281/r12539/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (91,9,36,13,'2026-07-05 20:00:00','16强赛',1,2,2,NULL,'FWC26-M091','https://fdp.fifa.org/assetspublic/ce281/r12541/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (92,1,45,1,'2026-07-06 00:00:00','16强赛',2,3,2,NULL,'FWC26-M092','https://fdp.fifa.org/assetspublic/ce281/r12540/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (93,41,29,8,'2026-07-06 19:00:00','16强赛',0,1,2,NULL,'FWC26-M093','https://fdp.fifa.org/assetspublic/ce281/r12538/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (94,13,25,16,'2026-07-07 00:00:00','16强赛',1,4,2,NULL,'FWC26-M094','https://fdp.fifa.org/assetspublic/ce281/r12543/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (95,37,26,4,'2026-07-07 16:00:00','16强赛',3,2,2,NULL,'FWC26-M095','https://fdp.fifa.org/assetspublic/ce281/r12537/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (96,8,44,5,'2026-07-07 20:00:00','16强赛',0,0,2,'点球大战 4–3','FWC26-M096','https://fdp.fifa.org/assetspublic/ce281/r12544/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (97,33,10,7,'2026-07-09 20:00:00','四分之一决赛',2,0,2,NULL,'FWC26-M097','https://fdp.fifa.org/assetspublic/ce281/r12545/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (98,29,25,11,'2026-07-10 19:00:00','四分之一决赛',2,1,2,NULL,'FWC26-M098','https://fdp.fifa.org/assetspublic/ce281/r12547/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (99,36,45,12,'2026-07-11 21:00:00','四分之一决赛',1,2,2,'加时赛决出胜负','FWC26-M099','https://fdp.fifa.org/assetspublic/ce281/r12548/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (100,37,8,10,'2026-07-12 01:00:00','四分之一决赛',3,1,2,'加时赛决出胜负','FWC26-M100','https://fdp.fifa.org/assetspublic/ce281/r12546/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (101,33,29,8,'2026-07-14 19:00:00','半决赛',0,2,2,NULL,'FWC26-M101','https://fdp.fifa.org/assetspublic/ce281/r12550/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (102,45,37,4,'2026-07-15 19:00:00','半决赛',1,2,2,NULL,'FWC26-M102','https://fdp.fifa.org/assetspublic/ce281/r12549/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (103,33,45,12,'2026-07-18 21:00:00','季军赛',4,6,2,NULL,'FWC26-M103','https://fdp.fifa.org/assetspublic/ce281/r12551/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
INSERT INTO `wc_football_match` VALUES (104,29,37,13,'2026-07-19 19:00:00','决赛',1,0,2,'加时赛决出胜负','FWC26-M104','https://fdp.fifa.org/assetspublic/ce281/r12552/pdf/FullTimeMatchReport-English.pdf#page=1',1795366436,1795366436);
/*!40000 ALTER TABLE `wc_football_match` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wc_football_match_goal` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `match_id` bigint(20) unsigned NOT NULL,
  `team_id` bigint(20) unsigned NOT NULL,
  `scorer_name` varchar(100) NOT NULL,
  `minute_text` varchar(12) DEFAULT NULL,
  `goal_type` varchar(20) NOT NULL DEFAULT 'normal',
  `source_url` varchar(500) DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-football-goal-match` (`match_id`),
  KEY `fk-football-goal-team` (`team_id`),
  CONSTRAINT `fk-football-goal-match` FOREIGN KEY (`match_id`) REFERENCES `wc_football_match` (`id`),
  CONSTRAINT `fk-football-goal-team` FOREIGN KEY (`team_id`) REFERENCES `wc_football_team` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=309 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `wc_football_match_goal` DISABLE KEYS */;
INSERT INTO `wc_football_match_goal` VALUES (1,1,1,'QUINONES Julian','9','normal','https://fdp.fifa.org/assetspublic/ce281/r12452/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (2,1,1,'JIMENEZ Raul','67','normal','https://fdp.fifa.org/assetspublic/ce281/r12452/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (3,2,4,'KREJCI Ladislav','59','normal','https://fdp.fifa.org/assetspublic/ce281/r12450/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (4,2,3,'HWANG Inbeom','67','normal','https://fdp.fifa.org/assetspublic/ce281/r12450/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (5,2,3,'OH Hyeongyu','80','normal','https://fdp.fifa.org/assetspublic/ce281/r12450/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (6,3,6,'LUKIC Jovo','21','normal','https://fdp.fifa.org/assetspublic/ce281/r12458/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (7,3,5,'LARIN Cyle','78','normal','https://fdp.fifa.org/assetspublic/ce281/r12458/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (8,4,13,'BOBADILLA Damian','7','own_goal','https://fdp.fifa.org/assetspublic/ce281/r12467/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (9,4,13,'BALOGUN Folarin','31','normal','https://fdp.fifa.org/assetspublic/ce281/r12467/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (10,4,13,'BALOGUN Folarin','45+5','normal','https://fdp.fifa.org/assetspublic/ce281/r12467/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (11,4,14,'MAURICIO','73','normal','https://fdp.fifa.org/assetspublic/ce281/r12467/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (12,4,13,'REYNA Giovanni','90+8','normal','https://fdp.fifa.org/assetspublic/ce281/r12467/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (13,5,12,'McGINN John','28','normal','https://fdp.fifa.org/assetspublic/ce281/r12462/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (14,6,15,'IRANKUNDA Nestory','27','normal','https://fdp.fifa.org/assetspublic/ce281/r12472/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (15,6,15,'METCALFE Connor','75','normal','https://fdp.fifa.org/assetspublic/ce281/r12472/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (16,7,10,'SAIBARI Ismael','21','normal','https://fdp.fifa.org/assetspublic/ce281/r12465/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (17,7,9,'VINICIUS JUNIOR','32','normal','https://fdp.fifa.org/assetspublic/ce281/r12465/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (18,8,8,'EMBOLO Breel','17','penalty','https://fdp.fifa.org/assetspublic/ce281/r12456/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (19,8,7,'MUHEIM Miro','90+4','own_goal','https://fdp.fifa.org/assetspublic/ce281/r12456/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (20,9,19,'DIALLO Amad','90','normal','https://fdp.fifa.org/assetspublic/ce281/r12476/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (21,10,17,'NMECHA Felix','6','normal','https://fdp.fifa.org/assetspublic/ce281/r12473/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (22,10,18,'COMENENCIA Livano','21','normal','https://fdp.fifa.org/assetspublic/ce281/r12473/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (23,10,17,'SCHLOTTERBECK Nico','38','normal','https://fdp.fifa.org/assetspublic/ce281/r12473/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (24,10,17,'HAVERTZ Kai','45+5','penalty','https://fdp.fifa.org/assetspublic/ce281/r12473/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (25,10,17,'MUSIALA Jamal','47','normal','https://fdp.fifa.org/assetspublic/ce281/r12473/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (26,10,17,'BROWN Nathaniel','68','normal','https://fdp.fifa.org/assetspublic/ce281/r12473/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (27,10,17,'UNDAV Deniz','78','normal','https://fdp.fifa.org/assetspublic/ce281/r12473/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (28,10,17,'HAVERTZ Kai','88','normal','https://fdp.fifa.org/assetspublic/ce281/r12473/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (29,11,21,'VAN DIJK Virgil','51','normal','https://fdp.fifa.org/assetspublic/ce281/r12480/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (30,11,22,'NAKAMURA Keito','57','normal','https://fdp.fifa.org/assetspublic/ce281/r12480/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (31,11,21,'SUMMERVILLE Crysencio','64','normal','https://fdp.fifa.org/assetspublic/ce281/r12480/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (32,11,22,'KAMADA Daichi','89','normal','https://fdp.fifa.org/assetspublic/ce281/r12480/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (33,12,23,'AYARI Yasin','7','normal','https://fdp.fifa.org/assetspublic/ce281/r12484/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (34,12,23,'ISAK Alexander','30','normal','https://fdp.fifa.org/assetspublic/ce281/r12484/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (35,12,24,'REKIK Omar','43','normal','https://fdp.fifa.org/assetspublic/ce281/r12484/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (36,12,23,'GYOKERES Viktor','59','normal','https://fdp.fifa.org/assetspublic/ce281/r12484/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (37,12,23,'SVANBERG Mattias','84','normal','https://fdp.fifa.org/assetspublic/ce281/r12484/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (38,12,23,'AYARI Yasin','90+6','normal','https://fdp.fifa.org/assetspublic/ce281/r12484/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (39,13,31,'ABDULELAH ALAMRI','41','normal','https://fdp.fifa.org/assetspublic/ce281/r12495/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (40,13,32,'ARAUJO Maxi','80','normal','https://fdp.fifa.org/assetspublic/ce281/r12495/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (41,15,28,'JUST Elijah','7','normal','https://fdp.fifa.org/assetspublic/ce281/r12485/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (42,15,27,'REZAEIAN Ramin','32','normal','https://fdp.fifa.org/assetspublic/ce281/r12485/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (43,15,28,'JUST Elijah','54','normal','https://fdp.fifa.org/assetspublic/ce281/r12485/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (44,15,27,'MOHEBBI Mohammad','64','normal','https://fdp.fifa.org/assetspublic/ce281/r12485/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (45,16,26,'EMAM ASHOUR','20','normal','https://fdp.fifa.org/assetspublic/ce281/r12488/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (46,16,25,'MOHAMED HANY','66','own_goal','https://fdp.fifa.org/assetspublic/ce281/r12488/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (47,17,33,'MBAPPE Kylian','66','normal','https://fdp.fifa.org/assetspublic/ce281/r12499/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (48,17,33,'BARCOLA Bradley','82','normal','https://fdp.fifa.org/assetspublic/ce281/r12499/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (49,17,34,'MBAYE Ibrahim','90+5','normal','https://fdp.fifa.org/assetspublic/ce281/r12499/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (50,17,33,'MBAPPE Kylian','90+6','normal','https://fdp.fifa.org/assetspublic/ce281/r12499/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (51,18,36,'HAALAND Erling','29','normal','https://fdp.fifa.org/assetspublic/ce281/r12497/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (52,18,35,'AYMEN HUSSEIN','39','normal','https://fdp.fifa.org/assetspublic/ce281/r12497/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (53,18,36,'HAALAND Erling','43','normal','https://fdp.fifa.org/assetspublic/ce281/r12497/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (54,18,36,'OSTIGARD Leo','76','normal','https://fdp.fifa.org/assetspublic/ce281/r12497/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (55,18,36,'AYMEN HUSSEIN','90+6','own_goal','https://fdp.fifa.org/assetspublic/ce281/r12497/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (56,19,37,'MESSI Lionel','17','normal','https://fdp.fifa.org/assetspublic/ce281/r12506/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (57,19,37,'MESSI Lionel','60','normal','https://fdp.fifa.org/assetspublic/ce281/r12506/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (58,19,37,'MESSI Lionel','76','normal','https://fdp.fifa.org/assetspublic/ce281/r12506/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (59,20,39,'SCHMID Romano','21','normal','https://fdp.fifa.org/assetspublic/ce281/r12508/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (60,20,40,'ALI OLWAN','50','normal','https://fdp.fifa.org/assetspublic/ce281/r12508/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (61,20,39,'YAZAN ALARAB','76','own_goal','https://fdp.fifa.org/assetspublic/ce281/r12508/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (62,20,39,'ARNAUTOVIC Marko','90+12','penalty','https://fdp.fifa.org/assetspublic/ce281/r12508/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (63,21,47,'YIRENKYI Caleb','90+5','normal','https://fdp.fifa.org/assetspublic/ce281/r12519/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (64,22,45,'KANE Harry','12','penalty','https://fdp.fifa.org/assetspublic/ce281/r12516/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (65,22,46,'BATURINA Martin','36','normal','https://fdp.fifa.org/assetspublic/ce281/r12516/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (66,22,45,'KANE Harry','42','normal','https://fdp.fifa.org/assetspublic/ce281/r12516/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (67,22,46,'MUSA Petar','45+5','normal','https://fdp.fifa.org/assetspublic/ce281/r12516/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (68,22,45,'BELLINGHAM Jude','47','normal','https://fdp.fifa.org/assetspublic/ce281/r12516/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (69,22,45,'RASHFORD Marcus','85','normal','https://fdp.fifa.org/assetspublic/ce281/r12516/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (70,23,41,'JOAO NEVES','6','normal','https://fdp.fifa.org/assetspublic/ce281/r12511/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (71,23,42,'WISSA Yoane','45+5','normal','https://fdp.fifa.org/assetspublic/ce281/r12511/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (72,24,44,'MUNOZ Daniel','41','normal','https://fdp.fifa.org/assetspublic/ce281/r12513/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (73,24,43,'FAYZULLAEV Abbosbek','60','normal','https://fdp.fifa.org/assetspublic/ce281/r12513/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (74,24,44,'DIAZ Luis','65','normal','https://fdp.fifa.org/assetspublic/ce281/r12513/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (75,24,44,'CAMPAZ Jaminton','90+9','normal','https://fdp.fifa.org/assetspublic/ce281/r12513/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (76,25,4,'SADILEK Michal','6','normal','https://fdp.fifa.org/assetspublic/ce281/r12449/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (77,25,2,'MOKOENA Teboho','83','penalty','https://fdp.fifa.org/assetspublic/ce281/r12449/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (78,26,8,'MANZAMBI Johan','74','normal','https://fdp.fifa.org/assetspublic/ce281/r12455/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (79,26,8,'VARGAS Ruben','84','normal','https://fdp.fifa.org/assetspublic/ce281/r12455/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (80,26,8,'MANZAMBI Johan','90','normal','https://fdp.fifa.org/assetspublic/ce281/r12455/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (81,26,6,'MAHMIC Ermin','90+3','normal','https://fdp.fifa.org/assetspublic/ce281/r12455/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (82,26,8,'XHAKA Granit','90+7','penalty','https://fdp.fifa.org/assetspublic/ce281/r12455/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (83,27,5,'LARIN Cyle','16','normal','https://fdp.fifa.org/assetspublic/ce281/r12459/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (84,27,5,'DAVID Jonathan','29','normal','https://fdp.fifa.org/assetspublic/ce281/r12459/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (85,27,5,'DAVID Jonathan','45+3','normal','https://fdp.fifa.org/assetspublic/ce281/r12459/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (86,27,5,'SALIBA Nathan','64','normal','https://fdp.fifa.org/assetspublic/ce281/r12459/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (87,27,5,'MOHAMED MANAI','75','own_goal','https://fdp.fifa.org/assetspublic/ce281/r12459/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (88,27,5,'DAVID Jonathan','90+2','normal','https://fdp.fifa.org/assetspublic/ce281/r12459/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (89,28,1,'ROMO Luis','50','normal','https://fdp.fifa.org/assetspublic/ce281/r12451/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (90,29,9,'MATHEUS CUNHA','23','normal','https://fdp.fifa.org/assetspublic/ce281/r12466/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (91,29,9,'MATHEUS CUNHA','36','normal','https://fdp.fifa.org/assetspublic/ce281/r12466/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (92,29,9,'VINICIUS JUNIOR','45+3','normal','https://fdp.fifa.org/assetspublic/ce281/r12466/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (93,30,10,'SAIBARI Ismael','2','normal','https://fdp.fifa.org/assetspublic/ce281/r12463/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (94,31,14,'GALARZA Matias','2','normal','https://fdp.fifa.org/assetspublic/ce281/r12470/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (95,32,13,'BURGESS Cameron','11','own_goal','https://fdp.fifa.org/assetspublic/ce281/r12471/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (96,32,13,'FREEMAN Alex','43','normal','https://fdp.fifa.org/assetspublic/ce281/r12471/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (97,33,19,'KESSIE Franck','30','normal','https://fdp.fifa.org/assetspublic/ce281/r12478/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (98,33,17,'UNDAV Deniz','68','normal','https://fdp.fifa.org/assetspublic/ce281/r12478/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (99,33,17,'UNDAV Deniz','90+4','normal','https://fdp.fifa.org/assetspublic/ce281/r12478/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (100,35,21,'BROBBEY Brian','5','normal','https://fdp.fifa.org/assetspublic/ce281/r12481/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (101,35,21,'BROBBEY Brian','17','normal','https://fdp.fifa.org/assetspublic/ce281/r12481/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (102,35,21,'GAKPO Cody','47','normal','https://fdp.fifa.org/assetspublic/ce281/r12481/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (103,35,21,'GAKPO Cody','54','normal','https://fdp.fifa.org/assetspublic/ce281/r12481/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (104,35,23,'ELANGA Anthony','59','normal','https://fdp.fifa.org/assetspublic/ce281/r12481/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (105,35,21,'SUMMERVILLE Crysencio','89','normal','https://fdp.fifa.org/assetspublic/ce281/r12481/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (106,36,22,'KAMADA Daichi','4','normal','https://fdp.fifa.org/assetspublic/ce281/r12483/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (107,36,22,'UEDA Ayase','31','normal','https://fdp.fifa.org/assetspublic/ce281/r12483/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (108,36,22,'ITO Junya','69','normal','https://fdp.fifa.org/assetspublic/ce281/r12483/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (109,36,22,'UEDA Ayase','83','normal','https://fdp.fifa.org/assetspublic/ce281/r12483/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (110,37,30,'KEVIN PINA','21','normal','https://fdp.fifa.org/assetspublic/ce281/r12496/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (111,37,32,'ARAUJO Maxi','44','normal','https://fdp.fifa.org/assetspublic/ce281/r12496/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (112,37,32,'CANOBBIO Agustin','45+6','normal','https://fdp.fifa.org/assetspublic/ce281/r12496/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (113,37,30,'HELIO VARELA','61','normal','https://fdp.fifa.org/assetspublic/ce281/r12496/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (114,38,29,'YAMAL Lamine','10','normal','https://fdp.fifa.org/assetspublic/ce281/r12491/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (115,38,29,'OYARZABAL Mikel','21','normal','https://fdp.fifa.org/assetspublic/ce281/r12491/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (116,38,29,'OYARZABAL Mikel','24','normal','https://fdp.fifa.org/assetspublic/ce281/r12491/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (117,38,29,'HASSAN ALTAMBAKTI','49','own_goal','https://fdp.fifa.org/assetspublic/ce281/r12491/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (118,40,28,'SURMAN Finn','15','normal','https://fdp.fifa.org/assetspublic/ce281/r12489/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (119,40,26,'MOSTAFA ZICO','58','normal','https://fdp.fifa.org/assetspublic/ce281/r12489/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (120,40,26,'MOHAMED SALAH','67','normal','https://fdp.fifa.org/assetspublic/ce281/r12489/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (121,40,26,'TREZEGUET','82','normal','https://fdp.fifa.org/assetspublic/ce281/r12489/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (122,41,36,'HOLMGREN PEDERSEN Marcus','43','normal','https://fdp.fifa.org/assetspublic/ce281/r12500/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (123,41,36,'HAALAND Erling','48','normal','https://fdp.fifa.org/assetspublic/ce281/r12500/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (124,41,34,'SARR Ismaila','53','normal','https://fdp.fifa.org/assetspublic/ce281/r12500/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (125,41,36,'HAALAND Erling','58','normal','https://fdp.fifa.org/assetspublic/ce281/r12500/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (126,41,34,'SARR Ismaila','90+3','normal','https://fdp.fifa.org/assetspublic/ce281/r12500/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (127,42,33,'MBAPPE Kylian','14','normal','https://fdp.fifa.org/assetspublic/ce281/r12501/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (128,42,33,'MBAPPE Kylian','54','normal','https://fdp.fifa.org/assetspublic/ce281/r12501/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (129,42,33,'DEMBELE Ousmane','66','normal','https://fdp.fifa.org/assetspublic/ce281/r12501/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (130,43,37,'MESSI Lionel','38','normal','https://fdp.fifa.org/assetspublic/ce281/r12504/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (131,43,37,'MESSI Lionel','90+5','normal','https://fdp.fifa.org/assetspublic/ce281/r12504/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (132,44,40,'NIZAR ALRASHDAN','36','normal','https://fdp.fifa.org/assetspublic/ce281/r12507/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (133,44,38,'BENBOUALI Nadhir','69','normal','https://fdp.fifa.org/assetspublic/ce281/r12507/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (134,44,38,'GOUIRI Amine','82','normal','https://fdp.fifa.org/assetspublic/ce281/r12507/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (135,46,46,'BUDIMIR Ante','54','normal','https://fdp.fifa.org/assetspublic/ce281/r12520/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (136,47,41,'CRISTIANO RONALDO','6','normal','https://fdp.fifa.org/assetspublic/ce281/r12512/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (137,47,41,'NUNO MENDES','17','normal','https://fdp.fifa.org/assetspublic/ce281/r12512/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (138,47,41,'CRISTIANO RONALDO','39','normal','https://fdp.fifa.org/assetspublic/ce281/r12512/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (139,47,41,'NEMATOV Abduvohid','60','own_goal','https://fdp.fifa.org/assetspublic/ce281/r12512/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (140,47,41,'RAFAEL LEAO','87','normal','https://fdp.fifa.org/assetspublic/ce281/r12512/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (141,48,44,'MUNOZ Daniel','76','normal','https://fdp.fifa.org/assetspublic/ce281/r12510/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (142,49,9,'VINICIUS JUNIOR','7','normal','https://fdp.fifa.org/assetspublic/ce281/r12464/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (143,49,9,'VINICIUS JUNIOR','45+3','normal','https://fdp.fifa.org/assetspublic/ce281/r12464/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (144,49,9,'MATHEUS CUNHA','60','normal','https://fdp.fifa.org/assetspublic/ce281/r12464/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (145,50,11,'BOUNOU Yassine','10','own_goal','https://fdp.fifa.org/assetspublic/ce281/r12461/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (146,50,10,'HAKIMI Achraf','39','normal','https://fdp.fifa.org/assetspublic/ce281/r12461/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (147,50,11,'ISIDOR Wilson','43','normal','https://fdp.fifa.org/assetspublic/ce281/r12461/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (148,50,10,'SAIBARI Ismael','45+1','normal','https://fdp.fifa.org/assetspublic/ce281/r12461/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (149,50,10,'RAHIMI Soufiane','78','normal','https://fdp.fifa.org/assetspublic/ce281/r12461/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (150,50,10,'YASSINE Gessime','89','normal','https://fdp.fifa.org/assetspublic/ce281/r12461/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (151,51,8,'VARGAS Ruben','46','normal','https://fdp.fifa.org/assetspublic/ce281/r12460/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (152,51,8,'MANZAMBI Johan','57','normal','https://fdp.fifa.org/assetspublic/ce281/r12460/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (153,51,5,'DAVID Promise','76','normal','https://fdp.fifa.org/assetspublic/ce281/r12460/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (154,52,6,'ALAJBEGOVIC Kerim','29','normal','https://fdp.fifa.org/assetspublic/ce281/r12457/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (155,52,6,'MAHMOUD ABUNADA','34','own_goal','https://fdp.fifa.org/assetspublic/ce281/r12457/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (156,52,7,'HASSAN ALHAYDOS','42','normal','https://fdp.fifa.org/assetspublic/ce281/r12457/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (157,52,6,'MAHMIC Ermin','80','normal','https://fdp.fifa.org/assetspublic/ce281/r12457/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (158,53,1,'CHAVEZ Mateo','55','normal','https://fdp.fifa.org/assetspublic/ce281/r12453/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (159,53,1,'QUINONES Julian','61','normal','https://fdp.fifa.org/assetspublic/ce281/r12453/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (160,53,1,'FIDALGO Alvaro','90+4','normal','https://fdp.fifa.org/assetspublic/ce281/r12453/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (161,54,2,'MASEKO Thapelo','63','normal','https://fdp.fifa.org/assetspublic/ce281/r12454/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (162,55,19,'PEPE Nicolas','7','normal','https://fdp.fifa.org/assetspublic/ce281/r12477/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (163,55,19,'PEPE Nicolas','64','normal','https://fdp.fifa.org/assetspublic/ce281/r12477/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (164,56,17,'SANE Leroy','2','normal','https://fdp.fifa.org/assetspublic/ce281/r12475/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (165,56,20,'ANGULO Nilson','9','normal','https://fdp.fifa.org/assetspublic/ce281/r12475/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (166,56,20,'PLATA Gonzalo','77','normal','https://fdp.fifa.org/assetspublic/ce281/r12475/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (167,57,22,'MAEDA Daizen','56','normal','https://fdp.fifa.org/assetspublic/ce281/r12479/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (168,57,23,'ELANGA Anthony','62','normal','https://fdp.fifa.org/assetspublic/ce281/r12479/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (169,58,21,'SKHIRI Ellyes','3','own_goal','https://fdp.fifa.org/assetspublic/ce281/r12482/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (170,58,21,'BROBBEY Brian','7','normal','https://fdp.fifa.org/assetspublic/ce281/r12482/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (171,58,24,'MASTOURI Hazem','54','normal','https://fdp.fifa.org/assetspublic/ce281/r12482/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (172,58,21,'VAN HECKE Jan Paul','62','normal','https://fdp.fifa.org/assetspublic/ce281/r12482/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (173,59,13,'TRUSTY Auston','3','normal','https://fdp.fifa.org/assetspublic/ce281/r12468/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (174,59,16,'GULER Arda','10','normal','https://fdp.fifa.org/assetspublic/ce281/r12468/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (175,59,16,'YILMAZ Baris Alper','31','normal','https://fdp.fifa.org/assetspublic/ce281/r12468/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (176,59,13,'BERHALTER Sebastian','49','normal','https://fdp.fifa.org/assetspublic/ce281/r12468/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (177,59,16,'AYHAN Kaan','90+8','normal','https://fdp.fifa.org/assetspublic/ce281/r12468/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (178,61,33,'DEMBELE Ousmane','7','normal','https://fdp.fifa.org/assetspublic/ce281/r12498/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (179,61,33,'DEMBELE Ousmane','20','normal','https://fdp.fifa.org/assetspublic/ce281/r12498/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (180,61,36,'AASGAARD Thelo','21','normal','https://fdp.fifa.org/assetspublic/ce281/r12498/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (181,61,33,'DEMBELE Ousmane','32','normal','https://fdp.fifa.org/assetspublic/ce281/r12498/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (182,61,33,'DOUE Desire','90+4','normal','https://fdp.fifa.org/assetspublic/ce281/r12498/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (183,62,34,'DIARRA Habib','4','normal','https://fdp.fifa.org/assetspublic/ce281/r12502/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (184,62,34,'SARR Ismaila','56','normal','https://fdp.fifa.org/assetspublic/ce281/r12502/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (185,62,34,'GUEYE Pape','59','normal','https://fdp.fifa.org/assetspublic/ce281/r12502/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (186,62,34,'GUEYE Pape','71','normal','https://fdp.fifa.org/assetspublic/ce281/r12502/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (187,62,34,'NDIAYE Iliman','82','normal','https://fdp.fifa.org/assetspublic/ce281/r12502/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (188,63,26,'MAHMOUD SABER','5','normal','https://fdp.fifa.org/assetspublic/ce281/r12487/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (189,63,27,'REZAEIAN Ramin','14','normal','https://fdp.fifa.org/assetspublic/ce281/r12487/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (190,64,25,'TROSSARD Leandro','28','normal','https://fdp.fifa.org/assetspublic/ce281/r12490/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (191,64,25,'TROSSARD Leandro','50','normal','https://fdp.fifa.org/assetspublic/ce281/r12490/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (192,64,25,'DE BRUYNE Kevin','66','normal','https://fdp.fifa.org/assetspublic/ce281/r12490/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (193,64,28,'JUST Elijah','84','normal','https://fdp.fifa.org/assetspublic/ce281/r12490/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (194,64,25,'LUKAKU Romelu','86','normal','https://fdp.fifa.org/assetspublic/ce281/r12490/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (195,64,25,'SAELEMAEKERS Alexis','90+4','normal','https://fdp.fifa.org/assetspublic/ce281/r12490/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (196,66,29,'BAENA Alex','42','normal','https://fdp.fifa.org/assetspublic/ce281/r12493/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (197,67,45,'BELLINGHAM Jude','62','normal','https://fdp.fifa.org/assetspublic/ce281/r12517/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (198,67,45,'KANE Harry','67','normal','https://fdp.fifa.org/assetspublic/ce281/r12517/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (199,68,46,'SUCIC Petar','31','normal','https://fdp.fifa.org/assetspublic/ce281/r12518/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (200,68,47,'LUCKASSEN Derrick','73','normal','https://fdp.fifa.org/assetspublic/ce281/r12518/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (201,68,46,'VLASIC Nikola','83','normal','https://fdp.fifa.org/assetspublic/ce281/r12518/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (202,69,39,'ARNAUTOVIC Marko','28','normal','https://fdp.fifa.org/assetspublic/ce281/r12505/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (203,69,38,'BELGHALI Rafik','45','normal','https://fdp.fifa.org/assetspublic/ce281/r12505/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (204,69,39,'SABITZER Marcel','55','normal','https://fdp.fifa.org/assetspublic/ce281/r12505/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (205,69,38,'MAHREZ Riyad','60','normal','https://fdp.fifa.org/assetspublic/ce281/r12505/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (206,69,38,'MAHREZ Riyad','90+3','normal','https://fdp.fifa.org/assetspublic/ce281/r12505/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (207,69,39,'KALAJDZIC Sasa','90+6','normal','https://fdp.fifa.org/assetspublic/ce281/r12505/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (208,70,37,'LO CELSO Giovani','19','normal','https://fdp.fifa.org/assetspublic/ce281/r12503/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (209,70,37,'MARTINEZ Lautaro','31','penalty','https://fdp.fifa.org/assetspublic/ce281/r12503/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (210,70,40,'MOUSA ALTAMARI','55','normal','https://fdp.fifa.org/assetspublic/ce281/r12503/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (211,70,37,'MESSI Lionel','80','normal','https://fdp.fifa.org/assetspublic/ce281/r12503/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (212,72,43,'SHOMURODOV Eldor','10','normal','https://fdp.fifa.org/assetspublic/ce281/r12509/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (213,72,42,'WISSA Yoane','68','penalty','https://fdp.fifa.org/assetspublic/ce281/r12509/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (214,72,42,'MAYELE Fiston','78','normal','https://fdp.fifa.org/assetspublic/ce281/r12509/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (215,72,42,'WISSA Yoane','90+1','normal','https://fdp.fifa.org/assetspublic/ce281/r12509/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (216,73,5,'EUSTAQUIO Stephen','90+2','normal','https://fdp.fifa.org/assetspublic/ce281/r12527/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (217,74,14,'ENCISO Julio','42','normal','https://fdp.fifa.org/assetspublic/ce281/r12522/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (218,74,17,'HAVERTZ Kai','54','normal','https://fdp.fifa.org/assetspublic/ce281/r12522/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (219,75,21,'GAKPO Cody','72','normal','https://fdp.fifa.org/assetspublic/ce281/r12531/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (220,75,10,'DIOP Issa','90+1','normal','https://fdp.fifa.org/assetspublic/ce281/r12531/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (221,76,22,'SANO Kaishu','29','normal','https://fdp.fifa.org/assetspublic/ce281/r12525/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (222,76,9,'CASEMIRO','56','normal','https://fdp.fifa.org/assetspublic/ce281/r12525/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (223,76,9,'GABRIEL MARTINELLI','90+5','normal','https://fdp.fifa.org/assetspublic/ce281/r12525/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (224,77,33,'MBAPPE Kylian','45','normal','https://fdp.fifa.org/assetspublic/ce281/r12532/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (225,77,33,'BARCOLA Bradley','53','normal','https://fdp.fifa.org/assetspublic/ce281/r12532/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (226,77,33,'MBAPPE Kylian','74','normal','https://fdp.fifa.org/assetspublic/ce281/r12532/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (227,78,36,'NUSA Antonio','39','normal','https://fdp.fifa.org/assetspublic/ce281/r12523/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (228,78,19,'DIALLO Amad','74','normal','https://fdp.fifa.org/assetspublic/ce281/r12523/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (229,78,36,'HAALAND Erling','86','normal','https://fdp.fifa.org/assetspublic/ce281/r12523/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (230,79,1,'QUINONES Julian','22','normal','https://fdp.fifa.org/assetspublic/ce281/r12529/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (231,79,1,'JIMENEZ Raul','31','normal','https://fdp.fifa.org/assetspublic/ce281/r12529/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (232,80,42,'CIPENGA Brian','7','normal','https://fdp.fifa.org/assetspublic/ce281/r12521/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (233,80,45,'KANE Harry','75','normal','https://fdp.fifa.org/assetspublic/ce281/r12521/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (234,80,45,'KANE Harry','86','normal','https://fdp.fifa.org/assetspublic/ce281/r12521/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (235,81,13,'BALOGUN Folarin','45','normal','https://fdp.fifa.org/assetspublic/ce281/r12533/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (236,81,13,'TILLMAN Malik','82','normal','https://fdp.fifa.org/assetspublic/ce281/r12533/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (237,82,34,'DIARRA Habib','25','normal','https://fdp.fifa.org/assetspublic/ce281/r12534/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (238,82,34,'SARR Ismaila','51','normal','https://fdp.fifa.org/assetspublic/ce281/r12534/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (239,82,25,'LUKAKU Romelu','86','normal','https://fdp.fifa.org/assetspublic/ce281/r12534/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (240,82,25,'TIELEMANS Youri','89','normal','https://fdp.fifa.org/assetspublic/ce281/r12534/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (241,82,25,'TIELEMANS Youri','125','penalty','https://fdp.fifa.org/assetspublic/ce281/r12534/pdf/FullTimeMatchReport-English.pdf#page=3',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (242,83,46,'PERISIC Ivan','53','normal','https://fdp.fifa.org/assetspublic/ce281/r12535/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (243,83,41,'CRISTIANO RONALDO','68','penalty','https://fdp.fifa.org/assetspublic/ce281/r12535/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (244,83,41,'GONCALO RAMOS','90+4','normal','https://fdp.fifa.org/assetspublic/ce281/r12535/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (245,84,29,'OYARZABAL Mikel','36','normal','https://fdp.fifa.org/assetspublic/ce281/r12528/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (246,84,29,'PORRO Pedro','66','normal','https://fdp.fifa.org/assetspublic/ce281/r12528/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (247,84,29,'OYARZABAL Mikel','89','normal','https://fdp.fifa.org/assetspublic/ce281/r12528/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (248,85,8,'EMBOLO Breel','10','normal','https://fdp.fifa.org/assetspublic/ce281/r12536/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (249,85,8,'NDOYE Dan','46','normal','https://fdp.fifa.org/assetspublic/ce281/r12536/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (250,86,37,'MESSI Lionel','29','normal','https://fdp.fifa.org/assetspublic/ce281/r12530/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (251,86,30,'DEROY DUARTE','59','normal','https://fdp.fifa.org/assetspublic/ce281/r12530/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (252,86,37,'MARTINEZ Lisandro','92','normal','https://fdp.fifa.org/assetspublic/ce281/r12530/pdf/FullTimeMatchReport-English.pdf#page=3',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (253,86,30,'SIDNY LOPES CABRAL','103','normal','https://fdp.fifa.org/assetspublic/ce281/r12530/pdf/FullTimeMatchReport-English.pdf#page=3',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (254,86,37,'DINEY BORGES','111','own_goal','https://fdp.fifa.org/assetspublic/ce281/r12530/pdf/FullTimeMatchReport-English.pdf#page=3',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (255,87,44,'ARIAS Jhon','14','normal','https://fdp.fifa.org/assetspublic/ce281/r12526/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (256,88,26,'EMAM ASHOUR','13','normal','https://fdp.fifa.org/assetspublic/ce281/r12524/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (257,88,15,'MOHAMED HANY','55','own_goal','https://fdp.fifa.org/assetspublic/ce281/r12524/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (258,89,33,'MBAPPE Kylian','70','penalty','https://fdp.fifa.org/assetspublic/ce281/r12542/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (259,90,10,'OUNAHI Azzedine','50','normal','https://fdp.fifa.org/assetspublic/ce281/r12539/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (260,90,10,'OUNAHI Azzedine','82','normal','https://fdp.fifa.org/assetspublic/ce281/r12539/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (261,90,10,'RAHIMI Soufiane','90+8','normal','https://fdp.fifa.org/assetspublic/ce281/r12539/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (262,91,36,'HAALAND Erling','79','normal','https://fdp.fifa.org/assetspublic/ce281/r12541/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (263,91,36,'HAALAND Erling','90','normal','https://fdp.fifa.org/assetspublic/ce281/r12541/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (264,91,9,'NEYMAR JR','90+10','penalty','https://fdp.fifa.org/assetspublic/ce281/r12541/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (265,92,45,'BELLINGHAM Jude','36','normal','https://fdp.fifa.org/assetspublic/ce281/r12540/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (266,92,45,'BELLINGHAM Jude','38','normal','https://fdp.fifa.org/assetspublic/ce281/r12540/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (267,92,1,'QUINONES Julian','42','normal','https://fdp.fifa.org/assetspublic/ce281/r12540/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (268,92,45,'KANE Harry','60','penalty','https://fdp.fifa.org/assetspublic/ce281/r12540/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (269,92,1,'JIMENEZ Raul','69','penalty','https://fdp.fifa.org/assetspublic/ce281/r12540/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (270,93,29,'MERINO Mikel','90+1','normal','https://fdp.fifa.org/assetspublic/ce281/r12538/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (271,94,25,'DE KETELAERE Charles','9','normal','https://fdp.fifa.org/assetspublic/ce281/r12543/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (272,94,13,'TILLMAN Malik','31','normal','https://fdp.fifa.org/assetspublic/ce281/r12543/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (273,94,25,'DE KETELAERE Charles','33','normal','https://fdp.fifa.org/assetspublic/ce281/r12543/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (274,94,25,'VANAKEN Hans','57','normal','https://fdp.fifa.org/assetspublic/ce281/r12543/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (275,94,25,'LUKAKU Romelu','90+3','normal','https://fdp.fifa.org/assetspublic/ce281/r12543/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (276,95,26,'YASSER IBRAHIM','15','normal','https://fdp.fifa.org/assetspublic/ce281/r12537/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (277,95,26,'MOSTAFA ZICO','67','normal','https://fdp.fifa.org/assetspublic/ce281/r12537/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (278,95,37,'ROMERO Cristian','79','normal','https://fdp.fifa.org/assetspublic/ce281/r12537/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (279,95,37,'MESSI Lionel','83','normal','https://fdp.fifa.org/assetspublic/ce281/r12537/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (280,95,37,'FERNANDEZ Enzo','90+2','normal','https://fdp.fifa.org/assetspublic/ce281/r12537/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (281,97,33,'MBAPPE Kylian','60','normal','https://fdp.fifa.org/assetspublic/ce281/r12545/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (282,97,33,'DEMBELE Ousmane','66','normal','https://fdp.fifa.org/assetspublic/ce281/r12545/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (283,98,29,'RUIZ Fabian','30','normal','https://fdp.fifa.org/assetspublic/ce281/r12547/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (284,98,25,'DE KETELAERE Charles','41','normal','https://fdp.fifa.org/assetspublic/ce281/r12547/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (285,98,29,'MERINO Mikel','88','normal','https://fdp.fifa.org/assetspublic/ce281/r12547/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (286,99,36,'SCHJELDERUP Andreas','36','normal','https://fdp.fifa.org/assetspublic/ce281/r12548/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (287,99,45,'BELLINGHAM Jude','45+2','normal','https://fdp.fifa.org/assetspublic/ce281/r12548/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (288,99,45,'BELLINGHAM Jude','93','normal','https://fdp.fifa.org/assetspublic/ce281/r12548/pdf/FullTimeMatchReport-English.pdf#page=3',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (289,100,37,'MAC ALLISTER Alexis','10','normal','https://fdp.fifa.org/assetspublic/ce281/r12546/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (290,100,8,'NDOYE Dan','67','normal','https://fdp.fifa.org/assetspublic/ce281/r12546/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (291,100,37,'ALVAREZ Julian','112','normal','https://fdp.fifa.org/assetspublic/ce281/r12546/pdf/FullTimeMatchReport-English.pdf#page=3',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (292,100,37,'MARTINEZ Lautaro','121','normal','https://fdp.fifa.org/assetspublic/ce281/r12546/pdf/FullTimeMatchReport-English.pdf#page=3',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (293,101,29,'OYARZABAL Mikel','22','penalty','https://fdp.fifa.org/assetspublic/ce281/r12550/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (294,101,29,'PORRO Pedro','58','normal','https://fdp.fifa.org/assetspublic/ce281/r12550/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (295,102,45,'GORDON Anthony','55','normal','https://fdp.fifa.org/assetspublic/ce281/r12549/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (296,102,37,'FERNANDEZ Enzo','85','normal','https://fdp.fifa.org/assetspublic/ce281/r12549/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (297,102,37,'MARTINEZ Lautaro','90+2','normal','https://fdp.fifa.org/assetspublic/ce281/r12549/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (298,103,45,'RICE Declan','3','normal','https://fdp.fifa.org/assetspublic/ce281/r12551/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (299,103,45,'KONSA Ezri','18','normal','https://fdp.fifa.org/assetspublic/ce281/r12551/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (300,103,45,'SAKA Bukayo','37','normal','https://fdp.fifa.org/assetspublic/ce281/r12551/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (301,103,45,'SAKA Bukayo','45+1','normal','https://fdp.fifa.org/assetspublic/ce281/r12551/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (302,103,33,'MBAPPE Kylian','48','normal','https://fdp.fifa.org/assetspublic/ce281/r12551/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (303,103,33,'BARCOLA Bradley','54','normal','https://fdp.fifa.org/assetspublic/ce281/r12551/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (304,103,33,'MBAPPE Kylian','66','normal','https://fdp.fifa.org/assetspublic/ce281/r12551/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (305,103,45,'SAKA Bukayo','87','penalty','https://fdp.fifa.org/assetspublic/ce281/r12551/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (306,103,33,'DEMBELE Ousmane','90+6','normal','https://fdp.fifa.org/assetspublic/ce281/r12551/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (307,103,45,'BELLINGHAM Jude','90+8','normal','https://fdp.fifa.org/assetspublic/ce281/r12551/pdf/FullTimeMatchReport-English.pdf#page=2',1795366436,1795366436);
INSERT INTO `wc_football_match_goal` VALUES (308,104,29,'TORRES Ferran','106','normal','https://fdp.fifa.org/assetspublic/ce281/r12552/pdf/FullTimeMatchReport-English.pdf#page=3',1795366436,1795366436);
/*!40000 ALTER TABLE `wc_football_match_goal` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wc_football_player` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` int(11) unsigned DEFAULT NULL,
  `position` varchar(30) DEFAULT NULL,
  `source_url` varchar(500) DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk-football-player-team` (`team_id`),
  CONSTRAINT `fk-football-player-team` FOREIGN KEY (`team_id`) REFERENCES `wc_football_team` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1249 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `wc_football_player` DISABLE KEYS */;
INSERT INTO `wc_football_player` VALUES (1,1,'RANGEL Raul',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (2,1,'SANCHEZ Jorge',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (3,1,'MONTES Cesar',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (4,1,'ALVAREZ Edson',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (5,1,'VASQUEZ Johan',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (6,1,'LIRA Erik',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (7,1,'ROMO Luis',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (8,1,'FIDALGO Alvaro',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (9,1,'JIMENEZ Raul',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (10,1,'VEGA Alexis',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (11,1,'GIMENEZ Santiago',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (12,1,'ACEVEDO Carlos',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (13,1,'OCHOA Guillermo',13,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (14,1,'GONZALEZ Armando',14,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (15,1,'REYES Israel',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (16,1,'QUINONES Julian',16,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (17,1,'PINEDA Orbelin',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (18,1,'VARGAS Obed',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (19,1,'MORA Gilberto',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (20,1,'CHAVEZ Mateo',20,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (21,1,'HUERTA Cesar',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (22,1,'MARTINEZ Guillermo',22,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (23,1,'GALLARDO Jesus',23,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (24,1,'CHAVEZ Luis',24,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (25,1,'ALVARADO Roberto',25,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (26,1,'GUTIERREZ Brian',26,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=28',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (27,2,'WILLIAMS Ronwen',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (28,2,'MATULUDI Thabang',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (29,2,'NDAMANE Khulumani',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (30,2,'MOKOENA Teboho',4,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (31,2,'MBATHA Thalente',5,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (32,2,'MODIBA Aubrey',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (33,2,'APPOLLIS Oswin',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (34,2,'MOREMI Tshepang',8,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (35,2,'FOSTER Lyle',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (36,2,'MOFOKENG Relebohile',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (37,2,'ZWANE Themba',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (38,2,'MASEKO Thapelo',12,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (39,2,'SITHOLE Sphephelo',13,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (40,2,'MBOKAZI Mbekezeli',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (41,2,'RAYNERS Iqraam',15,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (42,2,'CHAINE Sipho',16,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (43,2,'MAKGOPA Evidence',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (44,2,'KABINI Samukele',18,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (45,2,'SIBISI Nkosinathi',19,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (46,2,'MUDAU Khuliso',20,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (47,2,'OKON Ime',21,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (48,2,'GOSS Ricardo',22,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (49,2,'ADAMS Jayden',23,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (50,2,'MAKHANYA Olwethu',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (51,2,'SEBELEBELE Kamogelo',25,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (52,2,'CROSS Bradley',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=40',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (53,3,'KIM Seunggyu',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (54,3,'LEE Hanbeom',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (55,3,'LEE Gihyuk',3,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (56,3,'KIM Minjae',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (57,3,'KIM Taehyeon',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (58,3,'HWANG Inbeom',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (59,3,'SON Heungmin',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (60,3,'PAIK Seungho',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (61,3,'CHO Guesung',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (62,3,'LEE Jaesung',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (63,3,'HWANG Heechan',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (64,3,'SONG Bumkeun',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (65,3,'LEE Taeseok',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (66,3,'CHO Wije',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (67,3,'KIM Moonhwan',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (68,3,'PARK Jinseob',16,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (69,3,'BAE Junho',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (70,3,'OH Hyeongyu',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (71,3,'LEE Kangin',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (72,3,'YANG Hyunjun',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (73,3,'JO Hyeonwoo',21,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (74,3,'SEOL Youngwoo',22,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (75,3,'CASTROP Jens',23,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (76,3,'KIM Jingyu',24,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (77,3,'EOM Jisung',25,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (78,3,'LEE Donggyeong',26,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=27',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (79,4,'KOVAR Matej',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (80,4,'ZIMA David',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (81,4,'HOLES Tomas',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (82,4,'HRANAC Robin',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (83,4,'COUFAL Vladimir',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (84,4,'CHALOUPEK Stepan',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (85,4,'KREJCI Ladislav',7,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (86,4,'DARIDA Vladimir',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (87,4,'HLOZEK Adam',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (88,4,'SCHICK Patrik',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (89,4,'KUCHTA Jan',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (90,4,'CERV Lukas',12,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (91,4,'CHYTIL Mojmir',13,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (92,4,'JURASEK David',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (93,4,'SULC Pavel',15,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (94,4,'STANEK Jindrich',16,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (95,4,'PROVOD Lukas',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (96,4,'SADILEK Michal',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (97,4,'CHORY Tomas',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (98,4,'ZELENY Jaroslav',20,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (99,4,'DOUDERA David',21,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (100,4,'SOUCEK Tomas',22,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (101,4,'HORNICEK Lukas',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (102,4,'SOJKA Alexandr',24,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (103,4,'SOCHUREK Hugo',25,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (104,4,'VISINSKY Denis',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=15',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (105,5,'ST. CLAIR Dayne',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (106,5,'JOHNSTON Alistair',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (107,5,'JONES Alfie',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (108,5,'DE FOUGEROLLES Luc',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (109,5,'WATERMAN Joel',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (110,5,'CHOINIERE Mathieu',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (111,5,'EUSTAQUIO Stephen',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (112,5,'KONE Ismael',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (113,5,'LARIN Cyle',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (114,5,'DAVID Jonathan',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (115,5,'MILLAR Liam',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (116,5,'OLUWASEYI Tani',12,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (117,5,'CORNELIUS Derek',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (118,5,'SHAFFELBURG Jacob',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (119,5,'BOMBITO Moise',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (120,5,'CREPEAU Maxime',16,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (121,5,'BUCHANAN Tajon',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (122,5,'GOODMAN Owen',18,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (123,5,'DAVIES Alphonso',19,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (124,5,'AHMED Ali',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (125,5,'OSORIO Jonathan',21,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (126,5,'LARYEA Richie',22,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (127,5,'SIGUR Niko',23,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (128,5,'DAVID Promise',24,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (129,5,'SALIBA Nathan',25,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (130,5,'NELSON Jayden',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=9',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (131,6,'VASILJ Nikola',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (132,6,'MUJAKIC Nihad',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (133,6,'HADZIKADUNIC Dennis',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (134,6,'MUHAREMOVIC Tarik',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (135,6,'KOLASINAC Sead',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (136,6,'TAHIROVIC Benjamin',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (137,6,'DEDIC Amar',7,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (138,6,'GIGOVIC Armin',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (139,6,'BAZDAR Samed',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (140,6,'DEMIROVIC Ermedin',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (141,6,'DZEKO Edin',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (142,6,'JURKAS Mladen',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (143,6,'BASIC Ivan',13,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (144,6,'SUNJIC Ivan',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (145,6,'MEMIC Amar',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (146,6,'HADZIAHMETOVIC Amir',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (147,6,'BURNIC Dzenis',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (148,6,'KATIC Nikola',18,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (149,6,'ALAJBEGOVIC Kerim',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (150,6,'BAJRAKTAREVIC Esmir',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (151,6,'RADELJIC Stjepan',21,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (152,6,'ZLOMISLIC Martin',22,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (153,6,'TABAKOVIC Haris',23,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (154,6,'MALIC Arjan',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (155,6,'LUKIC Jovo',25,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (156,6,'MAHMIC Ermin',26,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=6',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (157,7,'MAHMOUD ABUNADA',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (158,7,'PEDRO MIGUEL',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (159,7,'LUCAS MENDES',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (160,7,'ISSA LAYE',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (161,7,'JASSEM GABER',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (162,7,'ABDULAZIZ HATEM',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (163,7,'AHMED ALAAELDIN',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (164,7,'EDMILSON JUNIOR',8,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (165,7,'MOHAMMED MUNTARI',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (166,7,'HASSAN ALHAYDOS',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (167,7,'AKRAM AFIF',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (168,7,'KARIM BOUDIAF',12,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (169,7,'AYOUB ALOUI',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (170,7,'HOMAM AHMED',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (171,7,'YUSUF ABDURISAG',15,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (172,7,'BOUALEM KHOUKHI',16,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (173,7,'AHMED ALGANEHI',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (174,7,'SULTAN ALBRAKE',18,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (175,7,'ALMOEZ ALI',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (176,7,'AHMED FATHY',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (177,7,'SALAH ZAKARIA',21,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (178,7,'MESHAAL BARSHAM',22,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (179,7,'ASSIM MADIBO',23,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (180,7,'TAHSIN MOHAMMED',24,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (181,7,'ALHASHMI ALHUSSEIN',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (182,7,'MOHAMED MANAI',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=36',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (183,8,'KOBEL Gregor',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (184,8,'MUHEIM Miro',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (185,8,'WIDMER Silvan',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (186,8,'ELVEDI Nico',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (187,8,'AKANJI Manuel',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (188,8,'ZAKARIA Denis',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (189,8,'EMBOLO Breel',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (190,8,'FREULER Remo',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (191,8,'MANZAMBI Johan',9,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (192,8,'XHAKA Granit',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (193,8,'NDOYE Dan',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (194,8,'MVOGO Yvon',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (195,8,'RODRIGUEZ Ricardo',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (196,8,'JASHARI Ardon',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (197,8,'SOW Djibril',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (198,8,'FASSNACHT Christian',16,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (199,8,'VARGAS Ruben',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (200,8,'COEMERT Eray',18,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (201,8,'OKAFOR Noah',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (202,8,'AEBISCHER Michel',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (203,8,'KELLER Marvin',21,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (204,8,'RIEDER Fabian',22,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (205,8,'AMDOUNI Zeki',23,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (206,8,'AMENDA Aurele',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (207,8,'JAQUEZ Luca',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (208,8,'ITTEN Cedric',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=43',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (209,9,'ALISSON',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (210,9,'EDERSON SILVA',2,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (211,9,'GABRIEL MAGALHAES',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (212,9,'MARQUINHOS',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (213,9,'CASEMIRO',5,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (214,9,'ALEX SANDRO',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (215,9,'VINICIUS JUNIOR',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (216,9,'BRUNO GUIMARAES',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (217,9,'MATHEUS CUNHA',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (218,9,'NEYMAR JR',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (219,9,'RAPHINHA',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (220,9,'WEVERTON',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (221,9,'DANILO',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (222,9,'BREMER',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (223,9,'LEO PEREIRA',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (224,9,'DOUGLAS SANTOS',16,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (225,9,'FABINHO',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (226,9,'DANILO SANTOS',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (227,9,'ENDRICK',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (228,9,'LUCAS PAQUETA',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (229,9,'LUIZ HENRIQUE',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (230,9,'GABRIEL MARTINELLI',22,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (231,9,'EDERSON',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (232,9,'ROGER IBANEZ',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (233,9,'IGOR THIAGO',25,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (234,9,'RAYAN',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=7',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (235,10,'BOUNOU Yassine',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (236,10,'HAKIMI Achraf',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (237,10,'MAZRAOUI Noussair',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (238,10,'AMRABAT Sofyan',4,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (239,10,'SAADANE Marwane',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (240,10,'BOUADDI Ayyoub',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (241,10,'TALBI Chemsdine',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (242,10,'OUNAHI Azzedine',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (243,10,'RAHIMI Soufiane',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (244,10,'DIAZ Brahim',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (245,10,'SAIBARI Ismael',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (246,10,'EL KAJOUI Munir',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (247,10,'EL OUAHDI Zakaria',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (248,10,'DIOP Issa',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (249,10,'EL MOURABET Samir',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (250,10,'YASSINE Gessime',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (251,10,'SBAI Amine',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (252,10,'RIAD Chadi',18,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (253,10,'BELAMMARI Youssef',19,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (254,10,'EL KAABI Ayoub',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (255,10,'AMAIMOUNI Ayoube',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (256,10,'TAGNAOUTI Ahmed Reda',22,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (257,10,'EL KHANNOUSS Bilal',23,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (258,10,'EL AYNAOUI Neil',24,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (259,10,'HALHAL Redouane',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (260,10,'SALAH EDDINE Anass',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=29',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (261,11,'PLACIDE Johny',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (262,11,'ARCUS Carlens',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (263,11,'THERMONCY Keeto',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (264,11,'ADE Ricardo',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (265,11,'DELCROIX Hannes',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (266,11,'SAINTE Carl',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (267,11,'ETIENNE Derrick',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (268,11,'EXPERIENCE Martin',8,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (269,11,'NAZON Duckens',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (270,11,'BELLEGARDE Jean-Ricner',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (271,11,'DEEDSON Louicius',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (272,11,'PIERRE Alexandre',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (273,11,'LACROIX Markhus',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (274,11,'METUSALA Garven',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (275,11,'PROVIDENCE Ruben',15,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (276,11,'JOSEPH Lenny',16,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (277,11,'JEAN JACQUES Danley',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (278,11,'ISIDOR Wilson',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (279,11,'FORTUNE Yassin',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (280,11,'PIERROT Frantzdy',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (281,11,'CASIMIR Josue',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (282,11,'DUVERNE Jean-Kevin',22,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (283,11,'DUVERGER Josue',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (284,11,'PAUGAIN Wilguens',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (285,11,'SIMON Dominique',25,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (286,11,'PIERRE Woodensky',26,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=22',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (287,12,'GUNN Angus',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (288,12,'HICKEY Aaron',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (289,12,'ROBERTSON Andy',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (290,12,'McTOMINAY Scott',4,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (291,12,'HANLEY Grant',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (292,12,'TIERNEY Kieran',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (293,12,'McGINN John',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (294,12,'FLETCHER Tyler',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (295,12,'DYKES Lyndon',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (296,12,'ADAMS Che',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (297,12,'CHRISTIE Ryan',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (298,12,'KELLY Liam',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (299,12,'HENDRY Jack',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (300,12,'STEWART Ross',14,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (301,12,'SOUTTAR John',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (302,12,'HYAM Dominic',16,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (303,12,'GANNON-DOAK Ben',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (304,12,'HIRST George',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (305,12,'FERGUSON Lewis',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (306,12,'SHANKLAND Lawrence',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (307,12,'GORDON Craig',21,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (308,12,'PATTERSON Nathan',22,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (309,12,'McLEAN Kenny',23,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (310,12,'RALSTON Anthony',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (311,12,'CURTIS Findlay',25,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (312,12,'McKENNA Scott',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=38',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (313,13,'TURNER Matt',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (314,13,'DEST Sergino',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (315,13,'RICHARDS Chris',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (316,13,'ADAMS Tyler',4,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (317,13,'ROBINSON Antonee',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (318,13,'TRUSTY Auston',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (319,13,'REYNA Giovanni',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (320,13,'McKENNIE Weston',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (321,13,'PEPI Ricardo',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (322,13,'PULISIC Christian',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (323,13,'AARONSON Brenden',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (324,13,'ROBINSON Miles',12,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (325,13,'REAM Tim',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (326,13,'BERHALTER Sebastian',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (327,13,'ROLDAN Cristian',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (328,13,'FREEMAN Alex',16,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (329,13,'TILLMAN Malik',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (330,13,'ARFSTEN Max',18,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (331,13,'WRIGHT Haji',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (332,13,'BALOGUN Folarin',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (333,13,'WEAH Timothy',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (334,13,'McKENZIE Mark',22,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (335,13,'SCALLY Joe',23,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (336,13,'FREESE Matt',24,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (337,13,'BRADY Chris',25,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (338,13,'ZENDEJAS Alex',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=47',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (339,14,'FERNANDEZ Gatito',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (340,14,'VELAZQUEZ Gustavo',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (341,14,'ALDERETE Omar',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (342,14,'CACERES Juan Jose',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (343,14,'BALBUENA Fabian',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (344,14,'ALONSO Junior',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (345,14,'SOSA Ramon',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (346,14,'GOMEZ Diego',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (347,14,'SANABRIA Antonio',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (348,14,'ALMIRON Miguel',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (349,14,'MAURICIO',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (350,14,'GILL Orlando',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (351,14,'CANALE Jose',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (352,14,'CUBAS Andres',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (353,14,'GOMEZ Gustavo',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (354,14,'BOBADILLA Damian',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (355,14,'ROMERO GAMARRA Alejandro',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (356,14,'ARCE Alex',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (357,14,'ENCISO Julio',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (358,14,'OJEDA Braian',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (359,14,'AVALOS Gabriel',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (360,14,'OLVEIRA Gaston',22,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (361,14,'GALARZA Matias',23,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (362,14,'CABALLERO Gustavo',24,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (363,14,'PITTA Isidro',25,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (364,14,'MAIDANA Alexandro',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=34',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (365,15,'RYAN Mathew',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (366,15,'DEGENEK Milos',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (367,15,'CIRCATI Alessandro',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (368,15,'ITALIANO Jacob',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (369,15,'BOS Jordan',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (370,15,'GERIA Jason',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (371,15,'LECKIE Mathew',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (372,15,'METCALFE Connor',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (373,15,'TOURE Mohamed',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (374,15,'HRUSTIC Ajdin',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (375,15,'MABIL Awer',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (376,15,'IZZO Paul',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (377,15,'ONEILL Aiden',13,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (378,15,'DEVLIN Cameron',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (379,15,'TREWIN Kai',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (380,15,'BEHICH Aziz',16,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (381,15,'IRANKUNDA Nestory',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (382,15,'BEACH Patrick',18,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (383,15,'SOUTTAR Harry',19,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (384,15,'VOLPATO Cristian',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (385,15,'BURGESS Cameron',21,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (386,15,'IRVINE Jackson',22,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (387,15,'VELUPILLAY Nishan',23,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (388,15,'OKON-ENGSTLER Paul',24,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (389,15,'HERRINGTON Lucas',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (390,15,'YENGI Tete',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=3',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (391,16,'GUNOK Mert',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (392,16,'CELIK Zeki',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (393,16,'DEMIRAL Merih',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (394,16,'SOYUNCU Caglar',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (395,16,'OZCAN Salih',5,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (396,16,'KOKCU Orkun',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (397,16,'AKTURKOGLU Kerem',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (398,16,'GULER Arda',8,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (399,16,'GUL Deniz',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (400,16,'CALHANOGLU Hakan',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (401,16,'YILDIZ Kenan',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (402,16,'BAYINDIR Altay',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (403,16,'ELMALI Eren',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (404,16,'BARDAKCI Abdulkerim',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (405,16,'KABAK Ozan',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (406,16,'YUKSEK Ismail',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (407,16,'KAHVECI Irfan Can',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (408,16,'MULDUR Mert',18,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (409,16,'AKGUN Yunus',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (410,16,'KADIOGLU Ferdi',20,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (411,16,'YILMAZ Baris Alper',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (412,16,'AYHAN Kaan',22,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (413,16,'CAKIR Ugurcan',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (414,16,'AYDIN Oguz',24,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (415,16,'AKAYDIN Samet',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (416,16,'UZUN Can',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=45',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (417,17,'NEUER Manuel',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (418,17,'RUEDIGER Antonio',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (419,17,'ANTON Waldemar',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (420,17,'TAH Jonathan',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (421,17,'PAVLOVIC Aleksandar',5,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (422,17,'KIMMICH Joshua',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (423,17,'HAVERTZ Kai',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (424,17,'GORETZKA Leon',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (425,17,'LEWELING Jamie',9,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (426,17,'MUSIALA Jamal',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (427,17,'WOLTEMADE Nick',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (428,17,'BAUMANN Oliver',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (429,17,'GROSS Pascal',13,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (430,17,'BEIER Maximilian',14,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (431,17,'SCHLOTTERBECK Nico',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (432,17,'STILLER Angelo',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (433,17,'WIRTZ Florian',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (434,17,'BROWN Nathaniel',18,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (435,17,'SANE Leroy',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (436,17,'AMIRI Nadiem',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (437,17,'NUEBEL Alexander',21,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (438,17,'RAUM David',22,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (439,17,'NMECHA Felix',23,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (440,17,'THIAW Malick',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (441,17,'OUEDRAOGO Assan',25,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (442,17,'UNDAV Deniz',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=20',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (443,18,'ROOM Eloy',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (444,18,'SAMBO Shurandy',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (445,18,'GAARI Jurien',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (446,18,'VAN EIJMA Roshon',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (447,18,'FLORANUS Sherel',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (448,18,'ROEMERATOE Godfried',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (449,18,'BACUNA Juninho',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (450,18,'COMENENCIA Livano',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (451,18,'LOCADIA Juergen',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (452,18,'BACUNA Leandro',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (453,18,'ANTONISSE Jeremy',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (454,18,'HANSEN Sontje',12,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (455,18,'NOSLIN Tyrese',13,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (456,18,'GORRE Kenji',14,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (457,18,'MARTHA Arjany',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (458,18,'MARGARITHA Jearl',16,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (459,18,'KUWAS Brandley',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (460,18,'OBISPO Armando',18,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (461,18,'KASTANEER Gervane',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (462,18,'BRENET Joshua',20,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (463,18,'CHONG Tahith',21,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (464,18,'FELIDA Kevin',22,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (465,18,'BAZOER Riechedly',23,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (466,18,'FONVILLE Deveron',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (467,18,'BODAK Tyrick',25,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (468,18,'DOORNBUSCH Trevor',26,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=14',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (469,19,'FOFANA Yahia',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (470,19,'DIOMANDE Ousmane',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (471,19,'KONAN Ghislain',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (472,19,'SERI Jean Michael',4,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (473,19,'SINGO Wilfried',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (474,19,'FOFANA Seko',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (475,19,'KOSSOUNOU Odilon',7,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (476,19,'KESSIE Franck',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (477,19,'BONNY Ange-Yoan',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (478,19,'ADINGRA Simon',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (479,19,'DIOMANDE Yan',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (480,19,'WAHI Elye',12,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (481,19,'OPERI Christopher',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (482,19,'DIAKITE Oumar',14,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (483,19,'DIALLO Amad',15,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (484,19,'KONE Mohamed',16,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (485,19,'DOUE Guela',17,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (486,19,'SANGARE Ibrahim',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (487,19,'PEPE Nicolas',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (488,19,'AGBADOU Emmanuel',20,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (489,19,'NDICKA Evan',21,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (490,19,'GUESSAND Evann',22,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (491,19,'LAFONT Alban',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (492,19,'TOURE Bazoumana',24,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (493,19,'GUIAGON Parfait',25,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (494,19,'OULAI Christ Inao',26,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=12',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (495,20,'GALINDEZ Hernan',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (496,20,'TORRES Felix',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (497,20,'HINCAPIE Piero',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (498,20,'ORDONEZ Joel',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (499,20,'ALCIVAR Jordy',5,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (500,20,'PACHO Willian',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (501,20,'ESTUPINAN Pervis',7,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (502,20,'VALENCIA Anthony',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (503,20,'YEBOAH John',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (504,20,'PAEZ Kendry',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (505,20,'RODRIGUEZ Kevin',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (506,20,'RAMIREZ Moises',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (507,20,'VALENCIA Enner',13,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (508,20,'MINDA Alan',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (509,20,'VITE Pedro',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (510,20,'CAICEDO Jordy',16,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (511,20,'PRECIADO Angelo',17,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (512,20,'CASTILLO Denil',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (513,20,'PLATA Gonzalo',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (514,20,'ANGULO Nilson',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (515,20,'FRANCO Alan',21,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (516,20,'VALLE Gonzalo',22,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (517,20,'CAICEDO Moises',23,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (518,20,'AREVALO Jeremy',24,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (519,20,'POROZO Jackson',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (520,20,'MEDINA Yaimar',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=16',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (521,21,'VERBRUGGEN Bart',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (522,21,'GEERTRUIDA Lutsharel',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (523,21,'DE ROON Marten',3,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (524,21,'VAN DIJK Virgil',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (525,21,'AKE Nathan',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (526,21,'VAN HECKE Jan Paul',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (527,21,'KLUIVERT Justin',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (528,21,'GRAVENBERCH Ryan',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (529,21,'WEGHORST Wout',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (530,21,'DEPAY Memphis',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (531,21,'GAKPO Cody',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (532,21,'WIEFFER Mats',12,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (533,21,'ROEFS Robin',13,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (534,21,'REIJNDERS Tijjani',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (535,21,'VAN DE VEN Micky',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (536,21,'TIL Guus',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (537,21,'LANG Noa',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (538,21,'MALEN Donyell',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (539,21,'BROBBEY Brian',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (540,21,'KOOPMEINERS Teun',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (541,21,'DE JONG Frenkie',21,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (542,21,'DUMFRIES Denzel',22,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (543,21,'FLEKKEN Mark',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (544,21,'SUMMERVILLE Crysencio',24,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (545,21,'HATO Jorrel',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (546,21,'TIMBER Quinten',26,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=30',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (547,22,'SUZUKI Zion',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (548,22,'SUGAWARA Yukinari',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (549,22,'TANIGUCHI Shogo',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (550,22,'ITAKURA Kou',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (551,22,'NAGATOMO Yuto',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (552,22,'MACHINO Shuto',6,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (553,22,'TANAKA Ao',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (554,22,'KUBO Takefusa',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (555,22,'GOTO Keisuke',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (556,22,'DOAN Ritsu',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (557,22,'MAEDA Daizen',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (558,22,'OSAKO Keisuke',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (559,22,'NAKAMURA Keito',13,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (560,22,'ITO Junya',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (561,22,'KAMADA Daichi',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (562,22,'WATANABE Tsuyoshi',16,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (563,22,'SUZUKI Yuito',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (564,22,'UEDA Ayase',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (565,22,'OGAWA Koki',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (566,22,'SEKO Ayumu',20,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (567,22,'ITO Hiroki',21,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (568,22,'TOMIYASU Takehiro',22,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (569,22,'HAYAKAWA Tomoki',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (570,22,'SANO Kaishu',24,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (571,22,'SUZUKI Junnosuke',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (572,22,'SHIOGAI Kento',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=25',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (573,23,'WIDELL ZETTERSTROM Jacob',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (574,23,'LAGERBIELKE Gustaf',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (575,23,'LINDELOF Victor',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (576,23,'HIEN Isak',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (577,23,'GUDMUNDSSON Gabriel',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (578,23,'JOHANSSON Herman',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (579,23,'BERGVALL Lucas',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (580,23,'SVENSSON Daniel',8,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (581,23,'ISAK Alexander',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (582,23,'NYGREN Benjamin',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (583,23,'ELANGA Anthony',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (584,23,'JOHANSSON Viktor',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (585,23,'SEMA Ken',13,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (586,23,'EKDAL Hjalmar',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (587,23,'STARFELT Carl',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (588,23,'KARLSTROM Jesper',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (589,23,'GYOKERES Viktor',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (590,23,'AYARI Yasin',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (591,23,'SVANBERG Mattias',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (592,23,'SMITH Eric',20,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (593,23,'BERNHARDSSON Alexander',21,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (594,23,'ZENELI Besfort',22,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (595,23,'NORDFELDT Kristoffer',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (596,23,'STROUD Elliot',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (597,23,'NILSSON Gustaf',25,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (598,23,'ALI Taha',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=42',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (599,24,'CHAMAKH Mouhib',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (600,24,'ABDI Ali',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (601,24,'TALBI Montassar',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (602,24,'REKIK Omar',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (603,24,'AROUS Adam',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (604,24,'BRONN Dylan',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (605,24,'ACHOURI Elias',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (606,24,'SAAD Elias',8,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (607,24,'MASTOURI Hazem',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (608,24,'MEJBRI Hannibal',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (609,24,'GHARBI Ismael',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (610,24,'BEN OUANES Mortadha',12,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (611,24,'KHEDIRA Rani',13,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (612,24,'AYARI Khalil',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (613,24,'HADJ MAHMOUD Mohamed',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (614,24,'DAHMEN Aymen',16,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (615,24,'SKHIRI Ellyes',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (616,24,'ELLOUMI Rayan',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (617,24,'CHAOUAT Firas',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (618,24,'VALERY Yan',20,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (619,24,'BEN HMIDA Mohamed Amine',21,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (620,24,'BEN HESSEN Sabri',22,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (621,24,'NEFFATI Moutaz',23,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (622,24,'CHIKHAOUI Raed',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (623,24,'SLIMANE Anis',25,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (624,24,'TOUNEKTI Sebastian',26,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=44',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (625,25,'COURTOIS Thibaut',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (626,25,'DEBAST Zeno',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (627,25,'THEATE Arthur',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (628,25,'MECHELE Brandon',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (629,25,'DE CUYPER Maxim',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (630,25,'WITSEL Axel',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (631,25,'DE BRUYNE Kevin',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (632,25,'TIELEMANS Youri',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (633,25,'LUKAKU Romelu',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (634,25,'TROSSARD Leandro',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (635,25,'DOKU Jeremy',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (636,25,'LAMMENS Senne',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (637,25,'PENDERS Mike',13,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (638,25,'LUKEBAKIO Dodi',14,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (639,25,'MEUNIER Thomas',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (640,25,'DE WINTER Koni',16,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (641,25,'DE KETELAERE Charles',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (642,25,'SEYS Joaquin',18,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (643,25,'MOREIRA Diego',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (644,25,'VANAKEN Hans',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (645,25,'CASTAGNE Timothy',21,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (646,25,'SAELEMAEKERS Alexis',22,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (647,25,'RASKIN Nicolas',23,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (648,25,'ONANA Amadou',24,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (649,25,'NGOY Nathan',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (650,25,'FERNANDEZ-PARDO Matias',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=5',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (651,26,'MOHAMED ELSHENAWY',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (652,26,'YASSER IBRAHIM',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (653,26,'MOHAMED HANY',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (654,26,'HOSSAM ABDELMAGUID',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (655,26,'RAMY RABIA',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (656,26,'MOHAMED ABDELMONEIM',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (657,26,'TREZEGUET',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (658,26,'EMAM ASHOUR',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (659,26,'HAMZA ABDELKARIM',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (660,26,'MOHAMED SALAH',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (661,26,'MOSTAFA ZICO',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (662,26,'HAISSEM HASSAN',12,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (663,26,'AHMED FATOUH',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (664,26,'HAMDY FATHY',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (665,26,'KARIM HAFEZ',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (666,26,'MAHDY SOLIMAN',16,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (667,26,'MOHANAD LASHIN',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (668,26,'NABIL DONGA',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (669,26,'MARAWAN ATTIA',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (670,26,'IBRAHIM ADEL',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (671,26,'MAHMOUD SABER',21,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (672,26,'OMAR MARMOUSH',22,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (673,26,'MOSTAFA SHOUBIR',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (674,26,'TAREK ALAA',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (675,26,'ZIZO',25,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (676,26,'MOHAMED ALAA',26,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=17',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (677,27,'BEIRANVAND Alireza',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (678,27,'HARDANI Saleh',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (679,27,'HAJISAFI Ehsan',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (680,27,'KHALILZADEH Shoja',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (681,27,'MOHAMMADI Milad',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (682,27,'EZATOLAHI Saeid',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (683,27,'JAHANBAKHSH Alireza',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (684,27,'MOHEBBI Mohammad',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (685,27,'TAREMI Mehdi',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (686,27,'GHAYEDI Mehdi',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (687,27,'ALIPOUR Ali',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (688,27,'NIAZMAND Payam',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (689,27,'KANANI Hossein',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (690,27,'GHODDOS Saman',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (691,27,'CHESHMI Roozbeh',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (692,27,'TORABI Mehdi',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (693,27,'YOUSEFI Arya',17,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (694,27,'HOSSEINZADEH Amirhossein',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (695,27,'NEMATI Ali',19,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (696,27,'MOGHANLOO Shahriyar',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (697,27,'GHORBANI Mohammad',21,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (698,27,'HOSSEINI Hossein',22,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (699,27,'REZAEIAN Ramin',23,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (700,27,'DARGAHI Dennis',24,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (701,27,'IRI Danial',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (702,27,'RAZAGHINIA Amirmohammad',26,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=23',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (703,28,'CROCOMBE Max',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (704,28,'PAYNE Tim',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (705,28,'DE VRIES Francis',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (706,28,'BINDON Tyler',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (707,28,'BOXALL Michael',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (708,28,'BELL Joe',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (709,28,'ROGERSON Logan',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (710,28,'STAMENIC Marko',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (711,28,'WOOD Chris',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (712,28,'SINGH Sarpreet',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (713,28,'JUST Elijah',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (714,28,'PAULSEN Alex',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (715,28,'CACACE Liberato',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (716,28,'RUFER Alex',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (717,28,'PIJNAKER Nando',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (718,28,'SURMAN Finn',16,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (719,28,'BARBAROUSES Kosta',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (720,28,'WAINE Ben',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (721,28,'OLD Ben',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (722,28,'McCOWATT Callum',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (723,28,'RANDALL Jesse',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (724,28,'WOUD Michael',22,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (725,28,'THOMAS Ryan',23,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (726,28,'ELLIOT Callan',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (727,28,'BAYLISS Lachlan',25,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (728,28,'SMITH Tommy',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=31',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (729,29,'RAYA David',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (730,29,'PUBILL Marc',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (731,29,'GRIMALDO Alex',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (732,29,'GARCIA Eric',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (733,29,'LLORENTE Marcos',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (734,29,'MERINO Mikel',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (735,29,'TORRES Ferran',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (736,29,'RUIZ Fabian',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (737,29,'GAVI',9,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (738,29,'OLMO Dani',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (739,29,'PINO Yeremy',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (740,29,'PORRO Pedro',12,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (741,29,'GARCIA Joan',13,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (742,29,'LAPORTE Aymeric',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (743,29,'BAENA Alex',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (744,29,'RODRI',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (745,29,'WILLIAMS Nico',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (746,29,'ZUBIMENDI Martin',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (747,29,'YAMAL Lamine',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (748,29,'PEDRI',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (749,29,'OYARZABAL Mikel',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (750,29,'CUBARSI Pau',22,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (751,29,'SIMON Unai',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (752,29,'CUCURELLA Marc',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (753,29,'MUNOZ Victor',25,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (754,29,'IGLESIAS Borja',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=41',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (755,30,'VOZINHA',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (756,30,'STOPIRA',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (757,30,'DINEY BORGES',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (758,30,'PICO LOPES',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (759,30,'LOGAN COSTA',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (760,30,'KEVIN PINA',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (761,30,'JOVANE CABRAL',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (762,30,'JOAO PAULO',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (763,30,'GILSON BENCHIMOL',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (764,30,'JAMIRO MONTEIRO',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (765,30,'GARRY RODRIGUES',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (766,30,'MARCIO ROSA',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (767,30,'SIDNY LOPES CABRAL',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (768,30,'DEROY DUARTE',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (769,30,'LAROS DUARTE',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (770,30,'YANNICK SEMEDO',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (771,30,'WILLY SEMEDO',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (772,30,'TELMO ARCANJO',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (773,30,'DAILON LIVRAMENTO',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (774,30,'RYAN MENDES',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (775,30,'NUNO DA COSTA',21,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (776,30,'STEVEN MOREIRA',22,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (777,30,'CJ DOS SANTOS',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (778,30,'WAGNER PINA',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (779,30,'KELVIN PIRES',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (780,30,'HELIO VARELA',26,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=8',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (781,31,'NAWAF ALAQIDI',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (782,31,'ALI MAJRASHI',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (783,31,'ALI LAJAMI',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (784,31,'ABDULELAH ALAMRI',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (785,31,'HASSAN ALTAMBAKTI',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (786,31,'NASSER ALDAWSARI',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (787,31,'MUSAB ALJUWAYR',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (788,31,'AIMAN YAHYA',8,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (789,31,'FERAS ALBRIKAN',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (790,31,'SALEM ALDAWSARI',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (791,31,'SALEH ALSHEHRI',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (792,31,'SAUD ABDULHAMID',12,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (793,31,'NAWAF BU WASHL',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (794,31,'HASSAN KADISH',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (795,31,'ABDULLAH ALKHAIBARI',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (796,31,'ZIYAD ALJOHANI',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (797,31,'KHALID ALGHANNAM',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (798,31,'ALA ALHAJJI',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (799,31,'ABDULLAH ALHAMDDAN',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (800,31,'SULTAN MANDASH',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (801,31,'MOHAMMED ALOWAIS',21,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (802,31,'AHMED ALKASSAR',22,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (803,31,'MOHAMED KANNO',23,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (804,31,'MOTEB ALHARBI',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (805,31,'JEHAD THIKRI',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (806,31,'MOHAMMED ABU ALSHAMAT',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=37',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (807,32,'ROCHET Sergio',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (808,32,'GIMENEZ Jose Maria',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (809,32,'CACERES Sebastian',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (810,32,'ARAUJO Ronald',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (811,32,'UGARTE Manuel',5,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (812,32,'BENTANCUR Rodrigo',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (813,32,'DE LA CRUZ Nicolas',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (814,32,'VALVERDE Federico',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (815,32,'NUNEZ Darwin',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (816,32,'DE ARRASCAETA Giorgian',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (817,32,'PELLISTRI Facundo',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (818,32,'MELE Santiago',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (819,32,'VARELA Guillermo',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (820,32,'CANOBBIO Agustin',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (821,32,'MARTINEZ Emiliano',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (822,32,'OLIVERA Mathias',16,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (823,32,'VINA Matias',17,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (824,32,'RODRIGUEZ Brian',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (825,32,'AGUIRRE Rodrigo',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (826,32,'ARAUJO Maxi',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (827,32,'VINAS Federico',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (828,32,'PIQUEREZ Joaquin',22,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (829,32,'MUSLERA Fernando',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (830,32,'BUENO Santiago',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (831,32,'SANABRIA Juan Manuel',25,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (832,32,'ZALAZAR Rodrigo',26,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=46',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (833,33,'SAMBA Brice',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (834,33,'GUSTO Malo',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (835,33,'DIGNE Lucas',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (836,33,'UPAMECANO Dayot',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (837,33,'KOUNDE Jules',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (838,33,'KONE Manu',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (839,33,'DEMBELE Ousmane',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (840,33,'TCHOUAMENI Aurelien',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (841,33,'THURAM Marcus',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (842,33,'MBAPPE Kylian',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (843,33,'OLISE Michael',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (844,33,'BARCOLA Bradley',12,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (845,33,'KANTE Ngolo',13,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (846,33,'RABIOT Adrien',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (847,33,'KONATE Ibrahima',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (848,33,'MAIGNAN Mike',16,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (849,33,'SALIBA William',17,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (850,33,'ZAIRE-EMERY Warren',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (851,33,'HERNANDEZ Theo',19,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (852,33,'DOUE Desire',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (853,33,'HERNANDEZ Lucas',21,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (854,33,'MATETA Jean-Philippe',22,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (855,33,'RISSER Robin',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (856,33,'CHERKI Rayan',24,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (857,33,'AKLIOUCHE Maghnes',25,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (858,33,'LACROIX Maxence',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=19',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (859,34,'DIOUF Yehvann',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (860,34,'SARR Mamadou',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (861,34,'KOULIBALY Kalidou',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (862,34,'SECK Abdoulaye',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (863,34,'GUEYE Idrissa Gana',5,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (864,34,'CISS Pathe',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (865,34,'DIAO Assane',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (866,34,'CAMARA Lamine',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (867,34,'DIENG Bamba',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (868,34,'MANE Sadio',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (869,34,'JACKSON Nicolas',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (870,34,'NDIAYE Cherif',12,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (871,34,'NDIAYE Iliman',13,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (872,34,'JAKOBS Ismail',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (873,34,'DIATTA Krepin',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (874,34,'MENDY Edouard',16,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (875,34,'SARR Pape Matar',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (876,34,'SARR Ismaila',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (877,34,'NIAKHATE Moussa',19,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (878,34,'MBAYE Ibrahim',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (879,34,'DIARRA Habib',21,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (880,34,'NDIAYE Bara Sapoko',22,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (881,34,'DIAW Mory',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (882,34,'MENDY Antoine',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (883,34,'DIOUF El Hadji Malick',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (884,34,'GUEYE Pape',26,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=39',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (885,35,'FAHAD TALIB',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (886,35,'REBIN SULAKA',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (887,35,'HUSSEIN ALI',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (888,35,'ZAID TAHSEEN',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (889,35,'AKAM HASHIM',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (890,35,'MUNAF YOUNUS',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (891,35,'YOUSSEF AMYN',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (892,35,'IBRAHIM BAYESH',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (893,35,'ALI ALHAMADI',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (894,35,'MOHANAD ALI',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (895,35,'AHMED QASEM',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (896,35,'JALAL HASSAN',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (897,35,'ALI YOUSIF',13,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (898,35,'ZIDANE IQBAL',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (899,35,'AHMED MAKNAZI',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (900,35,'AMIR ALAMMARI',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (901,35,'ALI JASIM',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (902,35,'AYMEN HUSSEIN',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (903,35,'KEVIN YAKOB',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (904,35,'AIMAR SHER',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (905,35,'MARKO FARJI',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (906,35,'AHMED BASIL',22,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (907,35,'MERCHAS DOSKI',23,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (908,35,'ZAID ISMAEL',24,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (909,35,'MUSTAFA SAADOON',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (910,35,'FRANS PUTROS',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=24',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (911,36,'NYLAND Orjan',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (912,36,'THORSBY Morten',2,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (913,36,'AJER Kristoffer',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (914,36,'OSTIGARD Leo',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (915,36,'MOLLER WOLFE David',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (916,36,'BERG Patrick',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (917,36,'SORLOTH Alexander',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (918,36,'BERGE Sander',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (919,36,'HAALAND Erling',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (920,36,'ODEGAARD Martin',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (921,36,'STRAND LARSEN Jorgen',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (922,36,'TANGVIK Sander',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (923,36,'SELVIK Egil',13,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (924,36,'AURSNES Fredrik',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (925,36,'BJORKAN Fredrik Andre',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (926,36,'HOLMGREN PEDERSEN Marcus',16,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (927,36,'HEGGEM Torbjorn',17,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (928,36,'THORSTVEDT Kristian',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (929,36,'AASGAARD Thelo',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (930,36,'NUSA Antonio',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (931,36,'SCHJELDERUP Andreas',21,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (932,36,'BOBB Oscar',22,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (933,36,'HAUGE Jens Petter',23,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (934,36,'LANGAS Sondre',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (935,36,'FALCHENER Henrik',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (936,36,'RYERSON Julian',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=32',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (937,37,'MUSSO Juan',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (938,37,'SENESI Marcos',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (939,37,'TAGLIAFICO Nicolas',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (940,37,'MONTIEL Gonzalo',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (941,37,'PAREDES Leandro',5,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (942,37,'MARTINEZ Lisandro',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (943,37,'DE PAUL Rodrigo',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (944,37,'BARCO Valentin',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (945,37,'ALVAREZ Julian',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (946,37,'MESSI Lionel',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (947,37,'LO CELSO Giovani',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (948,37,'RULLI Geronimo',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (949,37,'ROMERO Cristian',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (950,37,'PALACIOS Exequiel',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (951,37,'GONZALEZ Nico',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (952,37,'ALMADA Thiago',16,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (953,37,'SIMEONE Giuliano',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (954,37,'PAZ Nico',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (955,37,'OTAMENDI Nicolas',19,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (956,37,'MAC ALLISTER Alexis',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (957,37,'LOPEZ Jose Manuel',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (958,37,'MARTINEZ Lautaro',22,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (959,37,'MARTINEZ Emiliano',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (960,37,'FERNANDEZ Enzo',24,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (961,37,'MEDINA Facundo',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (962,37,'MOLINA Nahuel',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=2',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (963,38,'MASTIL Melvin',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (964,38,'MANDI Aissa',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (965,38,'ABADA Achref',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (966,38,'TOUGAI Mohamed Amine',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (967,38,'BELAID Zineddine',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (968,38,'ZERROUKI Ramiz',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (969,38,'MAHREZ Riyad',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (970,38,'AOUAR Houssem',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (971,38,'GOUIRI Amine',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (972,38,'CHAIBI Fares',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (973,38,'HADJ MOUSSA Anis',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (974,38,'BENBOUALI Nadhir',12,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (975,38,'HADJAM Jaouen',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (976,38,'BOUDAOUI Hicham',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (977,38,'AIT-NOURI Rayan',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (978,38,'BENBOT Oussama',16,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (979,38,'BELGHALI Rafik',17,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (980,38,'AMOURA Mohamed',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (981,38,'BENTALEB Nabil',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (982,38,'BOULBINA Adil',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (983,38,'BENSEBAINI Ramy',21,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (984,38,'MAZA Ibrahim',22,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (985,38,'ZIDANE Luca',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (986,38,'TITRAOUI Yassine',24,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (987,38,'GHEDJEMIS Fares',25,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (988,38,'CHERGUI Samir',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=1',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (989,39,'SCHLAGER Alexander',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (990,39,'AFFENGRUBER David',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (991,39,'DANSO Kevin',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (992,39,'SCHLAGER Xaver',4,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (993,39,'POSCH Stefan',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (994,39,'SEIWALD Nicolas',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (995,39,'ARNAUTOVIC Marko',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (996,39,'ALABA David',8,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (997,39,'SABITZER Marcel',9,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (998,39,'GRILLITSCH Florian',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (999,39,'GREGORITSCH Michael',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1000,39,'WIEGELE Florian',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1001,39,'PENTZ Patrick',13,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1002,39,'KALAJDZIC Sasa',14,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1003,39,'LIENHART Philipp',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1004,39,'MWENE Phillip',16,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1005,39,'CHUKWUEMEKA Carney',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1006,39,'SCHMID Romano',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1007,39,'LJUBICIC Dejan',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1008,39,'LAIMER Konrad',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1009,39,'WIMMER Patrick',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1010,39,'PRASS Alexander',22,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1011,39,'FRIEDL Marco',23,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1012,39,'WANNER Paul',24,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1013,39,'SVOBODA Michael',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1014,39,'SCHOEPF Alessandro',26,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=4',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1015,40,'YAZEED ABULAILA',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1016,40,'MOHAMMAD ABUHASHEESH',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1017,40,'ABDALLAH NASIB',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1018,40,'HUSAM ABUDAHAB',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1019,40,'YAZAN ALARAB',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1020,40,'AMER JAMOUS',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1021,40,'MOHAMMAD ABUZRAIQ',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1022,40,'NOOR ALRAWABDEH',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1023,40,'ALI OLWAN',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1024,40,'MOUSA ALTAMARI',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1025,40,'ODEH FAKHOURY',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1026,40,'NOUR BANIATEYAH',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1027,40,'MAHMOUD ALMARDI',13,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1028,40,'RAJAEI AYED',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1029,40,'IBRAHIM SADEH',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1030,40,'MOHAMMAD ABUALNADI',16,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1031,40,'SALEEM OBAID',17,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1032,40,'MOHAMMAD ABUGHOUSH',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1033,40,'SAED ALROSAN',19,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1034,40,'MOHANNAD ABUTAHA',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1035,40,'NIZAR ALRASHDAN',21,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1036,40,'ABDALLAH ALFAKHORI',22,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1037,40,'EHSAN HADDAD',23,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1038,40,'ALI AZAIZEH',24,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1039,40,'MOHAMMAD ALDAOUD',25,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1040,40,'ANAS BADAWI',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=26',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1041,41,'DIOGO COSTA',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1042,41,'NELSON SEMEDO',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1043,41,'RUBEN DIAS',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1044,41,'TOMAS ARAUJO',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1045,41,'DIOGO DALOT',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1046,41,'MATHEUS NUNES',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1047,41,'CRISTIANO RONALDO',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1048,41,'BRUNO FERNANDES',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1049,41,'GONCALO RAMOS',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1050,41,'BERNARDO SILVA',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1051,41,'JOAO FELIX',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1052,41,'JOSE SA',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1053,41,'RENATO VEIGA',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1054,41,'GONCALO INACIO',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1055,41,'JOAO NEVES',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1056,41,'FRANCISCO TRINCAO',16,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1057,41,'RAFAEL LEAO',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1058,41,'PEDRO NETO',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1059,41,'GONCALO GUEDES',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1060,41,'JOAO CANCELO',20,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1061,41,'RUBEN NEVES',21,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1062,41,'RUI SILVA',22,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1063,41,'VITINHA',23,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1064,41,'SAMU COSTA',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1065,41,'NUNO MENDES',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1066,41,'FRANCISCO CONCEICAO',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=35',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1067,42,'MPASI Lionel',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1068,42,'WAN-BISSAKA Aaron',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1069,42,'KAPUADI Steve',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1070,42,'TUANZEBE Axel',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1071,42,'BATUBINSIKA Dylan',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1072,42,'MUKAU Ngalayel',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1073,42,'MBUKU Nathanael',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1074,42,'MOUTOUSSAMY Samuel',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1075,42,'CIPENGA Brian',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1076,42,'BONGONDA Theo',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1077,42,'KAKUTA Gael',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1078,42,'KAYEMBE Joris',12,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1079,42,'ELIA Meschack',13,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1080,42,'SADIKI Noah',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1081,42,'TSHIBOLA Aaron',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1082,42,'FAYULU Timothy',16,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1083,42,'BAKAMBU Cedric',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1084,42,'PICKEL Charles',18,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1085,42,'MAYELE Fiston',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1086,42,'WISSA Yoane',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1087,42,'EPOLO Matthieu',21,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1088,42,'MBEMBA Chancel',22,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1089,42,'BANZA Simon',23,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1090,42,'KALULU Gedeon',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1091,42,'KAYEMBE Edo',25,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1092,42,'MASUAKU Arthur',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=11',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1093,43,'YUSUPOV Utkir',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366435,1795366435);
INSERT INTO `wc_football_player` VALUES (1094,43,'KHUSANOV Abdukodir',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1095,43,'ALIJONOV Khojiakbar',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1096,43,'SAYFIEV Farrukh',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1097,43,'ASHURMATOV Rustam',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1098,43,'MOZGOVOY Akmal',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1099,43,'SHUKUROV Otabek',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1100,43,'ISKANDEROV Jamshid',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1101,43,'XAMROBEKOV Odiljon',9,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1102,43,'JIYANOV Ruslanbek',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1103,43,'URUNOV Oston',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1104,43,'NEMATOV Abduvohid',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1105,43,'NASRULLAEV Sherzod',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1106,43,'SHOMURODOV Eldor',14,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1107,43,'ESHMURODOV Umar',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1108,43,'ERGASHEV Botirali',16,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1109,43,'KHAMDAMOV Dostonbek',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1110,43,'ABDULLAEV Abdulla',18,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1111,43,'GANIEV Azizjon',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1112,43,'AMONOV Azizbek',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1113,43,'SERGEEV Igor',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1114,43,'FAYZULLAEV Abbosbek',22,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1115,43,'ESANOV Sherzod',23,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1116,43,'KARIMOV Behruzjon',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1117,43,'ULMASALIYEV Avazbek',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1118,43,'UROZOV Jakhongir',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=48',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1119,44,'OSPINA David',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1120,44,'MUNOZ Daniel',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1121,44,'LUCUMI Jhon',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1122,44,'ARIAS Santiago',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1123,44,'CASTANO Kevin',5,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1124,44,'RIOS Richard',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1125,44,'DIAZ Luis',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1126,44,'CARRASCAL Jorge',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1127,44,'CORDOBA Jhon',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1128,44,'RODRIGUEZ James',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1129,44,'ARIAS Jhon',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1130,44,'VARGAS Camilo',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1131,44,'MINA Yerry',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1132,44,'PUERTA Gustavo',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1133,44,'PORTILLA Juan',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1134,44,'LERMA Jefferson',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1135,44,'MOJICA Johan',17,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1136,44,'DITTA Willer',18,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1137,44,'HERNANDEZ Cucho',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1138,44,'QUINTERO Juan',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1139,44,'CAMPAZ Jaminton',21,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1140,44,'MACHADO Deiver',22,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1141,44,'SANCHEZ Davinson',23,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1142,44,'MONTERO Alvaro',24,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1143,44,'SUAREZ Luis',25,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1144,44,'GOMEZ Andres',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=10',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1145,45,'PICKFORD Jordan',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1146,45,'KONSA Ezri',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1147,45,'OREILLY Nico',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1148,45,'RICE Declan',4,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1149,45,'STONES John',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1150,45,'GUEHI Marc',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1151,45,'SAKA Bukayo',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1152,45,'ANDERSON Elliot',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1153,45,'KANE Harry',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1154,45,'BELLINGHAM Jude',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1155,45,'RASHFORD Marcus',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1156,45,'CHALOBAH Trevoh',12,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1157,45,'HENDERSON Dean',13,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1158,45,'HENDERSON Jordan',14,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1159,45,'BURN Dan',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1160,45,'MAINOO Kobbie',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1161,45,'ROGERS Morgan',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1162,45,'GORDON Anthony',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1163,45,'WATKINS Ollie',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1164,45,'MADUEKE Noni',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1165,45,'EZE Eberechi',21,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1166,45,'TONEY Ivan',22,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1167,45,'TRAFFORD James',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1168,45,'JAMES Reece',24,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1169,45,'SPENCE Djed',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1170,45,'QUANSAH Jarell',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=18',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1171,46,'LIVAKOVIC Dominik',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1172,46,'STANISIC Josip',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1173,46,'PONGRACIC Marin',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1174,46,'GVARDIOL Josko',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1175,46,'CALETA-CAR Duje',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1176,46,'SUTALO Josip',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1177,46,'MORO Nikola',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1178,46,'KOVACIC Mateo',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1179,46,'KRAMARIC Andrej',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1180,46,'MODRIC Luka',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1181,46,'BUDIMIR Ante',11,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1182,46,'PANDUR Ivor',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1183,46,'VLASIC Nikola',13,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1184,46,'PERISIC Ivan',14,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1185,46,'PASALIC Mario',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1186,46,'BATURINA Martin',16,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1187,46,'SUCIC Petar',17,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1188,46,'JAKIC Kristijan',18,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1189,46,'FRUK Toni',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1190,46,'MATANOVIC Igor',20,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1191,46,'SUCIC Luka',21,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1192,46,'VUSKOVIC Luka',22,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1193,46,'KOTARSKI Dominik',23,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1194,46,'PASALIC Marco',24,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1195,46,'ERLIC Martin',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1196,46,'MUSA Petar',26,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=13',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1197,47,'ZIGI Lawrence Ati',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1198,47,'SEIDU Alidu',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1199,47,'YIRENKYI Caleb',3,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1200,47,'ADJETEY Jonas',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1201,47,'PARTEY Thomas',5,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1202,47,'MUMIN Abdul',6,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1203,47,'FATAWU Abdul',7,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1204,47,'SIBO Kwasi',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1205,47,'AYEW Jordan',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1206,47,'THOMAS-ASANTE Brandon',10,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1207,47,'SEMENYO Antoine',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1208,47,'ANANG Joseph',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1209,47,'BONSU BAAH Christopher',13,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1210,47,'MENSAH Gideon',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1211,47,'OWUSU Elisha',15,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1212,47,'ASARE Benjamin',16,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1213,47,'RAHMAN Baba',17,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1214,47,'OPOKU Jerome',18,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1215,47,'WILLIAMS Inaki',19,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1216,47,'BOAKYE Augustine',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1217,47,'OPPONG Kojo Peprah',21,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1218,47,'SULEMANA Kamaldeen',22,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1219,47,'LUCKASSEN Derrick',23,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1220,47,'NUAMAH Ernest',24,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1221,47,'ADU Prince',25,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1222,47,'SENAYA Marvin',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=21',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1223,48,'MEJIA Luis',1,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1224,48,'BLACKMAN Cesar',2,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1225,48,'CORDOBA Jose',3,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1226,48,'ESCOBAR Fidel',4,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1227,48,'FARINA Edgardo',5,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1228,48,'MARTINEZ Cristian',6,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1229,48,'RODRIGUEZ Jose Luis',7,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1230,48,'CARRASQUILLA Adalberto',8,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1231,48,'RODRIGUEZ Tomas',9,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1232,48,'DIAZ Ismael',10,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1233,48,'BARCENAS Edgar Yoel',11,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1234,48,'SAMUDIO Cesar',12,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1235,48,'RAMOS Jiovany',13,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1236,48,'HARVEY Carlos',14,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1237,48,'DAVIS Eric',15,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1238,48,'ANDRADE Andres',16,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1239,48,'FAJARDO Jose',17,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1240,48,'WATERMAN Cecilio',18,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1241,48,'QUINTERO Alberto',19,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1242,48,'GODOY Anibal',20,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1243,48,'YANIS Cesar',21,'中场','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1244,48,'MOSQUERA Orlando',22,'门将','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1245,48,'MURILLO Amir',23,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1246,48,'LONDONO Azarias',24,'前锋','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1247,48,'MILLER Roderick',25,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
INSERT INTO `wc_football_player` VALUES (1248,48,'GUTIERREZ Jorge',26,'后卫','https://fdp.fifa.org/assetspublic/ce281/pdf/SquadLists-English.pdf#page=33',1795366436,1795366436);
/*!40000 ALTER TABLE `wc_football_player` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wc_football_stadium` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `capacity` int(11) unsigned DEFAULT NULL,
  `source_url` varchar(500) DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `wc_football_stadium` DISABLE KEYS */;
INSERT INTO `wc_football_stadium` VALUES (1,'墨西哥城体育场','墨西哥城',80824,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (2,'瓜达拉哈拉体育场','瓜达拉哈拉',45664,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (3,'多伦多体育场','多伦多',43036,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (4,'亚特兰大体育场','亚特兰大',68239,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (5,'温哥华 BC Place 体育场','温哥华',52497,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (6,'蒙特雷体育场','蒙特雷',51243,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (7,'波士顿体育场','波士顿',64146,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (8,'达拉斯体育场','达拉斯',70649,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (9,'休斯敦体育场','休斯敦',68777,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (10,'堪萨斯城体育场','堪萨斯城',69045,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (11,'洛杉矶体育场','洛杉矶',70492,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (12,'迈阿密体育场','迈阿密',64478,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (13,'纽约新泽西体育场','纽约／新泽西',80663,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (14,'费城体育场','费城',68324,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (15,'旧金山湾区体育场','旧金山湾区',68827,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
INSERT INTO `wc_football_stadium` VALUES (16,'西雅图体育场','西雅图',66925,'https://tickets.fifa.com/organisation/news/fifa-world-cup-stadium-capacities-confirmed',1795366435,1795366435);
/*!40000 ALTER TABLE `wc_football_stadium` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wc_football_team` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `country_code` varchar(10) NOT NULL,
  `group_name` varchar(10) DEFAULT NULL,
  `source_url` varchar(500) DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `country_code` (`country_code`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `wc_football_team` DISABLE KEYS */;
INSERT INTO `wc_football_team` VALUES (1,'墨西哥','MEX','A','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (2,'南非','RSA','A','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (3,'韩国','KOR','A','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (4,'捷克','CZE','A','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (5,'加拿大','CAN','B','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (6,'波黑','BIH','B','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (7,'卡塔尔','QAT','B','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (8,'瑞士','SUI','B','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (9,'巴西','BRA','C','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (10,'摩洛哥','MAR','C','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (11,'海地','HAI','C','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (12,'苏格兰','SCO','C','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (13,'美国','USA','D','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (14,'巴拉圭','PAR','D','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (15,'澳大利亚','AUS','D','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (16,'土耳其','TUR','D','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (17,'德国','GER','E','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (18,'库拉索','CUW','E','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (19,'科特迪瓦','CIV','E','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (20,'厄瓜多尔','ECU','E','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (21,'荷兰','NED','F','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (22,'日本','JPN','F','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (23,'瑞典','SWE','F','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (24,'突尼斯','TUN','F','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (25,'比利时','BEL','G','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (26,'埃及','EGY','G','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (27,'伊朗','IRN','G','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (28,'新西兰','NZL','G','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (29,'西班牙','ESP','H','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (30,'佛得角','CPV','H','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (31,'沙特阿拉伯','KSA','H','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (32,'乌拉圭','URU','H','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (33,'法国','FRA','I','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (34,'塞内加尔','SEN','I','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (35,'伊拉克','IRQ','I','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (36,'挪威','NOR','I','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (37,'阿根廷','ARG','J','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (38,'阿尔及利亚','ALG','J','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (39,'奥地利','AUT','J','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (40,'约旦','JOR','J','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (41,'葡萄牙','POR','K','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (42,'刚果民主共和国','COD','K','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (43,'乌兹别克斯坦','UZB','K','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (44,'哥伦比亚','COL','K','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (45,'英格兰','ENG','L','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (46,'克罗地亚','CRO','L','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (47,'加纳','GHA','L','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
INSERT INTO `wc_football_team` VALUES (48,'巴拿马','PAN','L','https://digitalhub.fifa.com/asset/4b5d4417-3343-4732-9cdf-14b6662af407/FWC26-Match-Schedule_English.pdf',1795366435,1795366435);
/*!40000 ALTER TABLE `wc_football_team` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wc_news_article` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `title` varchar(200) NOT NULL,
  `summary` text DEFAULT NULL,
  `body` mediumtext NOT NULL,
  `source_name` varchar(100) DEFAULT NULL,
  `source_url` varchar(500) DEFAULT NULL,
  `published_at` int(11) unsigned DEFAULT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-news-article-category` (`category_id`),
  KEY `idx-news-article-published` (`status`,`published_at`,`id`),
  CONSTRAINT `fk-news-article-category` FOREIGN KEY (`category_id`) REFERENCES `wc_news_category` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `wc_news_article` DISABLE KEYS */;
/*!40000 ALTER TABLE `wc_news_article` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wc_news_article_tag` (
  `article_id` bigint(20) unsigned NOT NULL,
  `tag_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`article_id`,`tag_id`),
  KEY `idx-news-article-tag-tag` (`tag_id`),
  CONSTRAINT `fk-news-article-tag-article` FOREIGN KEY (`article_id`) REFERENCES `wc_news_article` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk-news-article-tag-tag` FOREIGN KEY (`tag_id`) REFERENCES `wc_news_tag` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `wc_news_article_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `wc_news_article_tag` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wc_news_category` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `wc_news_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `wc_news_category` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wc_news_tag` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `slug` varchar(80) NOT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `wc_news_tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `wc_news_tag` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wc_portal_assignment` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `category` varchar(20) NOT NULL,
  `owner_label` varchar(60) DEFAULT NULL,
  `relative_path` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(3) unsigned DEFAULT 0,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-wc_portal_assignment-status-category` (`status`,`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `wc_portal_assignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `wc_portal_assignment` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wc_portal_member` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `team_id` bigint(20) unsigned NOT NULL,
  `name` varchar(60) NOT NULL,
  `student_no` varchar(20) DEFAULT NULL,
  `role` varchar(60) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `avatar_path` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-wc_portal_member-team_id-sort_order` (`team_id`,`sort_order`),
  CONSTRAINT `fk-wc_portal_member-team_id` FOREIGN KEY (`team_id`) REFERENCES `wc_portal_team` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `wc_portal_member` DISABLE KEYS */;
/*!40000 ALTER TABLE `wc_portal_member` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wc_portal_message` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `display_name` varchar(60) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(3) unsigned DEFAULT 0,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx-wc_portal_message-status-created_at` (`status`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `wc_portal_message` DISABLE KEYS */;
/*!40000 ALTER TABLE `wc_portal_message` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wc_portal_reply` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `message_id` bigint(20) unsigned NOT NULL,
  `content` text NOT NULL,
  `admin_user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq-wc_portal_reply-message_id` (`message_id`),
  CONSTRAINT `fk-wc_portal_reply-message_id` FOREIGN KEY (`message_id`) REFERENCES `wc_portal_message` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `wc_portal_reply` DISABLE KEYS */;
/*!40000 ALTER TABLE `wc_portal_reply` ENABLE KEYS */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wc_portal_team` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `created_at` int(11) unsigned NOT NULL,
  `updated_at` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

/*!40000 ALTER TABLE `wc_portal_team` DISABLE KEYS */;
/*!40000 ALTER TABLE `wc_portal_team` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
