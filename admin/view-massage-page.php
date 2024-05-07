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
$sql = "SELECT * FROM contact_messages";
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
            <h3 class="page-header">འབྲེལ་གཏུགས།</h3>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    ཡིག་ཆུང་བལྟ་ཀློག
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body" >
                    <div class="table-responsive">
                    
                                <?php
                               $id = $_GET['id'];
                                   
                                    $sql = "SELECT * FROM contact_messages WHERE id=?";
                                    $result = $db->prepare($sql);
                                    $result->execute(array($id));
                                    $post = $result->fetch(PDO::FETCH_ASSOC);
                                    if ($post) 
                                   {
                                ?>
                                <div style="padding: 10px;">
                              
                                    <p><span style="padding: 6px;margin-top: 10px;color: #999;">ཡིག་ཆ་ཨང་། </span> <?php echo $post['id']; ?></p>
                                    <p><span style="padding: 6px;margin-top: 10px;color: #999;">མིང་། </span><?php echo $post['name']; ?></p>
                                    <p><span style="padding: 6px;margin-top: 10px;color: #999;">གློག་འཕྲིན། </span><?php echo $post['email']; ?></p>
                                    <p><span style="padding: 6px;margin-top: 10px;color: #999;">ཡིག་ཆུང་། </span><?php echo $post['message']; ?></p>
                                    <p><span style="padding: 6px;margin-top: 10px;color: #999;">ཟླ་ཚེས། </span><?php echo $post['created']; ?></p>
                                    <p><a class="btn btn-sm btn-info" href="view-contact-massage.php">རེའུ་མིག</a></p>
                                </div>
                                <?php } ?>
                           
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