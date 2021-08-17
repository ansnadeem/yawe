from database import configure
import yawe

from activities.baseActivity import BaseActivity
from activities.printActivity import PrintActivity

configure.loadSchema() # Ensure schema, create tables if necessary
configure.loadAllActivityTypes() # Ensure required pre-populated activities that user can select from

engine = yawe.Engine()
engine.start()