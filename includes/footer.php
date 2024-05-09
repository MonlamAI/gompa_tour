<!-- Footer -->
<?php
  

    // Prepare a SQL statement with a parameter
    $copyrightsql = "SELECT * FROM settings WHERE name=:name";
    $copyrightresult = $db->prepare($copyrightsql);

    // Bind the parameter and execute the statement
    $copyrightresult->execute(array(':name' => 'copyright'));

    // Fetch the result
    $copyright = $copyrightresult->fetch(PDO::FETCH_ASSOC);
?>
<footer style="position: absolute;left: 0;bottom: 0;width: 100%;z-index:9999" class="py-5 bg-dark">
  <div class="container">
    <p class="m-0 text-center text-white">© <?php echo date("Y"), " ", htmlspecialchars($copyright['value']); ?></p>
  </div>
  <!-- /.container -->
</footer>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
  window.addEventListener('load', function(){
    alert('སྐད་ཡིག་འདེམས།');
  })
</script>
</body>
</html>
