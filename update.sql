RENAME TABLE `icms_app` TO `icms_apps`;
RENAME TABLE `icms_filedata` TO `icms_file_data`;

ALTER TABLE `icms_article`
  CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL   COMMENT '排序';
ALTER TABLE `icms_category`
  CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;
ALTER TABLE `icms_group`
  CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;
ALTER TABLE `icms_links`
  CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;
ALTER TABLE `icms_prop`
  CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;
ALTER TABLE `icms_push`
  CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;
ALTER TABLE `icms_tags`
  CHANGE `ordernum` `sortnum` INT(10) UNSIGNED DEFAULT 0  NOT NULL;

ALTER TABLE `icms_keywords`
  CHANGE `url` `replace` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT ''  NOT NULL;
ALTER TABLE `icms_keywords`
  DROP COLUMN `times`;


ALTER TABLE `icms_prop`
  ADD COLUMN `appid` INT(10) UNSIGNED DEFAULT 0  NOT NULL AFTER `field`,
  CHANGE `type` `app` VARCHAR(255) CHARSET utf8 COLLATE utf8_general_ci DEFAULT ''  NOT NULL;


/* Create table in target */
CREATE TABLE `icms_file_map`(
    `fileid` int(10) unsigned NOT NULL  ,
    `userid` int(10) unsigned NOT NULL  ,
    `appid` int(10) unsigned NOT NULL  ,
    `indexid` int(10) unsigned NOT NULL  ,
    `addtimes` int(10) unsigned NOT NULL  ,
    PRIMARY KEY (`fileid`,`appid`,`indexid`)
) ENGINE=MyISAM DEFAULT CHARSET='utf8' COLLATE='utf8_general_ci';



/* Alter table in target */
ALTER TABLE `icms_article`
    CHANGE `id` `id` int(10) unsigned   NOT NULL auto_increment COMMENT '文章ID' first ,
    CHANGE `markdown` `markdown` tinyint(1) unsigned   NOT NULL DEFAULT 0 COMMENT 'markdown标识' after `weight` ;

/* Alter table in target */
ALTER TABLE `icms_file_data`
    CHANGE `userid` `userid` int(10) unsigned   NOT NULL DEFAULT 0 after `id` ,
    ADD COLUMN `status` tinyint(1) unsigned   NOT NULL DEFAULT 0 after `type` ,
    DROP COLUMN `indexid` ,
    DROP KEY `indexid` ;

/* Alter table in target */
ALTER TABLE `icms_prop`
    ADD COLUMN `appid` int(10) unsigned   NOT NULL DEFAULT 0 after `field` ,
    ADD COLUMN `app` varchar(255)  COLLATE utf8_general_ci NOT NULL DEFAULT '' after `appid` ,
    CHANGE `sortnum` `sortnum` int(10) unsigned   NOT NULL DEFAULT 0 after `app` ,
    DROP COLUMN `type` ,
    DROP KEY `type`, ADD KEY `type`(`app`) ;

ALTER TABLE `icms_article`
  DROP COLUMN `metadata`;

ALTER TABLE `icms_category`
  DROP COLUMN `metadata`,
  DROP COLUMN `hasbody`;

ALTER TABLE `icms_tags`
  DROP COLUMN `metadata`;

ALTER TABLE `icms_push`
  DROP COLUMN `metadata`;

ALTER TABLE `icms_category`
  DROP COLUMN `isexamine`,
  DROP COLUMN `issend`,
  DROP COLUMN `isucshow`,
  ADD COLUMN `config` TEXT NOT NULL AFTER `template`,
  CHANGE `count` `count` INT(10) UNSIGNED DEFAULT 0  NOT NULL  AFTER `config`;

ALTER TABLE `icms_members`
  DROP COLUMN `power`,
  DROP COLUMN `cpower`,
  ADD COLUMN `config` MEDIUMTEXT NOT NULL AFTER `info`;

ALTER TABLE `icms_apps`
  ADD COLUMN `title` VARCHAR(100) DEFAULT '' NOT NULL COMMENT '应用标题' AFTER `name`;

ALTER TABLE `icms62`.`icms_group`
  DROP COLUMN `power`,
  DROP COLUMN `cpower`,
  ADD COLUMN `config` MEDIUMTEXT NOT NULL AFTER `sortnum`;
