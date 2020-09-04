# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.4.14] - 2020-09-04

### Changed

- Updated dist file(s) forgotten in previous release
- Updated packages

## [1.4.13] - 2020-09-04

### Changed

- Fixed HasMany not showing correct sorting buttons
- Fixed ManyToMany not showing any sorting buttons in some cases

## [1.4.12] - 2020-09-01

### Changed

- Fixed a case where a relationship query tried to sort the wrong model (thanks to [@eduardoesternon](https://github.com/eduardoesternon))

## [1.4.11] - 2020-08-31

### Changed

- Fixed a case where a missing `ConvertEmptyStringsToNull` middleware could cause a 400 error

## [1.4.10] - 2020-08-31

### Changed

- Fixed buildSortQuery in updateOrder
- Add ability to allow or deny sorting capability on specific models (thanks to [@Nickotavers](https://github.com/Nickotavers))

## [1.4.9] - 2020-08-31

### Changed

- Support buildSortQuery in updateOrder (thanks to [@stevelacey](https://github.com/stevelacey))
- Updated packages

## [1.4.8] - 2020-08-10

### Changed

- Added French translations (thanks to [@Nyratas](https://github.com/Nyratas))
- Added Russian translations (thanks to [@everestmx](https://github.com/everestmx))

## [1.4.7] - 2020-04-28

### Changed

- Added Spanish translations (thanks to [@gerardnll](https://github.com/gerardnll))
- Fixed hardcoded primary key column names (thanks to [@newtongamajr](https://github.com/newtongamajr))

## [1.4.6] - 2020-04-28

### Changed

- Fix reorder component not being rendered when there's no checkbox due to permissions
- Updated packages

## [1.4.5] - 2020-04-23

### Changed

- Fixed global search not working

## [1.4.4] - 2020-04-22

### Changed

- Fixed crash when opening an empty `ManyToMany` field
- Fixed hard-coded primary key and sort order name columns

## [1.4.3] - 2020-04-22

### Changed

- Rework `ManyToMany` relationship handling (fix to `moveToStart` and `moveToEnd`)
- Update docs on setting up `ManyToMany` sorting
- Improve `HasMany` relationship handling
- Update packages

## [1.4.2] - 2020-04-21

### Changed

- Rework `ManyToMany` relationship handling
- Update docs on setting up `ManyToMany` sorting

## [1.4.1] - 2020-04-20

### Changed

- BelongsToMany fixes

## [1.4.0] - 2020-04-17

### Added

- HasMany support (see `README.md`)

### Changed

- NB! The setting `sort_on_pivot` has been renamed to `sort_on_belongs_to`
- Stop serving empty CSS file
- Refactored code
- Updated packages

## [1.3.1] - 2020-03-05

### Added

- Support Nova 3.0 in `composer.json` requirements

## [1.3.0] - 2020-02-19

### Added

- Pivot tables sorting support

## [1.2.0] - 2020-02-10

### Added

- Added Farsi (Persian) translations (by [@FaridAghili](https://github.com/FaridAghili))

### Changed

- Hide sortable buttons on pivot table
- Support Laravel Nova >= 2.10.0
- Updated packages

## [1.1.3] - 2019-12-18

### Changed

- Updated packages
- Removed usage of `:class` as a custom prop due to errors on some versions of Vue

## [1.1.2] - 2019-12-06

### Changed

- Added german translations (thanks to [@mynetx](https://github.com/mynetx))

## [1.1.1] - 2019-12-06

### Changed

- Fix locale loading logic

## [1.1.0] - 2019-12-05

### Added

- Localization support (see README)

### Changed

- Refactored front-endcode
- Updated dependencies

## [1.0.1] - 2019-11-13

### Changed

- Fixed being able to click on "to start" and "to end" arrows when reorder is disabled

## [1.0.0] - 2019-11-01

### Added

- Drag & drop reorder within one page of resources
- Move to start and end arrows (makes item first/last)
- Everything from [eloquent-sortable](https://github.com/spatie/eloquent-sortable)
