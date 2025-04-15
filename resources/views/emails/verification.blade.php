<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LiveAI ご登録の確認</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #eee;
        }
        h1 {
            color: #173F74;
            margin-top: 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
        .button {
            display: inline-block;
            background-color: #173F74;
            color: #ffffff;
            padding: 10px 20px;
            margin: 20px 0;
            border-radius: 5px;
            text-decoration: none;
        }
        .note {
            font-size: 14px;
            color: #6c757d;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LiveAI ご登録の確認</h1>
    </div>
    
    <div class="content">
        <p>LiveAIにご登録いただきありがとうございます。</p>
        <p>以下のリンクをクリックして、メール認証を完了してください：</p>
        
        <a href="{{ $verificationUrl }}" class="button">メールアドレスを認証する</a>
        
        <p class="note">このリンクの有効期限は24時間です。</p>
        <p>もしこのメールに心当たりがない場合は、このメールを無視していただければ幸いです。</p>
    </div>
    
    <div class="footer">
        <p>このメールはLiveAIの登録確認のために自動送信されています。</p>
    </div>
</body>
</html>
