# Changelog

All notable changes to this project will be documented in this file.

## [1.0.4]

- Fix optional allowRetake not being respected

## [1.0.3]

- Reorganize media files
- Add support for "retake" button on details view. Current editor will get 403 on next heartbeat.

## [1.0.2]

- Fix composer.json to include correct provider

## [1.0.1]

- Fix "dist" folder not being run as production in the previous release

## [1.0.0] - Initial release

- Field on index view to show who is editing
- Field on detail view to show who is editing
- Field on form view block resource from editing
- All migrations required for the above
- Trait to add to models to enable the above
- Configurable timeout for when a heartbeat is considered stale
- Configurable interval for how often to update the heartbeat
- Config file to configure various settings