---
id: 53
database_id: 341472153
node_id: MDU6SXNzdWUzNDE0NzIxNTM=
status: closed
title: "How to use this thing?"
labels: []
url: https://github.com/octolab/cleaner/issues/53
created_at: 2018-07-16T10:53:22Z
updated_at: 2018-07-16T14:57:36Z
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
