# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.4.4] - 07-03-2022

### Changed

- Fixed ambiguous `orderBy` clause (thanks to [@Sjoertjuh](https://github.com/Sjoertjuh))
- Updated packages

## [2.4.3] - 2022-01-14

### Changed

- Updated packages

## [2.4.2] - 2021-10-12

### Added

- Added option(s) to disable sortability cache

### Changed

- Fixed access error to `$sortabilityCache`

## [2.4.1] - 2021-10-01

### Changed

- Fixed N+1 queries on index views
- Updated packages

## [2.4.0] - 2021-08-09

### Added

- Added `nova_order_by` property to `$sortable` (thanks to [@lisotton](https://github.com/lisotton))
- Added `pt-BR` translations (thanks to [@lisotton](https://github.com/lisotton))

### Changed

- Updated packages

## [2.3.4] - 2021-07-05

### Changed

- Fixed Vue runtime warning - `reorderDisabled is already defined as a prop`
- Fixed compatibility with Nova 3.27 (thanks to [@mstaack](https://github.com/mstaack))
- Updated packages

## [2.3.3] - 2021-05-27

### Changed

- Fix reordering

## [2.3.2] - 2021-05-27

### Changed

- Fixed issue where rows reset to their original order after selecting one of the rows
- Applied Nova 3.5.0 updates to overridden components
- Updated packages

## [2.3.1] - 2021-05-07

### Changed

- Fixed issue with `getSortability` returning null in some cases (thanks to [@chiz-developer](https://github.com/chiz-developer))

## [2.3.0] - 2021-05-07

### Added

- `eloquent-sortable` version 4.0 support (thanks to [@veneliniliev](https://github.com/veneliniliev))

### Changed

- Updated Persian (Farsi) translations (thanks to [@FaridAghili](https://github.com/FaridAghili))
- Updated packages

## [2.2.0] - 2021-04-26

### Added

- Added `ignore_policies` option
- Added Polish translations (thanks to [@mgralikowski](https://github.com/mgralikowski))

### Changed

- Fixed `SoftDeletes` check (thanks to [@andre4nap](https://github.com/andre4nap))
- Fixed API routes not using Nova's configured domain (thanks to [@angelsk](https://github.com/angelsk))
- Updated packages

## [2.1.7] - 2021-03-04

### Added

- Added support for custom pivot accessors (thanks to [@mikaelpopowicz](https://github.com/mikaelpopowicz))

### Changed

- Restored support for BelongsTo + Index sorting
- Hide sorting buttons on Lenses
- Updated packages

## [2.1.6] - 2021-02-09

### Changed

- Fixed `only_show_on` and `dont_show_on` not working for one-to-many relationships
- Fixed a case where unauthorizing delete would also hide sortable buttons
- Fixed a case where `getSortability` would receive a model instead of a resource causing invalid configuration to be returned
- Fixed `canSort` fallback (was opposite of what it is supposed to be)
- Updated packages

## [2.1.5] - 2021-01-22

### Changed

- Fixed a case where an extra `<td>` was displayed in the table row
- Updated packages

## [2.1.4] - 2021-01-14

### Changed

- Fixed sort order not being normalized (thanks to [@marispro](https://github.com/marispro))

## [2.1.3] - 2021-01-13

### Changed

- Fixed Spatie's SortableTrait not detected in a recursive setting (thanks to [@shaffe-fr](https://github.com/shaffe-fr))

## [2.1.2] - 2021-01-11

### Changed

- Fixed issue with sorting by wrong keys in many-to-many relationships

## [2.1.1] - 2021-01-11

### Changed

- Fixed sorting buttons being displayed on all resources
- Updated packages

## [2.1.0] - 2021-01-08

### Changed

- Fixed inline actions in Lenses returning 404 (thanks to [@TheSETJ](https://github.com/TheSETJ))
- Use `nova-translations-loader` for loading translations
- Add fallback values to sort order when the column is empty
- Don't allow reordering when user is not `authorizedToUpdate` resource
- Allow `canSort` to be used on a per-resource basis
- Fix UI issues when delete policy is enabled
- Add Estonian translations
- Updated packages

## [2.0.0] - 2020-12-04

### Changed

- Dropped PHP 7.1, PHP 7.2, Laravel 6 and Nova 2 support
- Updated packages

## [1.6.2] - 2020-11-25

### Changed

- Bump `eloquent-sortable` version to 3.9

## [1.6.1] - 2020-10-22

### Changed

- Furher improved compatibility with `eloquent-sortable` (thanks to [@fkraefft](https://github.com/fkraefft))

## [1.6.0] - 2020-10-22

### Added

- Use `eloquent-sortable` default config when `$sortable` is not specified on the model (thanks to [@fkraefft](https://github.com/fkraefft))

### Changed

- Updated packages

## [1.5.0] - 2020-10-02

### Added

- Added ability to enable/disable sorting on a per-request basis (thanks to [@Naoray](https://github.com/Naoray))

### Changed

- Updated packages

## [1.4.17] - 2020-09-24

### Changed

- Fixed some crashes caused by `buildSortQuery` in `updateOrder` (again) (vol. 2)

## [1.4.16] - 2020-09-23

### Changed

- Fixed some crashes caused by `buildSortQuery` in `updateOrder` (again)

## [1.4.15] - 2020-09-23

### Changed

- Fixed some crashes caused by `buildSortQuery` in `updateOrder`
- Updated packages

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
