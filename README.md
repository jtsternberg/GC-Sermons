# GC Sermons #
**Contributors:**      jtsternberg  
**Donate link:**       http://dsgnwrks.pro  
**Tags:**  
**Requires at least:** 4.4  
**Tested up to:**      4.4  
**Stable tag:**        0.1.3  
**License:**           GPLv2  
**License URI:**       http://www.gnu.org/licenses/gpl-2.0.html  

## Description ##

Manage sermons and sermon content in WordPress. The [GC Staff](https://github.com/jtsternberg/GC-Staff) plugin is recommended to complement this plugin for church sites.

**Please note:** you will need to run `composer install` in order to fetch the dependenceis for this plugin/library, **or** you can [download the zip here](https://github.com/jtsternberg/GC-Sermons/blob/master/gc-sermons.zip?raw=true).

## Documentation

The plugin is customization friendly, and nearly anything can be changed with a well-placed filter.

#### Change a taxonomy rewrite slug
---

The plugin sets up the permalinks and rewrite slugs using a pretty standard naming convention for items, but you can modify to fit your church/data.

For example, you could change the series link from `/sermon-series/series-name` to `/series/series-name` with this filter:

```php
function gc_series_tax_rewrite_override( $args ) {
	$args['arg_overrides']['rewrite']['slug'] = 'series';

	return $args;
}
add_filter( 'gcs_taxonomies_series', 'gc_series_tax_rewrite_override' );
```

The other taxonomy filters are:

* `'gcs_taxonomies_speaker'`
* `'gcs_taxonomies_tag'`
* `'gcs_taxonomies_topic'`
* `'gcs_taxonomies_scripture'`

#### Change Sermon Post Type Name
---

To change the name from Sermons to Messages:

```php
function gc_sermon_name_override( $args ) {
	$args['singular'] = 'Message';
	$args['plural'] = 'Messages';
	$args['arg_overrides']['rewrite']['slug'] = 'message';

	return $args;
}
add_filter( 'gcs_post_types_sermon', 'gc_sermon_name_override' );
```

#### Add a [CMB2](https://github.com/WebDevStudios/CMB2) field to one of the registered metaboxes
---

The plugin uses CMB2 to register the fields. It includes a few handy filters which allow you to modify the fields before they are registered.

If you do not plan on using the [GC-Staff](https://github.com/jtsternberg/GC-Staff) plugin (which has a "position" taxonomy), you can add a "Position" (Pastor, Guest Speaker, Youth Pastor, etc) text input for your speakers. To do so:

```php
function gc_speaker_fields_add_position( $args ) {
	$args['fields']['gc_speaker_postion'] = array(
		'id'   => 'gc_speaker_postion',
		'name' => 'Speaker\'s Position',
		'desc' => 'What is this speaker\'s position at your church (e.g. "Guest Speaker")?',
		'type' => 'text',
		'attributes' => array(
			'placeholder' => 'Pastor',
		),
	);

	return $args;
}
add_filter( 'gcs_cmb2_box_args_speaker_gc_sermon_speaker_metabox', array( $this, 'gc_speaker_fields_add_position' ) );
```

_For more documentation on CMB2 Fields, [see the wiki](https://github.com/WebDevStudios/CMB2/wiki)._

#### Shortcodes
---

The plugin comes with several useful shortcodes:

##### `'sermon_play_button'` - Play Button Shortcode

Outputs a button to open the Sermon video in a modal.

#####  `'gc_recent_series'` - Recent Series Shortcode

Output a list of most recent Sermon Series.

#####  `'gc_recent_speaker'` - Recent Speaker Shortcode

Output a list of most recent Speakers.

#####  `'gc_related_links'` - Related Links Shortcode

Outputs the list of related links associated with a sermon.

#####  `'gc_video_player'` - Series Shortcode

Outputs the video player associated with a sermon.

#####  `'gc_audio_player'` - Sermons Shortcode

Outputs the audio player associated with a sermon.

#####  `'gc_series'` - Series Shortcode

Outputs a paginated list of all Sermon Series, in reverse chronological order.

#####  `'gc_sermons'` - Sermons Shortcode

Flexible shortcode which outputs a paginated list of all sermons, optionally filtered by Sermon Series or Speaker.

**Note:** All shortcodes have an equivelant action, so instead of using `do_shortcode()` in your theme, you can call `do_action()`. For example, to output the 8 most recent Sermons in the same Sermon Series, you could put this in your single template file below the `the_content()` call:

```php
do_action( 'gc_sermons', array(
	'per_page' => 8,
	'related_series' => 'this',
	'content' => '',
	'thumbnail_size' => 'medium',
	'number_columns' => 4,
) );
```

## Installation ###

1. Download the zip here](https://github.com/jtsternberg/GC-Sermons/blob/master/gc-sermons.zip?raw=true)
1. Upload the entire `/gc-sermons` directory to the `/wp-content/plugins/` directory.
1. Activate GC Sermons through the 'Plugins' menu in WordPress.

## Frequently Asked Questions ##


## Screenshots ##


## Changelog ##

### 0.1.3 ###
* Add "Scripture" Taxonomy.
* Fix `"gcs_cmb2_box_args_{$this->id}_{$cmb_id}"` filter.
* Add documentation to this README.

### 0.1.2 ###
* 

### 0.1.1 ###
* 

### 0.1.0 ###
* First release
