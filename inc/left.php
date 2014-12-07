<?php
if (!defined('AFFREE')) {
    die('You cannot access this page directly!');
}
?>
<div class="box_top">
    <h2>Categories</h2>
</div>
<div class="box">

    <?php echo Menu($db, 0, 0); ?>

</div>
<div class="box_bottom"></div>