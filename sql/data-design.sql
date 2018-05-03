ALTER DATABASE ABQ Outside CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS trail;
DROP TABLE IF EXISTS profile;

CREATE TABLE profile (
	profileId BINARY(16) NOT NULL,
	profileEmail VARCHAR(128) NOT NULL,
	profileImage VARCHAR(128),
	profileRefreshToken VARCHAR(128),
	profileUserName VARCHAR(128),
	UNIQUE(profileId),
	UNIQUE(profileEmail),
	PRIMARY KEY(profileId)
);

CREATE TABLE trails (
	trailId BINARY(16) NOT NULL,
	trailAddress VARCHAR(128) NOT NULL,
	trailAscent TINYINT,
	trailImage VARCHAR(255),
	trailLat INT,
	trailLength INT,
	trailName VARCHAR(128),
	trailRating INT,
	trailSummary VARCHAR(128),
	trailExternalId CHAR(7),
	PRIMARY KEY(trailId)
);

