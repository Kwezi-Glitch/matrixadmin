

<!--this is where users can change or stick to the same package after payment by the downliner has been made to the upliner-->

<div class="row-fluid">
  <div class="span12">
    <div class="widget-box">
      <div class="widget-title"> <span class="icon"> <i class="icon-list"></i> </span>
        <h5>Upgrade</h5>
      </div>
      <div class="alert alert-info alert-block"> <a class="close" data-dismiss="alert" href="#">�</a>
        <h4 class="alert-heading"></h4>
        <h2>Thanks for choosing Matrix Admin, Please select another package or recycle</h2>




      </div>
      <div>

        <a class="btn btn-success btn-large btn-block " href="select_package.php">Select Another Package</a>

        <form action="transaction.php" method="post">
        <input type="hidden" name="select_package" value=" <?php echo $_SESSION['package']; ?> ">
          <input type="submit" class="btn btn-danger btn-large btn-block " name="select_pack_btn" value="Recycle">

        </form>

      </div>


    </div>
  </div>
</div>