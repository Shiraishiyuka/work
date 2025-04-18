# work

## 環境構築

### 1.Dockerビルド

1. git clone git@github.com:Shiraishiyuka/resubmit.git
2. DockerDesktopアプリを立ち上げる
3. docker-compose up -d --build


### 2.Laravel環境構築
1. docker-compose exec php bash
2. composer install
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4. .envに以下の環境変数を追加（Mysqlとmailhog）


-DB_CONNECTION=mysql
-DB_HOST=mysql
-DB_PORT=3306
-DB_DATABASE=laravel_db
-DB_USERNAME=laravel_user
-DB_PASSWORD=laravel_pass

-MAIL_MAILER=smtp
-MAIL_HOST=mailhog
-MAIL_PORT=1025
-MAIL_USERNAME=null
-MAIL_PASSWORD=null
-MAIL_ENCRYPTION=null
-MAIL_FROM_ADDRESS=example@example.com
-MAIL_FROM_NAME="${APP_NAME}"


### 3.アプリケーションキーの作成
#### php artisan key:generate


###  4.Fortifyのインストール
#### composer require laravel/fortify
#### php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"


### 5.storage ディレクトリに画像を保存

#### storage/app/publicに商品画像を保存する用のimagesディレクトリを作成
#### githubのimagesフォルダの画像を入れてください。
#### storage/app/publicにユーザープロフィールのアイコン画像を保存するためのprofile_imagesディレクトリを作成
#### php artisan storage:link


### 6.マイグレーションの実行
#### php artisan migrate


### 7.シーディングの実行
#### php artisan db:seed


### 使用技術(実行環境)

* PHP7.4.9
* Laravel8.83.29
* MySQL10.3.39


### URL

#### [開発環境] http://localhost
#### [phpMyAdmin] http://localhost:8080


