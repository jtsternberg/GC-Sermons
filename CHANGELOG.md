# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased][unreleased]

## 0.2.1 - 2018-08-31

- Fix the `label_coming_soon` method and only check for posts in our post-type
- Use `$this->post_type()`, not (wrong) hard-coded post-type string
- Fix `get_the_id`

## 0.2.0 - 2018-08-28

### Enhancements

- Helper functions for getting the GC Sermons augmented taxonomy term objects.
- New helper functions: 
	- `gc_get_series_object( $term = 0, $args = array() )`
	- `gc_get_speaker_object( $term = 0, $args = array() )`
	- `gc_get_topic_object( $term = 0, $args = array() )`
	- `gc_get_tag_object( $term = 0, $args = array() )`
	- `gc_get_scripture_object( $term = 0, $args = array() )`

### Bug Fixes

- Make sure `GCS_Taxonomies_Base::filter_values` runs (so that custom post-type and taxonomy overrides work).
- fix composer.json license value to be compatible with packagist

## 0.1.6 - 2016-06-08

### Enhancements

* Move loading of plugin's classes to the `'plugins_loaded'` hook for more flexibility.
* Output "No results" string when Sermon/Series search results are empty.
* Update shortcode-button dependency to fix modal displaying before CSS loads.

### Bug Fixes

* Fix required plugins notices. WDS Shortcodes is now bundled and not required for installation.
* Fix php notice caused by looping an empty array.

## 0.1.5 - 2016-06-01

### Enhancements

* Add sermon/series search shortcode.
* Add `GCS_Taxonomies_Base::search()` method for searching for terms in the taxonomies.
* Move some functionality in series shortcode to dedicated methods.
* Add wrapper for `get_terms` to account for changes in WP 4.5 where taxonomy is expected as part of the arguments..
* Update WDS-Shortcodes dependency.

### Bug Fixes

* Fix bug where the no-thumb class may not be added properly when thumbs are disabled in shortcodes.
* Tidy up debug/whitespace stuff.
* Fix bug where last page may not show because of rounding error.
* Update sermons shortcode to account for inception issues.
* Cleanup shortcodes class, fixing bad property names.

### Other

* Move taxonomy-based files to taxonomy includes directory, and post-type-based files to post-type includes directory.
* Pass the series taxonomy object to `GCSS_Series_Run` (dependency injection).

## 0.1.4 - 2016-05-30

### Enhancements

- Shortcodes, shortcodes, shortcodes. Also, this changelog.
- Update readme, pointing to the wiki.
- Filter, `gc_do_sermon_series_fallback_image`, to disable image fallback.
- Update the term-image column and add a term-edit link.
- Move shortcode files around, and add video/audio player shortcodes.
- Clean up "recent" shortcodes and nicer UX.
- Update dependencies to fix some JS issues.
- Add speaker/series filters to the sermon shortcode.
- Add a prefix to shortcode fields, and method to de-prefix before inserting shortcode.
- Add a filter on the template content output.
- Update sermons shortcode, and make things more generic to be shared.
- Revamp/cleanup shortcodes and finalize the series shortcodes.
- Update wds-shortcodes dependency to get the bool_att method, for getting boolean values from shortcode values.
- Add `GCS_Template_Loader::maybe_output` for doing condtional output in templates.
- Update Template Loader.
- Clean up some typos.
- Add image column to speakers/series taxonomies.
- Add several shortcodes, series image fallback for sermson, allow future posts, etc.
- Rename file to match file/class nameing convention.
- Enable future posts to be displayed (with "coming soon") text on the front-end.
- Make audio player wrap class consistent.
- Make `GCS_Shortcodes_Play_Button` properties accessible.
- Implement template loader to allow overriding in themes, etc.

## 0.1.3 - 2016-06-01

- Add "Scripture" Taxonomy.
- Fix `"gcs_cmb2_box_args_{$this->id}_{$cmb_id}"` filter.

## 0.1.2 - 2016-04-14

- Move css to separate css templates.
- `most_recent_with_audio` method should search for audio, not video.

## 0.1.1 - 2016-04-14

- Specify rewite slugs for all taxonomies.
- When calling `get_audio_player`, init media.
- Be more discerning when removing metaboxes, and replace the featured image metabox.
- Use `plugins_loaded` hook to filter the post-type/taxonomy values so that the filter happens early (before init).
- Update to series column. Replace with image, if one is set.
- Check if `gc_staff` is loaded, and change speaker connected field and augmented data.
- Fix textdomains.
- Add sermon `get` and `get_many` methods.
- build a series image when we only have a url.
- Only get the user avatar if there is no speaker image set.
- on post save, asynchronously save the sermon series terms in order of recent sermons.
- Allow a `series_image_fallback`.
- New `GCS_Sermon_Post` methods, `permalink`, `title`, `series_image`, `get_others_in_series`, `get_others_by_speaker`.
- Make play button shortcode enqueue scripts/styles and for the video modal to actually work.
- Add `get_video_player` method to sermon post class.
- make `url`, `path`, `dir` publicly accessible variables.
- Add `wds-shortcodes` as dependency.
- Add `GCS_Sermon_Post` method for getting the sermon post meta.
- Add `GCS_Sermon_Post` method for getting the sermon audio player.
- Add methods to `GCS_Sermon_Post` for getting the post's series or speaker's term object.
- Add addional CMB2 field types and add related links, etc to sermon CPT.
- Add several utility methods for getting taxonomy term objects, and augmenting with additional info.
- Update rewrite for sermons.
- Fix a bug with taxonomy `most_recent`.
- Differentiate between taxonomy/post-type slug, and the object identifier (since slug can be changed via filter).
- clean up composer.json.