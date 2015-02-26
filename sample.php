<?php
if (isset($_POST['uri'])) {
    $uri = e($_POST['uri']);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="uft-8">
<title>sample</title>
<style type="text/css">
  #uri {
    width: 400px;
  }
</style>
</head>
<body>
    <form method="post">
    URI:<input id="uri" name="uri" type="text" value="<?php echo $uri; ?>">
        <input type="submit" value="get pics!!">
    </form>
<?php if (strlen($uri) !== 0): ?>
<hr>
<?php
if (! isUrl($uri)) {
    echo 'URLを正しく入力してください';
    exit;
}
$query = '';
while(true) {
    $res = file_get_contents($uri.$query);
    $doc = new DOMDocument();
    $doc->loadHtml($res);
    $xml = simplexml_import_dom($doc);
    $imgs = $xml->xpath("//img[@class='MTMItemThumb']");
    //var_dump($imgs);
    foreach ($imgs as $img) {
        echo '<img src="'.$img['src'].'">'.PHP_EOL;
    }

    $page = array_shift($xml->xpath("//div[@class='MdPagination03']"));
    $currentPage = $page->strong[0];
    $lastPage = $page->a[count($page->a)-1];
    if ($currentPage + 1 > $lastPage) {
        break;
    }
    $query = "?page=".($currentPage[0]+1);
    sleep(1);
}
?>
<?php endif; ?>
</body>
</html>
<?php

function e($value) {
    return htmlspecialchars($value, ENT_QUOTES);
}


function isUrl($uri) {
    if (preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'(a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $uri)) return TRUE;

    return FALSE;
}
