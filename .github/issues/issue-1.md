---
code: null
id: MDU6SXNzdWUxNTY1MzE0MDQ=
databaseId: 156531404
number: 1
url: https://github.com/octolab/cleaner/issues/1
title: "support two types of extra config"
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
createdAt: 2016-05-24T15:02:32Z
updatedAt: 2016-05-31T14:04:58Z
lastEditedAt: 2016-05-24T15:09:02Z
closedAt: 2016-05-31T14:04:58Z
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

<!-- 2016-05-24T15:06Z https://github.com/octolab/cleaner/issues/1#issuecomment-221301161
Or maybe simply

``` yaml
dev-files:
  - "/dev-for-clear/*"
  - "!dev-for-clear/subdir/miss.md"
dev-files-bin:
  - "/bin"
```
-->

<!-- 2016-05-24T15:10Z https://github.com/octolab/cleaner/issues/1#issuecomment-221302600
@mlocati, for the second case I don't imagine how composer remove only `docs` for `package1` and only `bin` for `package2`. any suggestion?
-->

<!-- 2016-05-24T15:15Z https://github.com/octolab/cleaner/issues/1#issuecomment-221304175
It's just to have a simpler process: instead of having many categories, we could have just two ("remove always", and "always remove + remove bin"): docs, tests, ... could be always removed, bin only if requested.
These two solutions are alternatives, not meant to be both valid... I'm just adding some arguments before coding starts.
-->

<!-- 2016-05-24T15:28Z https://github.com/octolab/cleaner/issues/1#issuecomment-221308348
How about this API:

**package 1**:

``` json
{
  "extra": {
    "dev-files": [
      "/Tests", "/docs"
    ],
  }
}
```

**package 2**:

``` json
{
  "extra": {
    "dev-files": {
        "docs": ["/docs/Some.md"],
        "bin": ["/bin", "/actions/test.sh"]
    },
  }
}
```

**project**:

``` json
{
  "require": {
    "package 1": "*",
    "package 2": "*"
  },
  "config": {
    "octolab/cleaner": {
      "clean": { "package 1": ["docs"], "package 2": ["bin"] }
    }
  }
}
```

`composer install` will remove only `bin` in package 2, because `docs` not presented in package 1

if I change config to this: `"clean": "*"`, than `composer install` remove all dev-files.
-->

<!-- 2016-05-24T15:33Z https://github.com/octolab/cleaner/issues/1#issuecomment-221310043
What about this?

``` json
{
    "require": {
        "package 1": "*",
        "package 2": "*",
        "package 3": "*"
    },
    "config": {
        "octolab/cleaner": {
            "clean": {
                "*": ["docs"],
                "package 2": ["bin"],
                "package 3": "*"
            }
        }
    }
}
```

The `"*"`  key for the package name could mean "all packages except the ones explicitly listed".
The `"*"` values mean "everything"

EDIT: I updated the above sample json
-->

<!-- 2016-05-24T15:36Z https://github.com/octolab/cleaner/issues/1#issuecomment-221311152
Another improvement option could be a syntax like

``` json
{
            "clean": {
                "*": "!bin"
            }
}
```

meaning "all except bin", but things start being quite complex :wink:
-->

<!-- 2016-05-24T15:38Z https://github.com/octolab/cleaner/issues/1#issuecomment-221311719
yep, https://github.com/octolab/Cleaner/issues/1#issuecomment-221310043 looks interesting. if `octolab/cleaner` config is not presented, than it do nothing.
-->

<!-- 2016-05-24T15:39Z https://github.com/octolab/cleaner/issues/1#issuecomment-221312194
https://github.com/octolab/Cleaner/issues/1#issuecomment-221311152 increase complexity and I would like to implement "excluding" outside the MVP (maybe in feature release)
-->

<!-- 2016-05-24T15:42Z https://github.com/octolab/cleaner/issues/1#issuecomment-221313037
> #1 (comment) increase complexity and I would like to implement "excluding" outside the MVP (maybe in feature release)

Yes, I'm totally with you.
By the way, we'd need to define a list of all the possible "categories", so that developers can say "delete all except bin" by listing the other categories.
For instance, we can impose that the categories could be only "bin", "doc", "example", "test", "other", and telling "all except bin" could be done by listing "doc example test other"
-->

<!-- 2016-05-24T15:44Z https://github.com/octolab/cleaner/issues/1#issuecomment-221313656
And in case of your "case 1." [above](https://github.com/octolab/Cleaner/issues/1#issue-156531404), the files could be assumed as "other".
-->

<!-- 2016-05-24T15:44Z https://github.com/octolab/cleaner/issues/1#issuecomment-221313911
OK, I will freeze API tomorrow morning and write specs on wiki. With the specs I continue my work above the plugin.

All features that will not be presented on it will moved to future releases.
-->

<!-- 2016-05-24T15:46Z https://github.com/octolab/cleaner/issues/1#issuecomment-221314452
https://github.com/octolab/Cleaner/issues/1#issuecomment-221313656 you mean that default category name is "other" ?
-->

<!-- 2016-05-24T15:47Z https://github.com/octolab/cleaner/issues/1#issuecomment-221314978
> #1 (comment) you mean that default category name is "other" ?

I don't see a better alternative...
-->

<!-- 2016-05-24T15:49Z https://github.com/octolab/cleaner/issues/1#issuecomment-221315448
I was just trying to think of a name for this category :) "other" is good to me
-->

<!-- 2016-05-25T08:32Z https://github.com/octolab/cleaner/issues/1#issuecomment-221507925
@mlocati done https://github.com/octolab/Cleaner/wiki, can you review?
-->

<!-- 2016-05-25T10:11Z https://github.com/octolab/cleaner/issues/1#issuecomment-221530843
Just two notes:

### Categories

Let's assume I want to delete all the unnecessary files but the `bin` ones: in this case I need to know which may be all the other categories.
So, I'd impose that we can have only a fixed set of categories (`bin`, `docs`, `tests`, `other`): if one tries to use another category, I'd raise an error.

### Path specification

It would be great if path specs will be compatible with the ones [used by git](https://git-scm.com/docs/gitignore#_pattern_format).
For instance:
- `*.c` matches all the files with a `.c` extension, in any directory
- `/test/*.c` matches all the files with a `.c` extension that are in the `/test` directory (but not in `/test/subfolder/`)
- `/test/**/*.c` matches all the files with a `.c` extension that are in the `/test` directory and in all its subdirectories

And also
- `path` matches any file or directory whose name is `path`
- `path/` matches only directories
-->

<!-- 2016-05-25T10:21Z https://github.com/octolab/cleaner/issues/1#issuecomment-221532929
about path specification: all works based on http://symfony.com/doc/current/components/finder.html, create another component not at the moment, but I try to do it extendable.

git pattern format is really true way, it is transparent to .gitattributes, and maybe it fully supported by `finder`, but at the start I would like to get feedback about how this decision will be demanded, and whether the work done by the effort

about categories: introduce the dictionary is not a flexible way, I freeze only one word `other`. I don't want to get issues like this ones

> Could you add category `buzzword`?
-->

<!-- 2016-05-25T10:25Z https://github.com/octolab/cleaner/issues/1#issuecomment-221533645
> about categories: introduce the dictionary is not a flexible way, I freeze only one word other. I don't want to get issues like this ones
>
> Could you add category buzzword?

So, how would you say "delete all the categories except `bin`"?
-->

<!-- 2016-05-25T10:41Z https://github.com/octolab/cleaner/issues/1#issuecomment-221536918
https://github.com/octolab/Cleaner/wiki was updated
-->

<!-- 2016-05-25T10:42Z https://github.com/octolab/cleaner/issues/1#issuecomment-221537049
important https://github.com/octolab/Cleaner/wiki#weight-of-operators to review
-->

<!-- 2016-05-25T13:54Z https://github.com/octolab/cleaner/issues/1#issuecomment-221583180
### Weight of operators:

Consider the case when we have 100 packages, and we want to keep the `bin` files of just one package.
This could be easily specified if we interpret this code

``` yaml
config:
  octolab/cleaner:
    clean:
      *: *
      vendor/package1: !bin
```

as

`by default remove all dev-files, but for vendor/package1 keep only bin`

What I mean is that the `*` package could be assumed to be the default behavior: if a package is not explicitly described we use this default behavior.
-->

<!-- 2016-05-25T14:11Z https://github.com/octolab/cleaner/issues/1#issuecomment-221588483
good point, changed for this

```
1. `package:  *`
2. `package: [!]`
3. `package: [ ]`
4. `*: *`
5. `*: [!]`
6. `*: [ ]`
```

What do you think?
-->

<!-- 2016-05-25T14:41Z https://github.com/octolab/cleaner/issues/1#issuecomment-221597849
What about

```
1. `package: [!]`
2. `package: [ ]`
3. `package:  *`
4. `*: [!]`
5. `*: [ ]`
6. `*: *`
```

I mean, the asterisk has always the lowest priority even when specifying the categories to delete:
by reading this:

``` yaml
config:
  octolab/cleaner:
    clean:
      vendor/package1: [*, !docs]
```

IMHO it's more natural to read it delete all, but not docs (furthermore it's much saver, if someone writes an asterisk by mistake he/she delete everything).

PS: what's the `~` [here](https://github.com/octolab/Cleaner/wiki/Home/d204a56378ae125f90f21d358c8d66280758b986#default-behavior)?
-->

<!-- 2016-05-25T14:46Z https://github.com/octolab/cleaner/issues/1#issuecomment-221599492
> PS: what's the ~ here?

in YAML is null after parsing
-->

<!-- 2016-05-25T14:49Z https://github.com/octolab/cleaner/issues/1#issuecomment-221600360
`[*, !docs, info] === [!docs, info] === [!docs]` -> remove all except docs?
-->

<!-- 2016-05-25T14:53Z https://github.com/octolab/cleaner/issues/1#issuecomment-221601951
I using examples on YAML because I really like this format:

https://github.com/kamilsk/Common/blob/3.x/package.meta
https://github.com/octolab/Cleaner/blob/master/package.meta
http://www.slideshare.net/ssuserb6ada9/octolab-cookbook-how-to-use-composeryml-and-stop-creating-issues-about

Sometimes it is very powerful: https://github.com/kamilsk/Common/blob/3.x/package.meta#L26-L29
-->

<!-- 2016-05-25T15:01Z https://github.com/octolab/cleaner/issues/1#issuecomment-221604682
wiki was updated, so, if you don't have any other suggestions I lock this conversation
-->

<!-- 2016-05-25T15:06Z https://github.com/octolab/cleaner/issues/1#issuecomment-221606207
> wiki was updated, so, if you don't have any other suggestions I lock this conversation

:+1:
-->

<!-- 2016-05-25T15:07Z https://github.com/octolab/cleaner/issues/1#issuecomment-221606492
> `[*, !docs, info] === [!docs, info] === [!docs]` -> remove all except docs?

:+1:
-->

<!-- 2016-05-25T15:11Z https://github.com/octolab/cleaner/issues/1#issuecomment-221607562
> I using examples on YAML because I really like this format:

Yes, it's cleaner and a bit shorter than json.
BTW I've worked with XML a lot, so for me JSON is short and clean enough :D
-->

<!-- 2016-05-31T14:04Z https://github.com/octolab/cleaner/issues/1#issuecomment-222698685
fixed
-->
