<?php
$before = microtime(true);

for ($i=0 ; $i<100000 ; $i++) {
    serialize($list);
}

$after = microtime(true);
echo ($after-$before)/$i . " sec/serialize\n";
?>