<hr class="featurette-divider">
<div id="contact" class="contact_page">
	<div class="container">		
		<div class="col-md-4">
		</div>					
		<div class="col-md-4">
			<form enctype="application/x-www-form-urlencoded" class="form-horizontal" action="/modules/contact/contactProc_LoggedOut.php" method="POST"><div class="padded">
				<h2 class="form-signin-heading">Contact Us</h2>
				<div class="control-group"><label for="category" class="control-label required">Category</label>
					<div class="controls">
						<select name="category" id="category" class="form-control">
						<option value="0">Making a Listing</option>
						<option value="1">Finding a Listing</option>
						<option value="2">Other</option>
						</select>
					</div>
				</div>
				
				<div class="control-group"><label for="email" class="control-label required">Your Email Address</label>
					<div class="controls">
						<input type="text" name="email" id="email" value="" size="30" class="form-control" />
					</div>
				</div>
				
				<div class="control-group"><label for="text" class="control-label required">Message</label>
					<div class="controls">
						<textarea name="text" id="text" rows="6" class="form-control" cols="80"></textarea>
					</div>
				</div>
				<br>
				<div class="form-actions">
					<button class="btn btn-lg btn-primary btn-block" id="sendButton">Send</button>
				</div>
			</form>
		</div>
	</div>
</div>
