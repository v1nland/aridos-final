-- MySQL dump 10.13  Distrib 5.7.28, for Linux (x86_64)
--
-- Host: localhost    Database: simple
-- ------------------------------------------------------
-- Server version	5.7.28-0ubuntu0.18.04.4

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
-- Table structure for table `accion`
--

DROP TABLE IF EXISTS `accion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `extra` text COLLATE utf8_unicode_ci,
  `proceso_id` int(10) unsigned NOT NULL,
  `exponer_variable` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_trigger_proceso1` (`proceso_id`),
  CONSTRAINT `fk_trigger_proceso1` FOREIGN KEY (`proceso_id`) REFERENCES `proceso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accion`
--

LOCK TABLES `accion` WRITE;
/*!40000 ALTER TABLE `accion` DISABLE KEYS */;
INSERT INTO `accion` VALUES (2,'geo check','rest','{\"var_response\":\"geo_check\",\"url\":\"http:\\/\\/200.14.84.23\\/backend\\/api\\/geocheck\\/1?token=tokensuperseguro\",\"uri\":\"http:\\/\\/200.14.84.23\\/backend\\/api\\/geocheck\\/1?token=tokensuperseguro\",\"tipoMetodo\":\"POST\",\"timeout\":null,\"timeout_reintentos\":\"3\",\"paramType\":\"json\",\"request\":\"{\\\"type\\\":\\\"FeatureCollection\\\",\\\"features\\\":[{\\\"type\\\":\\\"Feature\\\",\\\"geometry\\\":{\\\"type\\\":\\\"Polygon\\\",\\\"coordinates\\\":[[[-70.74364686797023,-33.47406420386338],[-70.73374867851497,-33.46685943507731],[-70.73089044953346,-33.47504554540993],[-70.74364686797023,-33.47406420386338]]]},\\\"properties\\\":null}]}\",\"header\":null,\"idSeguridad\":\"-1\"}',3,0);
/*!40000 ALTER TABLE `accion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `acontecimiento`
--

DROP TABLE IF EXISTS `acontecimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `acontecimiento` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `estado` tinyint(4) NOT NULL,
  `evento_externo_id` int(10) unsigned NOT NULL,
  `etapa_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ac_etapa_foreign_key` (`etapa_id`),
  KEY `ac_evento_externo_foreign_key` (`evento_externo_id`),
  CONSTRAINT `ac_etapa_foreign_key` FOREIGN KEY (`etapa_id`) REFERENCES `etapa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ac_evento_externo_foreign_key` FOREIGN KEY (`evento_externo_id`) REFERENCES `evento_externo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `acontecimiento`
--

LOCK TABLES `acontecimiento` WRITE;
/*!40000 ALTER TABLE `acontecimiento` DISABLE KEYS */;
/*!40000 ALTER TABLE `acontecimiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auditoria_operaciones`
--

DROP TABLE IF EXISTS `auditoria_operaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auditoria_operaciones` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` datetime NOT NULL,
  `motivo` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `detalles` text COLLATE utf8_unicode_ci NOT NULL,
  `operacion` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `usuario` varchar(390) COLLATE utf8_unicode_ci NOT NULL,
  `proceso` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `cuenta_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cuenta` (`cuenta_id`),
  CONSTRAINT `fk_cuenta` FOREIGN KEY (`cuenta_id`) REFERENCES `cuenta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auditoria_operaciones`
--

LOCK TABLES `auditoria_operaciones` WRITE;
/*!40000 ALTER TABLE `auditoria_operaciones` DISABLE KEYS */;
INSERT INTO `auditoria_operaciones` VALUES (1,'2019-10-22 18:23:09','uwu','{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos. v2\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"tramite\":{\"id\":\"1\",\"pendiente\":\"1\",\"created_at\":\"2019-10-22 18:19:53\",\"updated_at\":\"2019-10-22 18:20:38\",\"ended_at\":null,\"tramite_proc_cont\":\"1\",\"deleted_at\":null}}','Eliminación de Trámite','  <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(2,'2019-10-22 18:23:12','uwu','{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos. v2\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"tramite\":{\"id\":\"1\",\"pendiente\":\"1\",\"created_at\":\"2019-10-22 18:19:53\",\"updated_at\":\"2019-10-22 18:20:38\",\"ended_at\":null,\"tramite_proc_cont\":\"1\",\"deleted_at\":\"2019-10-22 18:23:09\"}}','Eliminación de Trámite','  <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(3,'2019-10-22 18:34:41','owo','{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos. v2\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"tramite\":{\"id\":\"3\",\"pendiente\":\"1\",\"created_at\":\"2019-10-22 18:30:21\",\"updated_at\":\"2019-10-22 18:32:54\",\"ended_at\":null,\"tramite_proc_cont\":\"1\",\"deleted_at\":null}}','Eliminación de Trámite','  <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(4,'2019-10-22 18:34:46','owo','{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos. v2\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"tramite\":{\"id\":\"3\",\"pendiente\":\"1\",\"created_at\":\"2019-10-22 18:30:21\",\"updated_at\":\"2019-10-22 18:32:54\",\"ended_at\":null,\"tramite_proc_cont\":\"1\",\"deleted_at\":\"2019-10-22 18:34:41\"}}','Eliminación de Trámite','  <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(5,'2019-10-22 18:39:19','jejjeje','{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos. v2\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"tramite\":{\"id\":\"4\",\"pendiente\":\"1\",\"created_at\":\"2019-10-22 18:35:22\",\"updated_at\":\"2019-10-22 18:36:47\",\"ended_at\":null,\"tramite_proc_cont\":\"1\",\"deleted_at\":null}}','Eliminación de Trámite','  <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(6,'2019-10-22 18:39:30','jejjeje','{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos. v2\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"tramite\":{\"id\":\"4\",\"pendiente\":\"1\",\"created_at\":\"2019-10-22 18:35:22\",\"updated_at\":\"2019-10-22 18:36:47\",\"ended_at\":null,\"tramite_proc_cont\":\"1\",\"deleted_at\":\"2019-10-22 18:39:19\"}}','Eliminación de Trámite','  <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(7,'2019-10-22 18:40:58','owo','{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos. v2\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"tramite\":{\"id\":\"6\",\"pendiente\":\"1\",\"created_at\":\"2019-10-22 18:39:46\",\"updated_at\":\"2019-10-22 18:39:53\",\"ended_at\":null,\"tramite_proc_cont\":\"1\",\"deleted_at\":null}}','Eliminación de Trámite','  <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(8,'2019-10-22 18:41:00','owo','{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos. v2\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"tramite\":{\"id\":\"6\",\"pendiente\":\"1\",\"created_at\":\"2019-10-22 18:39:46\",\"updated_at\":\"2019-10-22 18:39:53\",\"ended_at\":null,\"tramite_proc_cont\":\"1\",\"deleted_at\":\"2019-10-22 18:40:58\"}}','Eliminación de Trámite','  <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(9,'2019-10-22 19:37:56','jej','{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos. v2\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"tramite\":{\"id\":\"12\",\"pendiente\":\"1\",\"created_at\":\"2019-10-22 19:35:38\",\"updated_at\":\"2019-10-22 19:36:29\",\"ended_at\":null,\"tramite_proc_cont\":\"1\",\"deleted_at\":null}}','Eliminación de Trámite','  <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(10,'2019-10-22 19:38:00','owo','{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos. v2\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"tramite\":{\"id\":\"13\",\"pendiente\":\"1\",\"created_at\":\"2019-10-22 19:35:45\",\"updated_at\":\"2019-10-22 19:35:53\",\"ended_at\":null,\"tramite_proc_cont\":\"1\",\"deleted_at\":null}}','Eliminación de Trámite','  <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(11,'2019-11-05 13:40:08','F','{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos. v2\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"tramite\":{\"id\":\"18\",\"pendiente\":\"1\",\"created_at\":\"2019-11-05 13:25:23\",\"updated_at\":\"2019-11-05 13:27:18\",\"ended_at\":null,\"tramite_proc_cont\":\"1\",\"deleted_at\":null}}','Eliminación de Trámite','Administrador Simple <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(12,'2019-11-05 18:35:57','jeje','{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos. v2\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"tramite\":{\"id\":\"16\",\"pendiente\":\"0\",\"proceso_id\":\"1\",\"created_at\":\"2019-10-22 20:08:27\",\"updated_at\":\"2019-11-05 13:28:50\",\"ended_at\":\"2019-11-05 13:28:50\",\"tramite_proc_cont\":\"1\",\"deleted_at\":null},\"etapa\":{\"id\":\"60\",\"pendiente\":\"0\",\"vencimiento_at\":null,\"created_at\":\"2019-11-05 13:28:26\",\"updated_at\":\"2019-11-05 13:28:50\",\"ended_at\":\"2019-11-05 13:28:50\",\"extra\":\"{\\\"mostrar_hit\\\":false}\"},\"tarea\":{\"id\":\"3\",\"identificador\":\"box_3\",\"inicial\":\"0\",\"es_final\":\"1\",\"proceso_id\":\"1\",\"nombre\":\"Informe a municipio sobre la no factibilidad t\\u00e9cnica de la solicitud\",\"posx\":\"860\",\"posy\":\"256\",\"asignacion\":\"ciclica\",\"asignacion_usuario\":null,\"asignacion_notificar\":\"0\",\"almacenar_usuario\":\"0\",\"almacenar_usuario_variable\":null,\"acceso_modo\":\"grupos_usuarios\",\"grupos_usuarios\":\"2\",\"activacion\":\"si\",\"activacion_inicio\":null,\"activacion_fin\":null,\"vencimiento\":\"0\",\"vencimiento_valor\":\"5\",\"vencimiento_unidad\":\"D\",\"vencimiento_habiles\":\"0\",\"vencimiento_notificar\":\"0\",\"vencimiento_notificar_dias\":\"1\",\"vencimiento_notificar_email\":null,\"paso_confirmacion\":\"0\",\"previsualizacion\":null,\"externa\":\"0\",\"exponer_tramite\":null,\"paso_confirmacion_titulo\":\"Solicitud rechazada\",\"paso_confirmacion_contenido\":\"Se informa que no existe factibilidad t\\u00e9cnica para su solicitud.\",\"paso_confirmacion_texto_boton_final\":\"Terminar\"},\"usuario\":{\"id\":\"3\",\"usuario\":\"Municipio\",\"rut\":null,\"nombres\":\"Municipio\",\"apellido_paterno\":\"Municipio\",\"apellido_materno\":\"Municipio\",\"email\":\"Municipio@Municipio.Municipio\",\"vacaciones\":\"0\",\"cuenta_id\":\"1\",\"salt\":\"\",\"open_id\":\"0\",\"registrado\":\"1\",\"reset_token\":null,\"created_at\":\"2019-10-22 18:08:49\",\"updated_at\":\"2019-10-22 18:08:49\"},\"datos_seguimiento\":[]}','Retroceso a Etapa','Administrador Simple <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(13,'2019-11-05 18:36:04','mal asignado','{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos. v2\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"tramite\":{\"id\":\"16\",\"pendiente\":\"1\",\"proceso_id\":\"1\",\"created_at\":\"2019-10-22 20:08:27\",\"updated_at\":\"2019-11-05 18:35:57\",\"ended_at\":null,\"tramite_proc_cont\":\"1\",\"deleted_at\":null},\"etapa\":{\"id\":\"53\",\"pendiente\":\"0\",\"vencimiento_at\":null,\"created_at\":\"2019-10-22 20:11:40\",\"updated_at\":\"2019-11-05 13:28:50\",\"ended_at\":\"2019-11-05 13:28:26\",\"extra\":\"{\\\"mostrar_hit\\\":false}\"},\"tarea\":{\"id\":\"2\",\"identificador\":\"box_2\",\"inicial\":\"0\",\"es_final\":\"0\",\"proceso_id\":\"1\",\"nombre\":\"An\\u00e1lisis factibilidad t\\u00e9cnica DOH\",\"posx\":\"620\",\"posy\":\"118\",\"asignacion\":\"autoservicio\",\"asignacion_usuario\":null,\"asignacion_notificar\":\"0\",\"almacenar_usuario\":\"0\",\"almacenar_usuario_variable\":null,\"acceso_modo\":\"grupos_usuarios\",\"grupos_usuarios\":\"1\",\"activacion\":\"si\",\"activacion_inicio\":null,\"activacion_fin\":null,\"vencimiento\":\"0\",\"vencimiento_valor\":\"5\",\"vencimiento_unidad\":\"D\",\"vencimiento_habiles\":\"0\",\"vencimiento_notificar\":\"0\",\"vencimiento_notificar_dias\":\"1\",\"vencimiento_notificar_email\":null,\"paso_confirmacion\":\"0\",\"previsualizacion\":null,\"externa\":\"0\",\"exponer_tramite\":null,\"paso_confirmacion_titulo\":null,\"paso_confirmacion_contenido\":null,\"paso_confirmacion_texto_boton_final\":null},\"usuario\":{\"id\":\"2\",\"usuario\":\"DOH\",\"rut\":null,\"nombres\":\"DOH\",\"apellido_paterno\":\"DOH\",\"apellido_materno\":\"DOH\",\"email\":\"doh@doh.doh\",\"vacaciones\":\"0\",\"cuenta_id\":\"1\",\"open_id\":\"0\",\"registrado\":\"1\",\"reset_token\":null,\"created_at\":\"2019-10-22 18:08:21\",\"updated_at\":\"2019-10-22 18:08:21\"},\"datos_seguimiento\":[{\"id\":\"73\",\"nombre\":\"factible\",\"valor\":\"\\\"0\\\"\",\"etapa_id\":\"53\"},{\"id\":\"74\",\"nombre\":\"comentarios_factibilidad\",\"valor\":\"\\\"Sorry m3n\\\"\",\"etapa_id\":\"53\"}]}','Retroceso a Etapa','Administrador Simple <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(14,'2019-11-15 14:12:45','si','{\"proceso\":{\"id\":\"2\",\"nombre\":\"Proceso\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":null,\"destacado\":null,\"icon_ref\":null,\"activo\":\"1\",\"version\":null,\"root\":null,\"estado\":\"public\",\"descripcion\":null,\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-11-06 23:28:56\",\"updated_at\":null,\"concurrente\":null,\"eliminar_tramites\":null,\"ocultar_front\":null}}','Eliminación de Proceso','Administrador Simple <admin@admin.com>','Proceso',1),(15,'2019-11-20 20:30:09',NULL,'{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos.\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"tarea\":{\"id\":\"10\",\"identificador\":\"box_10\",\"inicial\":\"0\",\"es_final\":\"0\",\"nombre\":\"Tarea\",\"asignacion\":\"ciclica\",\"asignacion_usuario\":null,\"asignacion_notificar\":\"0\",\"almacenar_usuario\":\"0\",\"almacenar_usuario_variable\":null,\"acceso_modo\":\"grupos_usuarios\",\"grupos_usuarios\":null,\"activacion\":\"si\",\"activacion_inicio\":null,\"activacion_fin\":null,\"vencimiento\":\"0\",\"vencimiento_valor\":\"5\",\"vencimiento_unidad\":\"D\",\"vencimiento_habiles\":\"0\",\"vencimiento_notificar\":\"0\",\"vencimiento_notificar_dias\":\"1\",\"vencimiento_notificar_email\":null,\"paso_confirmacion\":\"1\",\"previsualizacion\":null,\"externa\":\"0\",\"exponer_tramite\":null,\"paso_confirmacion_titulo\":null,\"paso_confirmacion_contenido\":null,\"paso_confirmacion_texto_boton_final\":null}}','Eliminación de Tarea','Administrador Simple <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(16,'2019-12-05 18:06:07',NULL,'{\"proceso\":{\"id\":\"3\",\"nombre\":\"Proceso\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":null,\"destacado\":null,\"icon_ref\":null,\"activo\":\"1\",\"version\":null,\"root\":null,\"estado\":\"public\",\"descripcion\":null,\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-12-05 15:05:19\",\"updated_at\":null,\"concurrente\":null,\"eliminar_tramites\":null,\"ocultar_front\":null},\"formulario\":{\"id\":\"10\",\"nombre\":\"Formulario\",\"descripcion\":null}}','Eliminación de Formulario','Administrador Simple <admin@admin.com>','Proceso',1),(17,'2019-12-05 18:27:34',NULL,'{\"proceso\":{\"id\":\"3\",\"nombre\":\"Proceso\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":null,\"destacado\":null,\"icon_ref\":null,\"activo\":\"1\",\"version\":null,\"root\":null,\"estado\":\"public\",\"descripcion\":null,\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-12-05 15:05:19\",\"updated_at\":null,\"concurrente\":null,\"eliminar_tramites\":null,\"ocultar_front\":null},\"tarea\":{\"id\":\"13\",\"identificador\":\"box_3\",\"inicial\":\"0\",\"es_final\":\"0\",\"nombre\":\"Tarea\",\"asignacion\":\"ciclica\",\"asignacion_usuario\":null,\"asignacion_notificar\":\"0\",\"almacenar_usuario\":\"0\",\"almacenar_usuario_variable\":null,\"acceso_modo\":\"grupos_usuarios\",\"grupos_usuarios\":null,\"activacion\":\"si\",\"activacion_inicio\":null,\"activacion_fin\":null,\"vencimiento\":\"0\",\"vencimiento_valor\":\"5\",\"vencimiento_unidad\":\"D\",\"vencimiento_habiles\":\"0\",\"vencimiento_notificar\":\"0\",\"vencimiento_notificar_dias\":\"1\",\"vencimiento_notificar_email\":null,\"paso_confirmacion\":\"1\",\"previsualizacion\":null,\"externa\":\"0\",\"exponer_tramite\":null,\"paso_confirmacion_titulo\":null,\"paso_confirmacion_contenido\":null,\"paso_confirmacion_texto_boton_final\":null}}','Eliminación de Tarea','Administrador Simple <admin@admin.com>','Proceso',1),(18,'2019-12-07 18:34:07','Proceso de pruebas','{\"proceso\":{\"id\":\"3\",\"nombre\":\"Proceso\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":null,\"destacado\":null,\"icon_ref\":null,\"activo\":\"1\",\"version\":null,\"root\":null,\"estado\":\"public\",\"descripcion\":null,\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-12-05 15:05:19\",\"updated_at\":null,\"concurrente\":null,\"eliminar_tramites\":null,\"ocultar_front\":null}}','Eliminación de Proceso','Administrador Simple <admin@admin.com>','Proceso',1),(19,'2019-12-07 18:35:43',NULL,'{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos.\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"formulario\":{\"id\":\"4\",\"nombre\":\"Formulario Test\",\"descripcion\":null},\"campos\":[{\"id\":\"24\",\"nombre\":\"testbox\",\"posicion\":\"0\",\"tipo\":\"text\",\"formulario_id\":\"4\",\"etiqueta\":\"Testbox\",\"validacion\":[\"required\"],\"ayuda\":\"\",\"dependiente_tipo\":\"string\",\"dependiente_campo\":null,\"dependiente_valor\":null,\"dependiente_relacion\":\"==\",\"datos\":null,\"readonly\":\"0\",\"valor_default\":\"\",\"extra\":null,\"agenda_campo\":null,\"exponer_campo\":\"0\",\"condiciones_extra_visible\":null}]}','Eliminación de Formulario','Administrador Simple <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(20,'2019-12-07 20:12:52',NULL,'{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos.\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"documento\":{\"id\":\"1\",\"tipo\":\"certificado\",\"nombre\":\"Test\",\"titulo\":\"Certificado de aridos\",\"subtitulo\":\"Certificado Gratuito\",\"contenido\":\"<p align=\\\"center\\\">Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos<\\/p>\\r\\n<br\\/>\\r\\nQuer\\u00eddo @@nombre @@apellido, a continuaci\\u00f3n presentara los siguientes datos como solicitud:\\r\\n\\r\\nNombre de la persona natural o jur\\u00eddica: @@nombre_persona \\r\\nRUT\\/RUN: @@rutrun\\r\\nDomicilio: @@domicilio\\r\\nLocalidad: @@localidad<br\\/>\\r\\nRegi\\u00f3n\\/Comuna: @@regioncomuna\\r\\nCorreo: @@correo\\r\\nFecha solicitud: @@fecha_solicitud\\r\\nTipo de arido: @@tipo\\r\\nCategor\\u00eda: @@categoria\\r\\n\\r\\nEtapa Factibilidad\\r\\n\\r\\nCauce: @@cauce\\r\\nSector: @@sector\\r\\nComunas: @@comunasfact\\r\\nTipo Arido: @@tipo_arido\\r\\nPorcentaje: @@porcentaje\\r\\nDestino Arido: @@destino_arido\",\"servicio\":\"Ministerio de mi casita\",\"servicio_url\":\"http:\\/\\/www.google.cl\",\"validez\":\"90\",\"validez_habiles\":\"1\",\"firmador_nombre\":\"Juan Perez\",\"firmador_cargo\":\"Jefe de Servicio\",\"firmador_servicio\":\"Miniterio de la casa del Martin\",\"firmador_imagen\":\"\",\"timbre\":\"\",\"logo\":\"\",\"hsm_configuracion_id\":null,\"tamano\":\"letter\"}}','Eliminación de Documento','Administrador Simple <admin@admin.com>','Solicitud de permiso para extracción de áridos',1),(21,'2019-12-07 20:12:58',NULL,'{\"proceso\":{\"id\":\"1\",\"nombre\":\"Solicitud de permiso para extracci\\u00f3n de \\u00e1ridos\",\"width\":\"100%\",\"height\":\"800px\",\"cuenta_id\":\"1\",\"proc_cont\":null,\"categoria_id\":\"0\",\"destacado\":\"0\",\"icon_ref\":\"atomic.png\",\"activo\":\"1\",\"version\":\"1\",\"root\":null,\"estado\":\"public\",\"descripcion\":\"Formulario de ingreso para solicitar extracci\\u00f3n de aridos.\",\"url_informativa\":null,\"usuario_id\":\"1\",\"created_at\":\"2019-10-22 15:12:38\",\"updated_at\":null,\"concurrente\":\"0\",\"eliminar_tramites\":\"0\",\"ocultar_front\":\"0\"},\"accion\":{\"id\":\"1\",\"nombre\":\"Generar documento test\",\"tipo\":\"generar_documento\",\"extra\":{\"variable\":null,\"documento_id\":\"1\"},\"exponer_variable\":\"0\"}}','Eliminación de Acción','Administrador Simple <admin@admin.com>','Solicitud de permiso para extracción de áridos',1);
/*!40000 ALTER TABLE `auditoria_operaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacora`
--

DROP TABLE IF EXISTS `bitacora`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacora` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL,
  `tramite_id` int(11) NOT NULL,
  `fecha` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=186 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora`
--

LOCK TABLES `bitacora` WRITE;
/*!40000 ALTER TABLE `bitacora` DISABLE KEYS */;
INSERT INTO `bitacora` VALUES (183,'Se visita el lugar del proyecto y se evidencian trabajos aún en ejecución.',48,'2019-12-09 03:17:52'),(184,'Los trabajos siguen en ejecución desde la última visita.',48,'2019-12-09 03:18:00'),(185,'Bitácora de prueba.',62,'2019-12-10 16:59:19');
/*!40000 ALTER TABLE `bitacora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campo`
--

DROP TABLE IF EXISTS `campo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `readonly` tinyint(4) NOT NULL DEFAULT '0',
  `valor_default` text COLLATE utf8_unicode_ci NOT NULL,
  `posicion` int(10) unsigned NOT NULL,
  `tipo` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `formulario_id` int(10) unsigned NOT NULL,
  `etiqueta` text COLLATE utf8_unicode_ci NOT NULL,
  `ayuda` text COLLATE utf8_unicode_ci NOT NULL,
  `validacion` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `dependiente_tipo` enum('string','regex','numeric') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'string',
  `dependiente_campo` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `dependiente_valor` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datos` mediumtext COLLATE utf8_unicode_ci,
  `documento_id` int(10) unsigned DEFAULT NULL,
  `extra` text COLLATE utf8_unicode_ci,
  `dependiente_relacion` enum('==','!=','>','<','>=','<=') COLLATE utf8_unicode_ci NOT NULL DEFAULT '==',
  `condiciones_extra_visible` text COLLATE utf8_unicode_ci,
  `agenda_campo` bigint(20) DEFAULT NULL,
  `exponer_campo` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_campo_formulario1` (`formulario_id`),
  KEY `fk_campo_documento1` (`documento_id`),
  CONSTRAINT `campo_ibfk_1` FOREIGN KEY (`formulario_id`) REFERENCES `formulario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `campo_ibfk_2` FOREIGN KEY (`documento_id`) REFERENCES `documento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campo`
--

LOCK TABLES `campo` WRITE;
/*!40000 ALTER TABLE `campo` DISABLE KEYS */;
INSERT INTO `campo` VALUES (1,'factible',0,'',0,'select',1,'Marque si es factible o no:','','required','string',NULL,NULL,'[{\"etiqueta\":\"Si, es factible\",\"valor\":\"1\"},{\"etiqueta\":\"Si, es factible pero requiere algunos cambios\",\"valor\":\"2\"},{\"etiqueta\":\"No, no es factible\",\"valor\":\"0\"}]',NULL,'{\"ws\":null}','==',NULL,NULL,1),(2,'comentarios_factibilidad',0,'',1,'textarea',1,'Ingrese comentarios respecto a la factibilidad:','','','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(3,'proyecto',0,'',0,'select',2,'¿Requiere la solicitud un proyecto?','','required','string',NULL,NULL,'{\"0\":{\"etiqueta\":\"Si, requiere un proyecto\",\"valor\":\"1\"},\"2\":{\"etiqueta\":\"No, no requiere un proyecto\",\"valor\":\"0\"}}',NULL,'{\"ws\":null}','==',NULL,NULL,1),(4,'comentarios_proyectos',0,'',1,'textarea',2,'Comentarios respecto al proyecto:','','required','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(6,'nombre_persona',0,'',0,'text',3,'Nombre de la persona natural o juridica','e.g: Juan Perez','required','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(7,'rutrun',0,'',1,'text',3,'RUT/RUN','e.g: 12.345.678-9','required|rut','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(8,'domicilio',0,'',2,'text',3,'Domicilio','e.g: Calle A 123','required','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(9,'localidad',0,'',3,'text',3,'Localidad','Pirque','required','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(10,'regioncomuna',0,'',4,'comunas',3,'Region/Comuna','','required','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(11,'correo',0,'',5,'text',3,'Correo','e.g: aridos@arido.cl','required|email','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(12,'fecha_solicitud',0,'',6,'date',3,'Fecha Solicitud','','required','string',NULL,NULL,NULL,NULL,'{\"config_date\":{\"start\":{\"option\":\"no_restrictcion\",\"date\":null,\"number_days\":\"1\"},\"end\":{\"option\":\"no_restrictcion\",\"date\":null,\"number_days\":\"1\"}}}','==',NULL,NULL,1),(14,'categoria',0,'',7,'select',3,'Categoría','','required','string',NULL,NULL,'[{\"etiqueta\":\"Comunal\",\"valor\":\"comunal\"},{\"etiqueta\":\"Intercomunal\",\"valor\":\"intercomunal\"},{\"etiqueta\":\"Interregional\",\"valor\":\"interregional\"}]',NULL,'{\"ws\":null}','==',NULL,NULL,1),(15,'5da0cba3bdc70',1,'',8,'subtitle',3,'<hr>','','','string',NULL,NULL,NULL,NULL,'{\"nombre\":null}','==',NULL,NULL,0),(16,'5da0cb8e10170',1,'',9,'subtitle',3,'Etapa Factibilidad','','','string',NULL,NULL,NULL,NULL,'{\"nombre\":null}','==',NULL,NULL,0),(17,'cauce',0,'',10,'text',3,'Cauce','e.g: Clarillo','required','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(18,'sector',0,'',11,'text',3,'Sector','e.g: Pirque','required','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(19,'comunasfact',0,'',12,'comunas',3,'Comunas','','required','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(21,'destino_arido',0,'',18,'text',3,'Destino Árido','e.g: Construcción de edificio','required','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(22,'5da0ccabc5f31',1,'',25,'subtitle',3,'<hr>','','','string',NULL,NULL,NULL,NULL,'{\"nombre\":null}','==',NULL,NULL,0),(23,'5da0ccb2a0042',1,'',26,'subtitle',3,'Documentación','','','string',NULL,NULL,NULL,NULL,'{\"nombre\":null}','==',NULL,NULL,0),(25,'5daf4d869d1b4',1,'',1,'subtitle',5,'Se necesita agregar proyecto solicitado:','','','string',NULL,NULL,NULL,NULL,'{\"nombre\":null}','==',NULL,NULL,0),(27,'solicitud_proyecto',0,'',0,'select',6,'¿Desea aprobar la solicitud presentada?','','required','string',NULL,NULL,'[{\"etiqueta\":\"Si, deseo aprobar la solicitud\",\"valor\":\"1\"},{\"etiqueta\":\"Si, deseo aprobar la solicitud pero con algunas modificaciones\",\"valor\":\"2\"},{\"etiqueta\":\"No, deseo rechazar la solicitud\",\"valor\":\"0\"}]',NULL,'{\"ws\":null}','==',NULL,NULL,1),(28,'comentarios_proyecto',0,'',1,'textarea',6,'Comentarios y/o correcciones:','','','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(31,'5daf4ff622a9f',1,'',1,'subtitle',7,'Su solicitud fue aprobada con éxito.','','','string','solicitud_proyecto','1',NULL,NULL,'{\"nombre\":null}','==',NULL,NULL,0),(32,'5daf501a4f30b',1,'',2,'subtitle',7,'Su solicitud fue rechazada.','','','string','solicitud_proyecto','0',NULL,NULL,'{\"nombre\":null}','==',NULL,NULL,0),(33,'5daf58fb3435a',1,'',1,'subtitle',8,'Lo siento,  su solicitud no presenta la factibilidad técnica necesaria.','','','string',NULL,NULL,NULL,NULL,'{\"nombre\":null}','==',NULL,NULL,0),(36,'carga_documentos',0,'',2,'file',5,'Carga Documentos:','','','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(45,'inicio_extraccion',0,'',19,'date',3,'Inicio Extracción','','required','string',NULL,NULL,NULL,NULL,'{\"config_date\":{\"start\":{\"option\":\"no_restrictcion\",\"date\":null,\"number_days\":\"1\"},\"end\":{\"option\":\"no_restrictcion\",\"date\":null,\"number_days\":\"1\"}}}','==',NULL,NULL,1),(46,'fin_extraccion',0,'',20,'date',3,'Fin Extracción','','required','string',NULL,NULL,NULL,NULL,'{\"config_date\":{\"start\":{\"option\":\"no_restrictcion\",\"date\":null,\"number_days\":\"1\"},\"end\":{\"option\":\"no_restrictcion\",\"date\":null,\"number_days\":\"1\"}}}','==',NULL,NULL,1),(47,'volu',0,'0',21,'text',3,'Volúmen (m3)','','required|numeric|min:1','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(48,'longitud',0,'0',22,'text',3,'Longitud (m)','','required|numeric|min:1','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(49,'ancho',0,'0',23,'text',3,'Ancho (m)','','required|numeric|min:1','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(50,'espesor',0,'0',24,'text',3,'Espesor (m)','','required|numeric|min:1','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(55,'porcentaje_arena',0,'0',13,'text',3,'Porcentaje Arena (%)','0','required|min:0','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(56,'porcentaje_bolones',0,'0',14,'text',3,'Porcentaje Bolones (%)','%','required|min:0','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(57,'porcentaje_bolones',0,'0',15,'text',3,'Porcentaje Ripio (%)','%','required|min:0','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(58,'porcentaje_material_integral',0,'0',16,'text',3,'Porcentaje Material (%)','%','required|min:0','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(68,'geom',0,'',27,'maps_ol',3,'geom','','required','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(69,'test',0,'',1,'text',11,'test','test','required','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(70,'test2',0,'',2,'textarea',11,'test2','test2','required','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,1),(71,'test_recepcion',0,'',1,'text',12,'@@test','','required','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,0),(74,'documento_formulario',1,'',1,'documento',9,'Documento','','','string',NULL,NULL,NULL,2,'{\"regenerar\":\"0\",\"previsualizacion\":\"on\"}','==',NULL,NULL,1),(75,'5defeeda4e297',1,'',17,'javascript',3,'var value1 = parseInt(document.getElementById(\"55\").value);\r\nvar value2 = parseInt(document.getElementById(\"56\").value);\r\nvar value3 = parseInt(document.getElementById(\"57\").value);\r\nvar value4 = parseInt(document.getElementById(\"58\").value);\r\nvar sum = value1 + value2 + value3 + value4;\r\nif (sum != 100){\r\n    console.log(\"La suma de porcentajes debe ser igual a 100\");\r\n}\r\nconsole.log(sum);','','','string',NULL,NULL,NULL,NULL,NULL,'==',NULL,NULL,0);
/*!40000 ALTER TABLE `campo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `icon_ref` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cola_continuar_tramite`
--

DROP TABLE IF EXISTS `cola_continuar_tramite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cola_continuar_tramite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tramite_id` int(11) DEFAULT NULL,
  `tarea_id` int(11) DEFAULT NULL,
  `request` text COLLATE utf8_unicode_ci,
  `procesado` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cola_continuar_tramite`
--

LOCK TABLES `cola_continuar_tramite` WRITE;
/*!40000 ALTER TABLE `cola_continuar_tramite` DISABLE KEYS */;
/*!40000 ALTER TABLE `cola_continuar_tramite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conexion`
--

DROP TABLE IF EXISTS `conexion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conexion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tarea_id_origen` int(10) unsigned NOT NULL,
  `tarea_id_destino` int(10) unsigned DEFAULT NULL,
  `tipo` enum('secuencial','evaluacion','paralelo','paralelo_evaluacion','union') COLLATE utf8_unicode_ci NOT NULL,
  `regla` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tarea_origen_destino` (`tarea_id_origen`,`tarea_id_destino`),
  KEY `fk_ruta_tarea` (`tarea_id_origen`),
  KEY `fk_ruta_tarea1` (`tarea_id_destino`),
  CONSTRAINT `conexion_ibfk_1` FOREIGN KEY (`tarea_id_origen`) REFERENCES `tarea` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `conexion_ibfk_2` FOREIGN KEY (`tarea_id_destino`) REFERENCES `tarea` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conexion`
--

LOCK TABLES `conexion` WRITE;
/*!40000 ALTER TABLE `conexion` DISABLE KEYS */;
INSERT INTO `conexion` VALUES (1,3,NULL,'secuencial',NULL),(3,5,NULL,'secuencial',NULL),(5,8,NULL,'secuencial',NULL),(7,6,7,'secuencial',NULL),(11,4,5,'evaluacion','@@proyecto>=1'),(12,4,6,'evaluacion','@@proyecto==0'),(18,1,9,'secuencial',NULL),(19,9,2,'secuencial',NULL),(24,11,12,'secuencial',NULL),(25,12,NULL,'secuencial',NULL),(26,14,NULL,'secuencial',NULL),(28,7,8,'evaluacion',NULL),(29,7,14,'evaluacion',NULL),(30,2,3,'evaluacion','@@factible==0'),(31,2,4,'evaluacion','@@factible>=1');
/*!40000 ALTER TABLE `conexion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config`
--

DROP TABLE IF EXISTS `config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `idpar` int(10) unsigned NOT NULL,
  `endpoint` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nombre_visible` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cuenta_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config`
--

LOCK TABLES `config` WRITE;
/*!40000 ALTER TABLE `config` DISABLE KEYS */;
INSERT INTO `config` VALUES (1,2,'Connectors','Bezier','Curvo',0),(2,2,'Connectors','Straight','Recto',0),(3,2,'Connectors','Flowchart','Diagrama de flujo',0),(4,2,'Connectors','StateMachine','Curvo Ligero',0);
/*!40000 ALTER TABLE `config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `config_general`
--

DROP TABLE IF EXISTS `config_general`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `config_general` (
  `componente` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `cuenta` int(11) NOT NULL,
  `llave` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `valor` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`componente`,`cuenta`,`llave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `config_general`
--

LOCK TABLES `config_general` WRITE;
/*!40000 ALTER TABLE `config_general` DISABLE KEYS */;
/*!40000 ALTER TABLE `config_general` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuenta`
--

DROP TABLE IF EXISTS `cuenta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cuenta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `nombre_largo` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `metadata` text COLLATE utf8_unicode_ci,
  `mensaje` text COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `logof` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `favicon` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `api_token` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descarga_masiva` tinyint(4) NOT NULL DEFAULT '1',
  `client_id` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_secret` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ambiente` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'prod',
  `vinculo_produccion` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `entidad` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `estilo` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'app.css',
  `header` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'layouts.header',
  `footer` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'layouts.footer',
  `personalizacion` text COLLATE utf8_unicode_ci,
  `personalizacion_estado` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `seo_tags` varchar(4096) COLLATE utf8_unicode_ci DEFAULT NULL,
  `analytics` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`),
  KEY `vinculo_produccion_idx` (`vinculo_produccion`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuenta`
--

LOCK TABLES `cuenta` WRITE;
/*!40000 ALTER TABLE `cuenta` DISABLE KEYS */;
INSERT INTO `cuenta` VALUES (1,'aridos','Plataforma de Extracción de Áridos','{\"contacto_email\":null,\"contacto_link\":null}','Sitio en desarrollo',NULL,NULL,NULL,'EyMRb8Uhx',1,'25a95032fc7b4a06b11a93f932f00c85','ec9918a5decc453eb9a9d43c8fb34f95','prod',NULL,'2019-10-22 21:01:10','2019-12-09 19:51:33',NULL,'app.css','layouts.header','layouts.footer',NULL,'0',NULL,NULL);
/*!40000 ALTER TABLE `cuenta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuenta_has_config`
--

DROP TABLE IF EXISTS `cuenta_has_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cuenta_has_config` (
  `idpar` int(10) unsigned NOT NULL,
  `config_id` int(10) unsigned NOT NULL,
  `cuenta_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuenta_has_config`
--

LOCK TABLES `cuenta_has_config` WRITE;
/*!40000 ALTER TABLE `cuenta_has_config` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuenta_has_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dato_seguimiento`
--

DROP TABLE IF EXISTS `dato_seguimiento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dato_seguimiento` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `valor` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `etapa_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_etapa` (`nombre`,`etapa_id`),
  KEY `fk_dato_seguimiento_etapa1` (`etapa_id`),
  CONSTRAINT `fk_dato_seguimiento_etapa1` FOREIGN KEY (`etapa_id`) REFERENCES `etapa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=845 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dato_seguimiento`
--

LOCK TABLES `dato_seguimiento` WRITE;
/*!40000 ALTER TABLE `dato_seguimiento` DISABLE KEYS */;
INSERT INTO `dato_seguimiento` VALUES (649,'nombre_persona','\"Nicolas Hidalgo\"',170),(650,'rutrun','\"15.589.871-2\"',170),(651,'domicilio','\"EL EMBALSE\"',170),(652,'localidad','\"PE\\u00d1ALOLEN\"',170),(653,'regioncomuna','{\"region\":\"Metropolitana de Santiago\",\"comuna\":\"Lo Barnechea\",\"cstateCode\":\"13\",\"cstateName\":\"Metropolitana de Santiago\",\"ccityCode\":\"13115\",\"ccityName\":\"Lo Barnechea\"}',170),(654,'correo','\"aridos@aridos.cl\"',170),(655,'fecha_solicitud','\"05-12-2019\"',170),(656,'categoria','\"comunal\"',170),(657,'cauce','\"Clarillo\"',170),(658,'sector','\"PE\\u00d1ALOLEN\"',170),(659,'comunasfact','{\"region\":\"Metropolitana de Santiago\",\"comuna\":\"Conchalí\",\"cstateCode\":\"13\",\"cstateName\":\"Metropolitana de Santiago\",\"ccityCode\":\"13104\",\"ccityName\":\"Conchalí\"}',170),(660,'porcentaje','\"100\"',170),(661,'porcentaje_bolones','\"0\"',170),(662,'porcentaje_material_integral','\"0\"',170),(663,'destino_arido','\"Construccion\"',170),(664,'inicio_extraccion','\"21-12-2019\"',170),(665,'fin_extraccion','\"24-12-2019\"',170),(666,'volu','\"1000\"',170),(667,'longitud','\"0\"',170),(668,'ancho','\"0\"',170),(669,'espesor','\"0\"',170),(670,'geom','{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[-70.42756154553793,-33.366865969291695],[-70.40581550966728,-33.40851930250579],[-70.35937896420594,-33.39507042462285],[-70.36219919852853,-33.35837102600652],[-70.41845479770332,-33.33802960052784],[-70.48067388795671,-33.37857974428713],[-70.44209755504203,-33.382705301159476],[-70.42756154553793,-33.366865969291695]]]},\"properties\":null}]}',170),(671,'documento_formulario','\"5deeaf59cf24c.pdf\"',170),(672,'factible','\"1\"',172),(673,'comentarios_factibilidad','null',172),(674,'proyecto','\"0\"',173),(675,'comentarios_proyectos','\"sigue no mas\"',173),(676,'carga_documentos','null',174),(677,'solicitud_proyecto','\"1\"',175),(678,'comentarios_proyecto','\"gracias\"',175),(679,'nombre_persona','\"nicolas hidalgo\"',177),(680,'rutrun','\"15.589.871-2\"',177),(681,'domicilio','\"EMBALSE\"',177),(682,'localidad','\"Pirque\"',177),(683,'regioncomuna','{\"region\":\"Del Libertador Gral. Bernardo O’Higgins\",\"comuna\":\"Codegua\",\"cstateCode\":\"06\",\"cstateName\":\"Del Libertador Gral. Bernardo O’Higgins\",\"ccityCode\":\"06102\",\"ccityName\":\"Codegua\"}',177),(684,'correo','\"aridos@aridos.cl\"',177),(685,'fecha_solicitud','\"09-12-2019\"',177),(686,'categoria','\"comunal\"',177),(687,'cauce','\"Clarillo\"',177),(688,'sector','\"Pirque\"',177),(689,'comunasfact','{\"region\":\"Metropolitana de Santiago\",\"comuna\":\"Colina\",\"cstateCode\":\"13\",\"cstateName\":\"Metropolitana de Santiago\",\"ccityCode\":\"13301\",\"ccityName\":\"Colina\"}',177),(690,'porcentaje','\"1000\"',177),(691,'porcentaje_bolones','\"0\"',177),(692,'porcentaje_material_integral','\"0\"',177),(693,'destino_arido','\"Construccion\"',177),(694,'inicio_extraccion','\"03-12-2019\"',177),(695,'fin_extraccion','\"05-12-2019\"',177),(696,'volu','\"1000\"',177),(697,'longitud','\"0\"',177),(698,'ancho','\"0\"',177),(699,'espesor','\"0\"',177),(700,'geom','{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[-70.68982304115296,-33.20553395823291],[-70.60306967277526,-33.23997169803893],[-70.60017288703918,-33.18063736556463],[-70.68391145248412,-33.175923005482105],[-70.68982304115296,-33.20553395823291]]]},\"properties\":null}]}',177),(701,'documento_formulario','\"5def04d61f398.pdf\"',177),(702,'factible','\"2\"',179),(703,'comentarios_factibilidad','\"Se debe remover un volumen menor a 1000 m3 y el pol\\u00edgono debe ser cambiado.\"',179),(704,'nombre_persona','\"nicolas hidalgo\"',180),(705,'rutrun','\"15.589.871-2\"',180),(706,'domicilio','\"EMBALSE\"',180),(707,'localidad','\"Pirque\"',180),(708,'regioncomuna','{\"region\":\"Del Maule\",\"comuna\":\"Curicó\",\"cstateCode\":\"07\",\"cstateName\":\"Del Maule\",\"ccityCode\":\"07301\",\"ccityName\":\"Curicó\"}',180),(709,'correo','\"aridos@aridos.cl\"',180),(710,'fecha_solicitud','\"10-12-2019\"',180),(711,'categoria','\"comunal\"',180),(712,'cauce','\"Clarillo\"',180),(713,'sector','\"Pirque\"',180),(714,'comunasfact','{\"region\":\"De la Araucanía\",\"comuna\":\"Cunco\",\"cstateCode\":\"09\",\"cstateName\":\"De la Araucanía\",\"ccityCode\":\"09103\",\"ccityName\":\"Cunco\"}',180),(715,'porcentaje','\"110\"',180),(716,'porcentaje_bolones','\"0\"',180),(717,'porcentaje_material_integral','\"0\"',180),(718,'destino_arido','\"Construccion\"',180),(719,'inicio_extraccion','\"09-12-2019\"',180),(720,'fin_extraccion','\"02-12-2019\"',180),(721,'volu','\"1000\"',180),(722,'longitud','\"0\"',180),(723,'ancho','\"0\"',180),(724,'espesor','\"0\"',180),(725,'geom','{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[-72.05114769453249,-39.23073369704228],[-71.87925601398202,-39.372020536631396],[-71.63667799363564,-39.24165831556116],[-71.64187010766345,-39.106612162613196],[-71.88166809845163,-39.1052797435033],[-72.05114769453249,-39.23073369704228]]]},\"properties\":null}]}',180),(726,'documento_formulario','\"5def8e6682bd2.pdf\"',180),(727,'factible','\"0\"',182),(728,'comentarios_factibilidad','\"Se requiere modificaci\\u00f3n del pol\\u00edgono para su aceptaci\\u00f3n. Enviar proyecto con estas modificaciones.\"',182),(729,'nombre_persona','\"nicolas hidalgo\"',184),(730,'rutrun','\"15.589.871-2\"',184),(731,'domicilio','\"EMBALSE\"',184),(732,'localidad','\"Pirque\"',184),(733,'regioncomuna','{\"region\":\"Del Libertador Gral. Bernardo O’Higgins\",\"comuna\":\"Codegua\",\"cstateCode\":\"06\",\"cstateName\":\"Del Libertador Gral. Bernardo O’Higgins\",\"ccityCode\":\"Codegua\",\"ccityName\":\"Codegua\"}',184),(734,'correo','\"aridos@aridos.cl\"',184),(735,'fecha_solicitud','\"09-12-2019\"',184),(736,'categoria','\"comunal\"',184),(737,'cauce','\"Clarillo\"',184),(738,'sector','\"Pirque\"',184),(739,'comunasfact','{\"region\":\"Metropolitana de Santiago\",\"comuna\":\"Colina\",\"cstateCode\":\"13\",\"cstateName\":\"Metropolitana de Santiago\",\"ccityCode\":\"Colina\",\"ccityName\":\"Colina\"}',184),(740,'porcentaje','\"1000\"',184),(741,'porcentaje_bolones','\"0\"',184),(742,'porcentaje_material_integral','\"0\"',184),(743,'destino_arido','\"Construccion\"',184),(744,'inicio_extraccion','\"03-12-2019\"',184),(745,'fin_extraccion','\"05-12-2019\"',184),(746,'volu','\"1000\"',184),(747,'longitud','\"0\"',184),(748,'ancho','\"0\"',184),(749,'espesor','\"0\"',184),(750,'geom','{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[-70.73712244004632,-33.47119230630928],[-70.73420660776274,-33.469139434430694],[-70.73674964612725,-33.468020464051385],[-70.73712244004632,-33.47119230630928]]]},\"properties\":null}]}',184),(751,'proyecto','\"0\"',184),(752,'comentarios_proyectos','\"Es artesanal\"',184),(753,'nombre_persona','\"Luciano  Ahumada\"',186),(754,'rutrun','\"13103529-2\"',186),(755,'domicilio','\"EMBALSE\"',186),(756,'localidad','\"Pirque\"',186),(757,'regioncomuna','{\"region\":\"Valparaíso\",\"comuna\":\"Quilpué\",\"cstateCode\":\"05\",\"cstateName\":\"Valparaíso\",\"ccityCode\":\"05801\",\"ccityName\":\"Quilpué\"}',186),(758,'correo','\"aridos@aridos.cl\"',186),(759,'fecha_solicitud','\"10-12-2019\"',186),(760,'categoria','\"comunal\"',186),(761,'cauce','\"Clarillo\"',186),(762,'sector','\"Pirque\"',186),(763,'comunasfact','{\"region\":\"De la Araucanía\",\"comuna\":\"Cunco\",\"cstateCode\":\"09\",\"cstateName\":\"De la Araucanía\",\"ccityCode\":\"09103\",\"ccityName\":\"Cunco\"}',186),(764,'porcentaje','\"1100\"',186),(765,'porcentaje_bolones','\"0\"',186),(766,'porcentaje_material_integral','\"0\"',186),(767,'destino_arido','\"Construccion\"',186),(768,'inicio_extraccion','\"17-12-2019\"',186),(769,'fin_extraccion','\"26-12-2019\"',186),(770,'volu','\"1000\"',186),(771,'longitud','\"0\"',186),(772,'ancho','\"0\"',186),(773,'espesor','\"0\"',186),(774,'geom','{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[-72.05276075019836,-38.99775367511336],[-71.95648017539976,-38.98797236527349],[-71.97527709617614,-38.93938768718347],[-72.03442516937254,-38.94435279407811],[-72.05276075019836,-38.99775367511336]]]},\"properties\":null}]}',186),(775,'documento_formulario','\"5defddac3bf2e.pdf\"',186),(776,'nombre_persona','\"Brian Bast\\u00edas\"',189),(777,'rutrun','\"19.933.118-3\"',189),(778,'domicilio','\"Calle 123\"',189),(779,'localidad','\"Pirque\"',189),(780,'regioncomuna','{\"region\":\"Antofagasta\",\"comuna\":\"Mejillones\",\"cstateCode\":\"02\",\"cstateName\":\"Antofagasta\",\"ccityCode\":\"02102\",\"ccityName\":\"Mejillones\"}',189),(781,'correo','\"brianbastias@hotmail.com\"',189),(782,'fecha_solicitud','\"11-12-2019\"',189),(783,'categoria','\"intercomunal\"',189),(784,'cauce','\"Clarillo\"',189),(785,'sector','\"Pirque\"',189),(786,'comunasfact','{\"region\":\"Tarapacá\",\"comuna\":\"Colchane\",\"cstateCode\":\"01\",\"cstateName\":\"Tarapacá\",\"ccityCode\":\"01403\",\"ccityName\":\"Colchane\"}',189),(787,'porcentaje_arena','\"100\"',189),(788,'porcentaje_bolones','\"100\"',189),(789,'porcentaje_material_integral','\"0\"',189),(790,'destino_arido','\"Santiago\"',189),(791,'inicio_extraccion','\"12-12-2019\"',189),(792,'fin_extraccion','\"14-12-2019\"',189),(793,'volu','\"1\"',189),(794,'longitud','\"1\"',189),(795,'ancho','\"1\"',189),(796,'espesor','\"1\"',189),(797,'geom','{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[-68.66248465232849,-19.245205916930857],[-68.64641285591127,-19.398309129557177],[-68.49425650291442,-19.39544520479535],[-68.443927532959,-19.244841262724506],[-68.66248465232849,-19.245205916930857]]]},\"properties\":null}]}',189),(798,'documento_formulario','\"5deff01f1ec29.pdf\"',189),(799,'nombre_persona','\"Jonathan Frez\"',192),(800,'rutrun','\"15.589.871-2\"',192),(801,'domicilio','\"EMBALSE\"',192),(802,'localidad','\"Pirque\"',192),(803,'regioncomuna','{\"region\":\"Coquimbo\",\"comuna\":\"Illapel\",\"cstateCode\":\"04\",\"cstateName\":\"Coquimbo\",\"ccityCode\":\"04201\",\"ccityName\":\"Illapel\"}',192),(804,'correo','\"aridos@aridos.cl\"',192),(805,'fecha_solicitud','\"10-12-2019\"',192),(806,'categoria','\"comunal\"',192),(807,'cauce','\"Clarillo\"',192),(808,'sector','\"Pirque\"',192),(809,'comunasfact','{\"region\":\"Atacama\",\"comuna\":\"Copiapó\",\"cstateCode\":\"03\",\"cstateName\":\"Atacama\",\"ccityCode\":\"03101\",\"ccityName\":\"Copiapó\"}',192),(810,'porcentaje_arena','\"1000\"',192),(811,'porcentaje_bolones','\"0\"',192),(812,'porcentaje_material_integral','\"0\"',192),(813,'destino_arido','\"Construccion\"',192),(814,'inicio_extraccion','\"03-12-2019\"',192),(815,'fin_extraccion','\"01-12-2019\"',192),(816,'volu','\"100\"',192),(817,'longitud','\"100\"',192),(818,'ancho','\"50\"',192),(819,'espesor','\"10\"',192),(820,'geom','{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[-70.50797518386841,-27.330797680652772],[-70.50218161239624,-27.274978569894444],[-70.54878767623902,-27.293352877342073],[-70.50797518386841,-27.330797680652772]]]},\"properties\":null}]}',192),(821,'documento_formulario','\"5df00de777f73.pdf\"',192),(822,'nombre_persona','\"Diego Su\\u00e1rez\"',194),(823,'rutrun','\"11.111.111-1\"',194),(824,'domicilio','\"Calle Falsa 123\"',194),(825,'localidad','\"Santiago\"',194),(826,'regioncomuna','{\"region\":\"De la Araucanía\",\"comuna\":\"Angol\",\"cstateCode\":\"09\",\"cstateName\":\"De la Araucanía\",\"ccityCode\":\"Angol\",\"ccityName\":\"Angol\"}',194),(827,'correo','\"martin.saavedra@mail.udp.cl\"',194),(828,'fecha_solicitud','\"18-12-2019\"',194),(829,'categoria','\"comunal\"',194),(830,'cauce','\"Cau Cau\"',194),(831,'sector','\"Santiago\"',194),(832,'comunasfact','{\"region\":\"Antofagasta\",\"comuna\":\"Ollagüe\",\"cstateCode\":\"02\",\"cstateName\":\"Antofagasta\",\"ccityCode\":\"02202\",\"ccityName\":\"Ollagüe\"}',194),(833,'porcentaje_arena','\"25\"',194),(834,'porcentaje_bolones','\"25\"',194),(835,'porcentaje_material_integral','\"25\"',194),(836,'destino_arido','\"Construcci\\u00f3n de departamentos\"',194),(837,'inicio_extraccion','\"03-01-2020\"',194),(838,'fin_extraccion','\"04-01-2020\"',194),(839,'volu','\"110\"',194),(840,'longitud','\"450\"',194),(841,'ancho','\"900\"',194),(842,'espesor','\"320\"',194),(843,'geom','{\"type\":\"FeatureCollection\",\"features\":[{\"type\":\"Feature\",\"geometry\":{\"type\":\"Polygon\",\"coordinates\":[[[-68.14009701766967,-21.239130945816868],[-68.21420108833313,-21.71907700630652],[-68.59802519836425,-21.359722678003436],[-68.14009701766967,-21.239130945816868]]]},\"properties\":null}]}',194),(844,'documento_formulario','\"5df0219f1b5c3.pdf\"',194);
/*!40000 ALTER TABLE `dato_seguimiento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documento`
--

DROP TABLE IF EXISTS `documento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `documento` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` enum('blanco','certificado') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'blanco',
  `nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `contenido` text COLLATE utf8_unicode_ci NOT NULL,
  `servicio` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `servicio_url` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `timbre` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `firmador_nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `firmador_cargo` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `firmador_servicio` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `firmador_imagen` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `validez` int(10) unsigned DEFAULT NULL,
  `hsm_configuracion_id` int(10) unsigned DEFAULT NULL,
  `proceso_id` int(10) unsigned NOT NULL,
  `subtitulo` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `titulo` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `validez_habiles` tinyint(4) DEFAULT NULL,
  `tamano` enum('letter','legal') COLLATE utf8_unicode_ci DEFAULT 'letter',
  PRIMARY KEY (`id`),
  KEY `hsm_configuracion_id` (`hsm_configuracion_id`),
  KEY `fk_documento_proceso1` (`proceso_id`),
  CONSTRAINT `documento_ibfk_1` FOREIGN KEY (`hsm_configuracion_id`) REFERENCES `hsm_configuracion` (`id`),
  CONSTRAINT `fk_documento_proceso1` FOREIGN KEY (`proceso_id`) REFERENCES `proceso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documento`
--

LOCK TABLES `documento` WRITE;
/*!40000 ALTER TABLE `documento` DISABLE KEYS */;
INSERT INTO `documento` VALUES (2,'certificado','Certificado de formulario solicitud','<style>\r\ndiv.a {\r\n  text-align: center;\r\n}\r\ndiv.d {\r\n  text-align: justify;\r\n} \r\n</style>\r\n\r\n\r\n</br>\r\n<div class=\"d\">\r\nSu solicitud de extracción de áridos ha sido ingresada exitosamente en la plataforma.<br></br>\r\n<br></br>\r\n<br></br>\r\n<br><strong>Motivo:</strong>Solicitud de extración de áridos desde cauce natural</br>\r\n<br></br>\r\n<br><strong>Solicitante:</strong>@@nombre_persona</br>\r\n<br><strong>RUT:</strong>@@rutrun</br>\r\n<br><strong>Email:</strong>@@correo</br>','Dirección de obras  hidráulicas','http://Aridos.eit.technology','','','','','Ministerio de Obras Públicas','',0,NULL,1,'Dirección de Obras Fluviales','CONSTANCIA DIRECCIÓN OBRAS HIDRÁULICAS',NULL,'letter');
/*!40000 ALTER TABLE `documento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `etapa`
--

DROP TABLE IF EXISTS `etapa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `etapa` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tarea_id` int(10) unsigned NOT NULL,
  `usuario_id` int(10) unsigned DEFAULT NULL,
  `pendiente` tinyint(4) NOT NULL,
  `etapa_ancestro_split_id` int(10) unsigned DEFAULT NULL,
  `vencimiento_at` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `tramite_id` int(10) unsigned NOT NULL,
  `extra` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_etapa_tramite1` (`tramite_id`),
  KEY `etapa_ancestro_split_id` (`etapa_ancestro_split_id`),
  KEY `fk_etapa_tarea1` (`tarea_id`),
  KEY `fk_etapa_usuario1` (`usuario_id`),
  CONSTRAINT `etapa_ibfk_1` FOREIGN KEY (`tramite_id`) REFERENCES `tramite` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `etapa_ibfk_2` FOREIGN KEY (`etapa_ancestro_split_id`) REFERENCES `etapa` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_etapa_tarea1` FOREIGN KEY (`tarea_id`) REFERENCES `tarea` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_etapa_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `etapa`
--

LOCK TABLES `etapa` WRITE;
/*!40000 ALTER TABLE `etapa` DISABLE KEYS */;
INSERT INTO `etapa` VALUES (170,1,2,0,NULL,NULL,'2019-12-09 20:29:07','2019-12-09 20:47:50','2019-12-09 20:32:25',62,'{\"mostrar_hit\":false}'),(171,9,35,0,NULL,NULL,'2019-12-09 20:32:25','2019-12-09 20:47:50','2019-12-09 20:32:38',62,'{\"mostrar_hit\":false}'),(172,2,2,0,NULL,NULL,'2019-12-09 20:32:38','2019-12-09 20:47:50','2019-12-09 20:44:17',62,'{\"mostrar_hit\":false}'),(173,4,2,0,NULL,NULL,'2019-12-09 20:44:17','2019-12-09 20:47:50','2019-12-09 20:44:35',62,'{\"mostrar_hit\":false}'),(174,6,3,0,NULL,NULL,'2019-12-09 20:44:35','2019-12-09 20:47:50','2019-12-09 20:47:06',62,'{\"mostrar_hit\":false}'),(175,7,2,0,NULL,NULL,'2019-12-09 20:47:06','2019-12-09 20:47:50','2019-12-09 20:47:36',62,'{\"mostrar_hit\":false}'),(176,8,3,0,NULL,NULL,'2019-12-09 20:47:36','2019-12-09 20:47:50','2019-12-09 20:47:50',62,'{\"mostrar_hit\":false}'),(177,1,2,0,NULL,NULL,'2019-12-10 02:35:51','2019-12-10 02:37:09','2019-12-10 02:37:09',63,'{\"mostrar_hit\":false}'),(178,9,35,0,NULL,NULL,'2019-12-10 02:37:09','2019-12-10 02:44:55','2019-12-10 02:44:55',63,'{\"mostrar_hit\":false}'),(179,2,2,0,NULL,NULL,'2019-12-10 02:44:55','2019-12-10 14:49:30','2019-12-10 14:49:30',63,'{\"mostrar_hit\":false}'),(180,1,2,0,NULL,NULL,'2019-12-10 02:48:55','2019-12-10 17:51:19','2019-12-10 12:24:06',64,'{\"mostrar_hit\":false}'),(181,9,35,0,NULL,NULL,'2019-12-10 12:24:06','2019-12-10 17:51:19','2019-12-10 12:51:56',64,'{\"mostrar_hit\":false}'),(182,2,2,0,NULL,NULL,'2019-12-10 12:51:56','2019-12-10 17:51:19','2019-12-10 12:56:03',64,'{\"mostrar_hit\":false}'),(183,3,3,0,NULL,NULL,'2019-12-10 12:56:03','2019-12-10 17:51:19','2019-12-10 17:51:19',64,'{\"mostrar_hit\":false}'),(184,4,2,0,NULL,NULL,'2019-12-10 14:49:30','2019-12-10 15:32:36','2019-12-10 15:32:36',63,'{\"mostrar_hit\":false}'),(185,6,3,1,NULL,NULL,'2019-12-10 15:32:36','2019-12-10 20:50:29',NULL,63,'{\"mostrar_hit\":false}'),(186,1,2,0,NULL,NULL,'2019-12-10 17:06:20','2019-12-10 18:02:20','2019-12-10 18:02:20',65,'{\"mostrar_hit\":false}'),(187,9,35,0,NULL,NULL,'2019-12-10 18:02:20','2019-12-10 18:04:15','2019-12-10 18:04:15',65,'{\"mostrar_hit\":false}'),(188,2,2,1,NULL,NULL,'2019-12-10 18:04:15','2019-12-10 18:04:15',NULL,65,NULL),(189,1,2,0,NULL,NULL,'2019-12-10 19:18:27','2019-12-10 19:21:03','2019-12-10 19:21:03',66,'{\"mostrar_hit\":false}'),(190,9,35,0,NULL,NULL,'2019-12-10 19:21:03','2019-12-10 20:31:45','2019-12-10 20:31:45',66,'{\"mostrar_hit\":false}'),(191,2,2,1,NULL,NULL,'2019-12-10 20:31:45','2019-12-10 20:31:45',NULL,66,NULL),(192,1,3,0,NULL,NULL,'2019-12-10 20:50:32','2019-12-10 21:28:07','2019-12-10 21:28:07',67,'{\"mostrar_hit\":false}'),(193,9,35,0,NULL,NULL,'2019-12-10 21:28:07','2019-12-10 22:43:09','2019-12-10 22:43:09',67,'{\"mostrar_hit\":false}'),(194,1,3,0,NULL,NULL,'2019-12-10 22:04:32','2019-12-10 22:52:14','2019-12-10 22:52:14',68,'{\"mostrar_hit\":false}'),(195,2,2,1,NULL,NULL,'2019-12-10 22:43:09','2019-12-10 22:43:09',NULL,67,NULL),(196,9,35,1,NULL,NULL,'2019-12-10 22:52:14','2019-12-10 22:52:49',NULL,68,'{\"mostrar_hit\":false}');
/*!40000 ALTER TABLE `etapa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evento`
--

DROP TABLE IF EXISTS `evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evento` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `regla` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `instante` enum('antes','despues','durante') COLLATE utf8_unicode_ci NOT NULL,
  `tarea_id` int(10) unsigned NOT NULL,
  `accion_id` int(10) unsigned NOT NULL,
  `paso_id` int(10) unsigned DEFAULT NULL,
  `evento_externo_id` int(10) unsigned DEFAULT NULL,
  `campo_asociado` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_evento_accion1` (`accion_id`),
  KEY `paso_id` (`paso_id`),
  KEY `fk_evento_tarea1` (`tarea_id`),
  KEY `fke_evento_externo_foreign_key` (`evento_externo_id`),
  CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`accion_id`) REFERENCES `accion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `evento_ibfk_2` FOREIGN KEY (`paso_id`) REFERENCES `paso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_evento_tarea1` FOREIGN KEY (`tarea_id`) REFERENCES `tarea` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fke_evento_externo_foreign_key` FOREIGN KEY (`evento_externo_id`) REFERENCES `evento_externo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evento`
--

LOCK TABLES `evento` WRITE;
/*!40000 ALTER TABLE `evento` DISABLE KEYS */;
INSERT INTO `evento` VALUES (1,NULL,'antes',12,2,NULL,NULL,'');
/*!40000 ALTER TABLE `evento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evento_externo`
--

DROP TABLE IF EXISTS `evento_externo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `evento_externo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `metodo` enum('GET','POST','PUT') COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mensaje` text COLLATE utf8_unicode_ci,
  `regla` text COLLATE utf8_unicode_ci,
  `tarea_id` int(10) unsigned NOT NULL,
  `opciones` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `eetarea_foreign_key` (`tarea_id`),
  CONSTRAINT `eetarea_foreign_key` FOREIGN KEY (`tarea_id`) REFERENCES `tarea` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evento_externo`
--

LOCK TABLES `evento_externo` WRITE;
/*!40000 ALTER TABLE `evento_externo` DISABLE KEYS */;
/*!40000 ALTER TABLE `evento_externo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feriado`
--

DROP TABLE IF EXISTS `feriado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feriado` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fecha_UNIQUE` (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feriado`
--

LOCK TABLES `feriado` WRITE;
/*!40000 ALTER TABLE `feriado` DISABLE KEYS */;
/*!40000 ALTER TABLE `feriado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `file`
--

DROP TABLE IF EXISTS `file`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tipo` enum('dato','documento','s3') COLLATE utf8_unicode_ci DEFAULT NULL,
  `llave` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `llave_copia` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `llave_firma` varchar(12) COLLATE utf8_unicode_ci DEFAULT NULL,
  `validez` int(10) unsigned DEFAULT NULL,
  `tramite_id` int(10) unsigned NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `validez_habiles` tinyint(4) DEFAULT NULL,
  `extra` text COLLATE utf8_unicode_ci,
  `campo_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tipo_tramiteid_filename` (`tipo`,`tramite_id`,`filename`),
  KEY `fk_file_tramite1` (`tramite_id`),
  CONSTRAINT `fk_file_tramite1` FOREIGN KEY (`tramite_id`) REFERENCES `tramite` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file`
--

LOCK TABLES `file` WRITE;
/*!40000 ALTER TABLE `file` DISABLE KEYS */;
INSERT INTO `file` VALUES (50,'5deeaf59cf24c.pdf','documento','mlqatqczqxfj','ionm0ilfitjc','zzm1l733lctv',0,62,'2019-12-09 20:32:25','2019-12-09 20:32:25',NULL,NULL,NULL),(51,'5def04d61f398.pdf','documento','6icqf1ghbntx','cmmadbtxn9dh','8pai5sir0imc',0,63,'2019-12-10 02:37:10','2019-12-10 02:37:10',NULL,NULL,NULL),(52,'5def8e6682bd2.pdf','documento','zlk8qwrryftq','8dlo69wqzquz','8nvsgiif2c7q',0,64,'2019-12-10 12:24:06','2019-12-10 12:24:06',NULL,NULL,NULL),(53,'5defddac3bf2e.pdf','documento','bq952pyjtuf1','kxz2vqbkj8d5','ry52gekwsshg',0,65,'2019-12-10 18:02:20','2019-12-10 18:02:20',NULL,NULL,NULL),(54,'5deff01f1ec29.pdf','documento','qvdmoojass8z','hniimpgbaizq','gnxwlytrgsbk',0,66,'2019-12-10 19:21:03','2019-12-10 19:21:03',NULL,NULL,NULL),(55,'5df00de777f73.pdf','documento','xylhuxrvgrmi','vbqttnwc2vgk','rmpmd2xsqznf',0,67,'2019-12-10 21:28:07','2019-12-10 21:28:07',NULL,NULL,NULL),(56,'5df0219f1b5c3.pdf','documento','zxjpdwvebaod','w8tequzeyidq','9tlbuf5tdwul',0,68,'2019-12-10 22:52:15','2019-12-10 22:52:15',NULL,NULL,NULL);
/*!40000 ALTER TABLE `file` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formulario`
--

DROP TABLE IF EXISTS `formulario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `formulario` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8_unicode_ci,
  `proceso_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_formulario_proceso1` (`proceso_id`),
  CONSTRAINT `fk_formulario_proceso1` FOREIGN KEY (`proceso_id`) REFERENCES `proceso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formulario`
--

LOCK TABLES `formulario` WRITE;
/*!40000 ALTER TABLE `formulario` DISABLE KEYS */;
INSERT INTO `formulario` VALUES (1,'¿Aceptar?',NULL,1),(2,'¿Requiere proyecto?',NULL,1),(3,'Formulario: Ingresar solicitud','Ingreso de solicitud para la extracción de áridos.',1),(5,'Ingresar proyecto',NULL,1),(6,'Análisis proyecto',NULL,1),(7,'Resultado solicitud de proyecto',NULL,1),(8,'Solicitud rechazada',NULL,1),(9,'Constancia de solicitud',NULL,1),(11,'Formulario',NULL,3),(12,'recepcion',NULL,3);
/*!40000 ALTER TABLE `formulario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo_usuarios`
--

DROP TABLE IF EXISTS `grupo_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupo_usuarios` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `cuenta_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grupo_usuarios_UNIQUE` (`cuenta_id`,`nombre`),
  KEY `fk_grupo_usuarios_cuenta1` (`cuenta_id`),
  CONSTRAINT `grupo_usuarios_ibfk_1` FOREIGN KEY (`cuenta_id`) REFERENCES `cuenta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_usuarios`
--

LOCK TABLES `grupo_usuarios` WRITE;
/*!40000 ALTER TABLE `grupo_usuarios` DISABLE KEYS */;
INSERT INTO `grupo_usuarios` VALUES (6,'5ta. región',1),(4,'Analista DOH-DGA',1),(3,'Coordinador Regional',1),(5,'Región Metropolitana',1),(1,'Usuario DOH',1),(2,'Usuario Municipal',1);
/*!40000 ALTER TABLE `grupo_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo_usuarios_has_usuario`
--

DROP TABLE IF EXISTS `grupo_usuarios_has_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupo_usuarios_has_usuario` (
  `grupo_usuarios_id` int(10) unsigned NOT NULL,
  `usuario_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`grupo_usuarios_id`,`usuario_id`),
  KEY `fk_grupo_usuarios_has_usuario_usuario1` (`usuario_id`),
  KEY `fk_grupo_usuarios_has_usuario_grupo_usuarios1` (`grupo_usuarios_id`),
  CONSTRAINT `grupo_usuarios_has_usuario_ibfk_1` FOREIGN KEY (`grupo_usuarios_id`) REFERENCES `grupo_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `grupo_usuarios_has_usuario_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_usuarios_has_usuario`
--

LOCK TABLES `grupo_usuarios_has_usuario` WRITE;
/*!40000 ALTER TABLE `grupo_usuarios_has_usuario` DISABLE KEYS */;
INSERT INTO `grupo_usuarios_has_usuario` VALUES (1,2),(5,2),(2,3),(5,3),(3,35),(5,35),(4,98),(5,98),(1,166),(6,166);
/*!40000 ALTER TABLE `grupo_usuarios_has_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hsm_configuracion`
--

DROP TABLE IF EXISTS `hsm_configuracion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hsm_configuracion` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rut` int(10) unsigned NOT NULL,
  `nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `cuenta_id` int(10) unsigned NOT NULL,
  `entidad` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `proposito` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `estado` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hsm_configuracion_rut_cuenta_id_proposito_unique` (`rut`,`cuenta_id`,`proposito`),
  KEY `fk_hsm_configuracion_cuenta1` (`cuenta_id`),
  CONSTRAINT `fk_hsm_configuracion_cuenta1` FOREIGN KEY (`cuenta_id`) REFERENCES `cuenta` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hsm_configuracion`
--

LOCK TABLES `hsm_configuracion` WRITE;
/*!40000 ALTER TABLE `hsm_configuracion` DISABLE KEYS */;
/*!40000 ALTER TABLE `hsm_configuracion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` datetime DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `user_type` enum('frontend','backend') COLLATE utf8_unicode_ci NOT NULL,
  `extra` text COLLATE utf8_unicode_ci,
  `filename` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `filepath` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `arguments` mediumtext COLLATE utf8_unicode_ci,
  `status` enum('created','running','error','finished') COLLATE utf8_unicode_ci DEFAULT NULL,
  `downloads` int(11) NOT NULL DEFAULT '0',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `login_erroneo`
--

DROP TABLE IF EXISTS `login_erroneo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `login_erroneo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `horario` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_erroneo`
--

LOCK TABLES `login_erroneo` WRITE;
/*!40000 ALTER TABLE `login_erroneo` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_erroneo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2018_02_02_170310_create_cuenta_table',1),(2,'2018_02_02_170310_create_usuario_table',1),(3,'2018_02_02_170311_create_hsm_configuracion_table',1),(4,'2018_02_02_170311_create_proceso_table',1),(5,'2018_02_02_170312_create_tramite_table',1),(6,'2018_02_02_170314_create_tarea_table',1),(7,'2018_02_02_170315_create_accion_table',1),(8,'2018_02_02_170316_create_etapa_table',1),(9,'2018_02_02_170317_create_evento_externo_table',1),(10,'2018_02_02_170325_create_acontecimiento_table',1),(11,'2018_02_02_170334_create_auditoria_operaciones_table',1),(12,'2018_02_02_170340_create_formulario_table',1),(13,'2018_02_02_170341_create_documento_table',1),(14,'2018_02_02_170344_create_campo_table',1),(15,'2018_02_02_170354_create_categoria_table',1),(16,'2018_02_02_170403_create_cola_continuar_tramite_table',1),(17,'2018_02_02_170413_create_conexion_table',1),(18,'2018_02_02_170424_create_config_table',1),(19,'2018_02_02_170436_create_config_general_table',1),(20,'2018_02_02_170455_create_cuenta_has_config_table',1),(21,'2018_02_02_170505_create_dato_seguimiento_table',1),(22,'2018_02_02_170530_create_paso_table',1),(23,'2018_02_02_170533_create_evento_table',1),(24,'2018_02_02_170553_create_feriado_table',1),(25,'2018_02_02_170602_create_file_table',1),(26,'2018_02_02_170621_create_grupo_usuarios_table',1),(27,'2018_02_02_170631_create_grupo_usuarios_has_usuario_table',1),(28,'2018_02_02_170650_create_login_erroneo_table',1),(29,'2018_02_02_170729_create_proceso_cuenta_table',1),(30,'2018_02_02_170739_create_reporte_table',1),(31,'2018_02_02_170749_create_seguridad_table',1),(32,'2018_02_02_170758_create_suscriptor_table',1),(33,'2018_02_02_170817_create_tarea_has_grupo_usuarios_table',1),(34,'2018_02_02_170845_create_usuario_backend_table',1),(35,'2018_02_02_170855_create_usuario_manager_table',1),(36,'2018_02_02_170905_create_widget_table',1),(37,'2018_03_21_160123_create_password_resets_table',1),(38,'2018_09_26_181150_update_evento_table',1),(39,'2018_09_26_181909_update_evento_externo_table',1),(40,'2018_10_30_172557_update_proceso_table',1),(41,'2018_11_26_160350_alter_usuario_drop_unique_email',1),(42,'2018_11_26_160455_alter_usuario_backend_cuenta_email_unique',1),(43,'2018_11_28_124201_alter_hsm_configuracion_add_columns',1),(44,'2018_12_03_093812_alter_cuenta_add_column_entidad',1),(45,'2018_12_03_180747_alter_table_hsm_configuracion_add_column_rut',1),(46,'2018_12_03_194056_alter_hsm_configuracion_drop_unique_nombre',1),(47,'2018_12_03_194711_alter_hsm_configuracion_add_column_estado',1),(48,'2018_12_04_100345_alter_hsm_configuracion_add_unique__rut_ent_pro',1),(49,'2018_12_05_131157_update_file_types',1),(50,'2018_12_06_204758_file_add_extra',1),(51,'2018_12_09_122734_alter_cuenta_add_column_style',1),(52,'2018_12_10_143822_alter_table_cuenta_add_column_header_footer',1),(53,'2018_12_12_190810_alter_table_cuenta_add_column_personalizacion',1),(54,'2018_12_13_132620_update_file_add_campo_id',1),(55,'2018_12_14_142836_alter_cuenta_add_column_logof',1),(56,'2018_12_27_115809_update_file_unique_constraint',1),(57,'2019_01_07_175052_add_seo_tags_cuenta',1),(58,'2019_01_08_143724_create_jobs_table',1),(59,'2019_01_23_205024_update_campo_table',1),(60,'2019_01_28_183414_alter_campo_add_column_condiciones_extra',1),(61,'2019_02_05_201148_update_jobs',1),(62,'2019_02_12_155115_create_failed_jobs_table',1),(63,'2019_02_18_190123_add_instante_durante_evento',1),(64,'2019_02_18_213401_add_elemento_async_evento',1),(65,'2019_02_27_134742_update_tarea',1),(66,'2019_03_01_192157_alter_proceso_add_column_url_informativa',1),(67,'2019_03_19_144222_alter_proceso_add_columns_usuariobackend_timestamp',1),(68,'2019_03_25_192143_update_proceso_add_column_concurrente',1),(69,'2019_04_01_174851_update_tarea_update_vencimiento_dias',1),(70,'2019_05_06_204806_update_formulario_add_column_descripcion',1),(71,'2019_06_05_131117_update_cuenta_add_analytics',1),(72,'2019_06_06_130313_update_table_campo_add_depentente_rel_and_tipo',1),(73,'2019_06_18_184921_add_soft_delete_tramite',1),(74,'2019_06_18_190419_alter_proceso_add_column_eliminar_tramites',1),(75,'2019_07_09_200404_update_proceso_add_idrnt',1),(76,'2019_07_10_163359_update_eventos_externos',1),(77,'2019_07_10_211424_update_proceso_add_idcha',1),(78,'2019_07_17_160605_update_table_proceso_add_column_ocultar_front',1),(79,'2019_07_23_191352_drop_column_idrnt_idcha',1),(80,'2019_07_26_144509_update_table_cuenta_add_column_metadata_params',1),(81,'2019_08_05_184909_update_table_cuenta_add_favicon_field',1),(82,'2019_08_07_142526_add_column_extra_on_table_etapa',1),(83,'2019_08_27_231715_add_enum_anonimo_to_acceso_modo_on_tarea',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paso`
--

DROP TABLE IF EXISTS `paso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paso` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orden` int(10) unsigned NOT NULL,
  `modo` enum('edicion','visualizacion') COLLATE utf8_unicode_ci NOT NULL,
  `regla` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `formulario_id` int(10) unsigned NOT NULL,
  `tarea_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_paso_formulario1` (`formulario_id`),
  KEY `fk_paso_tarea1` (`tarea_id`),
  CONSTRAINT `fk_paso_tarea1` FOREIGN KEY (`tarea_id`) REFERENCES `tarea` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `paso_ibfk_1` FOREIGN KEY (`formulario_id`) REFERENCES `formulario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paso`
--

LOCK TABLES `paso` WRITE;
/*!40000 ALTER TABLE `paso` DISABLE KEYS */;
INSERT INTO `paso` VALUES (4,1,'visualizacion',NULL,1,4),(5,2,'edicion','@@factible==2',3,4),(8,1,'visualizacion',NULL,1,3),(9,1,'visualizacion',NULL,2,5),(10,1,'visualizacion',NULL,2,6),(11,2,'edicion',NULL,5,6),(12,1,'visualizacion',NULL,5,7),(13,2,'edicion',NULL,6,7),(14,1,'visualizacion',NULL,6,8),(15,2,'visualizacion',NULL,7,8),(16,2,'visualizacion',NULL,8,3),(22,1,'edicion',NULL,3,1),(23,1,'visualizacion',NULL,3,2),(24,2,'edicion',NULL,1,2),(25,3,'edicion',NULL,2,4),(26,1,'edicion',NULL,11,11),(27,1,'edicion',NULL,12,12),(28,2,'visualizacion',NULL,9,1),(29,1,'visualizacion',NULL,6,14),(30,2,'visualizacion',NULL,7,14),(31,3,'edicion','solicitud_proyecto == 2',3,7),(32,1,'visualizacion',NULL,3,9);
/*!40000 ALTER TABLE `paso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proceso`
--

DROP TABLE IF EXISTS `proceso`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proceso` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8_unicode_ci,
  `url_informativa` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `usuario_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `width` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '100%',
  `height` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '800px',
  `cuenta_id` int(10) unsigned NOT NULL,
  `proc_cont` int(11) DEFAULT NULL,
  `activo` tinyint(4) NOT NULL DEFAULT '1',
  `categoria_id` int(10) unsigned DEFAULT NULL,
  `destacado` int(11) DEFAULT NULL,
  `icon_ref` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `version` int(11) DEFAULT NULL,
  `root` int(11) DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'public',
  `concurrente` tinyint(1) DEFAULT NULL,
  `eliminar_tramites` tinyint(1) DEFAULT NULL,
  `ocultar_front` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_proceso_cuenta1` (`cuenta_id`),
  KEY `fk_categoria` (`categoria_id`),
  KEY `fk_proceso_usuario1` (`usuario_id`),
  CONSTRAINT `fk_proceso_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario_backend` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `proceso_ibfk_1` FOREIGN KEY (`cuenta_id`) REFERENCES `cuenta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proceso`
--

LOCK TABLES `proceso` WRITE;
/*!40000 ALTER TABLE `proceso` DISABLE KEYS */;
INSERT INTO `proceso` VALUES (1,'Solicitud de permiso para extracción de áridos','Formulario de ingreso para solicitar extracción de aridos.',NULL,1,'2019-10-22 15:12:38',NULL,'100%','800px',1,NULL,1,0,0,'atomic.png',1,NULL,'public',0,0,0),(2,'Proceso',NULL,NULL,1,'2019-11-06 23:28:56',NULL,'100%','800px',1,NULL,0,NULL,NULL,NULL,NULL,NULL,'public',NULL,NULL,NULL),(3,'Proceso',NULL,NULL,1,'2019-12-05 15:05:19',NULL,'100%','800px',1,NULL,0,NULL,NULL,NULL,NULL,NULL,'public',NULL,NULL,NULL);
/*!40000 ALTER TABLE `proceso` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `proceso_cuenta`
--

DROP TABLE IF EXISTS `proceso_cuenta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proceso_cuenta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_cuenta_origen` int(11) DEFAULT NULL,
  `id_cuenta_destino` int(11) DEFAULT NULL,
  `id_proceso` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `proceso_cuenta`
--

LOCK TABLES `proceso_cuenta` WRITE;
/*!40000 ALTER TABLE `proceso_cuenta` DISABLE KEYS */;
/*!40000 ALTER TABLE `proceso_cuenta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reporte`
--

DROP TABLE IF EXISTS `reporte`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reporte` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `campos` text COLLATE utf8_unicode_ci NOT NULL,
  `proceso_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reporte_proceso1` (`proceso_id`),
  CONSTRAINT `reporte_ibfk_1` FOREIGN KEY (`proceso_id`) REFERENCES `proceso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reporte`
--

LOCK TABLES `reporte` WRITE;
/*!40000 ALTER TABLE `reporte` DISABLE KEYS */;
/*!40000 ALTER TABLE `reporte` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seguridad`
--

DROP TABLE IF EXISTS `seguridad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `seguridad` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `institucion` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `servicio` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra` text COLLATE utf8_unicode_ci,
  `proceso_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seguridad`
--

LOCK TABLES `seguridad` WRITE;
/*!40000 ALTER TABLE `seguridad` DISABLE KEYS */;
/*!40000 ALTER TABLE `seguridad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suscriptor`
--

DROP TABLE IF EXISTS `suscriptor`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `suscriptor` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `institucion` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `extra` text COLLATE utf8_unicode_ci,
  `proceso_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suscriptor`
--

LOCK TABLES `suscriptor` WRITE;
/*!40000 ALTER TABLE `suscriptor` DISABLE KEYS */;
/*!40000 ALTER TABLE `suscriptor` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tarea`
--

DROP TABLE IF EXISTS `tarea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tarea` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `identificador` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `inicial` tinyint(4) NOT NULL DEFAULT '0',
  `nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `posx` int(10) unsigned NOT NULL DEFAULT '0',
  `posy` int(10) unsigned NOT NULL DEFAULT '0',
  `asignacion` enum('ciclica','manual','autoservicio','usuario') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'ciclica',
  `asignacion_usuario` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `asignacion_notificar` tinyint(4) NOT NULL DEFAULT '0',
  `proceso_id` int(10) unsigned NOT NULL,
  `almacenar_usuario` tinyint(4) NOT NULL DEFAULT '0',
  `almacenar_usuario_variable` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acceso_modo` enum('grupos_usuarios','publico','registrados','claveunica','anonimo') COLLATE utf8_unicode_ci NOT NULL,
  `activacion` enum('si','no','entre_fechas') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'si',
  `activacion_inicio` date DEFAULT NULL,
  `activacion_fin` date DEFAULT NULL,
  `vencimiento` tinyint(4) NOT NULL DEFAULT '0',
  `vencimiento_valor` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `vencimiento_unidad` enum('D','W','M','Y') COLLATE utf8_unicode_ci NOT NULL,
  `vencimiento_habiles` tinyint(4) NOT NULL DEFAULT '0',
  `vencimiento_notificar` tinyint(4) NOT NULL DEFAULT '0',
  `vencimiento_notificar_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `vencimiento_notificar_dias` int(10) unsigned NOT NULL DEFAULT '1',
  `grupos_usuarios` text COLLATE utf8_unicode_ci,
  `paso_confirmacion` tinyint(4) NOT NULL DEFAULT '1',
  `paso_confirmacion_titulo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `paso_confirmacion_contenido` text COLLATE utf8_unicode_ci,
  `paso_confirmacion_texto_boton_final` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `previsualizacion` text COLLATE utf8_unicode_ci,
  `externa` tinyint(4) NOT NULL DEFAULT '0',
  `es_final` tinyint(4) NOT NULL DEFAULT '0',
  `exponer_tramite` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `identificador_proceso` (`identificador`,`proceso_id`),
  KEY `fk_tarea_proceso1` (`proceso_id`),
  CONSTRAINT `tarea_ibfk_1` FOREIGN KEY (`proceso_id`) REFERENCES `proceso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tarea`
--

LOCK TABLES `tarea` WRITE;
/*!40000 ALTER TABLE `tarea` DISABLE KEYS */;
INSERT INTO `tarea` VALUES (1,'box_1',1,'Solicitud de permiso para extracción de áridos',488,40,'autoservicio',NULL,0,1,0,NULL,'grupos_usuarios','si',NULL,NULL,0,'5','D',0,0,NULL,1,'1,2',0,NULL,NULL,NULL,NULL,0,0,NULL),(2,'box_2',0,'Análisis factibilidad técnica DOH',524,207,'manual',NULL,0,1,0,NULL,'grupos_usuarios','si',NULL,NULL,0,'5','D',0,0,NULL,1,'1',0,NULL,NULL,NULL,NULL,0,0,NULL),(3,'box_3',0,'Informe a municipio sobre la no factibilidad técnica de la solicitud',982,338,'ciclica',NULL,0,1,0,NULL,'grupos_usuarios','si',NULL,NULL,0,'5','D',0,0,NULL,1,'2',0,'Solicitud rechazada','Se informa que no existe factibilidad técnica para su solicitud.','Terminar',NULL,0,1,NULL),(4,'box_4',0,'Requiere proyecto',291,338,'ciclica',NULL,0,1,0,NULL,'grupos_usuarios','si',NULL,NULL,0,'5','D',0,0,NULL,1,'1',0,NULL,NULL,NULL,NULL,0,0,NULL),(5,'box_5',0,'Se informa y registra la aprobación de la solicitud',665,434,'ciclica',NULL,0,1,0,NULL,'grupos_usuarios','si',NULL,NULL,0,'5','D',0,0,NULL,1,'2',1,'Su solicitud fue aprobada','Se informa que existe factibilidad técnica.','Terminar',NULL,0,1,NULL),(6,'box_6',0,'Solicitud de ingreso de antecedentes técnicos (proyecto)',102,435,'ciclica',NULL,0,1,0,NULL,'grupos_usuarios','si',NULL,NULL,0,'5','D',0,0,NULL,1,'2',0,NULL,NULL,NULL,NULL,0,0,NULL),(7,'box_8',0,'Análisis de proyecto',199,536,'ciclica',NULL,0,1,0,NULL,'grupos_usuarios','si',NULL,NULL,0,'5','D',0,0,NULL,1,'1',0,NULL,NULL,NULL,NULL,0,0,NULL),(8,'box_9',0,'Informe sobre aprobación de solicitud',0,636,'ciclica',NULL,0,1,0,NULL,'grupos_usuarios','si',NULL,NULL,0,'5','D',0,0,NULL,1,'2',0,NULL,NULL,NULL,NULL,0,1,NULL),(9,'box_7',0,'Asignación de tareas',555,120,'autoservicio',NULL,0,1,0,NULL,'grupos_usuarios','si',NULL,NULL,0,'5','D',0,0,NULL,1,'3',1,'Asignación Finalizada',NULL,'Terminar',NULL,0,0,NULL),(11,'box_1',1,'Tarea1',657,67,'ciclica',NULL,0,3,0,NULL,'grupos_usuarios','si',NULL,NULL,0,'5','D',0,0,NULL,1,'1,',0,NULL,NULL,NULL,NULL,0,0,NULL),(12,'box_2',0,'Tarea2',657,248,'ciclica',NULL,0,3,0,NULL,'grupos_usuarios','si',NULL,NULL,0,'5','D',0,0,NULL,1,'1,',0,NULL,NULL,NULL,NULL,0,1,NULL),(14,'box_10',0,'Informe sobre rechazo de solicitud',336,636,'ciclica',NULL,0,1,0,NULL,'grupos_usuarios','si',NULL,NULL,0,'5','D',0,0,NULL,1,'2',0,NULL,NULL,NULL,NULL,0,1,NULL);
/*!40000 ALTER TABLE `tarea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tarea_has_grupo_usuarios`
--

DROP TABLE IF EXISTS `tarea_has_grupo_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tarea_has_grupo_usuarios` (
  `tarea_id` int(10) unsigned NOT NULL,
  `grupo_usuarios_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`tarea_id`,`grupo_usuarios_id`),
  KEY `fk_tarea_has_grupo_usuarios_grupo_usuarios1` (`grupo_usuarios_id`),
  CONSTRAINT `fk_tarea_has_grupo_usuarios_grupo_usuarios1` FOREIGN KEY (`grupo_usuarios_id`) REFERENCES `grupo_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_tarea_has_grupo_usuarios_tarea1` FOREIGN KEY (`tarea_id`) REFERENCES `tarea` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tarea_has_grupo_usuarios`
--

LOCK TABLES `tarea_has_grupo_usuarios` WRITE;
/*!40000 ALTER TABLE `tarea_has_grupo_usuarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `tarea_has_grupo_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tramite`
--

DROP TABLE IF EXISTS `tramite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tramite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `proceso_id` int(10) unsigned NOT NULL,
  `pendiente` tinyint(4) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `ended_at` datetime DEFAULT NULL,
  `tramite_proc_cont` int(11) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_tramite_proceso1` (`proceso_id`),
  CONSTRAINT `fk_tramite_proceso1` FOREIGN KEY (`proceso_id`) REFERENCES `proceso` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tramite`
--

LOCK TABLES `tramite` WRITE;
/*!40000 ALTER TABLE `tramite` DISABLE KEYS */;
INSERT INTO `tramite` VALUES (62,1,0,'2019-12-09 20:29:07','2019-12-09 20:47:50','2019-12-09 20:47:50',1,NULL),(63,1,1,'2019-12-10 02:35:51','2019-12-10 15:32:36',NULL,1,NULL),(64,1,0,'2019-12-10 02:48:55','2019-12-10 17:51:19','2019-12-10 17:51:19',1,NULL),(65,1,1,'2019-12-10 17:06:20','2019-12-10 18:04:15',NULL,1,NULL),(66,1,1,'2019-12-10 19:18:27','2019-12-10 20:31:45',NULL,1,NULL),(67,1,1,'2019-12-10 20:50:32','2019-12-10 22:43:09',NULL,1,NULL),(68,1,1,'2019-12-10 22:04:32','2019-12-10 22:52:14',NULL,1,NULL);
/*!40000 ALTER TABLE `tramite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rut` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombres` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apellido_paterno` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apellido_materno` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `registrado` tinyint(4) NOT NULL DEFAULT '1',
  `vacaciones` tinyint(4) NOT NULL DEFAULT '0',
  `cuenta_id` int(10) unsigned DEFAULT NULL,
  `salt` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `open_id` tinyint(4) NOT NULL DEFAULT '0',
  `reset_token` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_UNIQUE` (`usuario`,`open_id`),
  KEY `fk_usuario_cuenta1` (`cuenta_id`),
  KEY `email_idx` (`email`,`open_id`),
  KEY `rut_idx` (`rut`),
  CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`cuenta_id`) REFERENCES `cuenta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'5daf454e971b7','$2y$10$0KNgjOhtrka.cPXtIAHXJOV9LkSgg/5qUqDPr/Yc5Mg2oqsrToKRC',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5daf454eaffb4',0,NULL,NULL,'2019-10-22 21:07:10','2019-10-22 21:07:10'),(2,'DOH','$2y$10$gX2xs6T7qhTKKuEXw5xbWOjb2JkFqNZRvN/dAW.BqJdY3v76xfjFy',NULL,'DOH','DOH','DOH','doh@doh.doh',1,0,1,'',0,NULL,'vg0LaTmHTq60dSl1pSA0ulz9xatCGb9VTMxLFvszxA9y4t9KysegPRtPWEVt','2019-10-22 21:08:21','2019-10-22 21:08:21'),(3,'Municipio','$2y$10$5FIMtN.ftCEaP2ZYDrfJk.1gL5LnBhQwRQVtnajsZM9llNblGJul.',NULL,'Municipio','Municipio','Municipio','Municipio@Municipio.Municipio',1,0,1,'',0,NULL,'qatJVAmmMu9N9GuiIqp3esGAlxVF0YpABwWU6MHTsk2xwvYyd3TwdGcjVSnH','2019-10-22 21:08:49','2019-10-22 21:08:49'),(4,'5daf45d7abdcd','$2y$10$A3UjhENDltXYlGyelf0X2.7wyGGASaMrs59YMcZznfJ.RinD7rdE6',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5daf45d7c07f7',0,NULL,NULL,'2019-10-22 21:09:27','2019-10-22 21:09:27'),(5,'5daf464e67858','$2y$10$Gr9RF1dT/tQ9VmshHs7d8et6v9QCq9CZNYl8cGEtthU97pslJgt0y',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5daf464e8086a',0,NULL,NULL,'2019-10-22 21:11:26','2019-10-22 21:11:26'),(6,'5daf487a21e37','$2y$10$agVPw.kC9fpreNPsjHg8mONtuc/VP1E4utF/X0v1EL/vlPZa/tyOG',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5daf487a3997b',0,NULL,NULL,'2019-10-22 21:20:42','2019-10-22 21:20:42'),(7,'5dbf6b60c7de3','$2y$10$8/N/v5vNxCdEJh1.Yqk1N.BrB6Qm7zD9UxXKGy4W6bS7yKvWmEE2e',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dbf6b60da5ca',0,NULL,NULL,'2019-11-04 03:05:52','2019-11-04 03:05:52'),(8,'5dbf6b864bbe1','$2y$10$rTZnxylbTFnQ06NSJFwUEOsGso9rUiHL..ZWeMkUA2jlhTT1/kdOi',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dbf6b865cbc1',0,NULL,NULL,'2019-11-04 03:06:30','2019-11-04 03:06:30'),(9,'5dc0241de1e6d','$2y$10$tnAknYpxm.kjfzrfmOkLWuYoZN9tNmK297nsQHfiZCedfZ3SAZoTm',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc0241df2f6a',0,NULL,NULL,'2019-11-04 16:14:05','2019-11-04 16:14:05'),(10,'5dc0243eccff4','$2y$10$ZzfUOc8kBI2eIJLnWDMfDOf6Xje4nXV4H/gCAqW6x3H2yGAvKOjh.',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc0243ede13b',0,NULL,'kVFmnv4FfASrNgDUO1rzfaxp9NrSPkCFC0GpI1xzdK5UChZ23dBFBI57fNOr','2019-11-04 16:14:38','2019-11-04 16:14:38'),(11,'5dc0243ef057b','$2y$10$PzzAdTEeiqwKffTGaHX5Oeon7cCn8Ex1TWS3pIQfuKmshO.ZCSLEO',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc0243f0fc1c',0,NULL,NULL,'2019-11-04 16:14:39','2019-11-04 16:14:39'),(12,'5dc0371d7283f','$2y$10$Gz2rZLMrxU12D7bqF8.Mt.c8LLREfWJVG7lL8QtMwtOxuQh03s4le',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc0371d83606',0,NULL,NULL,'2019-11-04 17:35:09','2019-11-04 17:35:09'),(13,'5dc177de5aa62','$2y$10$pOIhrB2hxG4pqF9wSytjfOw.LvB1cPYsQspuS8dgQs2KIKXJzJnRm',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc177de6e299',0,NULL,NULL,'2019-11-05 16:23:42','2019-11-05 16:23:42'),(14,'5dc17826d102d','$2y$10$UEehkTv8krcwBxrI/j.oueZ6fF8sM.bJQDUxucQPGREiR7kRVc88q',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc17826e2b1e',0,NULL,NULL,'2019-11-05 16:24:54','2019-11-05 16:24:54'),(15,'5dc1b47621e69','$2y$10$K6LbzNvgQGS0OdTG5yLM.uBF0rkKWe.cfJ3h4dJXB7ruRGtCP4c4O',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc1b4763569c',0,NULL,NULL,'2019-11-05 20:42:14','2019-11-05 20:42:14'),(16,'5dc1b4783def5','$2y$10$3c4RmJc2T7lX8lrf1uVmme6p8ayxWH8CmZaV.8QGgS1dYqYduNOLK',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc1b478508ae',0,NULL,NULL,'2019-11-05 20:42:16','2019-11-05 20:42:16'),(17,'5dc2158f79f5b','$2y$10$clxSw9PvAs/dyH21sEhuOu80wHLELUrZuWjGcPWWUnqOD6wNxG5t.',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc2158f8db7a',0,NULL,NULL,'2019-11-06 03:36:31','2019-11-06 03:36:31'),(18,'5dc21590cbd4d','$2y$10$ZepwYcZpn4m/ugxPbsB5GejimQ/gzMus9Tk8y.9L6VYCiPyVovGmm',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc21590e4a06',0,NULL,NULL,'2019-11-06 03:36:32','2019-11-06 03:36:32'),(19,'5dc21a49c4351','$2y$10$YfERj5UxTwl1h3/2fNA1.ez1lk9ymwvp73KyZkTrIZnoASNlsqWku',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc21a49d6890',0,NULL,NULL,'2019-11-06 03:56:41','2019-11-06 03:56:41'),(20,'5dc2294092d7c','$2y$10$FlHe880jZheR4l8ibisFxuRBGoA99sb.Z4oDeLHm/CB9Ttp7kg.Qm',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc22940a358d',0,NULL,NULL,'2019-11-06 05:00:32','2019-11-06 05:00:32'),(21,'5dc2294ed7a74','$2y$10$BIfbVxAsHALV7PfITVWW3eYwMMpe5iBHxhuyYTQXIA4Ho8DoBiHcq',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc2294ef0867',0,NULL,NULL,'2019-11-06 05:00:46','2019-11-06 05:00:46'),(22,'5dc24072a99b1','$2y$10$wjmWawcd/.QAjsmMgXh/wurukyVi79jGQ8pv.qNC5IQUiDXCrudWW',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc24072b9554',0,NULL,NULL,'2019-11-06 06:39:30','2019-11-06 06:39:30'),(23,'5dc2b3d936ba8','$2y$10$X7DESG9KUCLJCnWL1o2NrOnAHX5eFiuSVKZseOZHXRIC6lxVXU1me',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc2b3d946607',0,NULL,NULL,'2019-11-06 14:51:53','2019-11-06 14:51:53'),(24,'5dc2b5c0696bf','$2y$10$0sFYgcZvtVrqSv2sLJVW/Otsf8In3eDTEqQlb3zknTgCGlIC2yNuW',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc2b5c079461',0,NULL,NULL,'2019-11-06 15:00:00','2019-11-06 15:00:00'),(25,'5dc2b5d0a00eb','$2y$10$gYoxrXADTBbyevQbciJr2.kxHKGza6g9/4HrMwk90dBLduAB.P866',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc2b5d0b0c64',0,NULL,NULL,'2019-11-06 15:00:16','2019-11-06 15:00:16'),(26,'5dc38035c0102','$2y$10$bja9VDxgmn8o1cCqbTbdOOTF0KuDC1WM9FV0bgh1F5Io6Vg1PL706',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc38035cfecf',0,NULL,NULL,'2019-11-07 05:23:49','2019-11-07 05:23:49'),(27,'5dc382c1255d3','$2y$10$sCl9ItL92.lYCcd2J2aQb.w0dbwGznWiFKSCQftVxL26BJ/SzMdcG',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc382c134db6',0,NULL,NULL,'2019-11-07 05:34:41','2019-11-07 05:34:41'),(28,'5dc82e7207742','$2y$10$1L1zq7jiTIkm.7qAAdIkp.xDGjpLJ1.EJcvKbJwedIcpR2WOfmsoe',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc82e7217fcf',0,NULL,NULL,'2019-11-10 18:36:18','2019-11-10 18:36:18'),(29,'5dc8cc4c92374','$2y$10$zVJ05MZO8WTXUUY6QDFHaeoC11hHVnYP82mq1pYrVlkluC8fqZqbu',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc8cc4ca20df',0,NULL,NULL,'2019-11-11 05:49:48','2019-11-11 05:49:48'),(30,'5dc8cc610bec5','$2y$10$RHbRcsJSxW087sddK8HdnedxIw7uzzcKNsqz33N17hoayXbnCM3Na',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc8cc611cabc',0,NULL,NULL,'2019-11-11 05:50:09','2019-11-11 05:50:09'),(31,'5dc9c3b7c7521','$2y$10$3gBUwhPgmFpjHCXYrd/DmuZnnwjA/I4rmsOXvDZrzC2wJASkRBaiS',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dc9c3b7d7293',0,NULL,NULL,'2019-11-11 23:25:27','2019-11-11 23:25:27'),(32,'5dca0c443ec53','$2y$10$qHgslVVwIMaIWV.szPl2Wu1Ya24o7HsoFkv4ZpGrQKOXPUkLbQGDu',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dca0c444e088',0,NULL,NULL,'2019-11-12 04:35:00','2019-11-12 04:35:00'),(33,'5dcb1567769e0','$2y$10$2rdeVv6djuQR.VKjT4FgKOs5B2N4eVK/rE888PDWzPfF9Qf/A0qtm',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dcb156786654',0,NULL,NULL,'2019-11-12 23:26:15','2019-11-12 23:26:15'),(34,'5dcb1f7a1a06d','$2y$10$mJ8Q9VzE.PxmEApEXIl78ed6/Fzr2bIz71HAuq7fB6tQpjIp7unAq',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dcb1f7a30258',0,NULL,NULL,'2019-11-13 00:09:14','2019-11-13 00:09:14'),(35,'Asignador','$2y$10$ReW1kYOaQW1S6u/EhrVcJeAUVH0MnbFAehxj26mKgogij9yiL9nSe',NULL,'Asignador','de Tareas','Castro','asignador@asignador.asignador',1,0,1,'',0,NULL,'qoArRtCYFGfU9Jq4Jl4nb7ZT7KRbAHyX8hqgRO8lAJ8U803ut3y4qxKnVfkK','2019-11-13 03:13:18','2019-11-13 03:13:18'),(36,'5dcb4c55b5d7a','$2y$10$d22W92Ikb5XSd9jSv45UYuzslzrNIHIghCIZjr76HzBp2EdBNm2Jm',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dcb4c55c5b7c',0,NULL,NULL,'2019-11-13 03:20:37','2019-11-13 03:20:37'),(37,'5dcb5583df09b','$2y$10$vaNVWH335lYzbytMjwKRWuKilHavpcX1drtHuMxb4kGidc2XYkc8i',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dcb5583f0b49',0,NULL,NULL,'2019-11-13 03:59:47','2019-11-13 03:59:47'),(38,'5dcb558b7503c','$2y$10$GmQMfP.I1APZ2Y3tQbN.p.XTp08wHSXpg22.17nvb1ccpng86M5fy',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dcb558b86c5e',0,NULL,NULL,'2019-11-13 03:59:55','2019-11-13 03:59:55'),(39,'5dcb5811712b8','$2y$10$d9/rgwmPWp6mpJ6jhyv61uRvL2ZgYZCZhYjO/qtkmZL3WoEMbbQVW',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dcb58118e294',0,NULL,NULL,'2019-11-13 04:10:41','2019-11-13 04:10:41'),(40,'5dceb36a105ca','$2y$10$hTUNeGitpCvfeUXrKs2cyucWP1T70MnDgj5SgG7or5R/J5KeW.igS',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dceb36a210d0',0,NULL,NULL,'2019-11-15 17:17:14','2019-11-15 17:17:14'),(41,'5dceb3744129e','$2y$10$tM8otLCmEDn5MkQYFtmOJuoGi7smchpHPpeZ1vifbfhl/H1skFCui',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dceb3745288b',0,NULL,'XstBrfSNIqFLdFbIfFOjlFILZ3ztHVA9GEnOX933cuvLUmt4FiVlORwccBnY','2019-11-15 17:17:24','2019-11-15 17:17:24'),(42,'5dceb37464886','$2y$10$yohOHxS5vxXBtMu/2WnD3.lJjkHLgk8svyLMeL5dYfxc6JgDcKTMK',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dceb3747b744',0,NULL,NULL,'2019-11-15 17:17:24','2019-11-15 17:17:24'),(43,'5dcebe963825b','$2y$10$nyYeaSiKP6z3ICUTb0FqIusLwd/vCYIpUPm1mn0BYhSuX9MHADWAa',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dcebe9649702',0,NULL,NULL,'2019-11-15 18:04:54','2019-11-15 18:04:54'),(44,'5dcedc386bfb0','$2y$10$iweGsaNZC/APdtx7YM7q0OLP0MBtYXXoTEKOF2D2ZVI1tyb.7fmo6',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dcedc387e5da',0,NULL,NULL,'2019-11-15 20:11:20','2019-11-15 20:11:20'),(45,'5dcedc3a5bed7','$2y$10$2eBsCRcvaPU/zq3P5pIzFupvPagM68zUVUqrtKeTtDeKGTtZNlEw.',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dcedc3a6de26',0,NULL,NULL,'2019-11-15 20:11:22','2019-11-15 20:11:22'),(46,'5dd59ec8a2ff7','$2y$10$qSHKGhdDe4flWjKk12ymweyVPzyc3SdO3HeOGuGJZJkyMklw7Q.mu',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dd59ec8b53a7',0,NULL,NULL,'2019-11-20 23:15:04','2019-11-20 23:15:04'),(47,'5dd59f0c5f07d','$2y$10$IlBLgtz1vWXM0CwrNy0DOeVwKEyMyBFjWUEwGNGBwjfUMljYfs8x.',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dd59f0c704ad',0,NULL,NULL,'2019-11-20 23:16:12','2019-11-20 23:16:12'),(48,'5dd59f0e2215a','$2y$10$ObPBuvfWzq9PAXoX3HmCSePG6QKotoBJPo/h6Aih7wfRnoe9J8RCW',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dd59f0e3960e',0,NULL,NULL,'2019-11-20 23:16:14','2019-11-20 23:16:14'),(49,'5dd691a00a61d','$2y$10$iwPThcZb7YbIBUM6IFVtLOE2cmay3T2qMC/qtRp/jnZ6kdom4CQp6',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dd691a019fc1',0,NULL,NULL,'2019-11-21 16:31:12','2019-11-21 16:31:12'),(50,'5dd6921b8fdfe','$2y$10$UoU4ffwypQxXC66p4xHJyOLczfDmSBqbVWgYWvFGNNY0mjAucfpl.',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dd6921b9f472',0,NULL,NULL,'2019-11-21 16:33:15','2019-11-21 16:33:15'),(51,'5dd6922363bbd','$2y$10$x88gZzB23OtIA5cSac9CS.KFXdwiIp9KEXXvuqksr8xSfCkgjVU3y',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dd692237930e',0,NULL,NULL,'2019-11-21 16:33:23','2019-11-21 16:33:23'),(52,'5dd7309366917','$2y$10$QbpU.PlmDvRshWA0.YDd6..zT5b7cB8U9Q0AUOr09.bVVfhoYidH2',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dd7309377547',0,NULL,NULL,'2019-11-22 03:49:23','2019-11-22 03:49:23'),(53,'5dda082ab115b','$2y$10$5VWFvql0dqn.zpyInnfde.sgcklCuxFeRrgPMK3Fb15zai85TRV0q',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dda082ac0612',0,NULL,NULL,'2019-11-24 07:33:46','2019-11-24 07:33:46'),(54,'5ddd2b390e0ba','$2y$10$NJBDqdBDZbI3hROEuw0NzOCfTIF4auRCvklFqc3uMV1Lyneyc56a6',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5ddd2b391d212',0,NULL,NULL,'2019-11-26 16:40:09','2019-11-26 16:40:09'),(55,'5ddd49c73f041','$2y$10$1iDid9jMBjj4lqrmdWMkpeu9jOH/osfhMKt6i8TppKmIoSD8hKExK',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5ddd49c74f9b2',0,NULL,NULL,'2019-11-26 18:50:31','2019-11-26 18:50:31'),(56,'5ddd6b8eb6652','$2y$10$g1eowTFnXZbLfvEcKODAgOZ64M4I8uNvKji/fSEtWI.CW5lUuJFT6',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5ddd6b8ec814e',0,NULL,NULL,'2019-11-26 21:14:38','2019-11-26 21:14:38'),(57,'5de1615059db5','$2y$10$NT16mz4FaLqX4OHzix/47OzqaWaAZPOgVtXST2ukNT7wIWEB3jt/C',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5de1615069743',0,NULL,NULL,'2019-11-29 21:20:00','2019-11-29 21:20:00'),(58,'5de16bcd84d49','$2y$10$VKGiC58hbm9Yjefnh061COH5q1mv3okDW2ijrSIlimbZ/Y6lBxGU6',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5de16bcd941c1',0,NULL,NULL,'2019-11-29 22:04:45','2019-11-29 22:04:45'),(59,'5de526eb00c1d','$2y$10$Hd6k3A.Y/WxW7H3YDIwWQu1bbWkRXIOEpU7XtXgRH9Bx/P5piIx4y',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5de526eb10dbc',0,NULL,NULL,'2019-12-02 17:59:55','2019-12-02 17:59:55'),(60,'5de57b566feaf','$2y$10$MN5WASeVIM0NR/hnB0tcSe/3XtDB0C0wQL5jE1cfe8ThvNKPqgCUq',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5de57b567f32b',0,NULL,NULL,'2019-12-03 00:00:06','2019-12-03 00:00:06'),(61,'5de68878121a0','$2y$10$bQ.5g4d1QD4/ozVFYcqOeeee5DXN7g/Ma/eherJNN10dqML8Cw/AG',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5de68878214c0',0,NULL,NULL,'2019-12-03 19:08:24','2019-12-03 19:08:24'),(62,'5de6a4ab512c9','$2y$10$hYtgh9nEk9lnvsxi.hZv8OSzaplTg2TVSA5xk.Lpme8gv9FAT4zo6',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5de6a4ab60328',0,NULL,NULL,'2019-12-03 21:08:43','2019-12-03 21:08:43'),(63,'5de6da0956c59','$2y$10$WFfrwmQlAHidCvyOm1u7Q.2dXay7Ks21qi3xIhPXzr9jGJRbD3hY.',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5de6da0966a2f',0,NULL,NULL,'2019-12-04 00:56:25','2019-12-04 00:56:25'),(64,'5de71f4621f00','$2y$10$2A3YKtVnmp/xKu6.KcSwn.otlIErk5d.zyyp/SLRqHpY6HmCY6Wc.',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5de71f463162a',0,NULL,NULL,'2019-12-04 05:51:50','2019-12-04 05:51:50'),(65,'5de946d38ee49','$2y$10$azZH1Y0LVsjqqlfs3xbfFOcOZKtQlW08P.WuR.KAdsr3.TvCm7cH.',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5de946d39db4a',0,NULL,NULL,'2019-12-05 21:05:07','2019-12-05 21:05:07'),(66,'5de947ba02440','$2y$10$qWCUxw44pJWCQjIyylDflOvDS3.pJD964VxwCBODQkrSM16kq89re',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5de947ba13e02',0,NULL,NULL,'2019-12-05 21:08:58','2019-12-05 21:08:58'),(67,'5de95b1918791','$2y$10$AIXhpqKFt88ifhQWIu/8gOxlKXUsSXDnqhMgAGmtVIkcjTkGNvwLW',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5de95b192803f',0,NULL,NULL,'2019-12-05 22:31:37','2019-12-05 22:31:37'),(68,'5de99abb7da9c','$2y$10$AMAS8rRLUbkhEv93.BmBqeNWkj2eP1FMCirOidkwbGvxIJWrp//Zi',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5de99abb8d1e5',0,NULL,NULL,'2019-12-06 03:03:07','2019-12-06 03:03:07'),(69,'5de9a1c7d42ad','$2y$10$u5zqdJMe8ETcKHzMHHk7zeNUlKCox/PHnzDxCgxz22JHPXIoyRDWK',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5de9a1c7e3705',0,NULL,NULL,'2019-12-06 03:33:11','2019-12-06 03:33:11'),(70,'5dea6a7fdc526','$2y$10$eWOJ9Pg1NJlQNjyQ47xHgeH1ySsgsFBBckOgSeHSPfs7COJOShwvK',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dea6a7fee159',0,NULL,NULL,'2019-12-06 17:49:35','2019-12-06 17:49:35'),(71,'5dea76e6d19d1','$2y$10$/wjNE.0/GjQINn8mhl2oUu3EmkeD2TW7Ezus6HvmJ81MzIH/qILBu',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dea76e6e1c97',0,NULL,NULL,'2019-12-06 18:42:30','2019-12-06 18:42:30'),(72,'5deab8d7c89bb','$2y$10$/y7ol0ldFmc6ygepNLlcFO.tnNxaxOIkV48w8u4SyxzppFLrKLr36',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deab8d7dd46b',0,NULL,NULL,'2019-12-06 23:23:51','2019-12-06 23:23:51'),(73,'5deb2980d91c9','$2y$10$E3jdNaOQ.wBwqut4kto6JeE.8O8B6dJ/e7OisZ7HnRZ1ejyglt4Ly',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deb2980e8361',0,NULL,NULL,'2019-12-07 07:24:32','2019-12-07 07:24:32'),(74,'5debd4289d11f','$2y$10$5Iyiy1IZ.DpZzEX5uAwAv.IjQC7zhpuZq08JjzVtKJX5gHqOTJcDG',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5debd428acd0b',0,NULL,NULL,'2019-12-07 19:32:40','2019-12-07 19:32:40'),(75,'5debd44026085','$2y$10$dXvnA1xi.lpp4fZqMigkgeicz3rMBUZMdKw4wuVNUGBSjurpWdRf.',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5debd44036780',0,NULL,NULL,'2019-12-07 19:33:04','2019-12-07 19:33:04'),(76,'5dec0761373e3','$2y$10$YIV6bnP47GHWNoMSMMalw.RDijp9E68pe8sUQ.Xck/WqZ1QlEjOmK',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dec0761465f7',0,NULL,NULL,'2019-12-07 20:11:13','2019-12-07 20:11:13'),(77,'5dec335f7cfa3','$2y$10$zdRLbEMeD0fQHgMcieAnQOuXox5FlZfkE.ydnBtX.yu1hxiyl25Xy',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dec335f8c2b5',0,NULL,NULL,'2019-12-07 23:18:55','2019-12-07 23:18:55'),(78,'5dec506aede87','$2y$10$ZFmvHb3q3ht2B9F.D36Ka.i0zBaNIBSPuE3PEEL2wTPETAPP.rGpe',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dec506b08f60',0,NULL,NULL,'2019-12-08 01:22:51','2019-12-08 01:22:51'),(79,'5dec8519a7402','$2y$10$YrGwUP.IMe525AgE/aIQ7.NgIV1rgQXLPPxHR25VaBlbjW13V.Xrm',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dec8519b66d5',0,NULL,NULL,'2019-12-08 05:07:37','2019-12-08 05:07:37'),(80,'5ded674a8ddd2','$2y$10$CZATM9OWpB9ciO/YyMuqOeywt/HbEFF7PKrS/Ibw74C37NiKa6GTK',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5ded674a9d16e',0,NULL,NULL,'2019-12-08 21:12:42','2019-12-08 21:12:42'),(81,'5ded9f6a62cdd','$2y$10$ChH1Nzm27YXPtU7C/MjvVOHdPRYjSgHB5asaxnXH/Wkk4fLu/D.s.',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5ded9f6a72088',0,NULL,NULL,'2019-12-09 01:12:10','2019-12-09 01:12:10'),(82,'5dedbf729e0b9','$2y$10$kaNGW7nB.oYvVkhQev1ZGOU0zmRuh0JoeUrk7mxhewBjReadzoOwa',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dedbf72ad44d',0,NULL,NULL,'2019-12-09 03:28:50','2019-12-09 03:28:50'),(83,'5dedc1ccb1476','$2y$10$WiVcAxOKNhQSBFkS9O0mm.mRr5514ha7qrFTFih722ZgMwFxRjpyO',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dedc1ccc0847',0,NULL,NULL,'2019-12-09 03:38:52','2019-12-09 03:38:52'),(84,'5dedd47f423fc','$2y$10$Es7wtAAprf6MnkDH7iDAF.gyI.s5IDQnTehr6jJttqR4oReyjEaZW',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dedd47f516d3',0,NULL,NULL,'2019-12-09 04:58:39','2019-12-09 04:58:39'),(85,'5deddaa831d88','$2y$10$ksn3Od5.qvWt2gm5fXnR/uybFUprT4Zl3snFWn58sUS0KVav.Kaze',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deddaa84112f',0,NULL,NULL,'2019-12-09 05:24:56','2019-12-09 05:24:56'),(88,'5dee374aa9427','$2y$10$cFmsMVAPb.byj0kFCDkXR.lks87KUdObGMjeKZ7FQcNgGuZlqpMXq',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dee374ab8e6f',0,NULL,NULL,'2019-12-09 12:00:10','2019-12-09 12:00:10'),(89,'5dee53771d8a4','$2y$10$fG7PolDGyrqOxON37xv.VudWGxhrpWtC.zA8yB/Gq/qHW3J3KS556',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dee53772d439',0,NULL,NULL,'2019-12-09 14:00:23','2019-12-09 14:00:23'),(90,'5dee537e38308','$2y$10$SA1FACgbAqGCMmFjDrdeB.DoVs.ce7PgU984QAaTSMLdYT96/do36',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dee537e47849',0,NULL,NULL,'2019-12-09 14:00:30','2019-12-09 14:00:30'),(91,'5dee60f6ea244','$2y$10$rThLCWU..Cy8SaGXA/s/LeyGNFGN3bBBQdatOe/2J.SmuuMUbHF5y',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dee60f70541c',0,NULL,NULL,'2019-12-09 14:57:59','2019-12-09 14:57:59'),(92,'5dee613ecb572','$2y$10$MKfwSUXrNRT2wVfOst6N8.xbNCJj3yaF0JHway0jiQJJ47ApC6Te2',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dee613edab69',0,NULL,NULL,'2019-12-09 14:59:10','2019-12-09 14:59:10'),(93,'5dee62f92b1d8','$2y$10$H9/d2o9dUVDoUUvqnt5tIe.TCFaalMXO8GvzqIViSTjlAOGpg7iFm',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dee62f93a775',0,NULL,NULL,'2019-12-09 15:06:33','2019-12-09 15:06:33'),(94,'5dee6321b274b','$2y$10$wN51r8GktTovSXXdOOAhrubxsKuAu5rQOZsI8rfj0bl.Z0L56JraG',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dee6321c1a5a',0,NULL,NULL,'2019-12-09 15:07:13','2019-12-09 15:07:13'),(95,'5dee7d693f4e4','$2y$10$OK7JxQt6cqeTXlnxlyBv4.5yq.xa47USCGUnSvp9bU.BMalvd0is2',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dee7d694e8ae',0,NULL,NULL,'2019-12-09 16:59:21','2019-12-09 16:59:21'),(96,'5dee826f825e6','$2y$10$g0nflmoeQxs1rG9TkCcC6.8Av1F0IUOz2jnVZ9swlZk4b5g8S3dZa',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dee826f918fa',0,NULL,NULL,'2019-12-09 17:20:47','2019-12-09 17:20:47'),(97,'5dee8ca55c8e7','$2y$10$x99XqqEsWvdoAaoStZIO2emq.VOjj3cBq0aCoL67dEvEKqaAKEVc2',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dee8ca56c399',0,NULL,NULL,'2019-12-09 18:04:21','2019-12-09 18:04:21'),(98,'Analista DOH-DGA','$2y$10$lqsJeK6lizh/e2wUE4nvvOoENoF5Xjb5jcKZTPO19dQ5Md/GsXzqq',NULL,'Juan','Perez','Cotapo','analista@analista.analista',1,0,1,'',0,NULL,'CypRwvMJhMxUh2o56pK70aHs6h6rNP4Vc8ZtL9NzKOzW3KdKuhWCACemEnmP','2019-12-09 18:25:16','2019-12-09 18:25:16'),(99,'5dee96330424f','$2y$10$4pngZ6v6tA9Vss8kJdEG7.LvNVwRJZI6QMVqkpzSano5EnILIau5S',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dee9633137af',0,NULL,NULL,'2019-12-09 18:45:07','2019-12-09 18:45:07'),(100,'5dee997b633f8','$2y$10$aduAx41PoKGpYqPw6jE/WOg9R6XJqzx/RlR0yTfuyACzeFHVrWQx2',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5dee997b725f8',0,NULL,NULL,'2019-12-09 18:59:07','2019-12-09 18:59:07'),(101,'5deea0766ec21','$2y$10$CwMEef1Rvkwah7SnSYHtJu74cs6c0l83PYWX23Tva5XrJlB/GJT6S',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deea0767e953',0,NULL,NULL,'2019-12-09 19:28:54','2019-12-09 19:28:54'),(102,'5deea0fe3e2f3','$2y$10$6ZMOaWmj5DIHYnRn3dLOPe67BkO/.awWIbD4O25y/zmsMfiv9CDJW',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deea0fe4d709',0,NULL,NULL,'2019-12-09 19:31:10','2019-12-09 19:31:10'),(103,'5deea34365226','$2y$10$aAMaJCMM.eAQAf27kFiZneiZWp0Oy5oN2rnFe.MidlJPcZlEuauXq',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deea34374ada',0,NULL,NULL,'2019-12-09 19:40:51','2019-12-09 19:40:51'),(104,'5deea3e682f0a','$2y$10$w1s8JI/9c1YKZWmI3G8GyuEeVTd3YdyAgegXLlJ4criyYpiU3CFXq',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deea3e6929a5',0,NULL,NULL,'2019-12-09 19:43:34','2019-12-09 19:43:34'),(105,'5deea51033f29','$2y$10$8GPGmXUf2c5O4/PLwXccUOFMSjLFvAUxmAnDlZy7GRD9uXHd4fqKi',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deea51043194',0,NULL,NULL,'2019-12-09 19:48:32','2019-12-09 19:48:32'),(106,'5deea5ff6faaf','$2y$10$DWOpDWCct/6WjReMQIR9CuJZ9lADV/JHQTaeDaIyQi/J4yhKUM0xC',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deea5ff7f2d3',0,NULL,NULL,'2019-12-09 19:52:31','2019-12-09 19:52:31'),(107,'5deea8683dfff','$2y$10$pZ.uj2Sc1LX0tuynqRSPS.Q0IthY6IvdF1AcAsYTgBvGH03.CUfVa',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deea8684d25b',0,NULL,NULL,'2019-12-09 20:02:48','2019-12-09 20:02:48'),(108,'5deea95f2e64d','$2y$10$gX..l1w7GI1dyFpMOjmU.eZB5a8l9aEGgVGYv0554B.0FlzMfwbQS',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deea95f3d9d1',0,NULL,NULL,'2019-12-09 20:06:55','2019-12-09 20:06:55'),(109,'5deea9da1c8b1','$2y$10$niaU1g1VAjB9EwpC9Sc.jesDoM5iBU2Rd1x1KRGIrUGDMpR8PRUhu',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deea9da2bbbf',0,NULL,NULL,'2019-12-09 20:08:58','2019-12-09 20:08:58'),(110,'5deea9e580e60','$2y$10$qq3128i.n8kF302Jf/Wf1eTRY1DjuFQFS3YmmV7P/YfxH3JsiM7oq',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deea9e5901cc',0,NULL,NULL,'2019-12-09 20:09:09','2019-12-09 20:09:09'),(111,'5deeaa4b2089b','$2y$10$3QAHG.GqlY4EYy99qY5t8e0dcsOWJyM/OwXnfp95LMvgLxDo1NIZG',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeaa4b3006a',0,NULL,NULL,'2019-12-09 20:10:51','2019-12-09 20:10:51'),(112,'5deeab1c64cdd','$2y$10$BX4DRNVl0ooorGt6DZMm4.HjAuFqjWJ9uqTie9POPFi0f2GL.MT0i',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeab1c740ba',0,NULL,NULL,'2019-12-09 20:14:20','2019-12-09 20:14:20'),(113,'5deeaf38c182d','$2y$10$T8B5LXJlZaz0vxjWxvEFluys57BjqBUFodhN.Lic5LGYJW10xhiOi',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeaf38d0ad8',0,NULL,NULL,'2019-12-09 20:31:52','2019-12-09 20:31:52'),(114,'5deeaf692d2c4','$2y$10$VLohByg6I1FYyncEPRkA8eJ6Ukqhbe4kqrEfY0BxjXM4W2e/3jF9W',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeaf693c620',0,NULL,NULL,'2019-12-09 20:32:41','2019-12-09 20:32:41'),(115,'5deeb2428c240','$2y$10$qQZprWC5glj0jinj/1IO8O0uA7aPzoh3xoLuhaFi2iz8//GzbtZDK',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeb2429b5ac',0,NULL,NULL,'2019-12-09 20:44:50','2019-12-09 20:44:50'),(116,'5deeb27b56924','$2y$10$3WnVvbWoU06eZMomqT.XYOLkT9QZivn96ihrakSRJZgTLIj2ZeKTS',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeb27b66953',0,NULL,NULL,'2019-12-09 20:45:47','2019-12-09 20:45:47'),(117,'5deeb2b7bbf48','$2y$10$A3yk4AKvsFOyLM3Z9vEXveHhqkpga3sOa/KpmJ0cDmxOyhhYZphvy',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeb2b7cc7a4',0,NULL,NULL,'2019-12-09 20:46:47','2019-12-09 20:46:47'),(118,'5deeb2d25a416','$2y$10$igsg4ShX9jGtvv5lj3OwUOSWQUctolFMmaRLXUxZ4W4JMyDlVS7CS',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeb2d26a9c3',0,NULL,NULL,'2019-12-09 20:47:14','2019-12-09 20:47:14'),(119,'5deeb2d32e235','$2y$10$aqby6gO7aEySPTwjrIcgaeGwdb99yO8SU1JFT7cPDeQ.xWxjeMIoW',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeb2d33eed0',0,NULL,NULL,'2019-12-09 20:47:15','2019-12-09 20:47:15'),(120,'5deeb2ec84450','$2y$10$fn/nq0SeMMEBEaXq5LD2zOEC6LBPLTmWMe/oEJ9TsKh1gsH8wbWV6',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeb2ec94b14',0,NULL,NULL,'2019-12-09 20:47:40','2019-12-09 20:47:40'),(121,'5deeb360838ba','$2y$10$VA/q6fPRaitdwu2XbGEN.ePjUcRaDjvD4PnTU2gqmKK/eFSefwXLm',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeb36092d6f',0,NULL,NULL,'2019-12-09 20:49:36','2019-12-09 20:49:36'),(122,'5deeb9d266ace','$2y$10$RYhMXAca8CoHBPAgbHz2PelpJdbCYevWouSAU/7CNWDIcqbLp2YXu',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeb9d27679c',0,NULL,NULL,'2019-12-09 21:17:06','2019-12-09 21:17:06'),(123,'5deeb9d2ae927','$2y$10$677teuJAA.kkhDfui4vZjOBk7esbl40yKB5aRmi7ehqDg6fT3gL5i',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeb9d2be768',0,NULL,NULL,'2019-12-09 21:17:06','2019-12-09 21:17:06'),(124,'5deeb9d2bdf3a','$2y$10$Jyusi/.VXouK1kgK/ttgwuWAtZvpZNvjisuWJbKg9Ajk3Ayb1JmcC',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeb9d2cdf25',0,NULL,NULL,'2019-12-09 21:17:06','2019-12-09 21:17:06'),(125,'5deeb9d2c00ef','$2y$10$aUCEgujLuWPGhmdIT.kHi.nPpoR8qXjF0l1dXV8H7Iywk5aIX1j4C',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeb9d2cfffa',0,NULL,NULL,'2019-12-09 21:17:06','2019-12-09 21:17:06'),(126,'5deec78773475','$2y$10$rePPx8bobX.Jxcnwq80waOqq.wO1zUO7ADADX77oXIPKyCe6Cs9Me',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deec78782856',0,NULL,NULL,'2019-12-09 22:15:35','2019-12-09 22:15:35'),(127,'5deeecd25b95e','$2y$10$1ZuG9taqqtbafsV.3CUWgeMgL1P2re9zREGTOMzW3reuI5KRbpthG',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeecd26c7f0',0,NULL,NULL,'2019-12-10 00:54:42','2019-12-10 00:54:42'),(128,'5deeecf733fe2','$2y$10$bSEUBCBdNEFgPf/YSdOAuuCsy756dZEGHvHDorsovPXP.KwWCU336',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeecf743598',0,NULL,NULL,'2019-12-10 00:55:19','2019-12-10 00:55:19'),(129,'5deef4d04f936','$2y$10$h7Qv.Ro1vCURVwCLlJ.7cO7o0nmVyW.MU8wCHolJqBwQu/sI/GNYC',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deef4d05edf3',0,NULL,NULL,'2019-12-10 01:28:48','2019-12-10 01:28:48'),(130,'5deef7da9d332','$2y$10$O9jQBslHJwT0GVEhf7djuOgNYxwX4YT1PYfbYAhNuNFAeGWa5X4Ba',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deef7daaca13',0,NULL,NULL,'2019-12-10 01:41:46','2019-12-10 01:41:46'),(131,'5deef8488bbc6','$2y$10$ws1K9TY431CIInO77TFvYeVWKgrXe6VkVHM6iB08vX8IlUBNfbcWS',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deef8489b5c5',0,NULL,NULL,'2019-12-10 01:43:36','2019-12-10 01:43:36'),(132,'5deefe11af2af','$2y$10$g/6Ziu2LuhSPgyqcCImBAORAVSf9U/7oc2UJYcdKa/ujmNEnpkV..',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deefe11be6d7',0,NULL,NULL,'2019-12-10 02:08:17','2019-12-10 02:08:17'),(133,'5deeff4e392cf','$2y$10$X5ReoEsBCc3w7W6VHV888.38pcs2KoZYesPndz/b2QLoTnHsNxhkm',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deeff4e48e84',0,NULL,NULL,'2019-12-10 02:13:34','2019-12-10 02:13:34'),(134,'5def046836006','$2y$10$MquU7nOZ11GW2y2wq3s3uue1F1opM1yI5sydSbs.DqYEqQ9YaB/uC',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5def0468454ad',0,NULL,NULL,'2019-12-10 02:35:20','2019-12-10 02:35:20'),(135,'5def05698e87e','$2y$10$UBNthcvtWWtn6z075YjX5esVaA/bhwVj770ncjgaCP2gcZzNJZj..',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5def05699daf8',0,NULL,NULL,'2019-12-10 02:39:37','2019-12-10 02:39:37'),(136,'5def058403a6b','$2y$10$qqQJBdWvMA19gX.OTk209.HLACC1Qmx7rFbXUhomzlehJuqS9Pnn6',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5def058412e33',0,NULL,NULL,'2019-12-10 02:40:04','2019-12-10 02:40:04'),(137,'5def06da1b343','$2y$10$aBE9TpgyTjKOKT4UCiQmWeoELcdUEX0BeHhNp4eLGZgjibGqJbz0K',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5def06da2a91f',0,NULL,NULL,'2019-12-10 02:45:46','2019-12-10 02:45:46'),(138,'5def0787afa69','$2y$10$jen8nRrpBUsnLVOSiTDncusDwWQlhGddD8EOEICbFkZCPzpCs/.U2',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5def0787bee2f',0,NULL,NULL,'2019-12-10 02:48:39','2019-12-10 02:48:39'),(139,'5def07e778588','$2y$10$Bb9DwYJFA72jY8tax1F5YuCCvPDH.5aPV5uyaA23vwv.jT0DhMnl2',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5def07e787994',0,NULL,NULL,'2019-12-10 02:50:15','2019-12-10 02:50:15'),(140,'5def080411bdd','$2y$10$1fsbtqg5FuO07sZat9C5HOUxrCYVTVIdBNsnugOAhJ3kmTp6aWzz2',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5def080420e92',0,NULL,NULL,'2019-12-10 02:50:44','2019-12-10 02:50:44'),(141,'5def8e1e8758e','$2y$10$yk29KafkTiI0Foei60MXa.ZE1bqPfnnVO8xGP.4/FRbTxoUTsS4mK',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5def8e1e9682f',0,NULL,NULL,'2019-12-10 12:22:54','2019-12-10 12:22:54'),(142,'5def90a46cb0c','$2y$10$7S0.eptioSjZj5MrRI3oMOEmbTW2qi353FDS/Qk/R6eAPqN9Z2u/m',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5def90a47c602',0,NULL,NULL,'2019-12-10 12:33:40','2019-12-10 12:33:40'),(143,'5def945e31ffe','$2y$10$bzqUY0qDhzphj7hQorCbkuvBJsSoX0gVIVMEJAtJdyOOGppJyZnLu',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5def945e413ff',0,NULL,NULL,'2019-12-10 12:49:34','2019-12-10 12:49:34'),(144,'5def9476f1ae0','$2y$10$Bb55qWikvwttQ6F4UjE/8ORRn2XllWDtqljiFeqKZylWB1OCUOBhO',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5def94770ccd2',0,NULL,NULL,'2019-12-10 12:49:59','2019-12-10 12:49:59'),(145,'5def94ef8fbbc','$2y$10$qe7WK1ODzSckuB9YPBX6ke7B/ZKx9g8XLx/OmCGW9xBtfxTjWzoBC',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5def94ef9f74b',0,NULL,NULL,'2019-12-10 12:51:59','2019-12-10 12:51:59'),(146,'5def9925ee640','$2y$10$cqLGaNgicpEZZOIT7.1GeOm6SBzcR1XXB8lbiJiAQbK9/KBi1zwg2',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5def992609657',0,NULL,NULL,'2019-12-10 13:09:58','2019-12-10 13:09:58'),(147,'5defae35d916d','$2y$10$r.NoptqeuVvtS/dPlCho8eUnKESlumOPY2xpK8SVhXsbZl0raRsJO',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5defae35e8862',0,NULL,NULL,'2019-12-10 14:39:49','2019-12-10 14:39:49'),(148,'5defafc156d15','$2y$10$qHmxXn/L816zjlB3tJ343erDi86NrJS.WyeGmbg.m4KEBcdHjCNEW',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5defafc16614e',0,NULL,NULL,'2019-12-10 14:46:25','2019-12-10 14:46:25'),(149,'5defb636cb596','$2y$10$u1JJTlDlPFJxyo/03hoQpebZzaGP.fPVQ4Y7mIcKe83zmBh5wHGHe',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5defb636da852',0,NULL,NULL,'2019-12-10 15:13:58','2019-12-10 15:13:58'),(150,'5defd084ea304','$2y$10$orV8G1musNPJ798ZsBOWK.dziDQCpCBzqQRTHY56YhKMs2ce5LfLe',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5defd08505422',0,NULL,NULL,'2019-12-10 17:06:13','2019-12-10 17:06:13'),(151,'5defd1d559256','$2y$10$tS3CQ0p92xcqmtjaJ.8EKeV0lVwZfE821g0..ul0oIRSxHLyrv7Za',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5defd1d5684b7',0,NULL,NULL,'2019-12-10 17:11:49','2019-12-10 17:11:49'),(152,'5defd53062a94','$2y$10$KB2YlmGv8ZO.iaRQoMJMrOYCc4z4hwDqG159PY3ponLSsyh3Zr8B2',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5defd53072c58',0,NULL,NULL,'2019-12-10 17:26:08','2019-12-10 17:26:08'),(153,'5defddd58d3e3','$2y$10$YVaMgpvQ0mPRGduuT9YIpORuOBcFpvknD5HohDs5UAPeJc37UjgCC',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5defddd59c80f',0,NULL,NULL,'2019-12-10 18:03:01','2019-12-10 18:03:01'),(154,'5defdeecec6f5','$2y$10$dq6RlADYj0ylGT8MFI4iheu7CjufV2AHu62dJ.3IdNGeo/uHkzAzO',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5defdeed07765',0,NULL,NULL,'2019-12-10 18:07:41','2019-12-10 18:07:41'),(155,'5defe0642e139','$2y$10$rghpdQJ54vzR27rBBFu3AOJLJjHgawkBosDMBHcWJPRKJWB7Uq/JO',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5defe0643d4d3',0,NULL,NULL,'2019-12-10 18:13:56','2019-12-10 18:13:56'),(156,'5defe300abc8e','$2y$10$qY/g3SNWZ7vFDtreK6r24.2cK/lxFjFh9haAJ4HwBjKzaRGg5j59i',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5defe300bb131',0,NULL,NULL,'2019-12-10 18:25:04','2019-12-10 18:25:04'),(157,'5defe32c0f87d','$2y$10$P6ET3jT7uwpcC9zKC6gEdeoioVU/9nlLRGhHxGyDHux2JUXeuZop.',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5defe32c1ec2b',0,NULL,NULL,'2019-12-10 18:25:48','2019-12-10 18:25:48'),(158,'5defebc101f80','$2y$10$XTljk60IFiinZJRJx8TXEet7vXYjCIldFDYLN.sCFUu6C.fUWcHj.',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5defebc11145c',0,NULL,NULL,'2019-12-10 19:02:25','2019-12-10 19:02:25'),(159,'5defecef89a1d','$2y$10$zXy2oA0wd47/eqAdageYWu.wPDeRjsBSFm9Co0d6ZWbKWC1rC./OK',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5defecef98d05',0,NULL,NULL,'2019-12-10 19:07:27','2019-12-10 19:07:27'),(160,'5deff1e39bedc','$2y$10$UpK/LTAxtGdDZFKYtMNaCOWqFXCvBV32Q/n1xtMmISNUI6clnPh5m',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5deff1e3ab2f2',0,NULL,NULL,'2019-12-10 19:28:35','2019-12-10 19:28:35'),(161,'5df0003bbc5ca','$2y$10$TEqVr07kfxPXAkUrC4aK0.EFEwE2b2Kc4147Fh5WwZ96vO6putGiG',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5df0003bcb918',0,NULL,NULL,'2019-12-10 20:29:47','2019-12-10 20:29:47'),(162,'5df003a261b45','$2y$10$j4P7HQ.rwRK7QYwL9ZRYouZTijKUyTOvtykdCOqNvxCqBPjAMUzE2',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5df003a270e6f',0,NULL,NULL,'2019-12-10 20:44:18','2019-12-10 20:44:18'),(163,'5df00d0738316','$2y$10$uXSUFiT84hy9T0lQpyqbreVYuG8Ii8s98/k8.5ae88knIWk.3XECK',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5df00d0747fe3',0,NULL,NULL,'2019-12-10 21:24:23','2019-12-10 21:24:23'),(164,'5df01ecea3c0d','$2y$10$IekGd7b7mQeWqzKOi6W/0.tsNeHRQ1DKADNlM.cLAdUXdBJf9ZoQi',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5df01eceb30e8',0,NULL,NULL,'2019-12-10 22:40:14','2019-12-10 22:40:14'),(165,'5df01f59dc64a','$2y$10$SHL1r1ePFiXKXFthiFUq/OUNO/RWPe.prwBQg4bWtKEYVx4.8sUHC',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5df01f59eb9d8',0,NULL,NULL,'2019-12-10 22:42:33','2019-12-10 22:42:33'),(166,'DOH5','$2y$10$xRCzoBtMmPGnEUMo.vD2juyfKCUZWcAjlWA7FSbFVJDiKLV3Gf1PO',NULL,'Pedro','Campos','Sáez','p.c.s@gmail.com',1,0,1,'',0,NULL,NULL,'2019-12-10 22:49:29','2019-12-10 22:49:29'),(167,'5df02184af175','$2y$10$QXoSLjIh9wKm/5bSrUZQn.OByXkV1/sfbppu7VCpI3cltxxMRqsUa',NULL,NULL,NULL,NULL,NULL,0,0,NULL,'5df02184be5d5',0,NULL,NULL,'2019-12-10 22:51:48','2019-12-10 22:51:48');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_backend`
--

DROP TABLE IF EXISTS `usuario_backend`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_backend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `apellidos` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `rol` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `salt` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reset_token` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cuenta_id` int(10) unsigned NOT NULL,
  `procesos` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_backend_cuenta_id_email_unique` (`cuenta_id`,`email`),
  KEY `fk_usuario_backend_cuenta1` (`cuenta_id`),
  CONSTRAINT `usuario_backend_ibfk_1` FOREIGN KEY (`cuenta_id`) REFERENCES `cuenta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_backend`
--

LOCK TABLES `usuario_backend` WRITE;
/*!40000 ALTER TABLE `usuario_backend` DISABLE KEYS */;
INSERT INTO `usuario_backend` VALUES (1,'admin@admin.com','$2y$10$hpcoBecBshwZ2yprLHYyXedXz0C2qa6eYHMBJI2vKSMXxudUC64gq','Administrador','Simple','super',NULL,NULL,1,NULL,'By2cH85M5WgaANM276sTSY8pFdR8y6XcfA5GEqx3veilLCTgShcA5AfcYBcx',NULL,'2019-11-04 03:14:46');
/*!40000 ALTER TABLE `usuario_backend` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_manager`
--

DROP TABLE IF EXISTS `usuario_manager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario_manager` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `usuario` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `apellidos` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_manager_usuario_unique` (`usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_manager`
--

LOCK TABLES `usuario_manager` WRITE;
/*!40000 ALTER TABLE `usuario_manager` DISABLE KEYS */;
INSERT INTO `usuario_manager` VALUES (1,'manager','$2y$10$4AtqiDgUtNvaJUIOkmWC9ePd9sDYbFqJuOLTqScg5DL.3fnZVsEcC','','',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `usuario_manager` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `widget`
--

DROP TABLE IF EXISTS `widget`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widget` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `nombre` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `posicion` int(10) unsigned NOT NULL,
  `config` text COLLATE utf8_unicode_ci,
  `cuenta_id` int(10) unsigned NOT NULL,
  `anomin` date DEFAULT NULL,
  `anomax` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_widget_cuenta1` (`cuenta_id`),
  CONSTRAINT `widget_ibfk_1` FOREIGN KEY (`cuenta_id`) REFERENCES `cuenta` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widget`
--

LOCK TABLES `widget` WRITE;
/*!40000 ALTER TABLE `widget` DISABLE KEYS */;
INSERT INTO `widget` VALUES (9,'tramite_etapas','Trámite por etapas',0,'{\"proceso_id\":\"1\"}',1,'2018-01-01','2021-01-01'),(10,'tramites_cantidad','Trámites realizados',0,'{\"procesos\":[\"1\"]}',1,'2018-01-01','2021-01-01'),(11,'etapa_usuarios','Carga de usuarios por etapa',0,'{\"tarea_id\":\"2\"}',1,'2018-01-01','2021-01-01'),(12,'etapa_grupo_usuarios','Carga de grupos de usuarios por etapa',0,'{\"tarea_id\":\"2\"}',1,'2018-01-01','2022-01-01');
/*!40000 ALTER TABLE `widget` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-12-10 22:53:31
