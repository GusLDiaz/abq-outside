ALTER DATABASE ABQ Outside CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS trail;
DROP TABLE IF EXISTS profile;

CREATE TABLE profile (
	profileId BINARY(16) NOT NULL,
	profileEmail VARCHAR(128) NOT NULL,
	profileImage VARCHAR(128),
	profileRefreshToken VARCHAR(128),
	profileUserName VARCHAR(64),
	UNIQUE(profileUserName),
	UNIQUE(profileEmail),
	PRIMARY KEY(profileId)
);

CREATE TABLE trail (
	trailId BINARY(16) NOT NULL,
	trailAddress VARCHAR(128) NOT NULL,
	trailAscent TINYINT,
	trailImage VARCHAR(255),
	trailLat DECIMAL (12,9),
	trailLength DECIMAL (6,3),
	trailLong DECIMAL (12,9),
	trailName VARCHAR(128),
	trailRating TINYINT UNSIGNED,
	trailSummary VARCHAR(128),
	trailExternalId CHAR(7),
	PRIMARY KEY(trailId)
);

CREATE TABLE comment (
	commentId BINARY(16),
	commentProfileId BINARY(16) NOT NULL,
	commentTrailId BINARY(16) NOT NULL,
	commentTimeStamp DATETIME(6) NOT NULL,
	INDEX(commentProfileId),
	INDEX(commentTrailId),
	FOREIGN KEY(commentProfileId) REFERENCES profile(profileId),
	FOREIGN KEY(commentTrailId) REFERENCES trail(trailId),
	PRIMARY KEY(commentId)
);