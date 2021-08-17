class WorkflowRepository:
    def getAllWorkflows(self, unitOfWork):
        return unitOfWork.executeAndFetch("SELECT * FROM Workflows;")

    def setWorkflowExecutionCount(self, unitOfWork, workflowId, newValue):
        unitOfWork.execute('UPDATE workflows SET TimesToRun={} WHERE id={}'.format(str(newValue), str(workflowId)))

    def setLastRun(self, unitOfWork, workflowId, newValue):
        unitOfWork.execute('UPDATE workflows SET lastRun={} WHERE id={}'.format(str(newValue), str(workflowId)))

    def setNextRun(self, unitOfWork, workflowId, newValue):
        unitOfWork.execute('UPDATE workflows SET nextRun={} WHERE id={}'.format(str(newValue), str(workflowId)))

    def deleteWorkflow(self, unitOfWork, workflowId):
        unitOfWork.execute("DELETE FROM workflows WHERE id={}".format(str(workflowId)))


class ActivityRepository:
    def getWorkflowActivities(self, unitOfWork, workflowId):
        return unitOfWork.executeAndFetch("SELECT * FROM Activities WHERE workflowId={};".format(str(workflowId)))

    def getWorkflowActivitiesWithTypeInformation(self, unitOfWork, workflowId):
        return unitOfWork.executeAndFetch("SELECT * FROM Activities INNER JOIN ActivityTypes on activitytypes.id = activities.activitytype WHERE workflowId={};".format(str(workflowId)))
class ParameterRepository:
    def getActivityParameters(self, unitOfWork, activityId):
        return unitOfWork.executeAndFetch("SELECT * FROM Parameters WHERE activityId={};".format(str(activityId)))