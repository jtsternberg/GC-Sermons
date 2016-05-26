<div class="<?php $this->output( 'wrap_classes', 'esc_attr' ); ?>">
	<?php foreach ( $this->get( 'terms' ) as $year => $terms ) : ?>
		<?php if ( ! $this->get( 'remove_dates' ) ) : ?>
		<h4><?php echo $year; ?></h4>
		<?php endif; ?>
		<ul class="gc-sermons-list">
		<?php foreach ( $terms as $term ) : ?>
			<?php GCS_Template_Loader::output_template( 'list-item', (array) $term ); ?>
		<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>

	<?php GCS_Template_Loader::output_template( 'nav', $this->args ); ?>
</div>
