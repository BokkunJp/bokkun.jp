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
     * @param \Closure $callBack
     *
     * @return void|mixed
     */
    public function CommonProcessArray(string|int $parentId, string|int $childId, \Closure $callBack)
    {
        $data = null;
        if ($this->Judge($parentId)) {
            $tmp = $this->Read($parentId);
            $data = $callBack($tmp);
        }

        return $data;
    }
}
