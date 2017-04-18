CREATE TABLE IF NOT EXISTS `landing_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `partnerUID` int(11) unsigned NOT NULL,
  `PartnerKey` varchar(100) NOT NULL,
  `CouponsList` int(11) unsigned NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `AddressPostalCode` varchar(10) NOT NULL DEFAULT 'N/A',
  `AddressCity` varchar(50) NOT NULL DEFAULT 'N/A',
  `URN` varchar(100) NOT NULL,
  `customProperty1` varchar(255) DEFAULT NULL,
  `customProperty2` varchar(255) DEFAULT NULL,
  `participaciones` int(5) DEFAULT 0,
  `fecha` DATETIME DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE `unique_index` (`CouponsList`, `URN`),
  KEY `partnerUID` (`partnerUID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `landing_partner_user_promo` (
	`id_referer` int(11) unsigned NOT NULL,
	`id_customer` int(11) unsigned NOT NULL,
	`CouponsList` int(11) unsigned NOT NULL,
	KEY `id_referer` (`id_referer`),
	KEY `id_customer` (`id_customer`),
	KEY `CouponsList` (`CouponsList`)
) ENGINE=InnoDB DEFAULT CHARSET= utf8;
