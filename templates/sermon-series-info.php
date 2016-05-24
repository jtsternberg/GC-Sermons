<p>
	<strong><?php  _e( 'Series', 'gc-sermons' ); ?></strong>
</p>
<?php if ( $this->get( 'thumbnail' ) ) : ?>
	<a href="<?php $this->output( 'series_url', 'esc_url' ); ?>">
		<?php $this->output( 'thumbnail' ); ?>
	</a>
<?php endif; ?>
<h3>
	<a href="<?php $this->output( 'series_url', 'esc_url' ); ?>"><?php $this->output( 'series_title' ); ?></a>
</h3>
