# Changelog
All notable changes to this project will be documented in this file.

## [Unreleased][unreleased]

### Enhancements

### Bug Fixes

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
