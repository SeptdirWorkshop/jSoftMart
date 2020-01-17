CREATE TABLE IF NOT EXISTS `#__jsoftmart_categories`
(
    `id`        int(11)                                                NOT NULL AUTO_INCREMENT,
    `parent_id` int(10) unsigned                                       NOT NULL DEFAULT 0,
    `lft`       int(11)                                                NOT NULL DEFAULT 0,
    `rgt`       int(11)                                                NOT NULL DEFAULT 0,
    `level`     int(10) unsigned                                       NOT NULL DEFAULT 0,
    `path`      varchar(400)                                           NOT NULL DEFAULT '',
    `alias`     varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
    `state`     tinyint(3)                                             NOT NULL DEFAULT 0,
    `params`    text                                                   NOT NULL DEFAULT '',
    PRIMARY KEY `id` (`id`),
    KEY `idx_left_right` (`lft`, `rgt`),
    KEY `idx_path` (`path`(100)),
    KEY `idx_alias` (`alias`(191)),
    KEY `idx_state` (`state`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    DEFAULT COLLATE = utf8mb4_unicode_ci
    AUTO_INCREMENT = 0;

CREATE TABLE IF NOT EXISTS `#__jsoftmart_categories_translates`
(
    `id`        int(11)      NOT NULL DEFAULT 0,
    `language`  char(7)      NOT NULL DEFAULT '',
    `title`     varchar(255) NOT NULL DEFAULT '',
    `introtext` text         NOT NULL,
    `fulltext`  mediumtext   NOT NULL,
    `metadata`  text         NOT NULL,
    PRIMARY KEY (`id`, `language`)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8mb4
    DEFAULT COLLATE = utf8mb4_unicode_ci;