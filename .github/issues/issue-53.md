---
code: null
id: MDU6SXNzdWUzNDE0NzIxNTM=
databaseId: 341472153
number: 53
url: https://github.com/octolab/cleaner/issues/53
title: "How to use this thing?"
issueType: null
labels: []
assignees:
  - kamilsk
milestone: null
parent: null
issueFields: {}
state: CLOSED
stateReason: COMPLETED
duplicateOf: null
createdAt: 2018-07-16T10:53:22Z
updatedAt: 2026-11-08T14:37:20Z
lastEditedAt: 2018-07-16T10:56:03Z
closedAt: 2018-07-16T14:03:25Z
---

# How to use this thing?

Sorry, I don't find the docs very clear.  What I need is a simple 1,2,3 for how to set this up for my project to achieve my goal, which I think is just a common goal.

My goal is that when someone does "composer install <somepackage>" that requires my package, only production files are present in the vendor/<mypackage> directory.  Thus saving lots of space and clutter.  I should note that my package is hosted on github and registered on packagist.

I tried  this in my project's composer.json

```
{
    ...
    "require": {
        "octolab/cleaner": "1.0-alpha"
    },
   "extra": {
        "dev-files": {
            "other": [
                "/tools",
                "/bindings",
                "!/bindings/php",
                "*.json",
                "*.md",
                "!/README.md"
            ]
        }
    }
}
```

I pushed this to github, and then required that specific commit in another project's composer.json and ran  composer update for the 2nd project which installed latest version from github.  I expected a subset of files, but the full set of files was installed.

I did NOT run, composer GLOBAL require octolab/cleaner:...

It's a non-starter (misses goal) if everyone that depends on my project has to do that.

but in my first project I see::

```
$ composer show | egrep octolab/cleaner
octolab/cleaner       1.0.0-alpha Composer plugin to removing development files.
```

So:
1. Will this plugin do what I'm trying to achieve?
2. Am I doing something wrong?

thanks.

<!-- 2018-07-16T11:17Z https://github.com/octolab/cleaner/issues/53#issuecomment-405215883
@dan-da, hi!

> My goal is that when someone does "composer install " that requires my package, only production files are present in the vendor/ directory.

You can achieve this through two strategies:

1. `.gitattributes + release tag` - you can exclude unnecessary files from your release.
A first article I found in Google: https://madewithlove.be/gitattributes/
Real example: https://github.com/octolab/cleaner/blob/master/.gitattributes

2. or the second strategy uses the `cleaner` and more complex:
    1. declare unnecessary files in your package in the `extra.dev-files` section ([docs](https://github.com/octolab/cleaner/tree/master/docs#package-level))
    2. someone, who uses your package, must configure the `cleaner` package to remove your unnecessary files ([docs](https://github.com/octolab/cleaner/tree/master/docs#project-level))

Real example: https://github.com/octolab/cleaner/tree/master/docs#example-of-usages.
Your composer file must contain something like [this](https://github.com/octolab/Cleaner/blob/ac623257a4c5c4874b5fc11b5e7d529b266d5318/tests/integration/package1/composer.json#L7-L10), your users could configure their composer files like [this](https://github.com/octolab/Cleaner/blob/ac623257a4c5c4874b5fc11b5e7d529b266d5318/tests/integration/project/composer.json#L20-L28).

It is not possible to remove files not presented in `dev-files` yet, but I think it is a good option for the future.
-->

<!-- 2018-07-16T11:21Z https://github.com/octolab/cleaner/issues/53#issuecomment-405216698
In your case:

remove
```json
"require": {
    "octolab/cleaner": "1.0-alpha"
},
```
from your composer file.

add
```json
"require": {
    "octolab/cleaner": "1.0-alpha"
},
"config": {
  "octolab/cleaner": {
     "clean": { "your/package": ["other"] }
  }
}
```
to `another project's composer.json`
-->

<!-- 2018-07-16T11:23Z https://github.com/octolab/cleaner/issues/53#issuecomment-405217018
I'm not sure if it is possible to use `require-dev` instead of `require`. This is not tested.
-->

<!-- 2018-07-16T14:03Z https://github.com/octolab/cleaner/issues/53#issuecomment-405258022
@dan-da feel free to reopen this issue or request clarification
-->

<!-- 2018-07-16T14:57Z https://github.com/octolab/cleaner/issues/53#issuecomment-405275900
@kamilsk thanks for your help.

1. The gitattributes approach won't work for me because my package has bindings for other languages in it and anyway I want the github tarball to be an accurate representation of the source.  I just want to make a streamlined version automagically when  someone does a composer install.

For example in npm -- package.json includes a "files" parameter to define exactly which files are included in the package, or not.  I was hoping that composer does also, which is what finally led me to cleaner.

2. moving the configuration to a package that depends on mine moves the burden from my package in a single place where imho it belongs to all of the authors of dependent packages that know nothing about my package internals.

I guess I'm just trying to do something that the cleaner plugin is not designed for.  That wasn't immediately apparent to me because I didn't find a clear description of what this plugin does exactly, so maybe consider this a suggestion to clarify that up front in the readme with simple examples and consider my use case for future versions.

For now, I guess the composer installed version of my package will just have to include all the dev files.

thanks again for your quick feedback and suggestions.  really helpful, just not a fit for my specific need right now.
-->
