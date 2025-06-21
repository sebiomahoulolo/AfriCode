<?php

return [
    // Messages généraux
    'welcome' => 'AfriCodeへようこそ',
    'success' => '操作が成功しました',
    'error' => 'エラーが発生しました',
    'required' => 'この項目は必須です',
    'min' => 'この項目は最低:min文字必要です',
    'max' => 'この項目は最大:max文字までです',
    'email' => '有効なメールアドレスを入力してください',
    'unique' => 'この値は既に使用されています',
    'confirmed' => '確認が一致しません',
    'password' => 'パスワードは8文字以上で、文字、数字、記号を含める必要があります',

    // Messages de tutorat
    'tutoring' => [
        'session' => [
            'created' => 'セッションが作成されました',
            'updated' => 'セッションが更新されました',
            'deleted' => 'セッションが削除されました',
            'not_found' => 'セッションが見つかりません',
            'already_booked' => 'この時間枠は既に予約されています',
            'invalid_time' => '無効な時間枠です',
            'past_time' => '過去の時間枠は選択できません',
            'duration' => 'セッションの長さは30分から2時間の間でなければなりません',
        ],
        'subject' => [
            'created' => '科目が作成されました',
            'updated' => '科目が更新されました',
            'deleted' => '科目が削除されました',
            'not_found' => '科目が見つかりません',
        ],
        'level' => [
            'created' => 'レベルが作成されました',
            'updated' => 'レベルが更新されました',
            'deleted' => 'レベルが削除されました',
            'not_found' => 'レベルが見つかりません',
        ],
    ],

    // Messages d'authentification
    'auth' => [
        'login' => [
            'success' => 'ログインに成功しました',
            'failed' => 'ログインに失敗しました',
            'invalid_credentials' => 'メールアドレスまたはパスワードが正しくありません',
        ],
        'register' => [
            'success' => '登録に成功しました',
            'failed' => '登録に失敗しました',
        ],
        'logout' => [
            'success' => 'ログアウトしました',
        ],
        'password' => [
            'reset' => [
                'success' => 'パスワードがリセットされました',
                'failed' => 'パスワードのリセットに失敗しました',
                'invalid_token' => '無効なトークンです',
            ],
            'forgot' => [
                'success' => 'パスワードリセットのメールを送信しました',
                'failed' => 'パスワードリセットのメール送信に失敗しました',
            ],
        ],
    ],

    // Messages de validation
    'validation' => [
        'required' => ':attributeは必須です',
        'email' => ':attributeは有効なメールアドレスである必要があります',
        'min' => [
            'string' => ':attributeは最低:min文字必要です',
            'numeric' => ':attributeは最低:minである必要があります',
        ],
        'max' => [
            'string' => ':attributeは最大:max文字までです',
            'numeric' => ':attributeは最大:maxまでです',
        ],
        'unique' => ':attributeは既に使用されています',
        'confirmed' => ':attributeの確認が一致しません',
    ],

    // Messages de profil
    'profile' => [
        'updated' => 'プロフィールが更新されました',
        'not_found' => 'プロフィールが見つかりません',
        'invalid_image' => '無効な画像形式です',
        'image_too_large' => '画像サイズが大きすぎます',
    ],

    // Messages de paiement
    'payment' => [
        'success' => '支払いが成功しました',
        'failed' => '支払いに失敗しました',
        'refunded' => '返金が完了しました',
        'pending' => '支払いが保留中です',
    ],
]; 