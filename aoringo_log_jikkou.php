<?PHP
/*
文字列を読み込んで下処理作業をする。
*/
error_reporting(E_ALL ^ E_NOTICE);
$brbrbr =array("\r"  =>"\n","\r\n"  =>"\n",'<br>'  =>"\n",'<br />'=>"\n",'<br/>' =>"\n",'</br>' =>"\n",'：'    =>':','*topic' =>'topic','%' =>'％');
$tagmukouka = array('<' =>"＜",'>' =>"＞",'topic :' =>"(TOPIC)",);
$text = strtr($_POST['year'], $brbrbr);
$text = strtr($text, $tagmukouka);
$text = strip_tags($text);
$text = htmlspecialchars($text, ENT_QUOTES);
/*
単語を変更するstrtにより問答無用。
*/
$tangohenhen = explode(",", $_POST['kaimei']);
$tangohenhen_count = count($tangohenhen);
for($i = 0;$i < $tangohenhen_count;$i++){
    $tangokaikai[$tangohenhen[$i]] = $tangohenhen[++$i];
    }
$text = strtr($text,$tangokaikai);
// 時間除去、ユーザー発言以外の入退出、アナウンスなどを除去
$text = preg_replace("/^[0-9|:|\s]+/m", "", $text);
//$text = preg_replace("/^.*?(TOPIC).*?:(.*)/m", "($1)$2", $text);
$text = preg_replace("/^[^\[|\(|＜].*/m", "", $text);
// 行頭改行と文章全体の終端改行を除去
$text = preg_replace("/^\n/m", "", $text);
$text = preg_replace("/$\n/", "", $text);
// 発言者、囲い、発言をそれぞれ抜き取り整理
$text = preg_replace("/^(＜|\[|\()(.*?)(\]|\)|＞)\s*(.*)/m", "$2： $4", $text);
$text = preg_replace("/(https?.*?)([[:space:]　\n\r])/", "<a href=\"$1\" target=\"_blank\">>$1</a>$2", $text);
if (substr_count($text, "\n") < 2){
    echo "行数、文字数が少なすぎるみたいです。";
    return;
}
/*
単語を色変えするstrtにより問答無用。
*/
$tangocol = explode(",", $_POST['tokuteitango_color']);
$tangocol_count = count($tangocol);
for($i = 0;$i < $tangocol_count;$i++){
    $tagtango[$tangocol[$i]] = '<span style="color:' . $tangocol[++$i] . ';">' . $tangocol[$i-1] . "</span>";
    }
$text = strtr($text,$tagtango);
/*
名前処理部分
*/
$values = explode("\n", $text);
$count = count($values);
// ：ごとに分割して収納。名前と発言を分けている。収納先は$values[$i][$j]
for ($i = 0; $i < $count; $i++) {
    $values[$i] = explode("：", $values[$i]);
}
$html="<dl>";
for ($i = 0; $i < $count; $i++){
    for ($j = 0; $j < count($values[$j]); $j++){
        if($j==0){
            if($values[$i][0] != $values[$i-1][0]){
            $values[$i][0] = strip_tags($values[$i][0]);
            $html .='<dt style="<!'.$values[$i][0].'namae><!'.$values[$i][0].'dasiuti><!'.$values[$i][0].'dasiwaku>">'.'<!'.$values[$i][0].'nameimg>'.$values[$i][0]."</dt>";
            }
        }
        else {
            if($values[$i][0] == $values[$i-1][0] and $values[$i][0] == $values[$i+1][0]){
                //同一者の発言に挟まれている部分
                    $html .="<P>".$values[$i][$j] . "</P>";
            }elseif($values[$i][0] == $values[$i-1][0] and $values[$i][0] != $values[$i+1][0]){
                //前の発言者と同じ、かつ次の発言者が違う人
                $html .="<P>".$values[$i][$j] . "</P></dd>";
            }elseif($values[$i][0] != $values[$i-1][0] and $values[$i][0] == $values[$i+1][0]){
                //前の発言者が違う、かつ次の発言者が同じ
                $html .='<dd style="<!'.$values[$i][0].'hatugen><!'.$values[$i][0].'dasiuti><!'.$values[$i][0].'dasiwaku>">'."\n".'<P>'.$values[$i][$j] . "</P>";
            }else{
                //単一発言
                $html .='<dd style="<!'.$values[$i][0].'hatugen><!'.$values[$i][0].'dasiuti><!'.$values[$i][0].'dasiwaku>">'.$values[$i][$j] . "</dd>";
            }
        }
    }
}
$html .="</dl>";
// 画像を設定する
$namaecol = explode(",", $_POST['name_img']);
$namaecol_count = count($namaecol);
for($i = 0;$i < $namaecol_count;$i++){
    $namaecolcol['<!'.$namaecol[$i].'nameimg>'] = '<img src="'.$namaecol[++$i].'" alt="*" width="30" height="30"> ';
    }
/*
名前色と発言カラーを変更する。
*/
$namaecol = explode(",", $_POST['name_color']);
$namaecol_count = count($namaecol);
for($i = 0;$i < $namaecol_count;$i++){
    $namaecolcol['<!'.$namaecol[$i].'namae>'] = "color:".$namaecol[++$i].';';
    }
$hatugencol = explode(",", $_POST['hatugen_color']);
$hatugencol_count = count($hatugencol);
for($i = 0;$i < $hatugencol_count;$i++){
    $namaecolcol['<!'.$hatugencol[$i].'hatugen>'] = "color:".$hatugencol[++$i].';';
}
$hatugencol = explode(",", $_POST['hukidasi_color']);
$hatugencol_count = count($hatugencol);
for($i = 0;$i < $hatugencol_count;$i++){
    $namaecolcol['<!'.$hatugencol[$i].'dasiuti>'] = "background-color:".$hatugencol[++$i].';';
    $namaecolcol['<!'.$hatugencol[$i-1].'dasiwaku>'] = "border-color:".$hatugencol[++$i].';';
}
    $html = strtr($html,$namaecolcol);
    $html = preg_replace("/<!.*?>/", "", $html);
    $html = preg_replace("/\sstyle=\"\"/", "", $html);
    $html = str_replace("%", '&nbsp;', $html);
    $html = str_replace("&nbsp;&nbsp;", '　', $html);
        $html = str_replace("％", '%', $html);
    $html = "[logloghajime]\n" . $html . "[loglogowari]";
echo $html;

?>