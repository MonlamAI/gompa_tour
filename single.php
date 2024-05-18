<?php 
session_start();

require_once('includes/connect.php');
include('includes/header.php');
include('comment.php');
include('includes/navigation.php'); 


// Check if HTTPS is used, otherwise default to HTTP
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
// Get the server name (e.g., www.example.com)
$serverName = $_SERVER['SERVER_NAME'];
// Get the port number
$port = $_SERVER['SERVER_PORT'];

// If the port is not standard, include it in the URL
if (($protocol === "https://" && $port != 443) || ($protocol === "http://" && $port != 80)) {
    $serverName .= ":" . $port;
}
// Get the web root path (if your application is in a subdirectory e.g., /myapp)
$webRoot = dirname($_SERVER['PHP_SELF']);
// Construct the base URL
$baseUrl = $protocol . $serverName . $webRoot;

$sql = "SELECT * FROM posts WHERE slug=? AND status='published'";
$result = $db->prepare($sql);
$result->execute(array($_GET['url']));
$post = $result->fetch(PDO::FETCH_ASSOC);

$dateTimeString = $post['created'];
$dateTime = new DateTime($dateTimeString);

$usersql = "SELECT * FROM users WHERE id=?";
$userresult = $db->prepare($usersql);
$userresult->execute(array($post['uid']));
$user = $userresult->fetch(PDO::FETCH_ASSOC);
?>
<!-- Page Content -->
<div class="container">

  <div class="row">

    <!-- Post Content Column -->
    <div class="col-lg-8">
    <a href="javascript:history.back()"> <span style="font-size: 22px;position: relative;top: 2px;"><i class='fa fa-angle-left'></i></span> <?php echo htmlspecialchars(translate('go-back'), ENT_QUOTES, 'UTF-8'); ?></a>

      <?php
      if($_SESSION['lang'] === 'en'){
        $titel = $post['entitle'];
        $web_content = $post['encontent'];
      }else if($_SESSION['lang'] === 'bo'){
        $titel = $post['tbtitle'];
        $web_content = $post['tbcontent'];
      }else{
        $titel = $post['entitle'];
        $web_content = $post['encontent'];
      }
      ?>

      <!-- Title -->
      <h2 style="line-height: 50px;"class="mt-4"><?php echo $titel; ?></h2>

      <!-- Author -->
      
      <hr>

      <!-- Date/Time -->
      

      <!-- Preview Image -->
      <?php if(isset($post['pic']) & !empty($post['pic'])){ ?>
          <img style="max-height: 400px;width: 100%;object-fit: cover;" class="img-fluid rounded" src="<?php echo $baseUrl ."/". $post['pic']; ?>" alt="" onerror="this.onerror=null; this.src='vendor/img/noimage.jpg';">
      <?php }else{ ?>
          <img class="img-fluid rounded" src="http://placehold.it/900x300" alt="">
      <?php } ?>
      <hr>

      <!-- Post Content -->
      <div style="line-height: 30px; padding: 0px 0px 100px 0px; text-align: justify; font-family: 'Monlam', Arial, sans-serif; font-size: 14px;" class="content">
        <?php echo $web_content; ?>
      </div>

      <hr>
      <?php
          $comsql = "SELECT * FROM settings WHERE name='comments'";
          $comresult = $db->prepare($comsql);
          $comresult->execute();
          $com = $comresult->fetch(PDO::FETCH_ASSOC);
      ?>
      <?php if($com['value'] == 'yes'){ 
            if(isset($_SESSION['id']) & !empty($_SESSION['id'])){
              // Create CSRF token
              $token = md5(uniqid(rand(), TRUE));
              $_SESSION['csrf_token'] = $token;
              $_SESSION['csrf_token_time'] = time();
        ?>
      <!-- Comments Form -->
      <div class="card my-4">
        <h5 class="card-header">Leave a Comment:</h5>
        <div class="card-body">
          <?php
              if(!empty($messages)){
                  echo "<div class='alert alert-success'>";
                  foreach ($messages as $message) {
                      echo "<span class='glyphicon glyphicon-ok'></span>&nbsp;". $message ."<br>";
                  }
                  echo "</div>";
              }
          ?>
          <?php
              if(!empty($errors)){
                  echo "<div class='alert alert-danger'>";
                  foreach ($errors as $error) {
                      echo "<span class='glyphicon glyphicon-remove'></span>&nbsp;". $error ."<br>";
                  }
                  echo "</div>";
              }
          ?>
          <form method="post">
            <div class="form-group">
              <input type="hidden" name="uid" value="<?php echo $_SESSION['id']; ?>">
              <input type="hidden" name="pid" value="<?php echo $post['id']; ?>">
              <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">
              <textarea class="form-control" name="comment" rows="3" required=""></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
        </div>
      </div>
      <?php }else{ echo "<h6>You Should be LoggedIn to Post Comments.</h6><hr>"; }
            }else{ echo "<h6>". translate('comments-are-disabled') ."</h6><hr>"; } ?>
      <?php
          $sql = "SELECT comments.comment, users.username, users.fname, users.lname, users.role FROM comments INNER JOIN users ON comments.uid=users.id WHERE comments.pid=? AND comments.status='approved' ORDER BY comments.created DESC";
          $result = $db->prepare($sql);
          $result->execute(array($post['id'])) or die(print_r($result->errorInfo(), true));
          $comments = $result->fetchAll(PDO::FETCH_ASSOC);
          foreach($comments as $comment){
      ?>
      <!-- Single Comment -->
      <div class="media mb-4">
        <img class="d-flex mr-3 rounded-circle" src="http://placehold.it/50x50" alt="">
        <div class="media-body">
          <h5 class="mt-0">
              <?php if((isset($comment['fname']) || isset($comment['lname'])) & (!empty($comment['fname']) || !empty($comment['lname']))) { echo $comment['fname'] . " " . $comment['lname']; }else{ echo $comment['username']; } ?> 
              <?php if(($comment['role'] == 'administrator')){ echo "<span class='badge badge-danger'>Admin</span>"; }elseif(($comment['role'] == 'editor')){ echo "<span class='badge badge-primary'>Editor</span>"; } ?>
            </h5>
          <?php echo $comment['comment']; ?>
        </div>
      </div>
      <?php } ?>

    </div>

    <?php include('includes/sidebar.php'); ?>

  </div>
  <!-- /.row -->
  <div style="padding-bottom: 200px;">
       
   </div>

</div>
</div>
<!-- /.container -->
<?php include('includes/footer.php'); ?>