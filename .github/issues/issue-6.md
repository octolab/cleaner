---
id: 6
database_id: 157145225
node_id: MDU6SXNzdWUxNTcxNDUyMjU=
status: closed
title: "Remove references to deleted directories/files from composer autoload files"
labels: ["status:research"]
url: https://github.com/octolab/cleaner/issues/6
created_at: 2016-05-27T07:20:32Z
updated_at: 2016-06-01T07:31:16Z
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
