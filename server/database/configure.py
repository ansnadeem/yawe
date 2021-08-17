import json
from database.database import UnitOfWork

def loadSchema():
    unitOfWork = UnitOfWork()
    with open("database/script_schema.sql", "r") as schema_file:
        query = schema_file.read()
        unitOfWork.execute(query)
    unitOfWork.commit()
    del unitOfWork

def getDatabaseConfigurations():
    data = None
    with open("database/config.json", "r") as json_file:
        data = json.load(json_file)
    return data

def loadAllActivityTypes():
    unitOfWork = UnitOfWork()
    with open("database/script_activities.sql", "r") as activities_script:
        query = activities_script.read()
        unitOfWork.execute(query)
    unitOfWork.commit()
    del unitOfWork