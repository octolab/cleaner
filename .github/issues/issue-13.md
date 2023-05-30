---
code: null
id: MDU6SXNzdWUxNTcyMDE1NzQ=
databaseId: 157201574
number: 13
url: https://github.com/octolab/cleaner/issues/13
title: "add package config normalizer"
issueType: null
labels:
  - "type:task"
assignees:
  - kamilsk
milestone: "[[milestone-1]]"
parent: null
issueFields: {}
state: CLOSED
stateReason: COMPLETED
duplicateOf: null
createdAt: 2016-05-27T13:00:39Z
updatedAt: 2016-05-27T13:18:53Z
lastEditedAt: null
closedAt: 2016-05-27T13:18:53Z
---

# add package config normalizer

normalize

``` yaml
extra:
  dev-files:
  - "/docs/*"
  - "!docs/LICENSE.md"
```

to

``` yaml
extra:
  dev-files:
    other: ["/docs/*", "!docs/LICENSE.md"]
```
