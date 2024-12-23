<?php
class ErrorConfig
{
    private static string $mode = 'secure';    // モード

    // デフォルトの挙動をさせるモード
    public static function defaultMode():void
    {
        self::$mode = 'default';
    }

    // 挙動確認などの目的でエラーを出さない開発用モード
    public static function noErrorMode(): void
    {
        self::$mode = 'no-error';
    }

    // 厳密にエラーを出すモード
    public static function secureMode(): void
    {
        self::$mode = 'secure';
    }

    // モード取得
    public static function getMode()
    {
        return self::$mode;
    }
}