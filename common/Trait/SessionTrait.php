<?php

trait SessionTrait
{
    /**
     * CommonProcessArray
     *
     * セッション2次元配列の共通処理の記述
     *
     * @param string|integer $parentId
     * @param string|integer $childId
     * @param ?callable $callBack
     *
     * @return mixed
     */
    public function CommonProcessArray(string|int $parentId, string|int $childId, ?callable $callBack): mixed
    {
        $data = null;
        if ($this->Judge($parentId) && is_callable($callBack)) {
            $tmp = $this->Read($parentId);
            $data = $callBack($tmp);
        }

        return $data;
    }
}
