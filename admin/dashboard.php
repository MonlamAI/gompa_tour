<?php 
require_once('../includes/connect.php');
include('includes/check-login.php');
include('includes/check-subscriber.php');
include('includes/header.php');
include('includes/navigation.php');  
?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">འཛིན་སྐྱོང་མདུན་ངོས།</h3>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <!-- /རྟེན་བཤད། -->
        <div class="col-lg-4 col-md-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                        <i class='fa fa-american-sign-language-interpreting fa-5x'></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <?php 
                                $sql = "SELECT * FROM tensum";
                                $result = $db->prepare($sql);
                                $result->execute();
                                $comments = $result->fetchAll(PDO::FETCH_ASSOC); 
                                $commentscount = $result->rowCount();
                             ?>
                            <div class="huge"><?php echo $commentscount; ?></div>
                            <div>རྟེན་བཤད་བསྡོམས།</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left"><a href="view-tensum.php">རྟེན་བཤད་ལ་གཟིགས།</a></span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- /གནས་བཤད། -->
        <div class="col-lg-4 col-md-4">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                        <i class='fa fa-bank fa-5x'></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <?php 
                                $sql = "SELECT * FROM organization";
                                $result = $db->prepare($sql);
                                $result->execute(); 
                                $publishedcount = $result->rowCount();
                             ?>
                            <div class="huge"><?php echo $publishedcount; ?></div>
                            <div>གནས་བཤད་བསྡོམས།</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left"><a href="view-organization.php">གནས་བཤད་ལ་གཟིགས།</a></span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <!-- /དུས་ཆེན། -->
        <div class="col-lg-4 col-md-4">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class='fa fa-calendar fa-5x'></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <?php 
                                $sql = "SELECT * FROM events ";
                                $result = $db->prepare($sql);
                                $result->execute(); 
                                $draftcount = $result->rowCount();
                             ?>
                            <div class="huge"><?php echo $draftcount; ?></div>
                            <div>དུས་ཆེན་བསྡོམས།</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left"><a href="view-event.php">དུས་ཆེན་ལ་གཟིགས།</a></span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <!-- /མཆན། -->
        <div class="col-lg-4 col-md-4">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-comments fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <?php 
                                $sql = "SELECT * FROM contact_messages";
                                $result = $db->prepare($sql);
                                $result->execute();
                                $comments = $result->fetchAll(PDO::FETCH_ASSOC); 
                                $commentscount = $result->rowCount();
                             ?>
                            <div class="huge"><?php echo $commentscount; ?></div>
                            <div>འབྲེལ་གཏུགས་ཡིག་ཆུང་བསྡོམས།</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left"><a href="view-contact-massage.php">འབྲེལ་གཏུག་ཡིག་ཆུང་ལ་གཟིགས།</a></span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <!-- /རྩོམ་ཡིག་ཡོངས་གྲགས་བྱས་པ། -->
        <div class="col-lg-4 col-md-4">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-tasks fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <?php 
                                $sql = "SELECT * FROM posts WHERE status='published'";
                                $result = $db->prepare($sql);
                                $result->execute(); 
                                $publishedcount = $result->rowCount();
                             ?>
                            <div class="huge"><?php echo $publishedcount; ?></div>
                            <div>ཡིག་ཆ་ཡོངས་གྲགས་བྱས་པ།</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left"><a href="view-articles.php">ཡིག་ཆ་ཡོངས་གྲགས་བྱས་པར་གཟིགས།</a></span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <!-- /རྩོམ་ཡིག་ཟིན་བྲིས། -->
        <div class="col-lg-4 col-md-4">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                        <i class='fa fa-newspaper-o fa-5x'></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <?php 
                                $sql = "SELECT * FROM posts WHERE status='draft'";
                                $result = $db->prepare($sql);
                                $result->execute(); 
                                $draftcount = $result->rowCount();
                             ?>
                            <div class="huge"><?php echo $draftcount; ?></div>
                            <div>ཡིག་ཆ་ཟིན་བྲིས་བསྡོམས།</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left"><a href="view-articles.php">ཡིག་ཆ་ཟིན་བྲིས་བྱས་པར་གཟིགས།</a></span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <!-- /.panel -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> ཆེས་གསར་ཤོས་ཀྱི་རྟེན་བཤད་རྩོམ་སྒྲིག་བྱས་པ།
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body" style="background-color: color(srgb 0.97 0.97 0.97);">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>ཨང་།</th>
                                            <th>ཁ་བྱང་།</th>
                                            <th>སྒྲིག་མཁན།</th>
                                            <th>ཟླ་ཚེས།</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            //$sql = "SELECT * FROM tensum ORDER BY created DESC LIMIT 5";
                                            $sql = "SELECT tensum.id, tensum.tbtitle, tensum.created, users.username 
                                            FROM tensum 
                                            JOIN users ON tensum.uid = users.id 
                                            ORDER BY tensum.created DESC 
                                            LIMIT 5";
                                            $result = $db->prepare($sql);
                                            $result->execute(); 
                                            $tensums = $result->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($tensums as $tensum) {
                                               
                                         ?>
                                        <tr>
                                            <td><?php echo $tensum['id']; ?></td>
                                            <td><?php echo $tensum['tbtitle']; ?></td>
                                            <td><?php echo $user['username']; ?></td>
                                            <td><?php echo $tensum['created']; ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.col-lg-4 (nested) -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            
        </div>
        <div class="col-lg-12">
            <!-- /.panel -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> ཆེས་གསར་ཤོས་ཀྱི་གནས་བཤད་རྩོམ་སྒྲིག་བྱས་པ།
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body" style="background-color: color(srgb 0.97 0.97 0.97);">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                    <tr>
                                        <th>ཨང་།</th>
                                        <th>ཁ་བྱང་།</th>
                                        <th>སྒྲིག་མཁན།</th>
                                        <th>ཟླ་ཚེས།</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            //$sql = "SELECT * FROM organization ORDER BY created DESC LIMIT 5";
                                            $sql = "SELECT organization.id, organization.tbtitle, organization.created, users.username 
                                            FROM organization 
                                            JOIN users ON organization.uid = users.id 
                                            ORDER BY organization.created DESC 
                                            LIMIT 5";
                                            $result = $db->prepare($sql);
                                            $result->execute(); 
                                            $organizations = $result->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($organizations as $organization) {
                                                
                                            
                                         ?>
                                        <tr>
                                            <td><?php echo $organization['id']; ?></td>
                                            <td><?php echo $organization['tbtitle']; ?></td>
                                            <td><?php echo $user['username']; ?></td>
                                            <td><?php echo $organization['created']; ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.col-lg-4 (nested) -->
                    </div>
                    <!-- /.row -->
                </div>
                
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            
        </div>
        <div class="col-lg-12">
            <!-- /.panel -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> ཆེས་གསར་ཤོས་ཀྱི་དུས་ཆེན་རྩོམ་སྒྲིག་བྱས་པ།
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body" style="background-color: color(srgb 0.97 0.97 0.97);">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                        <th>ཨང་།</th>
                                            <th>ཁ་བྱང་།</th>
                                            <th>འགོ་འཛུགས།</th>
                                            <th>མཇུག་ཚེས།</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $sql = "SELECT * FROM events ORDER BY created DESC LIMIT 5";
                                            $result = $db->prepare($sql);
                                            $result->execute(); 
                                            $events = $result->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($events as $event) {
                                                $usersql = "SELECT * FROM users WHERE id=?";
                                                $userresult = $db->prepare($usersql);
                                                $userresult->execute(array($event['uid']));
                                                $user = $userresult->fetch(PDO::FETCH_ASSOC);
                                         ?>
                                        <tr>
                                            <td><?php echo $event['id']; ?></td>
                                            <td><?php echo $event['event_tbname']; ?></td>
                                            <td><?php echo $event['start_date']; ?></td>
                                            <td><?php echo $event['end_date']; ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.col-lg-4 (nested) -->
                    </div>
                    <!-- /.row -->
                </div>
                
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            
        </div>
        <div class="col-lg-8">
            <!-- /.panel -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> ཉེར་ཆར་གྱི་རྩོམ་ཡིག
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body" style="background-color: color(srgb 0.97 0.97 0.97);">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                        <th>ཨང་།</th>
                                            <th>ཁ་བྱང་།</th>
                                            <th>སྒྲིག་མཁན།</th>
                                            <th>གནས་བབ།</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            $sql = "SELECT * FROM posts ORDER BY created DESC LIMIT 5";
                                            $result = $db->prepare($sql);
                                            $result->execute(); 
                                            $posts = $result->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($posts as $post) {
                                                $usersql = "SELECT * FROM users WHERE id=?";
                                                $userresult = $db->prepare($usersql);
                                                $userresult->execute(array($post['uid']));
                                                $user = $userresult->fetch(PDO::FETCH_ASSOC);
                                         ?>
                                        <tr>
                                            <td><?php echo $post['id']; ?></td>
                                            <td><?php echo $post['tbtitle']; ?></td>
                                            <td><?php echo $user['username']; ?></td>
                                            <td><?php echo $post['status']; ?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.col-lg-4 (nested) -->
                    </div>
                    <!-- /.row -->
                </div>
                
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            
        </div>
        <!-- /.col-lg-8 -->
        <div class="col-lg-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> ཉེ་ཆར་གྱི་མཆན།
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body" style="background-color: color(srgb 0.97 0.97 0.97);">
                    <div class="list-group">
                        <?php 
                            foreach ($comments as $comment) {
                         ?>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-comment fa-fw"></i> <?php echo substr($comment['comment'],0,10); ?>
                            <span class="pull-right text-muted small"><em><?php echo $comment['created']; ?></em>
                            </span>
                        </a>
                    <?php } ?>
                    </div>
                    <!-- /.list-group -->
                    <a href="comments.php" class="btn btn-default btn-block">མཆན་ཡོངས་ལ་གཟིགས།</a>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            
        </div>
        <!-- /.col-lg-4 -->
        
        
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->
<?php include('includes/footer.php'); ?>
