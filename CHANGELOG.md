# Related Changelog

## 2.0.0 - 2022-08-10

### Updated
- Updated plugin to support Craft v4

## 1.2.1 - 2022-05-30

### Fixed
- Typecast query parameters before getting Craft elements

## 1.2.0 - 2022-03-08

### Added

- Added support for displaying relations on Asset edit pages.

## 1.1.6 - 2020-03-30

### Updated
- Thanks to @SHoogland for updating lookup for sections and categories to use handle instead of ID to support the de-sync between different databases. WARNING: You'll may need to reconfigure the plugin settings.
- Set minimum PHP support to >=7.2.5

### Fixed
- Changed status icon color to support Entry status colors reported by @terryupton.
- Hide category options if no categories' setup, fixing UI bug.

## 1.1.5 - 2020-03-23

### Added
- Added support for Categories and Users, allowing both viewing their relations and returning them in other relations.

### Updated
- Updated HTML layout and support more usable linking in the relation table.

## 1.1.4 - 2020-09-02
### Updated
- Updated to support latest CraftCMS release.

## 1.1.3 - 2020-04-02
### Fixed
- Composer deprecation notice ([#4](https://github.com/wrav/related/issues/4)).

## 1.1.2 - 2020-04-02
### Fixed
- Set the model as hidden till opened ([#5](https://github.com/wrav/related/issues/5)).

## 1.1.1 - 2020-03-06
### Fixed
- Removed trailing comma to support all PHP 7.x builds.

## 1.1.0 - 2020-03-05
### Added
- Added support for entries linked inside Matrix fields.

## 1.0.0 - 2019-03-25
### Added
- Initial release
