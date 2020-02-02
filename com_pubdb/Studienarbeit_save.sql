CREATE TABLE `pubdb_Literatur` (
  `pubdb_literatur_id` int PRIMARY KEY AUTO_INCREMENT,
  `CreatedBy` varchar(255),
  `CreatedOn` datetime,
  `ModifiedBy` varchar(255),
  `Abstract` text,
  `AccessDate` datetime,
  `BibTeXKey` text,
  `CoverPath` text,
  `Date` Date,
  `DOI` text,
  `Edition` text,
  `ISBN` text,
  `Language` text,
  `LanguageCode` text,
  `Number` text,
  `NumberOfVolume` text,
  `OnlineAddress` text,
  `OriginalCheckedDate` datetime,
  `OriginalPublicationDate` datetime,
  `PageCount` text,
  `PageRange` text,
  `Periodical_ID` int,
  `Parent_Literature_ID` int,
  `PubMedID` text,
  `ReferenceType` text,
  `SeriesTitleID` int,
  `ShortTitle` text,
  `SourceOfBibliographicInformation` text,
  `Subtitle` text,
  `Title` text,
  `TitlleInOtherLanguage` text,
  `TitleSupplement` text,
  `UniformTitle` text,
  `Volume` text,
  `Year` text,
  `eISBN` text,
  `PMCID` text,
  `ArXicID` text
);

CREATE TABLE `pubdb_LiteraturePubisher` (
  `pubdb_LiteraturePubisher_id` int PRIMARY KEY AUTO_INCREMENT,
  `pubdb_Literature_id` int,
  `pubdb_Publisher_id` int,
  `Index` int
);

CREATE TABLE `pubdb_Publisher` (
  `pubdb_Publisher_id` int PRIMARY KEY AUTO_INCREMENT,
  `CreatedBy` text,
  `CreatedOn` datetime,
  `ModifiedBy` text,
  `ModifiedOn` datetime,
  `Name` text,
  `Notes` text,
  `SortFulName` text,
  `UniqueFulName` text
);

CREATE TABLE `pubdb_Periodical` (
  `pubdb_periodical_id` int PRIMARY KEY AUTO_INCREMENT,
  `CreatedBy` text,
  `CreatedOn` datetime,
  `ModifiedBy` text,
  `ModifiedOn` datetime,
  `ISSN` text,
  `Name` text,
  `Notes` text,
  `StandardAbbreviation` text,
  `eISSN` text,
  `Pagination` text,
  `SortFulName` text,
  `UniqueFulName` text
);

CREATE TABLE `pubdb_SeriesTitle` (
  `pubdb_SeriesTitle_id` int PRIMARY KEY AUTO_INCREMENT,
  `CreatedBy` text,
  `CreatedOn` datetime,
  `ModifiedBy` text,
  `ModifiedOn` datetime,
  `Name` text,
  `Notes` text,
  `StandardAbbreviation` text,
  `SortFulName` text,
  `UniqueFulName` text
);

CREATE TABLE `pubdb_SeriesTitleEditor` (
  `pubdb_SeriesTitleEditor_id` int PRIMARY KEY AUTO_INCREMENT,
  `PersonID` int,
  `SeriesTitleID` int
);

CREATE TABLE `pubdb_people` (
  `pubdb_Person_id` int PRIMARY KEY AUTO_INCREMENT,
  `CreatedBy` text,
  `CreatedOn` datetime,
  `ModifiedBy` text,
  `ModifiedOn` datetime,
  `Abbreviation` text,
  `Firstname` text,
  `Lastname` text,
  `LastNameForSorting` text,
  `MiddleName` text,
  `Notes` text,
  `Prefix` text,
  `Sex` int,
  `Suffix` text,
  `Title` text,
  `SortFulName` text,
  `UniqueFulName` text
);

CREATE TABLE `pubdb_LiteratureAuthor` (
  `pubdb_LiteratureAuthor_id` int PRIMARY KEY AUTO_INCREMENT,
  `LiteratureID` int,
  `PersonID` int
);

CREATE TABLE `pubdb_LiteratureOtherInvolved` (
  `pubdb_LiteratureOtherInvolved_id` int PRIMARY KEY AUTO_INCREMENT,
  `LiteratureID` int,
  `PersonID` int
);

CREATE TABLE `pubdb_LiteratureTranslator` (
  `pubdb_LiteratureTranslator_id` int PRIMARY KEY AUTO_INCREMENT,
  `LiteratureID` int,
  `PersonID` int
);

CREATE TABLE `pubdb_LiteratureKeyword` (
  `pubdb_LiteratureKeyWord_id` int PRIMARY KEY AUTO_INCREMENT,
  `LiteratureID` int,
  `KeywordID` int
);

CREATE TABLE `pubdb_Keyword` (
  `pubdb_Keyword_id` int PRIMARY KEY AUTO_INCREMENT,
  `CreatedOn` datetime,
  `CreatedBy` text,
  `ModifiedOn` datetime,
  `ModifiedBy` text,
  `Name` text,
  `Notes` text,
  `SortFulName` text,
  `UniqueFulName` text
);

CREATE TABLE `pubdb_Category` (
  `pubdb_Category_id` int PRIMARY KEY AUTO_INCREMENT,
  `LiteratureID` int,
  `CreatedBy` text,
  `CreatedOn` datetime,
  `ModifiedBy` text,
  `ModifiedOn` datetime,
  `Name` text
);

CREATE TABLE `pubdb_CategoryAttributes` (
  `pubdb_CategoryAttributes_id` int PRIMARY KEY AUTO_INCREMENT,
  `CategoryID` int,
  `AttributeID` int
);

CREATE TABLE `pubdb_Attribute` (
  `pubdb_Attribute_id` int PRIMARY KEY AUTO_INCREMENT,
  `Name` text,
  `Value` text
);

CREATE TABLE `pubdb_CitationStyle` (
  `pubdb_CitationStyle_id` int PRIMARY KEY AUTO_INCREMENT,
  `Name` text,
  `String` text
);

ALTER TABLE `pubdb_LiteraturePubisher` ADD FOREIGN KEY (`pubdb_Literature_id`) REFERENCES `pubdb_Literatur` (`pubdb_literatur_id`);

ALTER TABLE `pubdb_LiteraturePubisher` ADD FOREIGN KEY (`pubdb_Publisher_id`) REFERENCES `pubdb_Publisher` (`pubdb_Publisher_id`);

ALTER TABLE `pubdb_Literatur` ADD FOREIGN KEY (`Periodical_ID`) REFERENCES `pubdb_Periodical` (`pubdb_periodical_id`);

ALTER TABLE `pubdb_SeriesTitle` ADD FOREIGN KEY (`pubdb_SeriesTitle_id`) REFERENCES `pubdb_Literatur` (`SeriesTitleID`);

ALTER TABLE `pubdb_SeriesTitleEditor` ADD FOREIGN KEY (`SeriesTitleID`) REFERENCES `pubdb_SeriesTitle` (`pubdb_SeriesTitle_id`);

ALTER TABLE `pubdb_SeriesTitleEditor` ADD FOREIGN KEY (`PersonID`) REFERENCES `pubdb_people` (`pubdb_Person_id`);

ALTER TABLE `pubdb_LiteratureAuthor` ADD FOREIGN KEY (`LiteratureID`) REFERENCES `pubdb_Literatur` (`pubdb_literatur_id`);

ALTER TABLE `pubdb_LiteratureAuthor` ADD FOREIGN KEY (`PersonID`) REFERENCES `pubdb_people` (`pubdb_Person_id`);

ALTER TABLE `pubdb_LiteratureOtherInvolved` ADD FOREIGN KEY (`LiteratureID`) REFERENCES `pubdb_Literatur` (`pubdb_literatur_id`);

ALTER TABLE `pubdb_LiteratureOtherInvolved` ADD FOREIGN KEY (`PersonID`) REFERENCES `pubdb_people` (`pubdb_Person_id`);

ALTER TABLE `pubdb_LiteratureTranslator` ADD FOREIGN KEY (`LiteratureID`) REFERENCES `pubdb_Literatur` (`pubdb_literatur_id`);

ALTER TABLE `pubdb_LiteratureTranslator` ADD FOREIGN KEY (`PersonID`) REFERENCES `pubdb_people` (`pubdb_Person_id`);

ALTER TABLE `pubdb_LiteratureKeyword` ADD FOREIGN KEY (`LiteratureID`) REFERENCES `pubdb_Literatur` (`pubdb_literatur_id`);

ALTER TABLE `pubdb_LiteratureKeyword` ADD FOREIGN KEY (`KeywordID`) REFERENCES `pubdb_Keyword` (`pubdb_Keyword_id`);

ALTER TABLE `pubdb_Category` ADD FOREIGN KEY (`LiteratureID`) REFERENCES `pubdb_Literatur` (`pubdb_literatur_id`);

ALTER TABLE `pubdb_CategoryAttributes` ADD FOREIGN KEY (`CategoryID`) REFERENCES `pubdb_Category` (`pubdb_Category_id`);

ALTER TABLE `pubdb_CategoryAttributes` ADD FOREIGN KEY (`AttributeID`) REFERENCES `pubdb_Attribute` (`pubdb_Attribute_id`);
