# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased][unreleased]

### Enhancements

### Bug Fixes

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

* Shortcodes, shortcodes, shortcodes. Also, this changelog.
