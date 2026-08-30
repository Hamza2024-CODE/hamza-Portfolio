-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for osx10.10 (x86_64)
--
-- Host: localhost    Database: portfolio_db
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

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
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `content` text DEFAULT NULL,
  `publish_date` date NOT NULL,
  `media_image` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `articles`
--

LOCK TABLES `articles` WRITE;
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` VALUES (1,'بناء أنظمة الـ ERP المؤسسية بـ Laravel و Redis','building-scalable-enterprise-erps','دراسة شاملة حول الهيكلية البرمجية النمطية، مصفوفات الصلاحيات RBAC، والتخزين المؤقت عالي السرعة لخدمة ملايين المعاملات.','محتوى المقال التفصيلي...','2026-06-15','public/assets/images/badimalika.jpg',1),(2,'تصميم واجهات الـ REST APIs وتطبيقات الـ PWA','designing-high-throughput-rest-apis','أفضل الممارسات في بناء الميكروسيرفيس، المصادقة الرقمية بـ JWT، وتطبيقات الويب الشغالة بدون إنترنت في البيئات الحكومية.','محتوى المقال التفصيلي...','2026-06-12','public/assets/images/Mountains-Nepal-II.jpg',1),(3,'الأمن السيبراني والـ DevOps للمنصات الحكومية','devops-security-institutional-platforms','تكوين سيرفرات Nginx العكسية، حماية سيرفرات Linux، والنسخ الاحتياطي التلقائي لقواعد البيانات بوزارة MFEP.','محتوى المقال التفصيلي...','2026-06-07','public/assets/images/marigold.jpg',1);
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'Web Development',
  `description` text NOT NULL,
  `key_contribution` text DEFAULT NULL,
  `conclusion` text DEFAULT NULL,
  `languages_used` varchar(255) NOT NULL,
  `project_media` text DEFAULT NULL,
  `github_link` varchar(255) DEFAULT NULL,
  `live_demo_link` varchar(255) DEFAULT NULL,
  `date_started` date NOT NULL,
  `date_finished` date DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES (1,'WSAP — منصة مسابقات وورلد سكيلز الجزائر','أنظمة مسابقات وتقييم','منصة رقمية متكاملة لإدارة المسابقات المهنية الوطنية، تقييم المترشحين، التفكير الآلي للنقاط، لجنة التحكيم، وإصدار الشهادات الرقمية المؤمنة.','Architected complete scoring matrix, RBAC security layer, and digital certificate generation.','Deployed successfully across competition centers in Algeria.','Laravel, PHP, MySQL, REST APIs, Tailwind CSS','public/assets/images/badimalika.jpg','https://github.com/Hamza2024-CODE/wsap','https://github.com/Hamza2024-CODE','2026-01-15','2026-06-20',1),(2,'Tassyir — نظام الـ ERP المؤسسي لقطاع التكوين المهني','أنظمة ERP مؤسسية','منصة تخطيط الموارد المؤسسية الموحدة لإدارة الشؤون الإدارية والمالية ومراكز التكوين والتعليم المهنيين عبر الوطن.','Engineered modular ERP architecture, administrative workflows, and real-time database synchronization.','Serves nationwide institutional centers.','Laravel, PHP, MySQL, Redis, REST APIs, Bootstrap','public/assets/images/Mountains-Nepal-II.jpg','https://github.com/Hamza2024-CODE/tassyir-erp',NULL,'2025-08-01','2026-03-10',1),(3,'SGFEP — المنصة الرقمية الوطنية لإدارة التكوين المهني','منصات حكومية وإدارية','منصة حكومية لمتابعة الإحصائيات الوطنية، التوجيه البرمجي، ولوحات التحكم الاستراتيجية لمتخذي القرار بوزارة MFEP.','Developed high-performance reporting engine and administrative analytics dashboards.','Enhanced operational efficiency across training sectors.','PHP, Laravel, MySQL, JavaScript, HTML5','public/assets/images/mani_baudha.jpg','https://github.com/Hamza2024-CODE/sgfep',NULL,'2025-04-10','2025-12-15',1),(4,'Tawjih — منصة التوجيه والتقييم الذكي للمترشحين','الذكاء الاصطناعي والتوجيه','محرك توجيه مهني مدعوم بالذكاء الاصطناعي لاختبار قدرات وميول المترشحين وتقديم التوصيات والمسارات المهنية المناسبة.','Implemented recommendation algorithms and adaptive question scoring modules.','Used by thousands of students for career orientation.','Python, Django, REST APIs, MySQL, JavaScript','public/assets/images/marigold.jpg','https://github.com/Hamza2024-CODE/tawjih-ai',NULL,'2025-02-01','2025-09-30',0),(5,'IKAMAT — منصة إدارة الإقامات والمرافق الطلابية','إدارة الخدمات والمرافق','نظام رقمي لتسيير وإدارة الإقامات الطلابية، توزيع الأسرة والغرف، ومتابعة طلبات الصيانة والإشعار الآلي.','Created cross-platform PWA interface and automated housing allocation algorithm.','Streamlined residence operations for vocational centers.','Flutter, Laravel, REST APIs, MySQL, PWA','public/assets/images/nepal boudhanath stupa.jpg','https://github.com/Hamza2024-CODE/ikamat',NULL,'2024-09-01','2025-01-20',0),(6,'Skills54 — منصة التعليم والتدريب عن بُعد','التعليم والتدريب الرقمي','منصة تعليمية رقمية لتقديم الدورات والمهارات الفنية والمحتوى المرئي التفاعلي وتتبع تقدم المتدربين.','Built video streaming pipelines, interactive quizzes, and course completion certificates.','Supported thousands of active learners.','PHP, Laravel, MySQL, JavaScript, HTML5/CSS3','public/assets/images/badimalika.jpg','https://github.com/Hamza2024-CODE/skills54',NULL,'2024-03-15','2024-08-30',0),(7,'Mahara — منصة إدارة وتصديق الكفاءات المهنية','إدارة الكفاءات والمؤهلات','نظام مؤسسي لتصديق المهارات المهنية، تتبع الخبرات، وإصدار جوازات السفر البرمجية وملفات الإنجاز الرقمية.','Designed competency framework model and verification workflow.','Adopted by training institutions.','ASP.NET, C#, SQL Server, JavaScript','public/assets/images/Mountains-Nepal-II.jpg','https://github.com/Hamza2024-CODE/mahara',NULL,'2023-11-01','2024-04-15',0),(8,'Special du Stagiaire — بوابة خدمات المتربص والمتدرب','بوابات الخدمات الرقمية','بوابة رقمية مخصصة للمتربصين للاطلاع على نتائج الامتحانات، الاستدعاءات الرقمية، والخدمات الإدارية المباشرة.','Optimized mobile web accessibility and fast query response times under heavy load.','Improved student service delivery.','PWA, JavaScript, PHP, MySQL, REST APIs','public/assets/images/marigold.jpg','https://github.com/Hamza2024-CODE/special-stagiaire',NULL,'2023-05-10','2023-10-01',0),(9,'Inchighal — بوابة الخدمات الرقمية واستقبال الانشغالات','الخدمات الإلكترونية للمواطنين','منصة حكومية رقمية لاستقبال، توجيه، ومعالجة عرايض وانشغالات المواطنين والمترشحين إلكترونياً.','Implemented secure ticketing system, multi-level RBAC, and audit logs.','Reduced response times for citizen inquiries.','PHP, MySQL, REST APIs, HTML5, Tailwind CSS','public/assets/images/mani_baudha.jpg','https://github.com/Hamza2024-CODE/inchighal',NULL,'2023-01-15','2023-06-20',0),(10,'MFEP Portfolio — المنصة التعريفية الرقمية للقطاع','بوابات رسمية','بوابة رسمية استعراضية للأنظمة والخدمات الرقمية التابعة لوزارة التكوين والتعليم المهنيين.','Designed modern responsive UI, clean API architecture, and content management.','Official institutional showcase platform.','Laravel, PHP, MySQL, Nginx, Linux','public/assets/images/nepal boudhanath stupa.jpg','https://github.com/Hamza2024-CODE/mfep-portfolio',NULL,'2026-02-01','2026-05-30',0),(11,'Smart Home — نظام أتمتة المنازل الذكية وإنترنت الأشياء','IoT وأنظمة مدمجة','نظام مدمج للتحكم في الحساسات والأجهزة الذكية في الوقت الفعلي عبر الويب وتطبيقات الهاتف.','Configured MQTT broker, embedded hardware communication, and responsive dashboard.','Demonstrated advanced IoT hardware-software integration.','C/C++, Java, REST APIs, MQTT, MySQL','public/assets/images/badimalika.jpg','https://github.com/Hamza2024-CODE/smart-home-iot',NULL,'2022-09-01','2023-02-15',0),(12,'Mihen TV — منصة البث الرقمي والتلفزيون المهني','البث الرقمي الوسائط','منصة بث متميزة لمقاطع الفيديو التعليمية والتلفزيون الرقمي باستخدام تقنيات HLS الحديثة.','Built HLS video encoding pipeline and fast web player integration.','Delivers high-definition educational video streaming.','PHP, JavaScript, HTML5 Video, MySQL, REST APIs','public/assets/images/Mountains-Nepal-II.jpg','https://github.com/Hamza2024-CODE/mihen-tv',NULL,'2022-01-10','2022-07-20',0),(13,'Hackathon Solution — الحل البرمجي الفائز بالمركز الأول 2026','حلول الهاكاثون والابتكار','مشروع برمجي مبتكر وفائز بالمركز الأول في الهاكاثون الوطني لعام 2026 لتسريع التحول الرقمي.','Led end-to-end development, API engineering, and live prototype demonstration.','Won 1st Place in the 2026 Algeria National Software Hackathon.','Laravel, Vue.js, Python, MySQL, REST APIs','public/assets/images/marigold.jpg','https://github.com/Hamza2024-CODE/hackathon-2026-winner',NULL,'2026-03-01','2026-03-03',0);
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `key_name` varchar(100) NOT NULL,
  `key_value` text NOT NULL,
  PRIMARY KEY (`key_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('site_email','boubakarseddikh@gmail.com'),('site_github','https://github.com/Hamza2024-CODE'),('site_institution_ar','وزارة التكوين والتعليم المهنيين (MFEP) | الجزائر العاصمة'),('site_institution_en','Ministry of Vocational Training & Education (MFEP) | Algiers, Algeria'),('site_linkedin','https://www.linkedin.com/in/hamza-boubakare-seddike'),('site_name_ar','حمزة بوبكر الصديق'),('site_name_en','Hamza Boubakar Seddik'),('site_phone','+213 779771993'),('site_title_ar','مهندس برمجيات ومطور متعدد المنصات'),('site_title_en','Software Engineer & Multi-Platform Developer');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-30 13:18:30
