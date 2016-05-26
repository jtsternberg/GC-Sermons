<li class="gc-series-item gc-<?php if ( ! $this->get( 'image' ) ) : ?>no-<?php endif; ?>thumb <?php $this->output( 'classes', 'esc_attr' ); ?>">

	<a class="gc-series-link" href="<?php $this->output( 'term_link', 'esc_url' ); ?>" title="<?php $this->output( 'name', 'esc_attr' ); ?>">
		<?php $this->maybe_output( 'image', '', 'do_image' ); ?>

		<div class="gc-sermons-shader"></div>
		<div class="gc-sermons-table-wrapper">
			<table>
				<tbody>
					<tr>
						<td>
							<h3 class=""><?php $this->output( 'name' ); ?></h3>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</a>

	<div class="gc-list-item-description">
		<?php $this->maybe_output( 'description', '', 'do_description' ); ?>
	</div>

</li>
