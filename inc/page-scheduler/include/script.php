
<script type="text/javascript">
  <?php for ($i = 1; $i < $count; $i++) { ?>
    let clicker<?php echo $i; ?> = document.querySelector("#remote<?php echo $i; ?>").onclick = function toggs<?php echo $i; ?> () {
      document.querySelector("#toggle_<?php echo $i; ?>").classList.toggle("toggs");
    }
  <?php } ?>
</script>
