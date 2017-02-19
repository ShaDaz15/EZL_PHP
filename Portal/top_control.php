<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">EZL</a>
        </div>
        <!--<ul class="nav navbar-nav"></ul>-->
        <?php if(!isset($_SESSION['user'])){ ?>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="glyphicon glyphicon-log-in"></span> Login
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <form class="form-inline">
                            <div class="form-group">
                                <label class="sr-only">Username: </label>
                                <input class="form-control" type="text" placeholder="Username" />
                            </div>
                            <div class="form-group">
                                <label class="sr-only">Password: </label>
                                <input class="form-control" type="password" placeholder="Password" />
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="submit" placeholder="Go!" />
                            </div>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
        <?php } else { ?>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="glyphicon glyphicon-log-in"></span>User
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="#">Account</a>
                        <a href="#">Options</a>
                        <a href="#">Logout</a>
                    </li>
                </ul>
            </li>
        </ul>
        <?php } ?>
    </div>
</nav>