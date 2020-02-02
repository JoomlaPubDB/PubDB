
DROP TABLE IF EXISTS `#__pubdb_Literature`;

CREATE TABLE `#__pubdb_Literature` (
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

CREATE TABLE `#__pubdb_People` (
  `pubdb_People_id` int PRIMARY KEY AUTO_INCREMENT,
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

CREATE TABLE `#__pubdb_LiteratureAuthor` (
  `pubdb_LiteratureAuthor_id` int PRIMARY KEY AUTO_INCREMENT,
  `LiteratureID` int,
  `PeopleID` int
);