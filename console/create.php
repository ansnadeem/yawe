<?php
    $dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=pass1234") or die('Could not connect: ' . pg_last_error());

    // Performing SQL query
    $query = "SELECT * FROM ActivityTypes;";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());

    $data_array = array();
    while ($data = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $data_array[] = $data;
    }

    echo "<script>";
    echo "activityTypes=[";
    foreach ($data_array as $data) {
        echo "{id:".$data["id"].", typename:'".$data["activitytypename"]."', description:'".$data["Description"]."'}";
    }
    echo "]\n";
    echo "</script>\n";
    // Free resultset
    pg_free_result($result);


    $query = "SELECT * FROM ParameterTypes;";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());

    $parameterTypesArray = array();
    while ($data = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        $parameterTypesArray[] = $data;
    }

    echo "<script>";
    echo "activityParameters=[";
    foreach ($parameterTypesArray as $data) {
        echo "{id:".$data["id"].", activitytype:".$data["activitytype"].", parametername:'".$data["parametername"]."', description:'".$data["Description"]."'}";
    }
    echo "]\n";
    echo "</script>\n";

    pg_free_result($result);


    // Closing connection
    pg_close($dbconn);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>YAWE - Create workflow</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <link href="css/jquery.datetimepicker.min.css" rel="stylesheet"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="index.html">YAWE</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Home</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
                            <div class="sb-sidenav-menu-heading">Workflows</div>
                            <a class="nav-link" href="create.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
                                Create Workflow
                            </a>
                            <a class="nav-link" href="workflows.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>
                                All Workflows
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid">
                        <h1 class="mt-4">Workflows</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Create Workflow</li>
                        </ol>
                        <div class="mb-4">
                            <form action="create_workflow.php" method="post">
                                <div class="form-group">
                                    <label class="small mb-1" for="name">Workflow Name</label>
                                    <input class="form-control py-4" id="name" name="name" type="text" placeholder="Enter workflow name" />
                                </div>
                                <div class="form-group">
                                    <label class="small mb-1" for="nextRun">Next Run</label>
                                    <input autocomplete="off" class="form-control py-4" id="nextRun" name="nextRun" type="text" placeholder="Schedule when to run" />
                                </div>
                                <div class="form-group">
                                    <label class="small mb-1" for="times_to_run">Times to Run (Enter -1 for unlimited)</label>
                                    <input class="form-control py-4" id="times_to_run" name="times_to_run" type="text" placeholder="Enter the number of times you want this workflow to run"/>
                                </div>
                                <h5 class="mt-4">Add activities in the workflow in sequence</h5>
                                <div class="form-group d-flex flex-column">
                                    <div class="d-flex py-4">
                                        <div>
                                            <?php 
                                                // Printing results in HTML
                                                echo "<select id='activityType' name='activityType' class='form-control'>\n";
                                                echo "<option value=0>Select an activity type</option>\n";
                                                foreach ($data_array as $data) {
                                                    echo "<option value='".$data["id"]."'>".$data["activitytypename"]."</option>\n";
                                                }
                                                echo "</select>\n";
                                            ?>
                                        </div>
                                        <div class="d-flex ml-2">
                                            <a class="btn btn-secondary" id="addActivity">Add Activity</a>
                                        </div>
                                    </div>
                                    <div>
                                        <table id="activitiesTable" class="table table-bordered" width="100%">
                                        </table>
                                    </div>
                                </div>

                                <input type="submit" class="btn btn-primary" value="Create"/>
                            </form>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="js/jquery.datetimepicker.full.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        
    </body>
    <script>
        currentActivities = []
        // dataTable = null

        var dataTable = $('#activitiesTable')[0];
        //dataTable.innerHTML = '<thead><th>Id</th><th>Activity Name</th><th>Activity Parameters</th></thead>';
        dataTable.innerHTML = '<thead><th>Activity Name</th><th>Activity Parameters</th></thead>';
        var tableBody = document.createElement("tbody");
        function addActivity(i){
            var row = document.createElement("tr");
            //var col1 = document.createElement("td");
            var col2 = document.createElement("td");
            var col3 = document.createElement("td");

            //col1.innerHTML = "<input class='d-none' readonly='true' name='activity_id[]' value='"+i.id+"'/>";
            col2.innerHTML = i.typename + "<input type='hidden' readonly='true' name='activity_id[]' value='"+i.id+"'/>";


            let specificParameters = []
            for(var j of activityParameters){
                if (j.activitytype == i.id){
                    specificParameters.push(j)
                }
            }

            for(var j of specificParameters){
                col3.innerHTML = col3.innerHTML + j.parametername + ": <input name='activityparms_"+currentActivities.length+"[]'/>\n";
            }
            //row.appendChild(col1);
            row.appendChild(col2);
            row.appendChild(col3);
            tableBody.appendChild(row);
            dataTable.appendChild(tableBody);
            currentActivities.push(i);
        }

        $(document).ready(function() {
            currentDate = new Date()
            $('#nextRun').datetimepicker({
                format:'Y-m-d H:i',
                minDate: currentDate.getMonth()+"/"+currentDate.getDay()+"/"+currentDate.getYear(),
                minTime: currentDate.getTime()
            });
            $('#addActivity').click(function(){
                toAdd = null;
                for(var i of activityTypes)
                {
                    if (i.id == $('#activityType').val()){
                        toAdd = i;
                        break;
                    }
                }
                if (toAdd == null){
                    alert("Please select a activity type to add first");
                }
                else{
                    addActivity(toAdd);
                }
            })
        })
    </script>
</html>