---
code: null
id: MDU6SXNzdWUxODMyMjI4Mzk=
databaseId: 183222839
number: 38
url: https://github.com/octolab/cleaner/issues/38
title: "drop php 5.3-5.5 supporting"
issueType: null
labels:
  - "type:refactoring"
assignees:
  - kamilsk
milestone: "[[milestone-1]]"
parent: null
issueFields: {}
state: CLOSED
stateReason: COMPLETED
duplicateOf: null
createdAt: 2016-10-15T17:47:42Z
updatedAt: 2018-02-20T15:48:53Z
lastEditedAt: null
closedAt: 2018-02-20T15:48:53Z
---

# drop php 5.3-5.5 supporting

http://php.net/supported-versions.php

<!-- 2016-10-24T20:21Z https://github.com/octolab/cleaner/issues/38#issuecomment-255854449
Hi,
Since you are using `declare(strict_types = 1);`, you already support only PHP 7.x.
When I tried to compose require the plugin on PHP 5.6, it resulted in a list of errors:

```
Warning: Unsupported declare 'strict_types' in [...]\vendor\octolab\cleaner\src\Plugin.php on line 3

Warning: Unsupported declare 'strict_types' in [...]\vendor\octolab\cleaner\src\Config\PluginConfig.php on line 3

Warning: Unsupported declare 'strict_types' in [...]\vendor\octolab\cleaner\src\Util\FileCleaner.php on line 3

Warning: Unsupported declare 'strict_types' in [...]\vendor\octolab\cleaner\src\Util\CleanerInterface.php on line 1

Warning: Unsupported declare 'strict_types' in [...]\vendor\octolab\cleaner\src\Util\WeightMatcher.php on line 3

Warning: Unsupported declare 'strict_types' in [...]\vendor\octolab\cleaner\src\Util\MatcherInterface.php on line 1

Warning: Unsupported declare 'strict_types' in [...]\vendor\octolab\cleaner\src\Util\CategoryNormalizer.php on line 3

Warning: Unsupported declare 'strict_types' in [...]\vendor\octolab\cleaner\src\Util\NormalizerInterface.php on line 1
Plugin installation failed, rolling back
  - Removing octolab/cleaner (dev-master)

```

Could you make your plugin compatible with at least PHP 5.6, please?

> <small>fixed by https://github.com/octolab/Cleaner/pull/39</small>
-->

<!-- 2018-02-20T15:48Z https://github.com/octolab/cleaner/issues/38#issuecomment-367020811
fixed by f9625224910e2dc0cab3a283fd639bba18624025
-->
