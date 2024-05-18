<?php 
require_once('../includes/connect.php');
include('includes/check-login.php');
include('includes/check-subscriber.php');
include('includes/header.php');
include('includes/navigation.php');
// get number of per page results from settings table
$rppsql = "SELECT * FROM settings WHERE name='resultsperpage'";
$rppresult = $db->prepare($rppsql);
$rppresult->execute();
$rpp = $rppresult->fetch(PDO::FETCH_ASSOC);
$perpage = $rpp['value'];

if(isset($_GET['page']) & !empty($_GET['page'])){
  $curpage = $_GET['page'];
}else{
  $curpage = 1;
}
// get the number of total posts from posts table
$sql = "SELECT * FROM tensum";
$result = $db->prepare($sql);
$result->execute();
$totalres = $result->rowCount();

// create startpage, nextpage, endpage variables with values
$endpage = ceil($totalres/$perpage);
$startpage = 1;
$nextpage = $curpage + 1;
$previouspage = $curpage - 1;
$start = ($curpage * $perpage) - $perpage; 
?>
<div id="page-wrapper" style="min-height: 345px;">
    <div class="row">
        <div class="col-lg-12">
            <h3 class="page-header">དཀར་ཆག</h3>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    དཀར་ཆག་ཡོངས། 
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body" >
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ཨང་།</th>
                                    <th>བོད་ཡིག</th>
                                    <th>དབྱིན་ཡིག</th>
                                    <th>བསྐྱར་བཅོས་ཟླ་ཚེས།</th>
                                    <th>བྱེད་སྒོ།</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql = "SELECT * FROM menu LIMIT $start, $perpage";
                                    $result = $db->prepare($sql);
                                    $result->execute();
                                    $res = $result->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($res as $cat) {
                                ?>
                                <tr>
                                    <td><?php echo $cat['id']; ?></td>
                                    <td><?php echo $cat['bo_title']; ?></td>
                                    <td><?php echo $cat['en_title']; ?></td>
                                    <td><?php echo $cat['updated']; ?></td>
                                    <td><a href="edit-menu.php?id=<?php echo $cat['id']; ?>"><span style="color: rgb(5, 182, 158); font-size: 18px; position: relative; bottom: -4px;"><i class='fa fa-edit'></i></span> </a> | <a href="delete-item.php?id=<?php echo $cat['id']; ?>&item=menu"><span style="color: rgb(251, 14, 43); font-size: 18px; position: relative; bottom: -3px;"><i class='fa fa-trash'></span></i></a></td>
                                    
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                    <!-- Pagination -->
                      <ul class="pagination justify-content-center mb-4">
                        <?php if($curpage != $startpage){ ?>
                        <li class="page-item">
                          <a class="page-link" href="?page=<?php echo $startpage; ?>">&laquo; First</a>
                        </li>
                        <?php } ?>
                        <?php if($curpage >= 2){ ?>
                        <li class="page-item">
                          <a class="page-link" href="?page=<?php echo $previouspage; ?>"><?php echo $previouspage; ?></a>
                        </li>
                        <?php } ?>
                        <?php if($curpage != $endpage ){ ?>
                        <li class="page-item">
                          <a class="page-link" href="?page=<?php echo $nextpage; ?>"><?php echo $nextpage; ?></a>
                        </li>
                        <?php } ?>
                        <?php if($curpage != $endpage){ ?>
                        <li class="page-item">
                          <a class="page-link" href="?page=<?php echo $endpage; ?>">&raquo; Last</a>
                        </li>
                        <?php } ?>
                      </ul>
                </div>
                <!-- /.panel-body -->
            </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<?php include('includes/footer.php'); ?>