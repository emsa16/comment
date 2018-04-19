Anax comment
==================================

[![Latest Stable Version](https://poser.pugx.org/anax/comment/v/stable)](https://packagist.org/packages/anax/comment)
[![Build Status](https://travis-ci.org/canax/comment.svg?branch=master)](https://travis-ci.org/canax/comment)
[![CircleCI](https://circleci.com/gh/canax/comment.svg?style=svg)](https://circleci.com/gh/canax/comment)
[![Build Status](https://scrutinizer-ci.com/g/canax/comment/badges/build.png?b=master)](https://scrutinizer-ci.com/g/canax/comment/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/canax/comment/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/canax/comment/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/canax/comment/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/canax/comment/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d831fd4c-b7c6-4ff0-9a83-102440af8929/mini.png)](https://insight.sensiolabs.com/projects/d831fd4c-b7c6-4ff0-9a83-102440af8929)

Anax comment module.


Requirements
------------------

In order to use this module you need an Anax framework environment. Some of the modules required are not publically available, so it is not possible to use a default Anax setup. In order to try out the below steps, you can use the [test branch](https://github.com/emsa16/anax/tree/comment-test) of the author's own Anax repo. Clone the branch with:

```
git clone https://github.com/emsa16/anax.git -b comment-test
cd comment-test
```

For the project to work the database needs to be setup. The DDL files necessary to fully run the comment module are `sql/ddl/comment_mysql_default.sql` and `sql/ddl/user_mysql_default.sql`.


Installation
------------------

Install the module with the following command:

```
composer require emsa/comment
```


Setup in Anax environment
------------------

Run the following command to automatically setup the module in your Anax installation (assumes a normal Anax project structure):

```
make install-module module=emsa/comment
```
The setup adds example pages and views that can be reached in the browser via the path `/comment`.


Usage
------------------

Short examples on how to use the module comment.



License
------------------

This software carries a MIT license.



```
 .  
..:  Copyright (c) 2018 Emil Sandberg (emil.hietanen@gmail.com)
```
