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
                    འབྲེལ་གཏུགས་དང་འབྲེལ་བའི་ཡིག་ཆུང་ཁག 
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body" >
                    <div class="table-responsive">
                    <form action="delete-multiple-contacts.php" method="post"> <!-- Form to handle deletion -->
                        <table class="table table-hover" style="border-bottom: 1px solid #ddd;">
                            <thead>
                                <tr>
                                    <th>རྟགས།</th>    
                                    <th>ཨང་།</th>
                                    <th>མིང་།</th>
                                    <th>གློག་འཕྲིན།</th>
                                    <th>ཡིག་ཆུང་།</th>
                                    <th>ཟླ་ཚེས།</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql = "SELECT * FROM contact_messages LIMIT $start, $perpage";
                                    $result = $db->prepare($sql);
                                    $result->execute();
                                    $res = $result->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($res as $cat) {
                                ?>
                                <tr>
                                    <td><input type="checkbox" name="delete_ids[]" value="<?php echo $cat['id']; ?>"></td>
                                    <td><?php echo $cat['id']; ?></td>
                                    <td><?php echo $cat['name']; ?></td>
                                    <td><?php echo $cat['email']; ?></td>
                                    <td><?php echo $cat['message']; ?></td>
                                    <td><?php echo $cat['created']; ?></td>
                                    <td><a class="btn btn-xs btn-info" href="view-massage-page.php?id=<?php echo $cat['id']; ?>">ཡིག་ཆུང་ཀློག</a>  <a class="btn btn-xs btn-danger" href="delete-contact.php?id=<?php echo $cat['id']; ?>&item=contact">གསུབ།</a></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <input type="submit" name="submit" value="རྟགས་རྒྱབ་ཟིན་པ་གསུབ།" class="btn btn-xs btn-danger">
                        </form>
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