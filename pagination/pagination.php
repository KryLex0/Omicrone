<div class="responsive-table pagination-container">
<label style="text-align:center; "><strong>Resultat par page </strong>
  <select onchange="pagination();" id="max-row">
    <?php for($i=5;$i<51; $i=$i+5){?>
      <option value="<?php echo $i ?>"><?php echo $i ?></option>
    <?php } ?>
 </select>
</div>

<nav>
  <ul id="pagination" style="text-align:center;"></ul>
</nav>

<script type="text/javascript">var noligne = <?php echo json_encode($noligne); ?></script>
