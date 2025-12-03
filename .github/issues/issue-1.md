---
id: 1
database_id: 156531404
node_id: MDU6SXNzdWUxNTY1MzE0MDQ=
status: closed
title: "support two types of extra config"
labels: ["type:task"]
url: https://github.com/octolab/cleaner/issues/1
created_at: 2016-05-24T15:02:32Z
updated_at: 2016-05-31T14:04:58Z
---

# support two types of extra config

case 1.

``` yaml
  dev-files:
  - "/dev-for-clear/*"
  - "!dev-for-clear/subdir/miss.md"
```

case 2.

``` yaml
  dev-files:
    docs: ["/CHANGELOG.md"]
    tests: ["/Tests", "/phpunit.xml.dist"]
    bin: ["/bin"]
    others: [".git*"]
```
