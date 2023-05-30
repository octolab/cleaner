---
code: null
id: MDU6SXNzdWUxNTc3NjcyNzg=
databaseId: 157767278
number: 29
url: https://github.com/octolab/cleaner/issues/29
title: "add docs about how to extend plugin"
issueType: null
labels:
  - "type:docs"
assignees:
  - kamilsk
milestone: "[[milestone-1]]"
parent: null
issueFields: {}
state: CLOSED
stateReason: COMPLETED
duplicateOf: null
createdAt: 2016-05-31T20:49:33Z
updatedAt: 2016-06-01T06:20:50Z
lastEditedAt: null
closedAt: 2016-06-01T06:20:49Z
---

# add docs about how to extend plugin

``` php
        $interfaces = array(
            'cleaner' => 'OctoLab\Cleaner\Util\CleanerInterface',
            'matcher' => 'OctoLab\Cleaner\Util\MatcherInterface',
            'normalizer' => 'OctoLab\Cleaner\Util\NormalizerInterface',
        );
```

<!-- 2016-06-01T06:20Z https://github.com/octolab/cleaner/issues/29#issuecomment-222903459
fixed https://github.com/octolab/Cleaner/wiki#extending
-->
