<!DOCTYPE html>
<html>
    <head>
        <title>Inventory | JayHawk Charity Admin</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Bootstrap -->
        <link rel="stylesheet" media="screen" href="../bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" media="screen" href="../bootstrap/css/bootstrap-theme.min.css">

        <!-- Bootstrap Admin Theme -->
        <link rel="stylesheet" media="screen" href="../bootstrap/css/bootstrap-admin-theme.css">

        <!-- Datatables -->
        <link rel="stylesheet" media="screen" href="../bootstrap/css/DT_bootstrap.css">

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
           <script type="text/javascript" src="js/html5shiv.js"></script>
           <script type="text/javascript" src="js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="bootstrap-admin-with-small-navbar">
        <!-- small navbar -->
        <nav class="navbar navbar-default navbar-fixed-top bootstrap-admin-navbar-sm" role="navigation">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="collapse navbar-collapse">
                            <ul class="nav navbar-nav navbar-right">                              
                                <li>
                                    <a href="#">Go to frontend <i class="glyphicon glyphicon-share-alt"></i></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#" role="button" class="dropdown-toggle" data-hover="dropdown"> <i class="glyphicon glyphicon-user"></i> Cheng-Yeh <i class="caret"></i></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="">Logout</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- main / large navbar -->
        <nav class="navbar navbar-default navbar-fixed-top bootstrap-admin-navbar bootstrap-admin-navbar-under-small" role="navigation">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".main-navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="index.html">Admin Panel</a>
                        </div>
                    </div>
                </div>
            </div><!-- /.container -->
        </nav>

        <div class="container">
            <!-- left, vertical navbar & content -->
            <div class="row">
                <!-- left, vertical navbar -->
                <div class="col-md-2 bootstrap-admin-col-left">
                    <ul class="nav navbar-collapse collapse bootstrap-admin-navbar-side">
                        <li>
                            <a href="index.php"><i class="glyphicon glyphicon-chevron-right"></i> Recently Added</a>
                        </li>
                        <li>
                            <a href="users.php"><span class="badge pull-right"> 6 </span> Users</a>
                        </li>
                        <li class="active">
                            <a href="inventory.php"><span class="badge pull-right"> 4 </span> Inventory</a>
                        </li>
                        <li>
                            <a href="incDonation.php"><span class="badge pull-right"> 4 </span> Incoming Donation</a>
                        </li>
                        <li>
                            <a href="outDonation.php"><span class="badge pull-right"> 20 </span> Outgoing Donation</a>
                        </li>
                    </ul>
                </div>

                <!-- content -->
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="page-header">
                                <h1>Inventory<div class="pull-right"><input type="submit" id="button" class="btn btn-sm btn-primary"  name="action" value="Generate Report"></div></h1>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="text-muted bootstrap-admin-box-title">Inventory</div>
                                    <div class="pull-right"><span class="badge"> 20 </span></div>
                                </div>
                                <div class="bootstrap-admin-panel-content">                               
                                    <table class="table bootstrap-admin-table-with-actions">
                                        <thead>
                                            <tr>
                                                <th>Item ID</th>
                                                <th>Name</th>
                                                <th>Amount</th>
                                                <th>Threshold</th>
                                                <th>Request</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        	<tr>
                                                <td>150</td>
                                                <td>Towel</td>
                                                <td><input type="number" name="amount"  value="54"></td>
                                                <td><input type="number" name="threshold" value="200"></td>
                                                <td>
                                                	<select name='request'>
                                                		<option selected>Pending request</option>
                                                		<option>Docia Bobby (5)</option>
                                                		<option>Edison Averill (7)</option>
                                                	</select>
												</td>
                                                <td>
                                                	<input type="submit" id="button" class="btn btn-sm btn-primary"  name="action" value="Edit">
	                                            </td>
                                            </tr>
                                            <tr>
                                                <td>231</td>
                                                <td>Computer</td>
                                                <td><input type="number" name="amount"  value="12"></td>
                                                <td><input type="number" name="threshold" value="30"></td>
                                                <td>
                                                	<select name='request'>
                                                		<option selected>No request</option>
                                                	</select>
												</td>
                                                <td>
                                                	<input type="submit" id="button" class="btn btn-sm btn-primary"  name="action" value="Edit">
	                                            </td>
                                            </tr>
                                            <tr>
                                                <td>341</td>
                                                <td>Pencil</td>
                                                <td><input type="number" name="amount"  value="119"></td>
                                                <td><input type="number" name="threshold" value="300"></td>
                                                <td>
                                                	<select name='request'>
                                                		<option selected>Pending request</option>
                                                		<option>Sheryll Idonea (10)</option>
                                                		<option>Kristopher Jodene (5)</option>
                                                	</select>
												</td>
                                                <td>
                                                	<input type="submit" id="button" class="btn btn-sm btn-primary"  name="action" value="Edit">
	                                            </td>
                                            </tr>
                                            <tr>
                                                <td>256</td>
                                                <td>Toothbrush</td>
                                                <td><input type="number" name="amount"  value="54"></td>
                                                <td><input type="number" name="threshold" value="200"></td>
                                                <td>
                                                	<select name='request'>
                                                		<option selected>Pending request</option>
                                                		<option>Beau Hammond (5)</option>
                                                	</select>
												</td>
                                                <td>
                                                	<input type="submit" id="button" class="btn btn-sm btn-primary"  name="action" value="Edit">                                       
	                                            </td>
                                            </tr>                                                                                     
                                        </tbody>
                                    </table>  
                                </div>
                            </div>
                        </div>
                    </div>           
                </div>
            </div>
        </div>

        <!-- footer -->
        <div class="navbar navbar-footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <footer role="contentinfo">
                            <p class="right">&copy; 2016 <a href="#">JayHawk Charity</a></p>
                        </footer>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>