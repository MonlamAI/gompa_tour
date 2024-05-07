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
$sql = "SELECT * FROM translations";
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
            <h3 class="page-header">ཡིག་སྒྱུར་ལྟེ་གནས།</h3>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
          <div>
          <form style="margin-top: 18px;" class="input-group custom-search-form">
    
    <input id="filterInput" style="border-color: rgb(199, 228, 254); background-color: rgb(228, 242, 255);margin-bottom: 10px;"name="search" type="text" class="form-control" placeholder="ཚགས་རྒྱག་ཡུལ།">

           <span class="input-group-btn">
               <button style="border-radius: 0px 5px 5px 0px;background-color: rgb(245, 246, 246);top: -5px;position: relative;" class="btn btn-default" type="submit">
               ཕྱིར་འཐེན།
                   <i class="fa fa-filter"></i>
           </button>
   </span>
   <div style="margin-left: 20px;" class="col-12 p-1">


  
   </div>
   </form>
          </div>
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
                                    <th>སྒྱུར་གཞི་ཨ་མ།</th>
                                    <th>དབྱིན་སྒྱུར།</th>
                                    <th>བོད་སྒྱུར།</th>
                                  
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql = "SELECT * FROM translations LIMIT $start, $perpage";
                                    $result = $db->prepare($sql);
                                    $result->execute();
                                    $res = $result->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($res as $cat) {
                                ?>
                                <tr>
                                    <td><?php echo $cat['id']; ?></td>
                                    <td><?php echo $cat['key_name']; ?></td>
                                    <td><?php echo $cat['text_en']; ?></td>
                                    <td><?php echo $cat['text_bo']; ?></td>
                                    <td><a href="edit-translation.php?id=<?php echo $cat['id']; ?>">བཟོ་བཅོས།</a></td>
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
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const filterInput = document.getElementById('filterInput');
    filterInput.addEventListener('keyup', function() {
      const filterValue = this.value.toLowerCase();
      const tableBody = document.querySelector('.table tbody');
      const rows = tableBody.getElementsByTagName('tr');

      for (let i = 0; i < rows.length; i++) {
        // Columns to be filtered: 'སྒྱུར་གཞི་ཨ་མ།' (1), 'དབྱིན་སྒྱུར།' (2), 'བོད་སྒྱུར།' (3)
        let keyNameCell = rows[i].getElementsByTagName("td")[1];
        let textEnCell = rows[i].getElementsByTagName("td")[2];
        let textBoCell = rows[i].getElementsByTagName("td")[3];

        if (keyNameCell || textEnCell || textBoCell) {
          let textValueKeyName = keyNameCell.textContent || keyNameCell.innerText;
          let textValueTextEn = textEnCell.textContent || textEnCell.innerText;
          let textValueTextBo = textBoCell.textContent || textBoCell.innerText;

          if (textValueKeyName.toLowerCase().indexOf(filterValue) > -1 ||
              textValueTextEn.toLowerCase().indexOf(filterValue) > -1 ||
              textValueTextBo.toLowerCase().indexOf(filterValue) > -1) {
            rows[i].style.display = "";
          } else {
            rows[i].style.display = "none";
          }
        }       
      }
    });
  });
</script>

<?php include('includes/footer.php'); ?>