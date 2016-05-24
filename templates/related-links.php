<p>
	<strong><?php  _e( 'Related Links', 'gc-sermons' ); ?></strong>
</p>
<ul class="gc-sermon-related-links">
	<?php foreach ( $this->get( 'links' ) as $link ) : ?>
	<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo $link['title']; ?></a></li>
	<?php endforeach; ?>
</ul>
