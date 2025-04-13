<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>お問い合わせがありました</title>
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
            color: #3490dc;
            margin-top: 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #6c757d;
            text-align: center;
        }
        .label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .message {
            white-space: pre-wrap;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LiveAI お問い合わせ</h1>
    </div>
    
    <div class="content">
        <p>以下の内容でお問い合わせがありました。</p>
        
        <div class="label">お名前:</div>
        <p>{{ $data['name'] }}</p>
        
        <div class="label">メールアドレス:</div>
        <p>{{ $data['email'] }}</p>
        
        <div class="label">お問い合わせ内容:</div>
        <div class="message">{{ $data['message'] }}</div>
    </div>
    
    <div class="footer">
        <p>このメールはLiveAIのお問い合わせフォームから自動送信されています。</p>
    </div>
</body>
</html>
