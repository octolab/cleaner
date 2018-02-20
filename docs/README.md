# API Specification

## Configuration

### Package level

The package level configuration located in [extra data](https://getcomposer.org/doc/04-schema.md#extra). The main target for this configuration is to describe files that can be deleted by [Cleaner plugin](https://github.com/octolab/Cleaner).

#### Basic usages

```yaml
extra:
  dev-files:
  - "/docs/*"
  - "!docs/LICENSE.md"
```

The example above will be interpreted as the equivalent of below

```yaml
extra:
  dev-files:
    other: ["/docs/*", "!docs/LICENSE.md"]
```

All paths are relative to a package root. The `other` is reserved word.

#### Advanced usages

```yaml
extra:
  dev-files:
    bin: ["/bin", "/action/demo.sh"]
    docs: ["/docs", "!docs/LICENSE.md"]
    tests: ["/tests"]
    other: ["/examples/*", "!examples/Bootstrap.md"]
```

#### Under the hood

The syntax for paths based on [glob](http://php.net/manual/en/function.glob.php) function.

### Project level

The project level configuration located at [config data](https://getcomposer.org/doc/04-schema.md#config). The main target for this configuration is to set rules for [Cleaner plugin](https://github.com/octolab/Cleaner) by which it will apply the cleaning.

#### Default behavior

```yaml
require:
  # main dependecies
  vendor/package1: *
  vendor/package2: *
  vendor/package3: *
  # you can use the plugin only in current project
  octolab/cleaner: 1.x-dev
  # or you can use it as global `composer global require octolab/cleaner`
config:
  octolab/cleaner:
    clear: ~
    debug: false
    cleaner:    \OctoLab\Cleaner\Util\FileCleaner
    matcher:    \OctoLab\Cleaner\Util\WeightMatcher
    normalizer: \OctoLab\Cleaner\Util\CategoryNormalizer
```

By default [Cleaner plugin](https://github.com/octolab/Cleaner) do nothing.

#### Using category

```yaml
config:
  octolab/cleaner:
    clean:
      vendor/package1: [bin, docs]
      vendor/package2: [tests]
      vendor/package3: [other]
```

Will be interpreted as
- _remove `bin` and `docs` categories of `dev-files` from package `vendor/package1`_
- _remove `tests` category of `dev-files` from package `vendor/package2`_
- _remove `other` category of `dev-files` from package `vendor/package3`_

##### Recommended categories

* __bin__
for command-line related files (for instance, if a project uses Doctrine DBAL only as a library, the bin folder is not needed).

* __doc__
for documentation, manual, readme, etc. (licenses are excluded since they should always stay with packages).

* __example__
for examples, tutorials, etc.

* __test__
for test-related files.

#### Using asterisk

```yaml
config:
  octolab/cleaner:
    clean:
      *: *
```

Will be interpreted as _remove all `dev-files` from all packages_.

```yaml
config:
  octolab/cleaner:
    clean:
      vendor/package1: *
```

Will be interpreted as _remove all `dev-files` from package `vendor/package1`_.

```yaml
config:
  octolab/cleaner:
    clean:
      *: [bin]
```

Will be interpreted as _remove `bin` category of `dev-files` from all packages_.

```yaml
config:
  octolab/cleaner:
    clean:
      vendor/*: [tests]
```

Will be interpreted as _remove `tests` category of `dev-files` from all packages of `vendor`_.

#### Using denial

```yaml
config:
  octolab/cleaner:
    clean:
      vendor/package1: [!bin]
```

Will be interpreted as _remove all categories of `dev-files` except `bin` from package `vendor/package1`_.

#### Using combinations

```yaml
config:
  octolab/cleaner:
    clean:
      *: *
      vendor/package1: [!bin]
```

Will be interpreted as _remove all `dev-files` from all packages except package `vendor/package1` where all categories of `dev-files` except `bin` will be removed_.

```yaml
config:
  octolab/cleaner:
    clean:
      *: [bin]
      vendor/package1: [!bin]
```

Will be interpreted as _remove `bin` category of `dev-files` from all packages except package `vendor/package1` where all categories of `dev-files` except `bin` will be removed_.

```yaml
config:
  octolab/cleaner:
    clean:
      *: [!bin]
      vendor/package1: [bin]
```

Will be interpreted as _remove all categories of `dev-files` except `bin` from all packages except package `vendor/package1` where only category `bin` of `dev-files` will be removed_.

#### Weight of operators

1. `package: [!]`
1. `package: [ ]`
1. `package:  *`
1. `*: [!]`
1. `*: [ ]`
1. `*: *`

This means that `[!docs, docs, *] === [!docs]` and `[docs, *] === [docs]`.

## Example of usages

### [Integration test](https://github.com/octolab/Cleaner/tree/master/tests/integration)

* [vendor/package1 configuration](https://github.com/octolab/Cleaner/blob/ac623257a4c5c4874b5fc11b5e7d529b266d5318/tests/integration/package1/composer.json#L7-L10)
* [vendor/package2 configuration](https://github.com/octolab/Cleaner/blob/ac623257a4c5c4874b5fc11b5e7d529b266d5318/tests/integration/package2/composer.json#L7-L11)
* [vendor/package3 configuration](https://github.com/octolab/Cleaner/blob/ac623257a4c5c4874b5fc11b5e7d529b266d5318/tests/integration/package3/composer.json#L7-L12)
* [project configuration](https://github.com/octolab/Cleaner/blob/ac623257a4c5c4874b5fc11b5e7d529b266d5318/tests/integration/project/composer.json#L20-L28)

## Extending

You very simply can change the behavior of the plugin by changing classes `cleaner`, `matcher` and `normalizer`. By default, they are represented by the following classes

```php
$default = array(
    ...,
    'cleaner'    => '\OctoLab\Cleaner\Util\FileCleaner',
    'matcher'    => '\OctoLab\Cleaner\Util\WeightMatcher',
    'normalizer' => '\OctoLab\Cleaner\Util\CategoryNormalizer',
);
```

The main thing that they implement the following interfaces

```php
$interfaces = array(
    'cleaner'    => 'OctoLab\Cleaner\Util\CleanerInterface',
    'matcher'    => 'OctoLab\Cleaner\Util\MatcherInterface',
    'normalizer' => 'OctoLab\Cleaner\Util\NormalizerInterface',
);
```

The configuration for this looks like

```yml
config:
  octolab/cleaner:
    clear: { ... }
    debug: false
    cleaner:    \Your\Cleaner
    matcher:    \Your\Matcher
    normalizer: \Your\Normalizer
```

## Debugging

If you set the `debug` option to `true`, then you activate debug mode. In this mode the `cleaner` option will be ignored and replaced by `\OctoLab\Cleaner\Util\FakeCleaner`.

The output looks like

![stdout in debug mode](http://content.screencast.com/users/kamilsk/folders/Jing/media/b4c11328-91ee-4ac8-bc2e-c886d294e606/00000069.png)

You can try this in [development installation](https://github.com/octolab/Cleaner#git-development) by the next steps

```bash
$ cd tests/integration/project
$ composer install
```
