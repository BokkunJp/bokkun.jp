<?php

$json_position = ['x' => 1, 'y' => 2, 'z' => 3];
$json_color = ['r' => 255, 'g' => 0, 'b' => 255, 'a' => 0];
$json = array_merge($json_position, $json_color);
$json = json_encode($json);
echo $json;         // JSONを返す
