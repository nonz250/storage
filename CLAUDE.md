# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## プロジェクト概要

PHP 8.1で構築された個人向けファイルストレージサーバー。REST APIでファイル管理機能を提供。Domain-Driven Design（DDD）とClean Architectureの原則に基づいて設計されている。

## 開発コマンド

```bash
# 初期セットアップ（環境構築からテストまで一括実行）
make setup

# 開発サーバー起動/停止
make up                 # Docker起動
make down               # Docker停止

# テスト・品質チェック
make test               # PHP-CS-Fixer + PHPUnit実行
make lint               # PHP-CS-Fixerのみ実行
make pr                 # PR前の確認（lint + test）

# データベース
make migrate            # マイグレーション実行

# メンテナンス
make clean              # コンテナとボリューム削除
make reset              # 完全リセット（clean + setup）
```

## アーキテクチャ

### ディレクトリ構成（backend/app/）

- **Domain/** - ドメインロジック
  - `Auth/` - Digest認証
  - `Client/` - クライアント管理
  - `File/` - ファイル管理（コアビジネスロジック）
- **Adapter/** - リポジトリパターンの実装
- **Http/** - HTTPアクションハンドラ（PSR-7/PSR-15）
- **Console/** - CLIコマンド（マイグレーション、クライアント管理）
- **Foundation/** - 基底クラス、ValueObject、例外階層
- **Provider/** - DIコンテナ用サービスプロバイダ

### 技術スタック

- **HTTP**: League Route, Laminas Diactoros/HttpHandlerRunner
- **DI**: League Container
- **CLI**: Symfony Console
- **ログ**: Monolog
- **DB**: MySQL 5.7（Docker）

## コーディング規約

PHP-CS-Fixer（`backend/.php-cs-fixer.dist.php`）で厳格に管理：

- `declare(strict_types=1)` 必須
- 全クラスは `final` 宣言
- 戻り値型宣言必須（`void`含む）
- 厳密比較演算子のみ（`===`, `!==`）
- 配列は短縮構文`[]`、複数行は末尾カンマ
- シングルクォート優先
- `DateTimeImmutable` 使用
- `mb_*` 関数優先

## テスト

```bash
# 全テスト実行
make test

# 個別テスト実行
docker compose exec app-test vendor/bin/phpunit tests/Domain/File/FileIdTest.php
```

テストファイルは `backend/tests/` に配置。`RepositoryTestCase` と `RepositoryTestTrait` をリポジトリテストで使用。

## 環境設定

- 開発環境: `docker-compose.yml`
- テスト環境: `docker-compose.test.yml`（ポート13306で別DBを使用）
- 環境変数: `backend/.env`

## 名前空間

- アプリケーション: `Nonz250\Storage\App\`
- テスト: `Tests\`
