<p>
	<strong><?php  _e( 'Speaker', 'gc-sermons' ); ?></strong>
</p>
<?php if ( $this->get( 'thumbnail' ) ) : ?>
	<a href="<?php $this->output( 'speaker_url', 'esc_url' ); ?>">
		<?php $this->output( 'thumbnail' ); ?>
	</a>
<?php endif; ?>
<h3>
	<a href="<?php $this->output( 'speaker_url', 'esc_url' ); ?>"><?php $this->output( 'speaker_title' ); ?></a>
</h3>
