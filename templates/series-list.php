<div class="gc-series-wrap <?php $this->output( 'series_wrap_classes', 'esc_attr' ); ?>">
	<?php foreach ( $this->get( 'terms' ) as $year => $terms ) : ?>
		<?php if ( ! $this->get( 'series_remove_dates' ) ) : ?>
		<h4><?php echo $year; ?></h4>
		<?php endif; ?>
		<ul class="gc-sermons-list">
		<?php foreach ( $terms as $term ) : ?>
			<?php GCS_Template_Loader::output_template( 'series-item', (array) $term ); ?>
		<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>

	<nav class="gc-prev-next-series">
		<?php if ( $this->get( 'prev_link' ) ) : ?>
			<span class="gc-prev-series-link">
				<?php $this->output( 'prev_link' ); ?>
			</span>
		<?php endif; ?>
		<?php if ( $this->get( 'next_link' ) ) : ?>
			<span class="gc-next-series-link">
				<?php $this->output( 'next_link' ); ?>
			</span>
		<?php endif; ?>
	</nav>
</div>
