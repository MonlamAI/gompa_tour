<?php 
require_once('../includes/connect.php');
include('includes/check-login.php');
//include('includes/check-subscriber.php');
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
// get the number of total tensum from tensum table
if($user['role'] == 'administrator'){
    $sql = "SELECT * FROM tensum";
    $result = $db->prepare($sql);
    $result->execute();
    $totalres = $result->rowCount();
}elseif($user['role'] == 'editor'){
    $sql = "SELECT * FROM tensum WHERE uid=?";
    $result = $db->prepare($sql);
    $result->execute(array($_SESSION['id']));
    $totalres = $result->rowCount();
}
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
            <h1 class="page-header">རྟེན་བཤད།</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                   རྟེན་བཤད་རེའུ་མིག 
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body" >
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>བོད་ཡིག་མཚན་བྱང་།</th>
                                    <th>དབྱིན་ཡིག་མཚན་བྱང་།</th>
                                    <th>རྩོམ་སྒྲིག་པ།</th>
                                    <th>སྡེ་ཚན།</th>
                                    <th>པར་རིས།</th>
                                    <th>ཟླ་ཚེས།</th>
                                    <th>གནས་བབ།</th>
                                    <th>ལག་བསྟར།</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $sql = "SELECT * FROM users WHERE id=?";
                                    $result = $db->prepare($sql);
                                    $result->execute(array($_SESSION['id']));
                                    $user = $result->fetch(PDO::FETCH_ASSOC); 
                                    if($user['role'] == 'administrator'){
                                        $sql = "SELECT * FROM tensum LIMIT $start, $perpage";
                                        $result = $db->prepare($sql);
                                        $result->execute();
                                    }elseif($user['role'] == 'editor'){
                                        $sql = "SELECT * FROM tensum WHERE uid=? LIMIT $start, $perpage";
                                        $result = $db->prepare($sql);
                                        $result->execute(array($_SESSION['id'])); 
                                    }
                                    $res = $result->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($res as $post) {
                                    // TODO : Only user with administrator privillages or user who created the article can only edit or delete post

                                    $catsql = "SELECT categories.title FROM categories INNER JOIN post_categories ON post_categories.cid=categories.id WHERE post_categories.pid=?";
                                    $catresult = $db->prepare($catsql);
                                    $catresult->execute(array($post['id']));
                                    $categories = $catresult->fetchAll(PDO::FETCH_ASSOC);

                                    $usersql = "SELECT * FROM users WHERE id=?";
                                    $userresult = $db->prepare($usersql);
                                    $userresult->execute(array($post['uid']));
                                    $user = $userresult->fetch(PDO::FETCH_ASSOC);
                                ?>
                                <tr>
                                    <td><?php echo $post['id']; ?></td>
                                    <td><?php echo $post['tbtitle']; ?></td>
                                    <td><?php echo $post['entitle']; ?></td>
                                    <td><a href="edit-user.php?id=<?php echo $user['id']; ?>"><?php echo $user['username']; ?></a></td>
                                    <td><?php foreach ($categories as $cat) {echo $cat['title'].", ";} ?></td>
                                    <td><?php if(isset($post['pic']) & !empty($post['pic'])){ echo "Yes"; }else{ echo "No"; } ?></td>
                                    <td><?php echo $post['updated']; ?></td>
                                    <td><?php echo $post['status']; ?></td>
                                    <td><a href="edit-tensum.php?id=<?php echo $post['id']; ?>">བཟོ་བཅོས།</a> | 
                      
                                    <a href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-delete-url="delete-item.php?id=<?php echo $post['id']; ?>&item=tensum">གསུབ།</a>
                                </td>
                                </tr>
                                <?php } ?>
                                
                            </tbody>
                            <script src="./vendor/jquery/jquery.slim.min.js"></script>
                    <script src="./vendor/jquery/popper.min.js"></script>
                    <script>
                        $(document).ready(function() {
                        $('#deleteConfirmModal').on('show.bs.modal', function (event) {
                            var button = $(event.relatedTarget); // Button that triggered the modal
                            var deleteUrl = button.data('delete-url'); // Extract URL from data-* attribute
                            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                            // Update the modal's confirm button.
                            $(this).find('#deleteConfirmBtn').attr('href', deleteUrl);
                        });
                    });
                    </script>
                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteConfirmModalLabel">རྟེན་བཤད་འདི་གསུབ་པར་ཁག་ཐེག</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ཁྱེད་ཀྱིས་ངེས་པར་དུ་འདི་གསུབ་ཀྱི་ཡིན་ནམ།?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">མིན།</button>
                            <a href="#" class="btn btn-danger" id="deleteConfirmBtn">ཡིན། གསུབ་རོགས།</a>
                        </div>
                        </div>
                    </div>
                    </div>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                    <!-- Pagination -->
                      <ul class="pagination justify-content-center mb-4">
                        <?php if($curpage != $startpage){ ?>
                        <li class="page-item">
                          <a class="page-link" href="?page=<?php echo $startpage; ?>">&laquo; དང་པོ།</a>
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
                          <a class="page-link" href="?page=<?php echo $endpage; ?>">&raquo; མཐའ་མ།</a>
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