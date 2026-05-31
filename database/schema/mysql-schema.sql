/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contacts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contacts_member_id_foreign` (`member_id`),
  CONSTRAINT `contacts_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `email_broadcast_recipients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_broadcast_recipients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `broadcast_id` bigint(20) unsigned NOT NULL,
  `member_id` bigint(20) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `tracking_token` varchar(255) NOT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `opened_at` timestamp NULL DEFAULT NULL,
  `open_count` int(10) unsigned NOT NULL DEFAULT 0,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_broadcast_recipients_tracking_token_unique` (`tracking_token`),
  KEY `email_broadcast_recipients_member_id_foreign` (`member_id`),
  KEY `email_broadcast_recipients_broadcast_id_index` (`broadcast_id`),
  KEY `email_broadcast_recipients_tracking_token_index` (`tracking_token`),
  CONSTRAINT `email_broadcast_recipients_broadcast_id_foreign` FOREIGN KEY (`broadcast_id`) REFERENCES `email_broadcasts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `email_broadcast_recipients_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `email_broadcasts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_broadcasts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subject_en` varchar(255) DEFAULT NULL,
  `subject_ar` varchar(255) DEFAULT NULL,
  `body_en` longtext DEFAULT NULL,
  `body_ar` longtext DEFAULT NULL,
  `audience_type` enum('all_members','event_members') NOT NULL,
  `event_id` varchar(255) DEFAULT NULL,
  `language` enum('en','ar') NOT NULL DEFAULT 'en',
  `from_email` varchar(255) DEFAULT NULL,
  `from_name` varchar(255) DEFAULT NULL,
  `sent_by` bigint(20) unsigned NOT NULL,
  `status` enum('draft','sending','sent') NOT NULL DEFAULT 'draft',
  `total_recipients` int(10) unsigned NOT NULL DEFAULT 0,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `email_broadcasts_sent_by_foreign` (`sent_by`),
  CONSTRAINT `email_broadcasts_sent_by_foreign` FOREIGN KEY (`sent_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `email_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `email_templates` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `mailable_class` varchar(255) NOT NULL,
  `from_email` varchar(255) DEFAULT NULL,
  `subject_en` varchar(255) NOT NULL,
  `subject_ar` varchar(255) NOT NULL,
  `body_en` longtext NOT NULL,
  `body_ar` longtext NOT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_templates_mailable_class_unique` (`mailable_class`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `event_member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event_member` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` char(36) NOT NULL,
  `member_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_member_event_id_member_id_unique` (`event_id`,`member_id`),
  KEY `event_member_member_id_foreign` (`member_id`),
  CONSTRAINT `event_member_member_id_foreign` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` char(36) NOT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `title_en` varchar(255) NOT NULL,
  `title_ar` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description_en` longtext DEFAULT NULL,
  `description_ar` longtext DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `join_url` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL COMMENT 'Event cover image',
  `lecturer_name_en` varchar(255) DEFAULT NULL,
  `lecturer_name_ar` varchar(255) DEFAULT NULL,
  `lecturer_title_en` varchar(255) DEFAULT NULL,
  `lecturer_title_ar` varchar(255) DEFAULT NULL,
  `lecturer_image` varchar(255) DEFAULT NULL,
  `lecturer_linkedin` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_slug_unique` (`slug`),
  KEY `events_created_by_foreign` (`created_by`),
  CONSTRAINT `events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `otp_code` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `phone_code` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `education_level` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL,
  `specialty` varchar(255) NOT NULL,
  `specialty_other` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `membership_type` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unsubscribed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `members_email_unique` (`email`),
  UNIQUE KEY `members_phone_number_unique` (`phone_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `membership_tiers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `membership_tiers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `name_ar` varchar(255) NOT NULL,
  `description_en` text NOT NULL,
  `description_ar` text NOT NULL,
  `features_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features_en`)),
  `features_ar` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features_ar`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `membership_tiers_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` char(36) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `title_ar` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content_en` longtext NOT NULL,
  `content_ar` longtext NOT NULL,
  `type` enum('announcement','update','article') NOT NULL DEFAULT 'article',
  `image` varchar(255) DEFAULT NULL,
  `tags` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tags`)),
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_created_by_foreign` (`created_by`),
  CONSTRAINT `posts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `trainer_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `trainer_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `program_type` varchar(255) DEFAULT NULL,
  `duration_hours` int(11) DEFAULT NULL,
  `duration_days` int(11) DEFAULT NULL,
  `agenda` text DEFAULT NULL,
  `agreed_to_free_provision` tinyint(1) NOT NULL DEFAULT 0,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin',
  `permissions` text DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (4,'2026_02_16_131545_create_members_table',1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (5,'2026_02_16_103821_add_unique_constraints_to_members_table',2);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (6,'2026_02_16_104710_create_contacts_table',3);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (7,'2026_02_16_125958_create_settings_table',4);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (8,'2026_02_17_120000_update_membership_type_to_intern',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (9,'2026_02_18_000000_create_membership_tiers_table',5);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (10,'2026_02_18_000000_create_events_table',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (11,'2026_02_18_000001_modify_events_table_bilingual',6);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (12,'2026_02_18_194149_add_join_url_to_events_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (13,'2026_02_18_235602_add_gender_to_members_table',7);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (14,'2026_02_19_194114_add_created_by_to_events_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (15,'2026_02_19_194114_add_role_and_permissions_to_users_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (16,'2026_02_20_024441_create_posts_table',8);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (17,'2026_02_21_035927_add_user_id_and_bio_to_members_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (18,'2026_02_23_133706_create_event_member_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (19,'2026_02_23_141507_add_phone_number_to_contacts_table',9);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (20,'2026_02_17_000000_add_education_level_to_members_table',10);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (21,'2026_02_26_194956_add_member_id_to_contacts_table',11);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (22,'2026_03_05_223211_create_email_templates_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (23,'2026_03_05_223212_add_email_verification_to_members_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (24,'2026_03_06_031828_add_from_email_to_email_templates_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (25,'2026_03_06_032021_create_trainer_requests_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (26,'2026_03_06_033936_add_email_to_trainer_requests_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (27,'2026_03_06_042019_add_linkedin_url_to_members_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (28,'2026_03_06_042020_add_linkedin_url_to_trainer_requests_table',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (29,'2026_03_06_050259_modify_posts_id_to_uuid',12);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (30,'2026_03_06_050300_modify_events_id_to_uuid',999);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (31,'2026_03_06_050300_modify_events_id_to_uuid',999);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (32,'2026_03_06_050259_modify_posts_id_to_uuid',999);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33,'2026_03_07_192813_modify_trainer_requests_table_schema',1000);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (34,'2026_03_07_194222_add_duration_days_to_trainer_requests_table',1000);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35,'2026_03_07_201649_drop_country_from_trainer_requests_table',1001);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (36,'2026_03_09_201109_cleanup_event_member_schema_for_uuids',1002);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (37,'2026_03_09_214530_add_is_featured_to_posts_table',1003);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (38,'2026_04_18_000001_create_email_broadcasts_table',1004);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (39,'2026_04_18_000002_create_email_broadcast_recipients_table',1004);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (40,'2026_04_18_000003_add_unsubscribed_at_to_members_table',1004);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (41,'2026_04_18_000004_nullable_broadcast_content_columns',1005);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (42,'2026_04_19_000001_add_is_active_to_email_templates_table',1006);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (43,'2026_04_22_000001_fix_event_confirmation_email_template',1007);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (44,'2026_04_24_230734_add_sent_at_to_email_broadcast_recipients_table',1008);
