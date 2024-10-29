<?php
/*
  Plugin Name: aoringo LOG maker
  Description: ＩＲＣなどのチャットログの見た目をキレイ？　に整えます。
  Version: 0.1.3
  Author: aoringo
  Author URI: http://cre.jp/honyomusi/
 */

add_action('admin_init', 'loglog_admin_init');

//記事投稿画面にウインドウを表示する
function loglog_admin_init() {
  add_meta_box('my_logmaker_post_box', "aoringo LOG maker", 'loglog_run_box', 'post');
  add_meta_box('my_logmaker_post_box', "aoringo LOG maker", 'loglog_run_box', 'page');
}

//投稿画面に埋め込むjqueryコードとウインドウ
function loglog_run_box() {
  ?>
  <script type="text/javascript">
    jQuery(function (){
      jQuery( "#loglogcopy" ).click( function () {
        var textvalu = jQuery( "#loglogmae" ).val();
        jQuery.post("<?php echo plugins_url() . "/" . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)) . "aoringo_log_jikkou.php"; ?>",
        { year                  : textvalu,
          kaimei                : "<?php echo get_option(loglog_kaimei) ?>",
          name_color            : "<?php echo get_option(loglog_name_color) ?>",
          name_img              : "<?php echo get_option(loglog_name_img) ?>",
          hatugen_color         : "<?php echo get_option(loglog_hatugen_color) ?>",
          tokuteitango_color    : "<?php echo get_option(loglog_tokuteitango_color) ?>",
          hukidasi_color    	: "<?php echo get_option(loglog_hukidasi_color) ?>"
        },function( data) {
          jQuery( "#loglogato" ) . html( data );
        }
        ,'html'
      );
        return false;
      } );
      jQuery('#loglogato').live('mouseup', function() {
        jQuery(this).select();
      });
      jQuery('#loglogmae').live('mouseup', function() {
        jQuery(this).select();
      });
    });
  </script>
  <textarea id="loglogmae" name="loglogmae" rows="3" cols="80" tabindex="18" maxlength="50000" style="width:99%;"></textarea>
  <p><input type="button" name="loglogcopy" id="loglogcopy" class="button" value="変換" />
    変換されると下にHTMLタグが出力されるので、<font color="red">HTMLエディター</font>に貼り付けてください。</p>
  <textarea id="loglogato" name="loglogato" rows="3" cols="80" tabindex="18" readonly="readonly" style="width:99%; background-color:#F0F8FF;">ここにコードが出ます。ワンクリックで自動で全選択する系のアレです。</textarea>
  <?php
}

// ログ成形用divコード変換部分
function aoringologhajime_func($atts) {
  if (is_single() or is_page()) {
    return "<div class= \"loglogaoringo\">\n";
  }
}

function aoringologowari_func($atts) {
  if (is_single() or is_page()) {
    return vrerf_logmaker() . "</div><!-- loglogaoringo-->\n";
  }
}

// ショートコードを登録する
add_shortcode('logloghajime', 'aoringologhajime_func');
add_shortcode('loglogowari', 'aoringologowari_func');

// ダッシュボード設定へのリンクを追加
function aoringologlog_option_menu() {
  add_submenu_page('options-general.php', 'aoringo LOG makerの設定', 'aoringo LOG makerの設定', 8, __FILE__, 'aoringologlog_admin_page');
}

add_action('admin_menu', 'aoringologlog_option_menu');

//***************************************************************** 以下設定画面用コード ****************************************************//
// 設定画面構成コード
function aoringologlog_admin_page() {
  //設定保存用処理、改行や今後の処理に関わりそうな文字を整理する。タグなども除去している。
  $jokyo = array('"' => "", "'" => "", "\n" => "", "\r" => "", "：" => ":",);
  if ($_POST['posted'] == 'Y') {
    // 入力されたデータに悪意あるコードがないかどうかを調べて保存する、改行なども反映する。
    update_option('loglog_def_style', $_POST['loglog_def_style']);
    if ($_POST['loglog_def_mojiiro'] != "" and 1 == substr_count(rtrim($_POST['loglog_def_mojiiro'], ","), ",")) {
      update_option('loglog_def_mojiiro', rtrim(strtr(strip_tags(stripslashes($_POST['loglog_def_mojiiro'])), $jokyo), ","));
    } else {
      update_option('loglog_def_mojiiro', "#ff69b4,#2f4f4f");
    }
    if ($_POST['loglog_def_hukidasi'] != "" and 1 == substr_count(rtrim($_POST['loglog_def_hukidasi'], ","), ",")) {
      update_option('loglog_def_hukidasi', rtrim(strtr(strip_tags(stripslashes($_POST['loglog_def_hukidasi'])), $jokyo), ","));
    } else {
      update_option('loglog_def_hukidasi', "#f0f8ff,#b0c4de");
    }
    update_option('loglog_tokuteitango_color', rtrim(strtr(strip_tags(stripslashes($_POST['loglog_tokuteitango_color'])), $jokyo), ","));
    update_option('loglog_kaimei', rtrim(strtr(strip_tags(stripslashes($_POST['loglog_kaimei'])), $jokyo), ","));
    update_option('loglog_name_color', rtrim(strtr(strip_tags(stripslashes($_POST['loglog_name_color'])), $jokyo), ","));
    update_option('loglog_name_img', rtrim(strtr(strip_tags(stripslashes($_POST['loglog_name_img'])), $jokyo), ","));
    update_option('loglog_hatugen_color', rtrim(strtr(strip_tags(stripslashes($_POST['loglog_hatugen_color'])), $jokyo), ","));
    update_option('loglog_tokuteitango_color', rtrim(strtr(strip_tags(stripslashes($_POST['loglog_tokuteitango_color'])), $jokyo), ","));
    update_option('loglog_hukidasi_color', rtrim(strtr(strip_tags(stripslashes($_POST['loglog_hukidasi_color'])), $jokyo), ","));
    //if( is_numeric( $_POST[ 'loglog_table_pa_sen'  ] ) >= 100 ) {update_option('loglog_table_pa_sen', strip_tags(stripslashes($_POST['loglog_table_pa_sen'])));}
  }
  ?>
  <script type="text/javascript" src="<?php echo plugins_url() . "/" . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)) . "farbtastic/farbtastic.js"; ?>"></script>
  <link rel="stylesheet" href="<?php echo plugins_url() . "/" . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)) . "farbtastic/farbtastic.css"; ?>" type="text/css" />
  <script type="text/javascript">
    // color picker の作成
    jQuery(document).ready(function() {
      jQuery('#picker').farbtastic('#color');
    });
  </script>
  <?php
// htmlで記述するため一旦phpから外れてend文では隠すようにしている。

  if ($_POST['posted'] == 'Y') :
    ?><div class="updated"><p><strong>設定を保存した気がします！</strong></p></div><?php endif; ?>

  <?php if ($_POST['posted'] == 'Y') : ?>
    <!-- order = <?php echo $_POST['order']; ?>, striped = <?php echo stripslashes($_POST['order']); ?>, saved = <?php get_option('fjscp_order'); ?> -->
  <?php endif; ?>
  <!-- おそらく設定画面用のクラスなのだろうこれは -->
  <div class="wrap">
    <h2>Aoringo LOG makerの設定</h2>
    <form id="mementomori">
      <input type="text" id="color" name="color" value="色探しの手助けにどうぞ" style="width:16em;"/><br>
      <div id="picker"></div>
    </form>

    <form method="post" action="<?php
  echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']);
  // フォームタグはmethodがpostの場合は本文としてデータを送信する。actionにアドレスを入れるとそのアドレスのフォームがリロードされたときなどに入力された状態で出力される。
  ?>">
      <input type="hidden" name="posted" value="Y">
      <p>08:33 (aoringo) いまのところライムチャットのデフォルトフォーマットに対応中</p>
      <p>08:34 (aoringo) 要望、報告などは<A Href="http://cre.jp/honyomusi/" Target="_blank">http://cre.jp/honyomusi/</A>までお気軽にどうぞ</p>
      <p class="submit"><input type="submit" name="Submit" class="button-primary" value="変更を保存" /></p>
      <table class="form-table">
        <tr valign="top">
          <th scope="row"><label for="loglog_def_style">デフォルトスタイルを選択</label>
          <td>
            <input type="radio" name="loglog_def_style" id="loglog_def_style" value="1" <?php if (get_option('loglog_def_style') == 1) {
    echo 'checked';
  }; ?>><img src="<?php echo plugins_url() . "/" . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)) . "image/style1.png"; ?>" width="150" height="80">　
            <input type="radio" name="loglog_def_style" id="loglog_def_style" value="2" <?php if (get_option('loglog_def_style') == 2) {
    echo 'checked';
  }; ?>><img src="<?php echo plugins_url() . "/" . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)) . "image/style2.png"; ?>" width="150" height="80">　
            <input type="radio" name="loglog_def_style" id="loglog_def_style" value="3" <?php if (get_option('loglog_def_style') == 3) {
    echo 'checked';
  }; ?>><img src="<?php echo plugins_url() . "/" . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)) . "image/style3.png"; ?>" width="150" height="80">
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="loglog_def_mojiiro" size="10" maxlength="10">デフォルトネーム、発言カラー</label></th>
          <td><input type="loglog_def_mojiiro" name="loglog_def_mojiiro" id="loglog_def_namaeiro" value="<?php echo get_option('loglog_def_mojiiro'); ?>" size="10" maxlength="20" class="regular-text code" />
            <br />デフォルトの名前色、発言色をカンマで区切ってください。</td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="loglog_def_mojiiro" size="10" maxlength="10">デフォルト吹き出し内側、枠カラー</label></th>
          <td><input type="loglog_def_hukidasi" name="loglog_def_hukidasi" id="loglog_def_hukidasi" value="<?php echo get_option('loglog_def_hukidasi'); ?>" size="10" maxlength="20" class="regular-text code" />
            <br />デフォルトの吹き出し色、枠色をカンマで区切ってください。</td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="loglog_kaimei">単語・名前を変更する</label><br />
            aoringo_chan　→　あおりんごちゃん</th>
          <td><textarea name="loglog_kaimei" id="loglog_kaimei" class="regular-text code" style="width:650px;" rows="2"><?php echo get_option('loglog_kaimei'); ?></textarea><br />
            変えたい単語や名前 , 変更後 , 変更前 , 変更後・・・とカンマで交互に記述してください<br />
            <font color = "red">※</font>名前は五文字くらいが最大です
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="loglog_name_color">名前に色をつける</label><br />
            <font color = "blue">あおりんごちゃん</font>：私ですけどです</th>
          <td><textarea name="loglog_name_color" id="loglog_name_color" class="regular-text code" style="width:650px;" rows="2"><?php echo get_option('loglog_name_color'); ?></textarea><br />
            色をつけたい人の名前 , 色 , 名前 , 色・・・とカンマで交互に記述してください。<br />
            <font color = "red">※</font>色変更は改名後の名前で変更されます
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="loglog_name_img">名前に画像を設定する</label><br />
            <img src="<?php echo plugins_url() . "/" . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)) . "image/ringoname.png"; ?>" width="30" height="30">：私ですけどです</th>
          <td><textarea name="loglog_name_img" id="loglog_name_img" class="regular-text code" style="width:650px;" rows="2"><?php echo get_option('loglog_name_img'); ?></textarea><br />
            画像をつけたい人の名前 , 画像アドレス , 名前 , アドレス・・・とカンマで交互に記述してください。<br />
            <font color = "red">※</font>画像、名前の順番に表示されるスタイルと、名前だけが表示されるスタイルがあります
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="loglog_hatugen_color">発言に色をつける</label><br />
            ( 0M0)：<font color = "red">ｹﾝｼﾞｬｷｨ―――――!!!</font></th>
          <td><textarea name="loglog_hatugen_color" id="loglog_hatugen_color" class="regular-text code" style="width:650px;" rows="2"><?php echo get_option('loglog_hatugen_color'); ?></textarea><br />
            発言に色をつける人の名前 , 色 , 名前 , 色・・・とカンマで交互に記述してください。<br />
            <font color = "red">※</font>色変更は改名後の名前で変更されます
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="loglog_tokuteitango_color">発言内の特定の単語を色づけする</label><br />
            ( 0M0)：<font color = "red">ｹﾝｼﾞｬ<font color = "blue">ｷｨ――――</font>―!!!</font></th>
          <td><textarea name="loglog_tokuteitango_color" id="loglog_tokuteitango_color" class="regular-text code" style="width:650px;" rows="2"><?php echo get_option('loglog_tokuteitango_color'); ?></textarea><br />
            色をつけたい単語 , 色 , 単語 , 色・・・とカンマで交互に記述してください。<br />
            <font color = "red">※</font>色変更は変更後の単語で変更されます
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><label for="loglog_hukidasi_color">吹き出しの色を変更する</label><br />
          </th>
          <td><textarea name="loglog_hukidasi_color" id="loglog_hukidasi_color" class="regular-text code" style="width:650px;" rows="2"><?php echo get_option('loglog_hukidasi_color'); ?></textarea><br />
            名前、吹き出し内の色色、枠色と指定してください。<br />
            <font color = "red">※</font>ここだけ三つずつ指定します
          </td>
        </tr>
      </table>
      <p class="submit"><input type="submit" name="Submit" class="button-primary" value="変更を保存" /></p>
    </form>
  </div>
  <style type="text/css">
    <!--
    .seteitable{margin:0;padding:0;}
    .seteitable * {margin:0;padding:0;border:1px;overflow:visible;}
    .seteitable p {padding:2px;}
    .seteitable span {text-shadow: 1px 1px 1px #ffffff,-1px -1px 1px #ffffff;}
    .seteitable table{background-color:#191970;width:90%;}
    .seteitable th{background-color:#5f9ea0;text-align:center;color:white;width:20%;}
    .seteitable td{background-color:#ffffff;text-align:center;vertical-align: top;}
    .seteitable img{width: 2em;height: 2em;vertical-align: middle;}
    -->
  </style>
  <div class="seteitable">
    <table>
      <tr>
        <th>単語</th>
        <th>名前色</th>
        <th>発言色</th>
        <th>発言内、単語色</th>
        <th>吹き出し内、吹き出し枠色</th>
      </tr>
      <tr>
        <td><?php
  // それぞれの設定を出力する
  // 単語変更
  $tantangogo = explode(",", get_option('loglog_kaimei'));
  $tan_count = count($tantangogo);
  $syuturyoku = "";
  for ($i = 0; $i < $tan_count; $i++) {
    $syuturyoku .= "<p>" . $tantangogo[$i] . " ⇒ " . $tantangogo[++$i] . "</p>";
  }
  echo $syuturyoku;
  ?></td>

        <td><?php
        // 名前色変更
        $tantangogo = explode(",", get_option('loglog_name_color'));
        $tan_count = count($tantangogo);
        $syuturyoku = "";
        for ($i = 0; $i < $tan_count; $i++) {
          $syuturyoku .= '<p>' . $tantangogo[$i] . ' ⇒ <span style="background-color:' . $tantangogo[++$i] . ';">' . $tantangogo[$i] . "</span></p>\n";
        }
        echo $syuturyoku;
  ?></td>


        <td><?php
        // 発言色変更
        $tantangogo = explode(",", get_option('loglog_hatugen_color'));
        $tan_count = count($tantangogo);
        $syuturyoku = "";
        for ($i = 0; $i < $tan_count; $i++) {
          $syuturyoku .= '<p>' . $tantangogo[$i] . ' ⇒ <span style="background-color:' . $tantangogo[++$i] . ';">' . $tantangogo[$i] . "</span></p>\n";
        }
        echo $syuturyoku;
  ?></td>

        <td><?php
        // 発言内、単語色変更
        $tantangogo = explode(",", get_option('loglog_tokuteitango_color'));
        $tan_count = count($tantangogo);
        $syuturyoku = "";
        for ($i = 0; $i < $tan_count; $i++) {
          $syuturyoku .= '<p>' . $tantangogo[$i] . ' ⇒ <span style="background-color:' . $tantangogo[++$i] . ';">' . $tantangogo[$i] . "</span></p>\n";
        }
        echo $syuturyoku;
        ?></td>

        <td><?php
        // 吹き出し内、枠変更
        $tantangogo = explode(",", get_option('loglog_hukidasi_color'));
        $tan_count = count($tantangogo);
        $syuturyoku = "";
        for ($i = 0; $i < $tan_count; $i++) {
          $syuturyoku .= '<p style="margin:5px 0;">' . $tantangogo[$i] . ' ⇒ <span style="background-color:' . $tantangogo[++$i] . ';border : 2px solid ' . $tantangogo[++$i] . ';">内' . $tantangogo[$i - 1] . '　枠' . $tantangogo[$i] . "</span></p>\n";
        }
        echo $syuturyoku;
  ?></td>
      </tr>
    </table>
  </div>
  <br />
  <!-- 二列目 -->
  <div class="seteitable">
    <table>
      <tr>
        <th>名前を画像化</th>
      </tr>
      <tr>
        <td><?php
        // 名前色変更
        $tantangogo = explode(",", get_option('loglog_name_img'));
        $tan_count = count($tantangogo);
        $syuturyoku = "";
        for ($i = 0; $i < $tan_count; $i++) {
          $syuturyoku .= '<p>' . $tantangogo[$i] . ' ⇒ <img src="' . $tantangogo[++$i] . '" width="30" height="30">　' . preg_replace("/.*\//", "", $tantangogo[$i]) . "</p>\n";
        }
        echo $syuturyoku;
  ?></td>
      </tr>
    </table>
  </div>

  <?php
}

function add_stylysheet_logmaler() {
  // 固定ページと投稿記事でのみスタイルシートを読み込み、さらに設定によってスタイルシートを変更する。
  if (is_single() or is_page()) {
    $mojiiro = explode(",", get_option(loglog_def_mojiiro));
    $wakuiro = explode(",", get_option(loglog_def_hukidasi));
    switch (get_option('loglog_def_style')) {
      case '1':
        echo "<link rel=\"stylesheet\" href=\"" . plugins_url() . "/" . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)) . "logstyle.css" . "\" type=\"text/css\">\n";
        ?>
        <style type="text/css">
          <!--
          div.loglogaoringo dt {color:<?php echo $mojiiro[0] ?>; border-color:<?php echo $wakuiro[1] ?>; background-color:<?php echo $wakuiro[0] ?>;}
          div.loglogaoringo dd {color:<?php echo $mojiiro[1] ?>; border-color:<?php echo $wakuiro[1] ?>; background-color:<?php echo $wakuiro[0] ?>;}
          -->
        </style>
        <?php
        break;

      case '2':
        echo "<link rel=\"stylesheet\" href=\"" . plugins_url() . "/" . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)) . "logstyle2.css" . "\" type=\"text/css\">\n";
        ?>
        <style type="text/css">
          <!--
          div.loglogaoringo dt {color:<?php echo $mojiiro[0] ?>;} div.loglogaoringo dd {color:<?php echo $mojiiro[1] ?>;}
          -->
        </style>
        <?php
        break;

      case '3':
        echo "<link rel=\"stylesheet\" href=\"" . plugins_url() . "/" . str_replace(basename(__FILE__), "", plugin_basename(__FILE__)) . "logstyle3.css" . "\" type=\"text/css\">\n";
        ?>
        <style type="text/css">
          <!--
          div.loglogaoringo dt {color:<?php echo $mojiiro[0] ?>; border-color:<?php echo $wakuiro[1] ?>; background-color:<?php echo $wakuiro[0] ?>;}
          div.loglogaoringo dd {color:<?php echo $mojiiro[1] ?>; border-color:<?php echo $wakuiro[1] ?>; background-color:<?php echo $wakuiro[0] ?>;}
          -->
        </style>
        <?php
        break;
    }
  }
}

function vrerf_logmaker() {
  return '<P style="width:180px; text-align: center; background-color:#fffafa; font-size:8px; float:left;">ぷろだくと　ばい　<A style="color:#228b22; font-size:8px; text-decoration:none;" Href="http://cre.jp/honyomusi/" Target="blank">あおりんごろぐめーかー</A></P>';
}

add_action('wp_head', 'add_stylysheet_logmaler');

function aoringologmaker_init_option() {
  //インストール時の初期設定
  if (!get_option('aoringolog_installed')) {
    update_option('loglog_def_style', '1');
    update_option('loglog_def_mojiiro', '#ff69b4,#2f4f4f');
    update_option('loglog_def_hukidasi', '#f0f8ff,#b0c4de');
    update_option('loglog_kaimei', 'aoringo_chan, あおりんごちゃん');
    update_option('loglog_name_color', 'あおりんごちゃん,#003399');
    update_option('loglog_name_img', 'あおりんごちゃん,http://hogehoge/hoge.jpg');
    update_option('loglog_hatugen_color', '( 0M0),red');
    update_option('loglog_tokuteitango_color', 'ｷｨ――――,#0000ff');
    update_option('loglog_hukidasi_color', 'まりこちゃん,#f0f8ff,#b0c4de');
    update_option('aoringolog_installed', 1);
  }
}

register_activation_hook(__FILE__, 'aoringologmaker_init_option')
?>