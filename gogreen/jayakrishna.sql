-- MySQL dump 10.13  Distrib 5.5.42, for Win32 (x86)
--
-- Host: localhost    Database: va_mtk
-- ------------------------------------------------------
-- Server version	5.5.42-log

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
-- Table structure for table `daily_generation_data`
--

DROP TABLE IF EXISTS `daily_generation_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_generation_data` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `IMEI` bigint(15) NOT NULL,
  `Windspeed` float(3,1) unsigned NOT NULL,
  `Power` decimal(4,1) NOT NULL,
  `Gen1_Min` double(11,1) unsigned NOT NULL,
  `Gen1_Max` double(11,1) unsigned NOT NULL,
  `Gen2_Min` double(18,1)  NOT NULL,
  `Gen2_Max` double(18,1)  NOT NULL,
  `Run_Hours` double(15,1) NOT NULL,
  `Gen1_Hours` double(15,1)  NOT NULL,
  `Line_Ok` float(6,2) unsigned NOT NULL,
  `Gen0` double(15,1) NOT NULL,
  `Date_S` date NOT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imeidates_indx` (`IMEI`,`Date_S`)
) ENGINE=InnoDB AUTO_INCREMENT=1190 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `device_data`
--

DROP TABLE IF EXISTS `device_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_data` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Message_ID` char(6) NOT NULL,
  `IMEI` bigint(15) NOT NULL,
  `Date_F` date NOT NULL,
  `Time_F` time NOT NULL,
  `Project_Version` char(3) NOT NULL,
  `ID_Number` char(6) NOT NULL,
  `GRPM` float(5,1) unsigned NOT NULL,
  `RRPM` float(3,1) unsigned NOT NULL,
  `Windspeed` float(3,1) unsigned NOT NULL,
  `Pitch` float(4,1) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Date` char(12) NOT NULL,
  `Time` char(8) NOT NULL,
  `Power` decimal(5,2) NOT NULL,
  `RPhase_Volt` smallint(3) unsigned NOT NULL,
  `YPhase_Volt` smallint(3) unsigned NOT NULL,
  `BPhase_Volt` smallint(3) unsigned NOT NULL,
  `RPhase_Current` smallint(3) DEFAULT NULL,
  `YPhase_Current` smallint(3) DEFAULT NULL,
  `BPhase_Current` smallint(3) DEFAULT NULL,
  `Power_Factor` float(5,2) NOT NULL,
  `Frequency` float(5,2) unsigned NOT NULL,
  `PAT_Gen0` int(10) NOT NULL,
  `PAT_Gen1` bigint(12) unsigned NOT NULL,
  `PAT_Gen2` bigint(12) unsigned NOT NULL,
  `Ambient_Temp` smallint(3) NOT NULL,
  `Nacel_Temp` smallint(3) NOT NULL,
  `Gear_Temp` smallint(3) NOT NULL,
  `Gen1_Temp` smallint(3) NOT NULL,
  `Hydraulic_Temp` smallint(3) NOT NULL,
  `Control_Temp` smallint(3) NOT NULL,
  `Bearing_Temp` smallint(3) NOT NULL,
  `Total_Hours` int(10) unsigned NOT NULL,
  `Line_Ok` int(10) unsigned NOT NULL,
  `Turbine_Ok` int(10) unsigned NOT NULL,
  `Run_Hours` int(10) unsigned NOT NULL,
  `Gen1_Hours` int(10) NOT NULL,
  `Date_S` date NOT NULL,
  `Time_S` time NOT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imei_indx` (`IMEI`),
  KEY `datestimes_indx` (`Date_S`,`Time_S`)
) ENGINE=InnoDB AUTO_INCREMENT=1304861 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `device_data_f10`
--

DROP TABLE IF EXISTS `device_data_f10`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_data_f10` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Message_ID` char(6) NOT NULL,
  `IMEI` bigint(15) NOT NULL,
  `Date_F` date NOT NULL,
  `Time_F` time NOT NULL,
  `Project_Version` char(3) NOT NULL,
  `ID_Number` char(6) NOT NULL,
  `GRPM` float(5,1) unsigned NOT NULL,
  `RRPM` float(3,1) unsigned NOT NULL,
  `Windspeed` float(3,1) unsigned NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Pitch` float(4,1) NOT NULL,
  `Date` char(12) NOT NULL,
  `Time` char(8) NOT NULL,
  `Power` decimal(5,2) NOT NULL,
  `RPhase_Volt` smallint(3) unsigned NOT NULL,
  `YPhase_Volt` smallint(3) unsigned NOT NULL,
  `BPhase_Volt` smallint(3) unsigned NOT NULL,
  `RPhase_Current` smallint(3) NOT NULL,
  `YPhase_Current` smallint(3) NOT NULL,
  `BPhase_Current` smallint(3) NOT NULL,
  `Power_Factor` float(5,2) NOT NULL,
  `Frequency` float(4,2) unsigned NOT NULL,
  `Ambient_Temp` smallint(3) NOT NULL,
  `Hydraulic_Temp` smallint(3) NOT NULL,
  `Gear_Temp` smallint(3) NOT NULL,
  `Gen1_Temp` smallint(3) NOT NULL,
  `Gen2_Temp` smallint(3) NOT NULL,
  `Total_Hours` int(10) unsigned NOT NULL,
  `Run_Hours` int(10) unsigned NOT NULL,
  `PAT_Gen0` int(10) NOT NULL,
  `PAT_Gen1` bigint(12) unsigned NOT NULL,
  `PAT_Gen2` bigint(12) unsigned NOT NULL,
  `Production_Total` bigint(12) unsigned NOT NULL,
  `Line_Hours` int(10) unsigned NOT NULL,
  `Line_Ok` int(10) unsigned NOT NULL,
  `Turbine_Ok` int(10) unsigned NOT NULL,
  `Gen1_Hours` int(10) NOT NULL,
  `Gen2_Hours` int(10) NOT NULL,
  `Date_S` date NOT NULL,
  `Time_S` time NOT NULL,
  `Nacel_Temp` smallint(3) NOT NULL,
  `Control_Temp` smallint(3) NOT NULL,
  `Bearing_Temp` smallint(3) NOT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imei_indx` (`IMEI`),
  KEY `datestimes_indx` (`Date_S`,`Time_S`)
) ENGINE=InnoDB AUTO_INCREMENT=131737 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `device_data_f2`
--

DROP TABLE IF EXISTS `device_data_f2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_data_f2` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Message_ID` char(6) NOT NULL,
  `IMEI` bigint(15) NOT NULL,
  `Date_F` date NOT NULL,
  `Time_F` time NOT NULL,
  `Project_Version` char(3) NOT NULL,
  `ID_Number` char(6) NOT NULL,
  `GRPM` smallint(4) unsigned NOT NULL,
  `RRPM` tinyint(2) unsigned NOT NULL,
  `Windspeed` float(3,1) unsigned NOT NULL,
  `Power` smallint(3) NOT NULL,
  `G1_Temp` smallint(3) NOT NULL,
  `G2_Temp` smallint(3) NOT NULL,
  `G3_Temp` smallint(3) NOT NULL,
  `G4_Temp` smallint(3) NOT NULL,
  `G5_Temp` smallint(3) NOT NULL,
  `G6_Temp` varchar(5) DEFAULT NULL,
  `RPhase_Volt` smallint(3) unsigned NOT NULL,
  `YPhase_Volt` smallint(3) unsigned NOT NULL,
  `BPhase_Volt` smallint(3) unsigned NOT NULL,
  `RPhase_Current` smallint(3) NOT NULL,
  `YPhase_Current` smallint(3) NOT NULL,
  `BPhase_Current` smallint(3) NOT NULL,
  `Power_Factor` float(3,2) NOT NULL,
  `PAT_Gen1` float(10,1) unsigned NOT NULL,
  `PAT_Gen2` float(10,1) unsigned NOT NULL,
  `Import_Kwh` float(10,1) unsigned NOT NULL,
  `Gen1_Hours` int(10) unsigned NOT NULL,
  `Gen2_Hours` int(10) unsigned NOT NULL,
  `Date` char(12) NOT NULL,
  `Time` char(8) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Date_S` date NOT NULL,
  `Time_S` time NOT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imei_indx` (`IMEI`),
  KEY `datestimes_indx` (`Date_S`,`Time_S`)
) ENGINE=InnoDB AUTO_INCREMENT=347983 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `device_data_f3`
--

DROP TABLE IF EXISTS `device_data_f3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_data_f3` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Message_ID` char(6) NOT NULL,
  `IMEI` bigint(15) NOT NULL,
  `Date_F` date NOT NULL,
  `Time_F` time NOT NULL,
  `Project_Version` char(3) NOT NULL,
  `ID_Number` char(6) NOT NULL,
  `GRPM` smallint(4) unsigned NOT NULL,
  `RRPM` tinyint(2) unsigned NOT NULL,
  `Windspeed` float(3,1) unsigned NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Date` char(12) NOT NULL,
  `Time` char(8) NOT NULL,
  `Power_Factor` float(3,2) NOT NULL,
  `Power` decimal(4,1) NOT NULL,
  `Reactive_Power` float(4,1) NOT NULL,
  `RPhase_Volt` smallint(3) unsigned NOT NULL,
  `YPhase_Volt` smallint(3) unsigned NOT NULL,
  `BPhase_Volt` smallint(3) unsigned NOT NULL,
  `RPhase_Current` smallint(3) NOT NULL,
  `YPhase_Current` smallint(3) NOT NULL,
  `BPhase_Current` smallint(3) NOT NULL,
  `Frequency` float(4,1) unsigned NOT NULL,
  `Total_Hours` double(12,1) unsigned NOT NULL,
  `Production_Total` double(11,1) unsigned NOT NULL,
  `PAT_Gen1` double(14,1) NOT NULL,
  `PAT_Gen2` double(14,1) unsigned NOT NULL,
  `Gen1_Hours` double(15,1) unsigned NOT NULL,
  `Gen2_Hours` double(15,1) unsigned NOT NULL,
  `Hydraulic_Pressure` tinyint(3) unsigned NOT NULL,
  `Nacelle_Position` smallint(3) NOT NULL,
  `Thyristor_Temp` smallint(3) NOT NULL,
  `Ambient_Temp` smallint(3) NOT NULL,
  `Main_Panel_Temp` smallint(3) NOT NULL,
  `Gen1_Temp` smallint(3) NOT NULL,
  `Gen2_Temp` smallint(3) NOT NULL,
  `Bearing_Temp` smallint(3) NOT NULL,
  `Gear_Temp` smallint(3) NOT NULL,
  `Nacel_Temp` smallint(3) NOT NULL,
  `Temp9` smallint(3) NOT NULL,
  `Temp10` smallint(3) NOT NULL,
  `Import_Kwh` double(18,1) NOT NULL,
  `Import_Kvarh` double(12,1) NOT NULL,
  `Yaw_CW_Counts` smallint(5) NOT NULL,
  `Yaw_CCW_Counts` smallint(5) unsigned NOT NULL,
  `Dummy1` varchar(10) DEFAULT NULL,
  `Dummy2` varchar(10) DEFAULT NULL,
  `Dummy3` varchar(10) DEFAULT NULL,
  `Dummy4` varchar(10) DEFAULT NULL,
  `Dummy5` varchar(10) DEFAULT NULL,
  `Dummy6` varchar(10) DEFAULT NULL,
  `Dummy7` varchar(10) DEFAULT NULL,
  `Dummy8` varchar(10) DEFAULT NULL,
  `Dummy9` varchar(10) DEFAULT NULL,
  `Dummy10` varchar(10) DEFAULT NULL,
  `Dummy11` varchar(50) DEFAULT NULL,
  `CPCM` char(3) NOT NULL,
  `Date_S` date NOT NULL,
  `Time_S` time NOT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imei_indx` (`IMEI`),
  KEY `datestimes_indx` (`Date_S`,`Time_S`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `device_data_f6`
--

DROP TABLE IF EXISTS `device_data_f6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_data_f6` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Message_ID` char(6) NOT NULL,
  `IMEI` bigint(15) NOT NULL,
  `Date_F` date NOT NULL,
  `Time_F` time NOT NULL,
  `Project_Version` char(3) NOT NULL,
  `ID_Number` char(6) NOT NULL,
  `GRPM` float(5,1) unsigned NOT NULL,
  `RRPM` float(3,1) unsigned NOT NULL,
  `Windspeed` float(3,1) unsigned NOT NULL,
  `Pitch` float(4,1) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Date` char(12) NOT NULL,
  `Time` char(8) NOT NULL,
  `Power` decimal(5,2) NOT NULL,
  `RPhase_Volt` smallint(3) unsigned NOT NULL,
  `YPhase_Volt` smallint(3) unsigned NOT NULL,
  `BPhase_Volt` smallint(3) unsigned NOT NULL,
  `RPhase_Current` smallint(3) NOT NULL,
  `YPhase_Current` smallint(3) NOT NULL,
  `BPhase_Current` smallint(3) NOT NULL,
  `Power_Factor` float(5,2) NOT NULL,
  `Frequency` float(4,2) unsigned NOT NULL,
  `PAT_Gen0` int(10) NOT NULL,
  `PAT_Gen1` bigint(12) unsigned NOT NULL,
  `PAT_Gen2` bigint(12) unsigned NOT NULL,
  `PAM_Gen0` int(10) NOT NULL,
  `PAM_Gen1` bigint(12) unsigned NOT NULL,
  `PAM_Gen2` bigint(12) unsigned NOT NULL,
  `PATP_Gen0` int(10) NOT NULL,
  `PATP_Gen1` bigint(12) unsigned NOT NULL,
  `PATP_Gen2` bigint(12) unsigned NOT NULL,
  `Total_Hours` int(11) unsigned NOT NULL,
  `Line_Ok` int(11) unsigned NOT NULL,
  `Turbine_Ok` int(11) unsigned NOT NULL,
  `Run_Hours` int(11) unsigned NOT NULL,
  `Gen1_Hours` int(10) NOT NULL,
  `Month_Total` int(11) unsigned NOT NULL,
  `Month_Line_Ok` int(11) unsigned NOT NULL,
  `Month_Turbine_Ok` int(11) unsigned NOT NULL,
  `Month_Run` int(11) unsigned NOT NULL,
  `Month_Gen1` int(10) NOT NULL,
  `Trip_Total` int(11) unsigned NOT NULL,
  `Trip_Line_Ok` int(11) unsigned NOT NULL,
  `Trip_Turbine_Ok` int(11) unsigned NOT NULL,
  `Trip_Run` int(11) unsigned NOT NULL,
  `Trip_Gen1` int(10) NOT NULL,
  `Status_Old` varchar(10) DEFAULT NULL,
  `Ambient_Temp` smallint(3) NOT NULL,
  `Nacel_Temp` smallint(3) NOT NULL,
  `Gear_Temp` smallint(3) NOT NULL,
  `Gen1_Temp` smallint(3) NOT NULL,
  `Control_Temp` smallint(3) NOT NULL,
  `Bearing_Temp` smallint(3) NOT NULL,
  `Hydraulic_Temp` smallint(3) NOT NULL,
  `Date_S` date NOT NULL,
  `Time_S` time NOT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imei_indx` (`IMEI`),
  KEY `datestimes_indx` (`Date_S`,`Time_S`)
) ENGINE=InnoDB AUTO_INCREMENT=1727362 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `device_data_f7`
--

DROP TABLE IF EXISTS `device_data_f7`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;

CREATE TABLE `device_data_f7` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Message_ID` char(6) NOT NULL,
  `IMEI` bigint(15) unsigned NOT NULL,
  `Date_F` varchar(15) NOT NULL,
  `Time_F` varchar(15) NOT NULL,
  `Project_Version` char(3) NOT NULL,
  `ID_Number` char(6) NOT NULL,
  `RRPM` float(3,1) unsigned NOT NULL,
  `GRPM` smallint(4) unsigned NOT NULL,
  `Windspeed` float(3,1) unsigned NOT NULL,
  `Date` char(12) NOT NULL,
  `time` char(8) NOT NULL,
  `Power` decimal(4,1) NOT NULL,
  `Reactive_Power` float(4,1) NOT NULL,
  `L_N_Voltage_R` smallint(3) unsigned NOT NULL,
  `L_N_Voltage_Y` smallint(3) unsigned NOT NULL,
  `L_N_Voltage_B` smallint(3) unsigned NOT NULL,
  `L_L_Voltage_RY` smallint(3) unsigned NOT NULL,
  `L_L_Voltage_YB` smallint(3) unsigned NOT NULL,
  `L_L_Voltage_BR` smallint(3) unsigned NOT NULL,
  `RPhase_Current` smallint(3) NOT NULL,
  `YPhase_Current` smallint(3) NOT NULL,
  `BPhase_Current` smallint(3) NOT NULL,
  `Power_Factor` float(4,3) NOT NULL,
  `Frequency` float(4,2) unsigned NOT NULL,
  `Active_Total_Gen_Import` bigint(12) unsigned NOT NULL,
  `Active_Total_Gen_Export` bigint(12) unsigned NOT NULL,
  `Reactive_Total_Gen_Import` bigint(12) unsigned NOT NULL,
  `Reactive_Total_Gen_Export` bigint(12) unsigned NOT NULL,
  `Active_Gen1_Import` bigint(12) unsigned NOT NULL,
  `Active_Gen1_Export` bigint(12) unsigned NOT NULL,
  `Reactive_Gen1_Import` bigint(12) unsigned NOT NULL,
  `Reactive_Gen1_Export` varchar(20) DEFAULT NULL,
  `Active_Gen2_Import` bigint(12) unsigned NOT NULL,
  `Active_Gen2_Export` bigint(12) unsigned NOT NULL,
  `Reactive_Gen2_Import` bigint(12) unsigned NOT NULL,
  `Reactive_Gen2_Export` bigint(12) unsigned NOT NULL,
  `G1_Connected_Counts` bigint(12) unsigned NOT NULL,
  `G2_Connected_Counts` bigint(12) unsigned NOT NULL,
  `Gen_Init_Date` varchar(16) NOT NULL,
  `Gen_Init_Time` varchar(16) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Control_Panel_Temp` float(4,1) NOT NULL,
  `Gear_Bearing1_Temp` float(4,1) NOT NULL,
  `Gear_Bearing2_Temp` float(4,1) NOT NULL,
  `Gear_Box_Oil_Temp` float(4,1) NOT NULL,
  `Gen_Winding1_Temp` float(4,2) unsigned NOT NULL,
  `Gen_Winding2_Temp` float(4,1) NOT NULL,
  `Gen_DE_Bearing_Temp` float(4,1) DEFAULT NULL,
  `Gen_DE_NDE_Bearing_Temp` float(4,1) DEFAULT NULL,
  `Nacelle_Temp` float(4,1) NOT NULL,
  `Main_Bearing_Temp` float(4,1) DEFAULT NULL,
  `Transformer_Oil_Temp` float(4,1) DEFAULT NULL,
  `Nacelle_Position` varchar(5) DEFAULT NULL,
  `Cable_Twist` varchar(5) DEFAULT NULL,
  `Tip_Pressure` varchar(5) DEFAULT NULL,
  `Kwh_Positive` smallint(5) unsigned NOT NULL,
  `Kwh_Negative` smallint(5) unsigned NOT NULL,
  `KVar_Positive` smallint(5) unsigned NOT NULL,
  `KVar_Negative` smallint(5) unsigned NOT NULL,
  `Total_Hours` float(4,2) unsigned NOT NULL,
  `Operate_Hours` float(4,2) unsigned NOT NULL,
  `Grid_failure_Hours` float(4,2) unsigned NOT NULL,
  `Stopped_Hours` float(4,2) unsigned NOT NULL,
  `Min3_Wind_Speed` float(3,1) unsigned NOT NULL,
  `Min3_Wind_Dir` float(3,1) NOT NULL,
  `Min3_Active_Power` float(5,2) NOT NULL,
  `Date_S` date NOT NULL,
  `Time_S` time NOT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imeidates_indx` (`IMEI`,`Date_S`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `device_data_f8`
--

DROP TABLE IF EXISTS `device_data_f8`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `device_data_f8` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Message_ID` char(6) NOT NULL,
  `IMEI` bigint(15) unsigned NOT NULL,
  `Date_F` date NOT NULL,
  `Time_F` varchar(15) NOT NULL,
  `Project_Version` char(3) NOT NULL,
  `ID_Number` char(6) NOT NULL,
  `RRPM` float(3,1) unsigned NOT NULL,
  `GRPM` smallint(4) unsigned NOT NULL,
  `Windspeed` float(3,1) unsigned NOT NULL,
  `Date` char(12) NOT NULL,
  `time` char(8) NOT NULL,
  `Power` decimal(4,1) NOT NULL,
  `Reactive_Power` float(4,1) NOT NULL,
  `L_N_Voltage_R` smallint(3) unsigned NOT NULL,
  `L_N_Voltage_Y` smallint(3) unsigned NOT NULL,
  `L_N_Voltage_B` smallint(3) unsigned NOT NULL,
  `L_L_Voltage_RY` smallint(3) unsigned NOT NULL,
  `L_L_Voltage_YB` smallint(3) unsigned NOT NULL,
  `L_L_Voltage_BR` smallint(3) unsigned NOT NULL,
  `RPhase_Current` smallint(3) NOT NULL,
  `YPhase_Current` smallint(3) NOT NULL,
  `BPhase_Current` smallint(3) NOT NULL,
  `Power_Factor` float(4,3) NOT NULL,
  `Frequency` float(4,2) unsigned NOT NULL,
  `Active_Total_Gen_Import` bigint(12) unsigned NOT NULL,
  `Active_Total_Gen_Export` bigint(12) unsigned NOT NULL,
  `Reactive_Total_Gen_Import` bigint(12) unsigned NOT NULL,
  `Reactive_Total_Gen_Export` bigint(12) unsigned NOT NULL,
  `Active_Gen1_Import` bigint(12) unsigned NOT NULL,
  `Active_Gen1_Export` bigint(12) unsigned NOT NULL,
  `Reactive_Gen1_Import` bigint(12) unsigned NOT NULL,
  `Reactive_Gen1_Export` varchar(20) DEFAULT NULL,
  `Active_Gen2_Import` bigint(12) unsigned NOT NULL,
  `Active_Gen2_Export` bigint(12) unsigned NOT NULL,
  `Reactive_Gen2_Import` bigint(12) unsigned NOT NULL,
  `Reactive_Gen2_Export` bigint(12) unsigned NOT NULL,
  `G1_Connected_Counts` bigint(12) unsigned NOT NULL,
  `G2_Connected_Counts` bigint(12) unsigned NOT NULL,
  `Gen_Init_Date` varchar(16) NOT NULL,
  `Gen_Init_Time` varchar(16) NOT NULL,
  `Status` varchar(100) NOT NULL,
  `Control_Panel_Temp` float(4,1) NOT NULL,
  `Gear_Bearing1_Temp` float(4,1) NOT NULL,
  `Gear_Bearing2_Temp` float(4,1) NOT NULL,
  `Gear_Box_Oil_Temp` float(4,1) NOT NULL,
  `Gen_Winding1_Temp` float(4,1) NOT NULL,
  `Gen_Winding2_Temp` float(4,1) NOT NULL,
  `Gen_DE_Bearing_Temp` varchar(10) DEFAULT NULL,
  `Gen_DE_NDE_Bearing_Temp` varchar(10) DEFAULT NULL,
  `Nacelle_Temp` float(4,1) NOT NULL,
  `Nacelle_Position` varchar(5) DEFAULT NULL,
  `Cable_Twist` varchar(5) DEFAULT NULL,
  `Kwh_Positive` smallint(5) unsigned NOT NULL,
  `Kwh_Negative` smallint(5) unsigned NOT NULL,
  `KVar_Positive` smallint(5) unsigned NOT NULL,
  `KVar_Negative` smallint(5) unsigned NOT NULL,
  `Total_Hours` float(4,2) unsigned NOT NULL,
  `Operate_Hours` float(4,2) unsigned NOT NULL,
  `Grid_failure_Hours` float(4,2) unsigned NOT NULL,
  `Stopped_Hours` float(4,2) unsigned NOT NULL,
  `Min3_Wind_Speed` float(3,1) unsigned NOT NULL,
  `Min3_Wind_Dir` float(3,1) NOT NULL,
  `Min3_Active_Power` float(5,2) NOT NULL,
  `Date_S` date NOT NULL,
  `Time_S` time NOT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imei_indx` (`IMEI`),
  KEY `datefdatet_indx` (`Date_S`,`Time_S`)
) ENGINE=InnoDB AUTO_INCREMENT=972604 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `error_data`
--

DROP TABLE IF EXISTS `error_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `error_data` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Status` varchar(100) NOT NULL,
  `IMEI` bigint(15) NOT NULL,
  `Date_F` date NOT NULL,
  `Time_F` time NOT NULL,
  `Phone` char(10) DEFAULT NULL,
  `Date_S` date NOT NULL,
  `Time_S` time NOT NULL,
  `Date` char(12) DEFAULT NULL,
  `Time` char(8) DEFAULT NULL,
  `Project_Version` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imei_indx` (`IMEI`)
) ENGINE=InnoDB AUTO_INCREMENT=27618 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `error_data_f10`
--

DROP TABLE IF EXISTS `error_data_f10`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `error_data_f10` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Status` varchar(100) NOT NULL,
  `IMEI` bigint(15) NOT NULL,
  `Date_F` date NOT NULL,
  `Time_F` time NOT NULL,
  `Phone` char(10) DEFAULT NULL,
  `Date_S` date NOT NULL,
  `Time_S` time NOT NULL,
  `Date` char(12) DEFAULT NULL,
  `Time` char(8) DEFAULT NULL,
  `Project_Version` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imei_indx` (`IMEI`)
) ENGINE=InnoDB AUTO_INCREMENT=4386 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `error_data_f2`
--

DROP TABLE IF EXISTS `error_data_f2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `error_data_f2` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Status` varchar(100) NOT NULL,
  `IMEI` bigint(15) NOT NULL,
  `Date_F` date NOT NULL,
  `Time_F` time NOT NULL,
  `Phone` char(10) DEFAULT NULL,
  `Date_S` date NOT NULL,
  `Time_S` time NOT NULL,
  `Date` char(12) DEFAULT NULL,
  `Time` char(8) DEFAULT NULL,
  `Project_Version` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imei_indx` (`IMEI`)
) ENGINE=InnoDB AUTO_INCREMENT=15189 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `error_data_f3`
--

DROP TABLE IF EXISTS `error_data_f3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `error_data_f3` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Status` varchar(100) NOT NULL,
  `IMEI` bigint(15) NOT NULL,
  `Date_F` date NOT NULL,
  `Time_F` time NOT NULL,
  `Phone` char(10) DEFAULT NULL,
  `Date_S` date NOT NULL,
  `Time_S` time NOT NULL,
  `Date` char(12) DEFAULT NULL,
  `Time` char(8) DEFAULT NULL,
  `Project_Version` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imei_indx` (`IMEI`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `error_data_f6`
--

DROP TABLE IF EXISTS `error_data_f6`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `error_data_f6` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Status` varchar(100) NOT NULL,
  `IMEI` bigint(15) NOT NULL,
  `Date_F` date NOT NULL,
  `Time_F` time NOT NULL,
  `Phone` char(10) DEFAULT NULL,
  `Date_S` date NOT NULL,
  `Time_S` time NOT NULL,
  `Date` char(12) DEFAULT NULL,
  `Time` char(8) DEFAULT NULL,
  `Project_Version` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imei_indx` (`IMEI`)
) ENGINE=InnoDB AUTO_INCREMENT=14544 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `error_data_f7`
--

DROP TABLE IF EXISTS `error_data_f7`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `error_data_f7` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Status` varchar(100) NOT NULL,
  `IMEI` bigint(15) NOT NULL,
  `Date_F` date NOT NULL,
  `Time_F` time NOT NULL,
  `Phone` char(10) DEFAULT NULL,
  `Date_S` date NOT NULL,
  `Time_S` time NOT NULL,
  `Date` char(12) DEFAULT NULL,
  `Time` char(8) DEFAULT NULL,
  `Project_Version` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imei_indx` (`IMEI`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `error_data_f8`
--

DROP TABLE IF EXISTS `error_data_f8`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `error_data_f8` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Status` varchar(100) NOT NULL,
  `IMEI` bigint(15) NOT NULL,
  `Date_F` date NOT NULL,
  `Time_F` time NOT NULL,
  `Phone` char(10) DEFAULT NULL,
  `Date_S` date NOT NULL,
  `Time_S` time NOT NULL,
  `Date` char(12) DEFAULT NULL,
  `Time` char(8) DEFAULT NULL,
  `Project_Version` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imei_indx` (`IMEI`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `ip_table`
--

DROP TABLE IF EXISTS `ip_table`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ip_table` (
  `Index_ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `IMEI` bigint(15) NOT NULL,
  `IP_Address` char(15) NOT NULL,
  `Date_Of_Listener` varchar(30) DEFAULT NULL,
  `Date_Stamp_IP` datetime NOT NULL,
  `Date_Stamp_Data` datetime DEFAULT NULL,
  `Valid_Pkt` tinyint(2) NOT NULL DEFAULT '0',
  `Error_Pkt` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Index_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pocket_time_calc`
--

DROP TABLE IF EXISTS `pocket_time_calc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pocket_time_calc` (
  `Record_Index` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `Error` varchar(60) NOT NULL,
  `IMEI` bigint(15) NOT NULL,
  `Date_S` varchar(15) NOT NULL,
  `Time_S` varchar(15) NOT NULL,
  `Date_Stamp` varchar(50) DEFAULT NULL,
  `Error_Type` varchar(15) DEFAULT NULL,
  `Epoch_Time` varchar(50) DEFAULT NULL,
  `Time_Diff` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`Record_Index`),
  KEY `imei_indx` (`IMEI`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-08-29 11:28:22
