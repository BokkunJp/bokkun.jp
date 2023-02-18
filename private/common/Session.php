<?php

namespace private;

// セッションクラス (管理側)
class Session extends \common\Session
{
    use \SessionTrait;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * JudgeArray
     *
     * セッション2次元配列判定
     *
     * @param string|int $parentId
     * @param string|int $childId
     *
     * @return bool
     */
    public function JudgeArray(string|int $parentId, string|int $childId): bool
    {
        $ret = false;
        $judgeProccess = function ($childData) use ($childId) {
            if (isset($childData[$childId])) {
                return true;
            } else {
                return false;
            }
        };

        $ret = $this->CommonProcessArray($parentId, $childId, $judgeProccess);

        return $ret;
    }

    /**
     * ReadArray
     *
     * セッション2次元配列読み込み
     *
     * @param string|int $parentId
     * @param string|int $childId
     *
     * @return mixed
     */
    public function ReadArray(string|int $parentId, string|int $childId): mixed
    {
        $ret = null;

        $readProccess = function ($childElm) use ($childId) {
            return $childElm[$childId];
        };

        $ret = $this->CommonProcessArray($parentId, $childId, $readProccess);

        return $ret;
    }

    /**
     * DeleteArray
     *
     * セッション2次元配列の特定の要素の削除
     *
     * @param string|int $parentId
     * @param string|int $childId
     *
     * @return mixed
     */
    public function DeleteArray(string|int $parentId, string|int $childId): mixed
    {
        $deleteProccess = function ($childData) use ($parentId, $childId) {
            unset($childData[$childId]);
            $this->Write($parentId, $childData);
        };

        $ret = $this->CommonProcessArray($parentId, $childId, $deleteProccess);

        return $ret;
    }
}
