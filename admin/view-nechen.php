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
// get the number of total organization from organization table
if($user['role'] == 'administrator'){
    $sql = "SELECT * FROM organization";
    $result = $db->prepare($sql);
    $result->execute();
    $totalres = $result->rowCount();
}elseif($user['role'] == 'editor'){
    $sql = "SELECT * FROM organization WHERE uid=?";
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
            <h1 class="page-header">གནས་ཆེན།</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    གནས་བཤད་རྩོམ་སྒྲིག་ཟིན་པ། 
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body" >
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>བོད་མཚན།</th>
                                    <th>དབྱིན་མཚན།</th>
                                    <th>སྒྲིག་མཁན།</th>
                                    <th>སྡེ་ཚན་དབྱེ་བ།</th>
                                    <th>པར་རིས།</th>
                                    <th>ཟླ་ཚེས།</th>
                                    <th>གནས་བབ།</th>
                                    <th>ལས་དོན།</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $sql = "SELECT * FROM users WHERE id=?";
                                    $result = $db->prepare($sql);
                                    $result->execute(array($_SESSION['id']));
                                    $user = $result->fetch(PDO::FETCH_ASSOC); 
                                    if($user['role'] == 'administrator'){
                                        $sql = "SELECT * FROM nechen LIMIT $start, $perpage";
                                        $result = $db->prepare($sql);
                                        $result->execute();
                                    }elseif($user['role'] == 'editor'){
                                        $sql = "SELECT * FROM nechen WHERE uid=? LIMIT $start, $perpage";
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
                                    <td><?php echo $post['categories']; ?></td>
                                    <td><?php if(isset($post['pic']) & !empty($post['pic'])){ echo "Yes"; }else{ echo "No"; } ?></td>
                                    <td><?php echo $post['updated']; ?></td>
                                    <td><?php echo $post['status']; ?></td>
                                    <td><a href="edit-organization.php?id=<?php echo $post['id']; ?>">Edit</a> | 
                                    <a href="#" data-toggle="modal" data-target="#deleteConfirmModal" data-delete-url="delete-item.php?id=<?php echo $post['id']; ?>&item=organization">Delete</a>
                                    
                                </td>
                                </tr>
                                <?php } ?>
                                
                            </tbody>
                        </table>
                    </div>
                    <!-- Bootstrap JS, Popper.js, and jQuery -->

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
                            <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Delete</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this item?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                            <a href="#" class="btn btn-danger" id="deleteConfirmBtn">Yes, Delete</a>
                        </div>
                        </div>
                    </div>
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
