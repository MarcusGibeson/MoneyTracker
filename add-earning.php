<?php
require_once 'includes/view/earning_view.inc.php';
?>

<h3>Add earning</h3>

<form action="includes/earning.inc.php" method="post">
    <?php
    addEarning_Inputs();
    ?>
</form>