<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>LiveAI 新規アカウント登録通知</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto;">
    <div style="background-color: #f9f9f9; padding: 20px; border-radius: 5px; margin-top: 20px;">
        <h2 style="color: #344EAF;">LiveAI 新規アカウント登録通知</h2>
        
        <p>新しいユーザーがアカウントを登録しました。</p>
        
        <div style="background-color: #fff; padding: 15px; border-radius: 3px; margin: 15px 0;">
            <p><strong>メールアドレス:</strong> {{ $user->email }}</p>
            <p><strong>登録日時:</strong> {{ $user->created_at->format('Y年m月d日 H:i') }}</p>
        </div>
        
        <p>このメールはシステムにより自動送信されています。</p>
    </div>
</body>
</html>
