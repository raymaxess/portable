<?php
$this->load->view('shared/header_view');
?>

<div class="container">
  <br />
  <br />
  <br />
  <div class="form-group">
    <div class="input-group">
      <span class="input-group-addon">Search</span>
      <input type="text" name="search_text" id="search_text" placeholder="Search by Customer Details" class="form-control" />
    </div>
  </div>
  <br />
  <div id="result"></div>
</div>

<?php
$this->load->view('shared/footer_view');
?>

<script>
  function checkboxfunction(obj) {
    if($(obj).is(":checked")) {
      $.ajax({
        url:"<?php echo base_url(); ?>bookmark",
        method:"POST",
        data:{id: $(obj).val(), action: 'check'},
        success:function(data){
        
        }
      })
    }
    else {
      $.ajax({
        url:"<?php echo base_url(); ?>bookmark",
        method:"POST",
        data:{id: $(obj).val(), action: 'uncheck'},
        success:function(data){
        
        }
      })
    }   
  }

  $(document).ready(function(){
    load_data();

    function load_data(query)
    {
      $.ajax({
        url:"<?php echo base_url(); ?>search/getArticles",
        method:"POST",
        data:{query:query},
        success:function(data){
        $('#result').html(data);
        }
      })
    }

    $('#search_text').keyup(function(){
      var search = $(this).val();
      if(search != '')
      {
        load_data(search);
      }
      else
      {
        load_data();
      }
    });
});
</script>