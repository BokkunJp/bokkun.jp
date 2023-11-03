<?php
$session = new public\Session();

class Post {
    use PublicTrait;
    private $key;
    private array $data;

    public function __construct(array $postData)
    {
        if (isset($postData)) {
            foreach ($postData as $key => $value) {
                $this->key = $key;
                $this->data[$key] = $value;
            }
        }
    }

    public function add($data, int $index = -1)
    {
        if (is_array($data) || $index !== -1) {
            $this->data[$index] = $data[$index];
        } else {
            $this->data[] = $data;
        }
    }
}


$post = new Post([1, 2, 3]);
$post->add([5 => 'test'], 5);
$post->add('test');

?>
<form method='POST'>
    <input type='text' name='data' />
    <button type='button' class='jsSend'>送信(JS)</button>
    <button class='send'>送信(PHP)</button>
    <p><output class='jsForm'></output></p>
    <p><output><?= $session->OnlyView('output') ?></output></p>

</form>