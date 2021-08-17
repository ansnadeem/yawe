def runWorkflow(workflowObject):
    workflowActivities = workflowObject['activities']
    for activity in workflowActivities:
        activity.run()