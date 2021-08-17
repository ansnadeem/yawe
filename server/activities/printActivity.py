from activities.baseActivity import BaseActivity

class PrintActivity(BaseActivity):

    def __init__(self, parameters):
        self.printString = parameters[0]
    
    def run(self):
        print(self.printString)
        return True