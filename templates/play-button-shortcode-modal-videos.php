<div id="gc-video-overlay" style="display:none;">
	<?php foreach ( $this->get( 'videos' ) as $sermon_id => $player ) : ?>
	<div id="gc-sermons-video-<?php echo absint( $sermon_id ); ?>" class="gc-sermons-modal gcinvisible">
		<div class="gc-sermons-video-container"></div>
		<script type="text/template" class="tmpl-videoModal">
			<?php echo $player; ?>
		</script>
	</div>
	<?php endforeach; ?>
</div><!-- #gc-video-overlay -->
