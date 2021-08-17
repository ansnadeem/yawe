import threading
import time
from datetime import datetime
from datetime import timedelta

from database.database import UnitOfWork
from database.repositories import WorkflowRepository
from database.repositories import ActivityRepository
from database.repositories import ParameterRepository

from activities.baseActivity import BaseActivity
from activities.printActivity import PrintActivity

import workflow as wf
import activities.mapper as mapper

class Engine:
    def __init__(self):
        print("Engine has started")

    def start(self):
        unitOfWork = UnitOfWork()
        workflowRepository = WorkflowRepository()
        activityRepository = ActivityRepository()
        parameterRepository = ParameterRepository()

        while(True):
            print("[EVENT] Getting all workflows")
            workflows = workflowRepository.getAllWorkflows(unitOfWork)
            print("- "+str(len(workflows))+" found. Checking if any workflow is due to run ...")
            for workflow in workflows:
                w_nextRun = workflow[3]
                print(w_nextRun)
                if (w_nextRun == datetime.now()):
                    print ("[EVENT] Scheduled workflow triggered. Building up workflow to prepare for execution...")

                    w_id = workflow[0]
                    w_name = workflow[1]
                    w_nextRun = workflow[3]
                    times_to_run = workflow[4]

                    workflowActivities = activityRepository.getWorkflowActivitiesWithTypeInformation(unitOfWork, w_id)
                    w_activities = []
                    workflow_string = ''
                    for activity in workflowActivities:
                        #a_id = activity[0]
                        a_type = activity[2]
                        parm_tuples = parameterRepository.getActivityParameters(unitOfWork, a_type)
                        a_parms = [x[2] for x in parm_tuples]
                        mappedActivity = mapper.LoadActivityTypes(a_type, a_parms)
                        w_activities.append(mappedActivity)
                        workflow_string += activity[4]
                        if (activity!= workflowActivities[len(workflowActivities)-1]):
                            workflow_string += '->'
                    print("--- ACTIVITIES: " + workflow_string)
                    workflowObject = {
                        "id": w_id,
                        "name": w_name,
                        #"lastrun": w_lastRan,
                        "nextrun": w_nextRun,
                        "activities": w_activities
                    }

                    
                    print ("----------------------- Workflow Metadata end -----------------------\n")

                    th = threading.Thread(target=wf.runWorkflow, args=(workflowObject,))
                    th.start()
                    workflowRepository.setLastRun(unitOfWork, w_id, datetime.now())
                    workflowRepository.setNextRun(unitOfWork, w_id, datetime.now() + timedelta(min=5)) # run it with our time interval
                    #wf.runWorkflow(workflowObject)
                    if (times_to_run != -1):
                        if (times_to_run == 1):
                            print("[EVENT] Deleting workflow. Workflow has been executed maximum number times.")
                        else:
                            workflowRepository.setWorkflowExecutionCount(unitOfWork, w_id, times_to_run-1)
            #break
            time.sleep(10)
             # DEBUG Mode, Only try executing one workflow then terminate