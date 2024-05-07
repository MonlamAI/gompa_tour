<?php 
// Enable error reporting
error_reporting(E_ALL);

// Display errors
ini_set('display_errors', 1);
session_start();

require_once('includes/connect.php');
include('includes/header.php');

// add the pagination links
?>
<!-- Page Content -->
<style>
  body{
    background-color: color(srgb 0.0208 0.2509 0.495) !important;
  }
</style>
<div  class="container">


  <div style="margin-top: 10px;" class="row">
  <div  class="col-md-8 mx-auto mt-5" style="text-align: center;">
  <img src="vendor/img/logo192x192.png" style="width: 100px;" alt="Monlam Grand Dictionary">
    <h3 style="color:#fdf6b8!important">སྨོན་ལམ་ཚིག་མཛོད་ཆེན་མོ།</h3>
  </div>

    <!-- Blog Entries Column -->
    <div  class="col-md-8 mx-auto mt-5">
    <div class="dropdown">
      <button style="background: none;color: white;"class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
        སྐད་ཡིག
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="#">བོད་ཡིག</a>
        <a class="dropdown-item" href="#">དབྱིན་བོད།</a>
        <a class="dropdown-item" href="#">བོད་དབྱིན།</a>
        <a class="dropdown-item" href="#">བོད་རྒྱ།</a>
        <a class="dropdown-item" href="#">རྒྱ་བོད།</a>
        <a class="dropdown-item" href="#">འཇར་བོད།</a>
        <a class="dropdown-item" href="#">ཧྥ་བོད།</a>
        <a class="dropdown-item" href="#">བོད་ལེཊ</a>
      </div>
    </div>
      
      <form style="margin-top: 18px;" action="search.php?" class="input-group custom-search-form">
                <input name="title" type="text" class="form-control" placeholder="འཚོལ་ཞིབ།...">
                    <span class="input-group-btn">
                        <button style="border-radius: 0px 5px 5px 0px;padding-top: 9px;background-color: color(srgb 0.0081 0.4763 0.994);color: white;" class="btn btn-default" type="submit">འཚོལ།
                            <i class="fa fa-search"></i>
                    </button>
                </span>
        </form>
    
    </div>
    <!-- Blog Entries Column -->
    <div style="border-radius: 0px 0px 0px 0px;" class="col-md-8 mx-auto">

      
      
        <div style="background-color: white; padding: 8px;border-radius: 6px;margin-top: 4px;">
        <table class="col-12">
                  <tr style="font-size: 14px;">
                    <th ><span style="font-weight: 200;">འདི་ནི་ཚིག་མཛོད་མ་དཔེ་ཡིན།</span></th>
                  </tr>
                  <tr style="font-size: 14px;">
                    <td class="p-1"><span style="color: color(srgb 0.1634 0.5031 0.86);">དོན་དམ་བདེན་པ་</td>
                  </tr>
                  <tr>
                  <td class="p-1"><span style="color: color(srgb 0.1634 0.5031 0.86);">དོན་གྱི་བདག་པོ་</td>
                  </tr>
                  <tr>
                    <td class="p-1"><span style="color: color(srgb 0.1634 0.5031 0.86);">དོན་ཆེན་རིག་པ་འཛིན་པ་</td>
                  </tr>
                </table>
        </div>
      <hr>
    </div>

    

  </div>
  <!-- /.row -->
  

</div>
<!-- /.container -->
<?php
include('includes/footer.php');

