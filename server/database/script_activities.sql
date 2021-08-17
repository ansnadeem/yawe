--PRINT ACTIVITY AND ITS PARAMETERS
INSERT INTO ActivityTypes VALUES (1, 'Print Activity', 'An activity that just prints a piece of text') ON CONFLICT DO NOTHING;
INSERT INTO ParameterTypes VALUES (1, 1, 'printStr','string','Value to print') ON CONFLICT DO NOTHING;

