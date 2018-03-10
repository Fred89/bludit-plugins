<?php 
/**
 *  Contact layout
 *
 *  @package: Bludit
 *  @subpackage: Contact
 *  @author: Frédéric K.
 *  @copyright: 2015-2018 Frédéric K.
 *  @info: Duplicate this layout in your themes/YOUR_THEME/php/ 
 *	for a custom template.
 *  @url $Site->url()
 */	
?>
<form method="post" action="<?php echo '.' . DS . $Page->slug(); ?>" class="contact">
	<input type="hidden" name="tokenCSRF" value="<?php echo $Security->getTokenCSRF(); ?>">
	
	<div class="form-group">
	   <input id="name" type="text" name="name" value="<?php echo sanitize::html($name); ?>" placeholder="<?php echo $Language->get('Name'); ?>" class="form-control" >
	</div>

	<div class="form-group">
	   <input id="email" type="email" name="email" value="<?php echo sanitize::email($email); ?>" placeholder="<?php echo $Language->get('Email'); ?>" class="form-control">
	</div>

	<div class="form-group">
	   <textarea id="message" rows="6" name="message" placeholder="<?php echo $Language->get('Message'); ?>" class="form-control"><?php echo sanitize::html($message); ?></textarea>
	</div>

	<input type="checkbox" name="interested">
	<button id="submit" name="submit" type="submit" class="btn btn-primary"><?php echo $Language->get('Send'); ?></button>
</form>