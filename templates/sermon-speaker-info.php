<div class="gc-speaker gc-<?php if ( ! $this->get( 'image' ) ) : ?>no-<?php endif; ?>thumb <?php $this->output( 'classes', 'esc_attr' ); ?>">
	<p>
		<strong><?php  _e( 'Speaker', 'gc-sermons' ); ?></strong>
	</p>
	<?php if ( $this->get( 'image' ) ) : ?>
		<a href="<?php $this->output( 'term_link', 'esc_url' ); ?>">
			<?php $this->output( 'image' ); ?>
		</a>
	<?php endif; ?>
	<h3>
		<a href="<?php $this->output( 'term_link', 'esc_url' ); ?>"><?php $this->output( 'name' ); ?></a>
	</h3>
</div>
