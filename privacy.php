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


?>
<!-- Page Content -->
<div class="container">

  <div class="row">

    <!-- Post Content Column -->
    <div class="col-lg-8">
    <header>
        <h1>Privacy Policy for Gonpa Nekor</h1>
    </header>
    <section>
        <p>Welcome to Gonpa Nekor, the definitive app for exploring the spiritual and cultural heritage of Tibet. This Privacy Policy outlines how we collect, use, and safeguard your information when you use our app. We are committed to protecting your privacy and ensuring that your personal information is handled responsibly.</p>
    </section>
    <section>
        <h2>Information Collection and Use</h2>
        <p>Gonpa Nekor collects information that you provide directly when you interact with the app, including but not limited to, creating an account, submitting queries, or using our services. We also collect technical data about your device and interaction with our app for improving your user experience.</p>
        <ul>
            <li><strong>Personal Identification Information:</strong> We may collect personal identification information including your name, email address, and location to provide a personalized experience.</li>
            <li><strong>Non-Personal Identification Information:</strong> We collect non-personal identification information such as browser name, device type, and technical information about means of connection to our app, such as the operating system and other similar information.</li>
        </ul>
    </section>
    <section>
        <h2>Children's Privacy</h2>
        <p>If your target audience includes children under the age of 13, please note that Gonpa Nekor complies with the Children’s Online Privacy Protection Act (COPPA). We do not knowingly collect personal information from children under 13 without appropriate parental consent. If you believe that we have mistakenly or unintentionally collected such information, please notify us so that we can immediately delete the information from our servers.</p>
    </section>
    <section>
        <h2>Data Security</h2>
        <p>We implement a variety of security measures to maintain the safety of your personal information when you enter, submit, or access your personal information. However, no method of transmission over the internet, or method of electronic storage, is 100% secure. While we strive to use commercially acceptable means to protect your personal information, we cannot guarantee its absolute security.</p>
    </section>
    <section>
        <h2>Changes to This Privacy Policy</h2>
        <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page. You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>
    </section>
    <section>
        <h2>Contact Us</h2>
        <p>If you have any questions about this Privacy Policy, please contact us at [your contact information].</p>
    </section> 


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