# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.4.7] - 27-11-2023

### Changed

- Fixed resource table keys causing ghost items to appear when re-ordering
- Updated packages

## [3.4.6] - 13-09-2023

### Changed

- Fixed resource table keys causing items to not re-render when searching inside the table
- Fixed indexQuery return type (#176) (thanks to [@mennotempelaar](https://github.com/mennotempelaar))

## [3.4.5] - 18-08-2023

### Changed

- Fixed unstyled disabled buttons in dark mode (issue #174)

## [3.4.4] - 30-06-2023

### Changed

- Fixed an issue with Nova where the inline actions button would appear even if there aren't any actions defined (#172)
- Bumped minimum Nova version to 4.24.0

## [3.4.3] - 17-03-2023

### Added

- Added Italian translations (thanks to [@trippo](https://github.com/trippo))

### Changed

- Fixed item duplication on drag-and-drop move
- Fixed update order failing with global scopes (thanks to [@kiritokatklian](https://github.com/kiritokatklian))
- Fixed light/dark theme handling when set to "System"
- Updated packages

## [3.4.2] - 16-01-2023

### Changed

- Fixed issue with resource overflow not being scrollable when using alongside other outl1ne packages.

## [3.4.1] - 13-01-2023

### Changed

- Removed o1- prefix from gray colors. Allows package to use nova's color configuration.

### Added

- Added MutationObserver to observer dark theme.

## [3.4.0] - 12-01-2023

### Added

- Updated components to `nova: 4.20.2` version.

## [3.3.1] - 11-01-2023

### Added

- Added Arabic language support (thanks to [@Abather](https://github.com/Abather))
- Added tailwind config. (thanks to [@JonErickson](https://github.com/JonErickson))
- Updated packages

## [3.3.0] - 28-10-2022

### Changed

- Rearranged :key on ResourceTable in a theoretical fix for sorting not working
- Fixed "load more" pagination not working
- Fixed duplicated "moveToStart" and "moveToEnd" messages
- Fixed table heading row breaking when there's no checkboxes (thanks to [@shaffe-fr](https://github.com/shaffe-fr))
- Bumped minimum Nova version to 4.17
- Updated packages

## [3.2.1] - 19-07-2022

### Changed

- Fixed console error 'reading stopPropagation of undefined'

## [3.2.0] - 19-07-2022

### Added

- Added Slovak language support (thanks to [@wamesro](https://github.com/wamesro))

### Changed

- Fixed visual duplicate rows appearing when reordering items
- Fixed deprecation warnings (thanks to [@mihai-stancu](https://github.com/mihai-stancu))
- Updated packages

## [3.1.0] - 19-07-2022

### Changed

- Updated components to `nova: 4.12.0` version. (thanks to [@stefblokdijk](https://github.com/stefblokdijk))

## [3.0.1] - 24-05-2022

### Changed

- Bumped Nova requirement to 4.6.0 (thanks to [@mertasan](https://github.com/mertasan))
- Fixed click events firing twice (thanks to [@ramcda](https://github.com/ramcda))

## [3.0.0] - 13-05-2022

**NB! Will not working until Nova releases a fix for Vue component overriding. Possibly coming in ^4.5.5.**

### Added

- Added Nova 4.0 support (huge thanks to [@mertasan](https://github.com/mertasan) and [@LTKort](https://github.com/LTKort))

### Changed

- Dropped PHP 7.X support
- Dropped Nova 3.0 support
- Changed namespace from OptimistDigital to Outl1ne
- Updated packages
