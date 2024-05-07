<!-- Navigation -->
<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="dashboard.php">འཛིན་སྐྱོང་སྡེ་ཚན།</a>
    </div>
    <!-- /.navbar-header -->

    <ul style="float: right;"class="nav navbar-top-links navbar-right">

        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="profile.php"><i class="fa fa-user fa-fw"></i> ཐོ་ཞུགས་ཡིག་ཆ།</a>
                </li>
                <li><a href="settings.php"><i class="fa fa-gear fa-fw"></i> སྒྲིག་འགོད།</a>
                </li>
                <li class="divider"></li>
                <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> ཕྱིར་འབུད།</a>
                </li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <li class="sidebar-search">
                    <div class="input-group custom-search-form">
                    <form class="input-group custom-search-form">
                <input name="search" type="text" class="form-control" placeholder="འཚོལ་ཞིབ།...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="submit">
                            <i class="fa fa-search"></i>
                    </button>
                </span>
                </form>
                    </div>
                    <!-- /input-group -->
                </li>
                <li>
                    <a href="dashboard.php"><i class="fa fa-dashboard fa-fw"></i> འཛིན་སྐྱོང་མདུན་ངོས།</a>
                </li>
                <li>
                    <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> སྡེ་ཚན་དབྱེ་བ།<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="add-category.php"> སྡེ་ཚན་གསར་སྣོན།</a>
                        </li>
                        <li>
                            <a href="view-categories.php"> སྡེ་ཚན་རེའུ་མིག</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class='fa fa-bars'></i> དཀར་ཆག<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="add-menu.php"> དཀར་ཆག་གསར་སྣོན།</a>
                        </li>
                        <li>
                            <a href="view-menu.php"> དཀར་ཆག་རེའུ་མིག</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class="fa fa-empire"></i> རྟེན་བཤད།<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="add-tensum.php"> རྟེན་བཤད་གསར་སྣོན།</a>
                        </li>
                        <li>
                            <a href="view-tensum.php"> རྟེན་བཤད་རེའུ་མིག</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class="fa fa-institution"></i> གནས་བཤད།<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="add-organization.php">གནས་བཤད་གསར་སྣོན།</a>
                        </li>
                        <li>
                            <a href="view-organization.php">གནས་བཤད་རེའུ་མིག</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class='fa fa-calendar-check-o'></i> དུས་ཆེན།<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="add-event.php">དུས་ཆེན་གསར་སྣོན།</a>
                        </li>
                        <li>
                            <a href="view-event.php">དུས་ཆེན་རེའུ་མིག།</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class='fa fa-pencil-square'></i> ཡིག་ཆ།<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="add-article.php"> ཡིག་ཆ་གསར་སྣོན།</a>
                        </li>
                        <li>
                            <a href="view-articles.php"> ཡིག་ཆའི་རེའུ་མིག</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class="fa fa-file"></i> ཤོག་ངོས།<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="add-page.php"> ཤོག་ངོས་གསར་སྣོན།</a>
                        </li>
                        <li>
                            <a href="view-pages.php"> ཤོག་ངོས་རེའུ་མིག</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class='fa fa-language'></i> ཡིག་སྒྱུར།<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="add-translation.php"> ཡིག་སྒྱུར་གསར་སྣོན།</a>
                        </li>
                        <li>
                            <a href="view-translation.php"> ཡིག་སྒྱུར་རེའུ་མིག</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="comments.php"><i class="fa fa-commenting"></i> མཆན།</a>
                </li>
                <?php 
                    $sql = "SELECT * FROM users WHERE id=?";
                    $result = $db->prepare($sql);
                    $result->execute(array($_SESSION['id']));
                    $user = $result->fetch(PDO::FETCH_ASSOC); 
                    if($user['role'] == 'administrator'){
                ?>
                <li>
                    <a href="#"><i class="fa fa-users"></i> ཐོ་ཞུགས་པ།<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="add-user.php"> ཐོ་ཞུགས་པ་གསར་སྣོན།</a>
                        </li>
                        <li>
                            <a href="view-users.php"> ཐོ་ཞུགས་པའི་རེའུ་མིག</a>
                        </li>
                        <li>
                            <a href="profile.php"> ངའི་ཐོ་དེབ།</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class='fa fa-envelope'></i> འབྲེལ་གཏུགས།<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="view-contact-massage.php"> ཡིག་ཆུང་རེའུ་མིག</a>
                        </li>
                       
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class='fa fa-sticky-note'></i> ཟུར་སྦྱར།<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="add-widget.php"> ཟུར་སྦྱར་གསར་འཇུག</a>
                        </li>
                        <li>
                            <a href="view-widgets.php"> ཟུར་སྦྱར་རེའུ་མིག</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="#"><i class="fa fa-gears"></i> སྒྲིགས་འགོད།<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="settings.php"> དྲ་བའི་སྒྲིགས་འགོད།</a>
                        </li>
                        <li>
                            <a href="#"> སྦྱིར་བཏང་གི་རྩོམ་སྒྲིགས།</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <?php } ?>
            </ul>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
    <!-- /.navbar-static-side -->
</nav>
<!-- jQuery -->



