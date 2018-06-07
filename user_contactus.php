<?php include('header_signup.php'); ?>
<div class="container" >
<div class="col-md-6 col-md-offset-3"style="    margin-top: 30px;" >
<form class="form-horizontal" role="form" method="POST" action="user_contactussend.php">
	<div class="form-group">
		<label for="name" class="col-sm-2 control-label">Name</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" id="name" name="user_name" placeholder="First & Last Name" value="" required >
		</div>
	</div>
	<div class="form-group">
		<label for="email" class="col-sm-2 control-label">Email</label>
		<div class="col-sm-10">
			<input type="email" class="form-control" id="email" name="user_email" placeholder="example@domain.com" value="" required>
		</div>
	</div>
	<div class="form-group">
		<label for="mobile" class="col-sm-2 control-label">Mobile</label>
		<div class="col-sm-10">
			<input type="number" class="form-control" minlength="10" id="mobile" name="user_mobile" placeholder="809339989" value="" required>
		</div>
	</div>
	<div class="form-group">
		<label for="message" class="col-sm-2 control-label">Message</label>
		<div class="col-sm-10">
			<textarea class="form-control" rows="4" name="user_message"  required></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<input id="submit" name="submit" type="submit" value="Send" class="btn btn-primary">
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-10 col-sm-offset-2">
			<! Will be used to display an alert to the user>
		</div>
	</div>
</form></div></div>
<?php include('footer.php');?>