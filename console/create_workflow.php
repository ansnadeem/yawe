<?php
    $workflowName = $_POST['name'];
    $workflowNextRun = $_POST['nextRun'];
    $dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=pass1234") or die('Could not connect: ' . pg_last_error());
    $activityIds = $_POST['activity_id'];
    $times_to_run = $_POST['times_to_run'];
    // Performing SQL query
    $query = "INSERT INTO workflows VALUES(DEFAULT,'".$workflowName."', NULL, '".$workflowNextRun."','".$times_to_run."') RETURNING id;";
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());
    $workflowID = pg_fetch_row($result, null, PGSQL_ASSOC)['id'];
    $activitiesCount = count($activityIds);

    for ($i = 0; $i < $activitiesCount; $i++) {
        $parameters = $_POST['activityparms_'.$i];
        $query = "INSERT INTO activities VALUES(DEFAULT,'".$workflowID."', ".$activityIds[$i].") RETURNING id;";
        $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        $activityIDinDB = pg_fetch_row($result, null, PGSQL_ASSOC)['id'];
        foreach($parameters as $param){
            //$query = "INSERT INTO parameters VALUES(DEFAULT,'".$activityIds[$i]."', NULL, '".$param."');"; Commenting this because not keeping parameter type now
            $query = "INSERT INTO parameters VALUES(DEFAULT,'".$activityIDinDB."', '".$param."');";
            $result = pg_query($query) or die('Query failed: ' . pg_last_error());
        }
    }

    // Printing results in HTML

    // Free resultset
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
        <title>YAWE - Creation Successful</title>
        <link href="css/styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
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
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <div class="b-4">
                        <div class="alert alert-success" role="alert">
                            Successfuly created workflow. <a href="workflows.php">Click here to see all workflows</a>
                        </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    </body>
</html>