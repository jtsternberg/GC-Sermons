# GC Sermons - BETA #
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

**Also please note:** This plugin is still in beta, and in active development, and _things may change_. If you have any questions, [let me know](http://twitter.com/jtsternberg).

## Documentation

The plugin is customization friendly, and nearly anything can be changed using a well-placed filter.

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

_Keep in mind, if you change the rewrite/slugs, you will need to reset your site's permalinks by visiting the permalinks page._

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

**Note:** All shortcodes have an equivelant action, so instead of using `do_shortcode()` in your theme, you can call `do_action()`. For example, to output the 8 most recent Sermons in the same Sermon Series, you could put the following in your single template file below the `the_content()` call:

```php
do_action( 'gc_sermons', array(
	'per_page' => 8,
	'related_series' => 'this',
	'content' => '',
	'thumbnail_size' => 'medium',
	'number_columns' => 4,
) );
```

#### Template Files
---

Everything in the [`/templates` directory](https://github.com/jtsternberg/GC-Sermons/tree/master/templates) can be overridden by your theme, by copying the file to a `/gc-sermons` directory in your theme.

If you prefer using filters, the template loader has a filter for the arguments passed to the template, `"template_args_for_{$template_file_name}"`, as well as filters the result of the template output, `"template_output_for_{$template_file_name}"`.

For instance, if you wanted to change the "Related Links" list to an ordered list: 

```php
function gc_related_links_maybe_ol( $output ) {
	// Only make an ordered list on singular sermons.
	if ( is_singular( gc_sermons()->sermons->post_type() ) ) {
		$output = str_replace( array( '<ul', '</ul' ), array( '<ol', '</ol' ), $output );
	}
	return $output;
}
add_filter( 'template_output_for_related-links.php', 'gc_related_links_maybe_ol' );
```

#### Functionality
---

GC Sermons comes with a `GCS_Sermon_Post` `WP_Post` wrapper class specifically for posts in the Sermon post-type. You can get an instance of this class using the `gc_get_sermon_post( $post_id )` helper function. This instance provides a lot of the functionality of this plugin in one object.

First, get the object:

```php
$sermon = gc_get_sermon_post( $sermon_post_id );

// $sermon->ID, $sermon->post_name,  $sermon->post_date, etc,
// all work as normal WP_Post parameters 
```
Now that you have the Sermon object:

###### Wrapper for `wp_oembed_get`/`wp_video_shortcode`

 * `$args` Param: Optional. Args are passed to either `WP_Embed::shortcode()`, or `wp_video_shortcode()`.
 * **Returns:** The video player if successful.

```php
$video_player = $sermon->get_video_player( $args = array() );
```

###### Wrapper for `wp_audio_shortcode`

 * **Returns:** The audio player if successful.

```php
$audio_player = $sermon->get_audio_player();
```

###### Wrapper for `get_permalink`.

 * **Returns:** Sermon post permalink.

```php
$permalink = $sermon->permalink();
```

###### Wrapper for `get_the_title`.

 * **Returns:** Sermon post title.

```php
$title = $sermon->title();
```

###### Wrapper for `the_excerpt`. Returns value. Must be used in loop.

 * **Returns:** Sermon post excerpt.

```php
$excerpt = $sermon->loop_excerpt();
```

###### Wrapper for `get_the_post_thumbnail` which stores the results to the object

 * `$size` Param: Optional. Image size to use. Accepts any valid image size, or an array of width and height values in pixels (in that order). Default 'full'.
 * `$attr` Param: Optional. Query string or array of attributes. Default empty.
 * **Returns:** The post thumbnail image tag.

```php
$featured_image = $sermon->featured_image( $size = 'full', $attr = '' );
```

###### Wrapper for `get_post_thumbnail_id`
 
 * **Returns:** Post thumbnail ID or empty string.

```php
$featured_image_id = $sermon->featured_image_id();
```

###### Get the series image.

 * `$size` Param:  Optional. Image size to use. Accepts any valid image size, or an array of width and height values in pixels (in that order). Default 'full'.
 * `$attr` Param: Optional. Query string or array of attributes. Default empty.
 * **Returns:** The series image tag.

```php
$series_image = $sermon->series_image( $size = 'full', $attr = '' );
```

###### Get single speaker for this sermon

 * `$args` Param: to pass to `GCS_Taxonomies_Base::get()`
 * **Returns:** Speaker term object.

```php
$speaker = $sermon->get_speaker( $args = array() );
```

###### Get single series for this sermon
 
 * `$args` Param: to pass to `GCS_Taxonomies_Base::get()`
 * **Returns:** Series term object.

```php
$series = $sermon->get_series( $args = array() );
```

###### Get other sermons in the same series.

 * `$args` Param: Array of `WP_Query` arguments.
 * **Returns:** `WP_Query` instance if successful.

```php
$others_in_series = $sermon->get_others_in_series( $args = array() );
```

###### Get other sermons by the same speaker.

 * `$args` Param: Array of `WP_Query` arguments.
 * **Returns:** `WP_Query` instance if successful.

```php
$others_by_speaker = $sermon->get_others_by_speaker( $args = array() );
```

###### Wrapper for `get_the_terms` for the series taxonomy
 
 * **Returns:** Array of series terms

```php
$series = $sermon->series();
```

###### Wrapper for `get_the_terms` for the speaker taxonomy
 
 * **Returns:** Array of speaker terms

```php
$speakers = $sermon->speakers();
```

###### Wrapper for `get_the_terms` for the topic taxonomy
 
 * **Returns:** Array of topic terms

```php
$topics = $sermon->topics();
```

###### Wrapper for `get_the_terms` for the tag taxonomy
 
 * **Returns:** Array of tag terms

```php
$tags = $sermon->tags();
```

###### Wrapper for `get_post_meta`
 
 * `$key` Param: Meta key
 * **Returns:** Value of post meta

```php
$meta = $sermon->get_meta( $key );
```

## Installation ###

1. [Download the zip here](https://github.com/jtsternberg/GC-Sermons/blob/master/gc-sermons.zip?raw=true)
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
