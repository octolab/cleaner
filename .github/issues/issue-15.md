---
code: null
id: MDU6SXNzdWUxNTc1MDczMTU=
databaseId: 157507315
number: 15
url: https://github.com/octolab/cleaner/issues/15
title: "check correctness of test cases"
issueType: null
labels:
  - "status:research"
assignees:
  - kamilsk
milestone: "[[milestone-1]]"
parent: null
issueFields: {}
state: CLOSED
stateReason: COMPLETED
duplicateOf: null
createdAt: 2016-05-30T13:53:40Z
updatedAt: 2026-11-08T14:37:20Z
lastEditedAt: null
closedAt: 2016-05-30T16:51:52Z
---

# check correctness of test cases

configuration for packages: https://github.com/octolab/Cleaner/blob/master/tests/fixtures/packages.yml

test cases: https://github.com/octolab/Cleaner/tree/master/tests/fixtures/testcases/matcher/weight

example:
project configuration https://github.com/octolab/Cleaner/blob/master/tests/fixtures/testcases/matcher/weight/mixed/setup.yml#L5-L11

categories for removing https://github.com/octolab/Cleaner/blob/master/tests/fixtures/testcases/matcher/weight/mixed/expected.yml#L1-L5

<!-- 2016-05-30T13:54Z https://github.com/octolab/cleaner/issues/15#issuecomment-222497634
@mlocati could you help with the issue?
-->

<!-- 2016-05-30T14:28Z https://github.com/octolab/cleaner/issues/15#issuecomment-222504100
> @mlocati could you help with the issue?

Sorry but I don't understand what would you like me to do... Should I manually check if the `expected.yml` files are good for their corresponding `setup.yml` files?
-->

<!-- 2016-05-30T14:36Z https://github.com/octolab/cleaner/issues/15#issuecomment-222505584
That all test cases have correct behavior. package.yml (input) - setup.yml (rules) - expected.yml (output)
-->

<!-- 2016-05-30T14:42Z https://github.com/octolab/cleaner/issues/15#issuecomment-222506729
Yes, everything is fine. Good work!
-->

<!-- 2016-05-30T16:51Z https://github.com/octolab/cleaner/issues/15#issuecomment-222527209
ok, thanks
-->
