-- DROP TABLE Workflows CASCADE;
-- DROP TABLE ActivityTypes CASCADE;
-- DROP TABLE ParameterTypes CASCADE;
-- DROP TABLE Activities CASCADE;
-- DROP TABLE Parameters CASCADE;

CREATE TABLE IF NOT EXISTS Workflows(
    Id SERIAL PRIMARY KEY,
    WorkflowName VARCHAR(50),
    LastRun TIMESTAMP,
    NextRun TIMESTAMP,
    TimesToRun INT
);

CREATE TABLE IF NOT EXISTS ActivityTypes(
    Id SERIAL PRIMARY KEY,
    ActivityTypeName VARCHAR(50),
    "Description" VARCHAR(500)
);
CREATE TABLE IF NOT EXISTS ParameterTypes(
    Id SERIAL PRIMARY KEY,
    ActivityType INT,
    ParameterName VARCHAR(50),
    LogicalType VARCHAR(50),
    "Description" VARCHAR(500),
    FOREIGN KEY (ActivityType) REFERENCES ActivityTypes(Id)
);
CREATE TABLE IF NOT EXISTS Activities(
    Id SERIAL PRIMARY KEY,
    WorkflowId INT,
    ActivityType INT,
    FOREIGN KEY (WorkflowId) REFERENCES Workflows(Id) ON DELETE CASCADE,
    FOREIGN KEY (ActivityType) REFERENCES ActivityTypes(Id)
);
CREATE TABLE IF NOT EXISTS Parameters(
    Id SERIAL PRIMARY KEY,
    ActivityId INT,
    --ParameterType INT, NOT NECESSARY
    ParameterValue VARCHAR(100),
    FOREIGN KEY (ActivityId) REFERENCES Activities(Id)
    --FOREIGN KEY (ParameterType) REFERENCES ParameterTypes(Id) NOT NECESSARY
);