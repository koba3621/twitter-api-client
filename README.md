# twitter-api-client
本Repositoryの内容は、Twitter API (v1.1)を実行するためのClientになります。  
いくつかのGET/POST系のエンドポイント(API)で動作確認をしていますので、
ご参考としてご利用いただければと思います。

## 概要
- .env.exampleを.envへファイル名を変更し、Twitter Developerから確認できるご自身のKey, Token情報をご記入ください。
- example_index.phpに実行するコード例を記載していますので、改変して実装いただければと思います。
- .gitignoreに.envを追加していますので、そのままremote repositoryにpush頂いても、認証情報系はuploadされません