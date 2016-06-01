<form role="search" method="get" class="search-form gc-sermons-search" action="<?php $this->output( 'action_url', 'esc_url' ); ?>">
	<?php if ( $this->get( 'show_filter' ) ) : ?>
	<div class="gc-search-results-filter">
		<span><?php _ex( 'Show search results for:', 'Search results filter', 'gc-sermons' ); ?></span>
		<label>
			<input type="radio" class="search-field-radio" name="results-for" value="" <?php checked( $this->get( 'show_results' ), '' ); ?>/> <span><?php _ex( 'Both', 'Show search results for both sermons and sermon series.', 'gc-sermons' ); ?></span>
		</label>
		<label>
			<input type="radio" class="search-field-radio" name="results-for" value="<?php $this->output( 'sermons_value', 'esc_attr' ); ?>" <?php checked( $this->get( 'show_results' ), $this->get( 'sermons_value', 'esc_attr' ) ); ?>/> <span><?php $this->output( 'sermons_label', 'esc_attr' ); ?></span>
		</label>
		<label>
			<input type="radio" class="search-field-radio" name="results-for" value="<?php $this->output( 'series_value', 'esc_attr' ); ?>" <?php checked( $this->get( 'show_results' ), $this->get( 'series_value', 'esc_attr' ) ); ?>/> <span><?php $this->output( 'series_label', 'esc_attr' ); ?></span>
		</label>
	</div>
	<?php endif; ?>

	<label>
		<span class="screen-reader-text"><?php _ex( 'Search for:', 'label' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder' ); ?>" value="<?php $this->output( 'search_query', 'esc_attr' ); ?>" name="sermon-search" />
	</label>

	<input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>" />
</form>
