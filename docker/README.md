# 構成
* OS:centos7
* webサーバー:apache2.4.6
* DB:MySQL
* クライアント:8.0.23
* サーバー:8.0.23
* 言語:PHP8.0.6
* ライブラリ管理ツール:Composer2.0.14
* フレームワーク:Laravel 8.44.0

# ローカル開発環境構築手順
* プロジェクトのGitリポジトリをクローン
$ git clone https://mari.backlog.jp/git/SHIP_CUSTOMER/ship-customer-server.git -b develop

* Dockerディレクトリに移動
$ cd docker

* Dockerfileからイメージを作成し、コンテナ起動
$ docker-compose up -d

* Webコンテナに入る
$ docker exec -it web-container bash

* プロジェクトディレクトリに移動
$ cd /var/www/app

* Composerインストール
※composer.jsonのあるディレクトリ（上記の /var/www/app）で実行
$ composer install

* LaravelのMigrate処理実行（DB作成）
※artisanのあるディレクトリ（/var/www/app）で実行
$ php artisan migrate

* LaravelのSeeder実行（DBデータ挿入）
※artisanのあるディレクトリ（/var/www/app）で実行
$ php artisan db:seed

* ブラウザで localhost を入力し、顧客管理のページを確認できればOK
* ブラウザで localhost:8080 を入力し、phpMyAdminのページを確認
→ ユーザー名:app パスワード:123456 でログインできればOK
