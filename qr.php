<?php 
session_start();
require_once('includes/connect.php');
include('includes/header.php');
include('includes/navigation.php'); 

if(isset($_GET['qr-link']) & !empty($_GET['qr-link'])){
    $qrlink = $_GET['qr-link'];
    $qrtype = $_GET['qr-type'];
  }else{
    $qrlink = "www.gompatour.com";
  }

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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script async src="./vendor/qr-gnr/js.js"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-149838823-1');
    </script>
    <style>
    @media only screen and (max-width: 768px) {
    /* For mobile phones: */
    .col-6   {
      width: 100% !important;
    }
  }
  @media only screen and (max-width: 768px) {
    /* For mobile phones: */
    [class*="col-"] {
      width: 100%;
    }
  }
    </style>
<link href="vendor/qr-gnr/qr.css" rel="stylesheet">

</head>

   
        <section style="badding-top:80px; background-image: linear-gradient(90deg, rgb(0, 0, 0) 0%, rgb(106, 26, 76) 50%, rgb(255, 255, 255) 100%);display: none;" class="container qr-description" id="qr-description">
            
        </section>
        <section class="container" style="padding-top: 80px;">
            <div class="row row--body">
            <div style="margin-bottom: 15px; margin-left:10px; margin-right: 10px;" class="col- qr-code-container">
                    <div class="qr-code" id="qr-code-generated" style="text-align: center;"></div>
                    <div class="qr-download-group">
                        <button class="btn btn-primary" style="border: none; position: relative; left: -40px;" id="qr-download"><?php echo translate('download');?></button>
                        <label class="hide" for="qr-extension" style="position: relative;display: contents; top: 3px; margin-left: 6px; left: -40px;"><?php echo translate('extension');?></label>
                        <select class="btn btn-primary" id="qr-extension" style="position: relative; right: -40px;">
                            <option value="png" selected="">PNG</option>
                            <option value="jpeg">JPEG</option>
                        </select>
                    </div>
                </div>
                <form class="col- qr-form" id="form">
                    <button type="button" class="accordion accordion--open"><?php echo translate('main-options'); ?></button>
                    <div class="panel panel--open">
                        
                        <input name="qr-link" node="data" node-change-event="oninput" id="form-data"  readonly value="<?php if(isset($qrlink)){ echo $baseUrl. '/'. $qrtype.'.php?url='.$qrlink;} ?>" />
                        <br>
                        <label for="form-image-file"><?php echo translate('your-log-file');?></label>
                        <div class="buttons-container">
                            <input node="image" class="btn btn-primary" node-data-field="files"style="max-width: 350px;" id="form-image-file" type="file" />
                            <button class="btn btn-primary" type="button" id="button-cancel"><?php echo translate('cancel');?></button>
                        </div>
                        <label for="form-width"><?php echo translate('width');?></label>
                        <div>
                            <input node="width" id="form-width" type="number" min="100" max="10000" value="300"/>
                        </div>
                        <label for="form-height"><?php echo translate('height');?></label>
                        <div>
                            <input node="height" id="form-height" type="number" min="100" max="10000" value="300"/>
                        </div>
                        <label for="form-height"><?php echo translate('margin');?></label>
                        <div>
                            <input node="margin" id="form-margin" type="number" min="0" max="10000" value="0"/>
                        </div>
                    </div>
                    <button type="button" class="accordion"><?php echo translate('dots-options');?></button>
                    <div class="panel">
                        <label for="form-dots-type"><?php echo translate('dots-dtyle');?></label>
                        <div>
                            <select node="dotsOptions.type" id="form-dots-type">
                                <option value="square"selected><?php echo translate('square');?></option>
                                <option value="dots"><?php echo translate('dots');?></option>
                                <option value="rounded"><?php echo translate('rounded');?></option>
                                <option value="extra-rounded"><?php echo translate('extra-rounded');?></option>
                                <option value="classy"><?php echo translate('classy');?></option>
                                <option value="classy-rounded"><?php echo translate('classy-rounded');?></option>
                            </select>
                        </div>
                        <label><?php echo translate('color-type');?></label>
                        <div class="space-between-container">
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="dotsOptionsHelper.colorType.single" id="form-dots-color-type-single" type="radio" name="dots-color-type" checked/>
                                <label for="form-dots-color-type-single"><?php echo translate('sngle-color');?></label>
                            </div>
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="dotsOptionsHelper.colorType.gradient" id="form-dots-color-type-gradient" type="radio" name="dots-color-type"/>
                                <label for="form-dots-color-type-gradient"><?php echo translate('color-gradient');?></label>
                            </div>
                        </div>
                        <label class="dotsOptionsHelper.colorType.single" for="form-dots-color"><?php echo translate('dots-color');?></label>
                        <div class="dotsOptionsHelper.colorType.single">
                            <input node="dotsOptions.color" id="form-dots-color" type="color" value="#6a1a4c"/>
                        </div>
                        <label class="dotsOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0"><?php echo translate('gradient-type');?></label>
                        <div class="dotsOptionsHelper.colorType.gradient space-between-container" style="visibility: hidden; height: 0">
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="dotsOptionsHelper.gradient.linear" id="form-dots-gradient-type-linear" type="radio" name="dots-gradient-type" checked/>
                                <label for="form-dots-gradient-type-linear"><?php echo translate('linear');?></label>
                            </div>
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="dotsOptionsHelper.gradient.radial" id="form-dots-gradient-type-radial" type="radio" name="dots-gradient-type"/>
                                <label for="form-dots-gradient-type-radial"><?php echo translate('radial');?></label>
                            </div>
                        </div>
                        <label class="dotsOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0"><?php echo translate('dots-gradient');?></label>
                        <div class="dotsOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0">
                            <input node="dotsOptionsHelper.gradient.color1" type="color" value="#6a1a4c"/>
                            <input node="dotsOptionsHelper.gradient.color2" type="color" value="#6a1a4c"/>
                        </div>
                        <label class="dotsOptionsHelper.colorType.gradient" for="form-dots-gradient-rotation" style="visibility: hidden; height: 0"><?php echo translate('rotation');?></label>
                        <div class="dotsOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0">
                            <input node="dotsOptionsHelper.gradient.rotation" id="form-dots-gradient-rotation" type="number" min="0" max="360" value="0"/>
                        </div>
                    </div>
                    <button type="button" class="accordion"><?php echo translate('corners-square-options');?></button>
                    <div class="panel">
                        <label for="form-corners-square-type"><?php echo translate('corners-square-style');?></label>
                        <div>
                            <select node="cornersSquareOptions.type" id="form-corners-square-type">
                                <option value="">None</option>
                                <option value="square">Square</option>
                                <option value="dot">Dot</option>
                                <option value="extra-rounded" selected>Extra rounded</option>
                            </select>
                        </div>
                        <label><?php echo translate('color-type');?></label>
                        <div class="space-between-container">
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="cornersSquareOptionsHelper.colorType.single" id="form-corners-square-color-type-single" type="radio" name="corners-square-color-type" checked/>
                                <label for="form-corners-square-color-type-single"><?php echo translate('sngle-color');?></label>
                            </div>
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="cornersSquareOptionsHelper.colorType.gradient" id="form-corners-square-color-type-gradient" type="radio" name="corners-square-color-type"/>
                                <label for="form-corners-square-color-type-gradient"><?php echo translate('color-gradient');?></label>
                            </div>
                        </div>
                        <label class="cornersSquareOptionsHelper.colorType.single" for="form-corners-square-color"><?php echo translate('corners-square-color');?></label>
                        <div class="cornersSquareOptionsHelper.colorType.single buttons-container">
                            <input node="cornersSquareOptions.color" id="form-corners-square-color" type="color" value="#000000"/>
                            <button type="button" id="button-clear-corners-square-color">Clear</button>
                        </div>
                        <label class="cornersSquareOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0">Gradient Type</label>
                        <div class="cornersSquareOptionsHelper.colorType.gradient space-between-container" style="visibility: hidden; height: 0">
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="cornersSquareOptionsHelper.gradient.linear" id="form-corners-square-gradient-type-linear" type="radio" name="corners-square-gradient-type" checked/>
                                <label for="form-corners-square-gradient-type-linear">Linear</label>
                            </div>
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="cornersSquareOptionsHelper.gradient.radial" id="form-corners-square-gradient-type-radial" type="radio" name="corners-square-gradient-type"/>
                                <label for="form-corners-square-gradient-type-radial">Radial</label>
                            </div>
                        </div>
                        <label class="cornersSquareOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0">Dots Gradient</label>
                        <div class="cornersSquareOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0">
                            <input node="cornersSquareOptionsHelper.gradient.color1" type="color" value="#000000"/>
                            <input node="cornersSquareOptionsHelper.gradient.color2" type="color" value="#000000"/>
                        </div>
                        <label class="cornersSquareOptionsHelper.colorType.gradient" for="form-corners-square-gradient-rotation" style="visibility: hidden; height: 0">Rotation</label>
                        <div class="cornersSquareOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0">
                            <input node="cornersSquareOptionsHelper.gradient.rotation" id="form-corners-square-gradient-rotation" type="number" min="0" max="360" value="0"/>
                        </div>
                    </div>
                    <button type="button" class="accordion"><?php echo translate('corners-dot-options');?></button>
                    <div class="panel">
                        <label for="form-corners-dot-type"><?php echo translate('corners-dot-style');?></label>
                        <div>
                            <select node="cornersDotOptions.type" id="form-corners-dot-type">
                                <option value="" selected>None</option>
                                <option value="square">Square</option>
                                <option value="dot">Dot</option>
                            </select>
                        </div>
                        <label><?php echo translate('color-type');?></label>
                        <div class="space-between-container">
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="cornersDotOptionsHelper.colorType.single" id="form-corners-dot-color-type-single" type="radio" name="corners-dot-color-type" checked/>
                                <label for="form-corners-dot-color-type-single"><?php echo translate('sngle-color');?></label>
                            </div>
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="cornersDotOptionsHelper.colorType.gradient" id="form-corners-dot-color-type-gradient" type="radio" name="corners-dot-color-type"/>
                                <label for="form-corners-dot-color-type-gradient"><?php echo translate('color-gradient');?></label>
                            </div>
                        </div>
                        <label class="cornersDotOptionsHelper.colorType.single" for="form-corners-dot-color"><?php echo translate('corners-dot-color');?></label>
                        <div class="cornersDotOptionsHelper.colorType.single buttons-container">
                            <input node="cornersDotOptions.color" id="form-corners-dot-color" type="color" value="#000000"/>
                            <button type="button" id="button-clear-corners-dot-color">Clear</button>
                        </div>
                        <label class="cornersDotOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0">Gradient Type</label>
                        <div class="cornersDotOptionsHelper.colorType.gradient space-between-container" style="visibility: hidden; height: 0">
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="cornersDotOptionsHelper.gradient.linear" id="form-corners-dot-gradient-type-linear" type="radio" name="corners-dot-gradient-type" checked/>
                                <label for="form-corners-dot-gradient-type-linear">Linear</label>
                            </div>
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="cornersDotOptionsHelper.gradient.radial" id="form-corners-dot-gradient-type-radial" type="radio" name="corners-dot-gradient-type"/>
                                <label for="form-corners-dot-gradient-type-radial">Radial</label>
                            </div>
                        </div>
                        <label class="cornersDotOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0">Dots Gradient</label>
                        <div class="cornersDotOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0">
                            <input node="cornersDotOptionsHelper.gradient.color1" type="color" value="#000000"/>
                            <input node="cornersDotOptionsHelper.gradient.color2" type="color" value="#000000"/>
                        </div>
                        <label class="cornersDotOptionsHelper.colorType.gradient" for="form-corners-dot-gradient-rotation" style="visibility: hidden; height: 0">Rotation</label>
                        <div class="cornersDotOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0">
                            <input node="cornersDotOptionsHelper.gradient.rotation" id="form-corners-dot-gradient-rotation" type="number" min="0" max="360" value="0"/>
                        </div>
                    </div>
                    <button type="button" class="accordion"><?php echo translate('background-options');?></button>
                    <div class="panel">
                        <label><?php echo translate('color-type');?></label>
                        <div class="space-between-container">
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="backgroundOptionsHelper.colorType.single" id="form-background-color-type-single" type="radio" name="background-color-type" checked/>
                                <label for="form-background-color-type-single"><?php echo translate('sngle-color');?></label>
                            </div>
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="backgroundOptionsHelper.colorType.gradient" id="form-background-color-type-gradient" type="radio" name="background-color-type"/>
                                <label for="form-background-color-type-gradient"><?php echo translate('color-gradient');?></label>
                            </div>
                        </div>
                        <label class="backgroundOptionsHelper.colorType.single" for="form-background-color"><?php echo translate('background-color');?></label>
                        <div class="backgroundOptionsHelper.colorType.single">
                            <input node="backgroundOptions.color" id="form-background-color" type="color" value="#ffffff"/>
                        </div>
                        <label class="backgroundOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0"><?php echo translate('gradient-type');?></label>
                        <div class="backgroundOptionsHelper.colorType.gradient space-between-container" style="visibility: hidden; height: 0">
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="backgroundOptionsHelper.gradient.linear" id="form-background-gradient-type-linear" type="radio" name="background-gradient-type" checked/>
                                <label for="form-background-gradient-type-linear"><?php echo translate('linear');?></label>
                            </div>
                            <div style="flex-grow: 1">
                                <input node-data-field="checked" node="backgroundOptionsHelper.gradient.radial" id="form-background-gradient-type-radial" type="radio" name="background-gradient-type"/>
                                <label for="form-background-gradient-type-radial"><?php echo translate('radial');?></label>
                            </div>
                        </div>
                        <label class="backgroundOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0">Background Gradient</label>
                        <div class="backgroundOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0">
                            <input node="backgroundOptionsHelper.gradient.color1" type="color" value="#ffffff"/>
                            <input node="backgroundOptionsHelper.gradient.color2" type="color" value="#ffffff"/>
                        </div>
                        <label class="backgroundOptionsHelper.colorType.gradient" for="form-background-gradient-rotation" style="visibility: hidden; height: 0">Rotation</label>
                        <div class="backgroundOptionsHelper.colorType.gradient" style="visibility: hidden; height: 0">
                            <input node="backgroundOptionsHelper.gradient.rotation" id="form-background-gradient-rotation" type="number" min="0" max="360" value="0"/>
                        </div>
                    </div>
                    <button type="button" class="accordion"><?php echo translate('image-options');?></button>
                    <div class="panel">
                        <label for="form-hide-background-dots"><?php echo translate('hide-background-dots');?></label>
                        <div>
                            <input node="imageOptions.hideBackgroundDots" node-data-field="checked" id="form-hide-background-dots" type="checkbox" checked/>
                        </div>
                        <label for="form-image-size"><?php echo translate('image-size');?></label>
                        <div>
                            <input node="imageOptions.imageSize" id="form-image-size" type="number" min="0" max="1" step="0.1" value="0.4"/>
                        </div>
                        <label for="form-image-margin"><?php echo translate('margin');?></label>
                        <div>
                            <input node="imageOptions.margin" id="form-image-margin" type="number" min="0" max="10000" value="0"/>
                        </div>
                    </div>
                    <button type="button" class="accordion"><?php echo translate('qr-options');?></button>
                    <div class="panel">
                        <label for="form-qr-type-number"><?php echo translate('type-number');?></label>
                        <div>
                            <input node="qrOptions.typeNumber" id="form-qr-type-number" type="number" min="0" max="40" value="0"/>
                        </div>
                        <label for="form-qr-mode"><?php echo translate('mode');?></label>
                        <div>
                            <select node="qrOptions.mode" id="form-qr-mode">
                                <option value="Numeric"><?php echo translate('numeric');?></option>
                                <option value="Alphanumeric"><?php echo translate('alphanumeric');?></option>
                                <option value="Byte" selected>Byte</option>
                                <option value="Kanji">Kanji</option>
                            </select>
                        </div>
                        <label for="form-qr-error-correction-level"><?php echo translate('error-correction-level');?></label>
                        <div>
                            <select node="qrOptions.errorCorrectionLevel" id="form-qr-error-correction-level">
                                <option value="L">L</option>
                                <option value="M">M</option>
                                <option value="Q" selected>Q</option>
                                <option value="H">H</option>
                            </select>
                        </div>
                    </div>
                    <div class="options-export-group">
                        <a class="button" id="export-options"><?php echo translate('export-options-as-json');?></a>
                    </div>
                </form>
                
            </div>
            
        </section>
        
   
<script type="text/javascript" src="vendor/qr-gnr/qr.js"></script></body>




<?php include('includes/footer.php'); ?>
