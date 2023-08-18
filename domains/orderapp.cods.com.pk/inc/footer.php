<script type="text/javascript" src="<?php echo BASE_URL ?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL ?>js/jquery-3.5.1.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL ?>js/dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable();
} );

var selector = '.sidebar li a';
$(selector).on('click',function(){
  $(selector).removeClass('active');
  $(this).addClass('active');
});
</script>
</body>
</html>