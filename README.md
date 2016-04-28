BokunoSlim3 ver 1.0.0
=======================

Using Composer
----------------------------
Alternately, clone the repository and manually invoke `composer` using the shipped
`composer.phar`:

    cd my/project/dir
    php composer.phar self-update
    php composer.phar install
    
    cd my/project/dir/app/public
    npm install
    

DocumentRoot
--------------------
my/project/dir/app/public

自動生成
------------
スキーマ生成(DBからxmlファイルを作成)

    cd my/project/dir/generator/bin
    php schema.php

Entity,Model,Table生成

    cd my/project/dir/generator/bin
    php orm.php

Operation生成(ディレクトリの作成)

    cd my/project/dir/generator/bin
    php operation.php
