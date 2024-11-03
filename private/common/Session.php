<?php

namespace Private\Important;

// セッションクラス (管理側)
class Session extends \Common\Important\Session
{
    use \SessionTrait;

    public function __construct(?string $sessionName = null)
    {
        $this->start();

        $this->setType('private');
        $this->setSessionName($sessionName);

        parent::__construct($sessionName);
    }

    /**
     * judgeArray
     *
     * セッション2次元配列判定
     *
     * @param string|int $parentId
     * @param string|int $childId
     *
     * @return bool
     */
    public function judgeArray(string|int $parentId, string|int $childId): bool
    {
        $ret = false;
        $judgeProccess = function ($childData) use ($childId) {
            if (isset($childData[$childId])) {
                return true;
            } else {
                return false;
            }
        };

        $ret = $this->commonProcessArray($parentId, $childId, $judgeProccess);

        return $ret;
    }

    /**
     * readArray
     *
     * セッション2次元配列読み込み
     *
     * @param string|int $parentId
     * @param string|int $childId
     *
     * @return mixed
     */
    public function readArray(string|int $parentId, string|int $childId): mixed
    {
        $ret = null;

        $readProccess = function ($childElm) use ($childId) {
            $returnData = false;
            if (isset($childElm[$childId])) {
                $returnData = $childElm[$childId];
            }
            return $returnData;
        };

        $ret = $this->commonProcessArray($parentId, $childId, $readProccess);

        return $ret;
    }

    /**
     * deleteArray
     *
     * セッション2次元配列の特定の要素の削除
     *
     * @param string|int $parentId
     * @param string|int $childId
     *
     * @return mixed
     */
    public function deleteArray(string|int $parentId, string|int $childId): mixed
    {
        $deleteProccess = function ($childData) use ($parentId, $childId) {
            unset($childData[$childId]);
            $this->write($parentId, $childData);
        };

        $ret = $this->commonProcessArray($parentId, $childId, $deleteProccess);

        return $ret;
    }

    /**
     * switchChild
     * 
     * 子要素のデータを取得
     *
     * @param string $childName
     * @return void
     */
    public function switchChild(string $childName)
    {
        $childData = $this->read($childName);

        if (!$childData) {
            return false;
        }

        $this->delete();
        $this->write($childName, $childData);
        $this->setSessionName($childName);
    }
}
