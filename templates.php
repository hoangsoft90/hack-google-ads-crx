<?php
/**
* item search result for google,...
* 
* @param mixed $data
*/
function item_search_result($data=array()){
    ob_start();
    extract($data);
    if(!isset($gprofile)) $gprofile='https://plus.google.com/115594655546267198237/';
    if(!$thumbnail) $thumbnail='https://lh3.googleusercontent.com/-JeHws7YVNz8/AAAAAAAAAAI/AAAAAAAAAAA/gJJ23aqxMak/s46-c-k-no/photo.jpg';
    if(!$url) $url='http://hoangweb.com';
    if(!$short_desc) $short_desc='Chuyên nhận thiết kế web, làm bài tập môn CNTT - đồ án tốt nghiệp. Liên hệ: 01663.930.250';
    ?>
    <li class="g"><!--m--><div class="rc" data-hveid="75"><span class="altcts"></span><h3 class="r"><a href="<?php ?>" data-href=""><?php echo $title?></a></h3><div class="s"><div><div class="thb th" style="height:44px;width:44px"><a href="<?php echo $gprofile?>" ><img height="44" id="apthumb0" src="<?php echo $thumbnail?>" width="44" border="0"></a></div></div><div style="margin-left:53px"><div class="f kv" style="white-space:nowrap"><cite class="vurls"><?php echo $url?></cite>‎<div class="action-menu ab_ctl"><a class="clickable-dropdown-arrow ab_button" href="#" id="am-b0" aria-label="Chi tiết về kết quả tìm kiếm" jsaction="ab.tdd;keydown:ab.hbke;keypress:ab.mskpe" aria-expanded="false" aria-haspopup="true" role="button" data-ved="0CE8Q7B0wAA"><span class="mn-dwn-arw"></span></a></div></div><div class="f"><div></div><a class="authorship_link" href="<?php echo $gprofile?>" >của Đoàn Hữu Từ</a> - <a class="authorship_link" href="https<?php echo $gprofile?>" ><span>trong 2.167 vòng kết nối trên Google+</span></a></div><span class="st"><?php echo $short_desc?></span></div><div style="clear:left"></div></div></div><!--n--></li>
    <?php
    $generatedoutput = ob_get_contents();
    ob_end_clean();
    return $generatedoutput;
}
?>