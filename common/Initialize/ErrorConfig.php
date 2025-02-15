<?php
class ErrorConfig
{
    // 各モード
    public const DEFAULT_MODE = 'default';
    public const NO_ERROR_MODE = 'no-error';
    public const SECURE_MODE = 'secure';
    private static string $mode = self::SECURE_MODE;    // モード

    // デフォルトの挙動をさせるモード
    public static function defaultMode():void
    {
        self::$mode = self::DEFAULT_MODE;
    }

    // 挙動確認などの目的でエラーを出さない開発用モード
    public static function noErrorMode(): void
    {
        self::$mode = self::NO_ERROR_MODE;
    }

    // 厳密にエラーを出すモード
    public static function secureMode(): void
    {
        self::$mode = self::SECURE_MODE;
    }

    // モード取得
    public static function getMode()
    {
        return self::$mode;
    }
}