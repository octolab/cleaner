---
id: 13
database_id: 157201574
node_id: MDU6SXNzdWUxNTcyMDE1NzQ=
status: closed
title: "add package config normalizer"
labels: ["type:task"]
url: https://github.com/octolab/cleaner/issues/13
created_at: 2016-05-27T13:00:39Z
updated_at: 2016-05-27T13:18:53Z
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
