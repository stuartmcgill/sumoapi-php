# Change Log

## [0.8.1] - 2024-09-14

### Fixed
- Return [] instead of `null` when no more kimarite to return

## [0.8.0] - 2024-09-14

### Added
- Kimarite API

## [0.7.1] - 2024-03-10

### Fixed
- Fix bug when fetching non-existent rikishi

## [0.7.0] - 2024-03-10

### Changed
- Minimum dependency PHP 8.3
- Composer update

## [0.6.0] - 2023-11-04

### Changed
- Minimum dependency PHP 8.2
- Composer update

## [0.5.0] - 2023-07-27

### Added
- Matchups API

## [0.4.8] - 2023-07-10

### Added
- Installation section to `README`

## [0.4.7] - 2023-07-01

### Fixed
- Birthdate warning when creating debuting Rikishi

## [0.4.6] - 2023-07-01

### Changed
- Allow more `null` properties (needed for debuting rikishi)

## [0.4.5] - 2023-07-01

### Changed
- Allow for `null` heya

## [0.4.4] - 2023-06-30

### Changed
- `fetchAllByDivision()` is now sorted

## [0.4.3] - 2023-06-30

### Added
- `RikishiService::fetchAllByDivision()`

## [0.4.2] - 2023-06-29

### Added
- `Rank::matchesPerBasho()`

## [0.4.1] - 2023-05-13

### Added
- Prevent PRs from merging if the build fails

## [0.4.0] - 2023-05-13

### Changed
- Add `isGreaterThan()` function to `Rank` class

## [0.3.0] - 2023-05-08

### Added
- Rikishi service `fetshSome()` and `fetchDivision()`
- Basho service

## [0.2.0] - 2023-04-17

### Added
- Add Rikishi service

## [0.1.0] - 2023-04-17

### Added
- Initial version of the API
