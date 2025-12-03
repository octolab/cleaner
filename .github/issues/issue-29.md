---
id: 29
database_id: 157767278
node_id: MDU6SXNzdWUxNTc3NjcyNzg=
status: closed
title: "add docs about how to extend plugin"
labels: ["type:docs"]
url: https://github.com/octolab/cleaner/issues/29
created_at: 2016-05-31T20:49:33Z
updated_at: 2016-06-01T06:20:50Z
---

# add docs about how to extend plugin

``` php
        $interfaces = array(
            'cleaner' => 'OctoLab\Cleaner\Util\CleanerInterface',
            'matcher' => 'OctoLab\Cleaner\Util\MatcherInterface',
            'normalizer' => 'OctoLab\Cleaner\Util\NormalizerInterface',
        );
```
