<?php include('header.php')?>
<!--<iframe id="iframepdf"  width="100%" height="100%" src="http://zaireprojects.com/test/ViewBlobFiles.php?PRID=127"></iframe>-->
<div style="width: 852px;
    border: 3px solid #CCCCCC;
    margin: 0 auto;"><iframe id="fred" style="border:1px solid #FFFFFF;" title="PDF in an i-Frame" src="https://zaireprojects.com/test/ViewBlobFiles.php?prid=<?php echo $_SESSION['g_PR_id'] ;?>" frameborder="1" scrolling="auto" height="1100" width="850" ></iframe></div>
    <?php include('footer.php')?>