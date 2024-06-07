-- drop table `magnalister_amazon_prepare`;
/** @todo maybe three tables?*/
CREATE TABLE IF NOT EXISTS `magnalister_amazon_prepare` (
  `mpID` int(8) unsigned NOT NULL comment "marketplaceid",
  `ProductsID` int(11) NOT NULL comment "magnalister_products.id", 
  `PrepareType` enum('manual','auto','apply') NOT NULL, 
  `AIdentID` varchar(16) NOT NULL, 
  `AIdentType` enum('ASIN','EAN') NOT NULL,
  `Price` decimal(15,2) NULL comment "price is frozen, if not null ",
  `LeadtimeToShip` int(11) NULL comment "time is frozen if not null",
  `Quantity` int(11) NULL comment "quantity is frozen if not null",
  `LowestPrice` decimal(15,2) NOT NULL comment "lowest price (amazon)",
  `ConditionType` varchar(10) NOT NULL comment "item condition",
  `ConditionNote` text NOT NULL comment "additional condition info",
  `Shipping` varchar(10) NOT NULL comment "old will ship internationally",

  
  `MainCategory` varchar(64) NOT NULL comment "only apply",
  
  `TopMainCategory` varchar(64) NOT NULL comment "only apply, for top-ten-categories",
  `TopProductType` varchar(64) NOT NULL comment "only apply, for top-ten-categories",
  `TopBrowseNode1` varchar(64) NOT NULL comment "only apply, for top-ten-categories",
  `TopBrowseNode2` varchar(64) NOT NULL comment "only apply, for top-ten-categories",
  
  `ApplyData` text NOT NULL comment "only apply",
  

  `IsComplete` enum('true', 'false') NOT NULL default 'false' comment "if matching, true",

  UNIQUE KEY `UC_products_id` (`mpID`,`ProductsID`)
);
