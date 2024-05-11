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
      

      <!-- Title -->
      <h4 style="line-height: 50px;"class="mt-4">Privacy Policy for Gonpa Nekor</h4>

      <!-- Author -->
      <p class="lead">Effective Date: Sunday, 12 May 2024</p>
      <p class="lead">This privacy policy (“Policy”) describes how Department of Religion & Culture ("we", "us", or "our") collects, uses, and shares personal information of users of the Gonpa Nekor app. This Policy applies to visitors and users (collectively, "you") of the app. By using Gonpa Nekor, you agree to the collection and use of information in accordance with this Policy.</p>
      <h4>Information Collection and Use</h4>
      <p class="lead">We collect the following types of information about you:
        <ul>
  <li>Personal Information: Information such as your name, email address, and contact details when you register for the app or communicate with us.</li>
  <li>Usage Information: Information about how you use the app, including your interaction with content and services available through Gonpa Nekor.</li>
  <li>Device Information: Information about your device, including hardware model, operating system, unique device identifiers, and mobile network information.</li>
</ul>  

</p>
<h4>Children’s Privacy</h4>
<p class="lead">Gonpa Nekor does not knowingly collect or solicit personal information from children under the age of 13. If you believe that we might have any information from or about a child under 13, please contact us so we can promptly remove the information.</p>
<h4>Use of Collected Information</h4>
<p class="lead">We use the information collected for purposes such as:
  <ul>
  <li>Providing and improving our app</li>
  <li>Communicating with you about updates, support, and administrative messages</li>
  <li>Understanding and analyzing usage trends and preferences</li>
</ul>  
</p>
<h4>Sharing of Information</h4>
<p class="lead">We may share your information with third-party service providers that perform services on our behalf, including marketing, analytics, customer service, and data processing. We do not share your personal information with third parties for their direct marketing purposes without your consent.</p>
<h4>Data Security</h4>
<p class="lead">We take reasonable measures to protect your personal information from loss, theft, misuse, and unauthorized access, disclosure, alteration, and destruction.</p>
<h4>Your Rights</h4>
<p class="lead">You may inquire as to whether we are processing your personal information, request access to your personal information, and ask that we correct, amend, or delete your personal information where it is inaccurate or has been processed in violation of your rights.</p>
<h4>Changes to This Privacy Policy</h4>
<p class="lead">We may update this Policy from time to time. You are advised to review this Policy periodically for any changes.</p>
<h4>Contact Us</h4>
<p class="lead">If you have any questions about this Policy, please contact us at  <span style="color:blue">religion@tibet.net</span>
<br> .</p>
<br>

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