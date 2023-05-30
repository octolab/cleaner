---
code: null
id: MDU6SXNzdWUxNTcxNDUyMjU=
databaseId: 157145225
number: 6
url: https://github.com/octolab/cleaner/issues/6
title: "Remove references to deleted directories/files from composer autoload files"
issueType: null
labels:
  - "status:research"
assignees:
  - kamilsk
milestone: null
parent: null
issueFields: {}
state: CLOSED
stateReason: COMPLETED
duplicateOf: null
createdAt: 2016-05-27T07:20:32Z
updatedAt: 2026-11-08T14:37:19Z
lastEditedAt: null
closedAt: 2016-06-01T07:31:16Z
---

# Remove references to deleted directories/files from composer autoload files

This is not mandatory, but it would be nice if this tool could remove references to the deleted files/directories from the composer autoload files:
- `vendor/composer/autoload_classmap.php`
- `vendor/composer/autoload_files.php`
- `vendor/composer/autoload_namespaces.php`
- `vendor/composer/autoload_psr4.php`
- `vendor/composer/autoload_static.php`

For instance, if we delete the `tests` directory from package `vendor1/package1`, we should strip out the following line from `vendor/composer/autoload_classmap.php`:

``` php
  'VendorNamespace1\\Tests\\TestClass' => $vendorDir . '/vendor1/package1/tests/TestClass.php',
```

<!-- 2016-06-01T07:31Z https://github.com/octolab/cleaner/issues/6#issuecomment-222915022
I did the following test with the current plugin version:

Create this `composer.json` file:

``` json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/mlocati/class-loader"
    },
    {
      "type": "vcs",
      "url": "https://github.com/mlocati/log"
    }
  ],
  "require": {
    "symfony/class-loader": "99.*",
    "psr/log": "1.*"
  },
  "config": {
    "optimize-autoloader": true,
    "preferred-install": {
      "*": "dist"
    },
    "octolab/cleaner": {
      "clear": {
        "*": "*"
      }
    }
  }
}
```

Running `composer install`, `vendor/composer/autoload_classmap.php` contains references to the test classes of `psr/log` (as expected, since we're installing the _official_ psr/log package).

Running `composer require psr/log:99.*` uses my forked version of psr/log (that specifies the files to be removed), and the references to the test classes are removed from composer autoload files... Great!

:+1:
-->
