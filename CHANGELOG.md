# Changelog

All notable changes to `laravel-self-diagnosis` will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

## 1.5.0 - 2018-XX-XX

### Changed

- DocBlocks updated
- If `self-diagnosis` command fails - exit code is greater than `0` [#33]
- Minimal `php` version up to `v7.1.3`
- Required package `illuminate/support` changed to `laravel/framework` *(this package uses ServiceProvider, ConsoleCommand classes from him)*

[#33]:https://github.com/beyondcode/laravel-self-diagnosis/issues/33

[keepachangelog]:https://keepachangelog.com/en/1.0.0/
[semver]:https://semver.org/spec/v2.0.0.html
