<?php

    class Option
    {
        private string $parentClass, $childClass;

        public function __construct(private array $option = [])
        {
            if (empty($option)) {
            }
        }

        public function setType(string $type): void
        {
            switch ($type) {
                case 'list':
                    $this->parentClass = 'ul';
                case 'numberList':
                $this->parentClass = 'ol';
                $this->childClass = 'li';
                    break;
                case 'select':
                    $this->parentClass = 'select';
                    $this->childClass = 'option';
            }
        }

        public function getOption(string $key)
        {
            $result = null;
            if (searchData($key, $this->option)) {
                $result = $this->option[$key];
            }
            return $result;
        }

        public function addOption(string $val)
        {
            $this->option[] = $val;
        }

        public function deleteOption(string $val)
        {
            if (searchData($val, $this->option)) {
            }
        }

        public function outputChildOption(bool $outputFlg = false)
        {
            $resultValue = "";
            $childOption = new HTMLClass();
            foreach ($this->option as $value) {
                $childOption->setTag($this->childClass, $value);
                $resultValue .= $childOption->execTag($outputFlg);
            }

            return $resultValue;
        }

        public function outputOption(bool $outputFlg = false)
        {

            $option = new HTMLClass();
            $option->setTag($this->parentClass, $this->outputChildOption());
            $option->execTag($outputFlg);
        }

    }