from activities.printActivity import PrintActivity

mappings = {}
mappings[1] = PrintActivity

def LoadActivityTypes(activity, parameters):
    mappedActivity = mappings[activity](parameters)
    return mappedActivity