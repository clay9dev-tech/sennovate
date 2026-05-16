<div class="search-form-wrapper">
	<form role="search" method="get" class="search-form" action="<?php echo home_url(); ?>">
		<label for="site-search-keyword" class="search-form-label screen-reader-text">Search&hellip;</label>
		<input id="site-search-keyword" type="search" class="search-form-field" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="Search by keyword&hellip;" oninvalid="this.setCustomValidity('Please enter your search terms')" oninput="setCustomValidity('')" required>
		<button type="submit" class="search-submit" aria-label='Search'></button>
	</form>
</div>
