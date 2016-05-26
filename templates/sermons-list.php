<div class="<?php $this->output( 'wrap_classes', 'esc_attr' ); ?>">
	<ul class="gc-sermons-list">
	<?php foreach ( $this->get( 'sermons' ) as $year => $sermon ) : ?>
		<?php GCS_Template_Loader::output_template( 'list-item', (array) $sermon ); ?>
	<?php endforeach; ?>
	</ul>

	<?php GCS_Template_Loader::output_template( 'nav', $this->args ); ?>
</div>
