Anax comment
==================================

[![Latest Stable Version](https://poser.pugx.org/emsa/comment/v/stable)](https://packagist.org/packages/emsa/comment)
[![Build Status](https://travis-ci.org/canax/comment.svg?branch=master)](https://travis-ci.org/canax/comment)
[![CircleCI](https://circleci.com/gh/canax/comment.svg?style=svg)](https://circleci.com/gh/canax/comment)
[![Build Status](https://scrutinizer-ci.com/g/canax/comment/badges/build.png?b=master)](https://scrutinizer-ci.com/g/canax/comment/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/canax/comment/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/canax/comment/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/canax/comment/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/canax/comment/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d831fd4c-b7c6-4ff0-9a83-102440af8929/mini.png)](https://insight.sensiolabs.com/projects/d831fd4c-b7c6-4ff0-9a83-102440af8929)

Anax comment module.


Requirements
------------------

In order to use this module you need an Anax framework environment. Some of the modules required are not publically available, so it is not possible to use a default Anax setup. In order to try out the below steps, you can use the [test branch](https://github.com/emsa16/anax/tree/comment-test) of the author's own Anax repo:

```
git clone https://github.com/emsa16/anax.git -b comment-test
cd anax
composer install
```

For the project to work the database also needs to be setup:

```
mv config/database_default.php config/database.php
```

Then change dsn, username and password within `database.php` to match your environment.

A User table also needs to be added to the database manually, `sql/ddl/user_mysql_default.sql` contains the necessary DDL for that.


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

Finally, a Comment table needs to be manually added to the database, `sql/ddl/comment_mysql_default.sql` contains the necessary DDL for that.

The comment functionality can now be demoed under `/comment/1` and `/comment/2`.


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
