<?php
$type = $_GET['type'];
    if ($type == 1) {
        /*
        //临时关闭
        $out['errno'] = "0";
        $out['url'] = 'http://img.wochacha.com/user_images/9/a/6859094064634_1386829009.jpg';
        $out['length'] = 100;
        JsonOut($out);
        return;
        */
        if (filecheck($type)) {
            move($type);
        } else {
            $out['errno'] = "4";
            JsonOut($out);
            return;
        }
    } elseif ($type <= 18) {
        move($type);
    } else {
        $out['errno'] = "5";
        JsonOut($out);
        return;
    }

function filecheck($type)
{
	switch ($type) {
		case "1":
			$barcode = $_GET['barcode'];
			$imgtype = $_GET['imgtype'];
			$time = $_GET['time'];
			$subtype = $_GET['subtype'];
			$tmp = md5($barcode);
			$first = substr($tmp, 0, 1);
			$second = substr($tmp, 1, 1);
			if (!empty($subtype)) {
				$filename = $barcode . "_" . $subtype . "_" . $time . "." . $imgtype;
			} else {
				$filename = $barcode . "_" . $time . "." . $imgtype;
			}
			if (!file_exists("user_images/$first/$second/$filename")) {
				return true;
			} else {
				return false;
			}
			break;
		default :
			$out['errno'] = "5";
			JsonOut($out);
			return;
	}
}

function JsonOut($arr)
{
	header("Content-type: application/json");
	$arr = ArrFormat($arr);
	$json = json_encode($arr);
	echo $json;
}

function ArrFormat($arr)
{
	if (is_array($arr)) {
		foreach ($arr as $k => $v) {
			$arr[$k] = ArrFormat($arr[$k]);
		}
	} else {
		$arr = (string)$arr;
	}
	return $arr;
}

function move($type)
{
    switch ($type) {
        case "1":
            $barcode = $_GET['barcode'];
            $imgtype = $_GET['imgtype'];
            $time = $_GET['time'];
            $subtype = $_GET['subtype'];
            $tmp = md5($barcode);
            $subname = "user_images";
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            if (!empty($subtype)) {
                $filename = $barcode . "_" . $subtype . "_" . $time . "." . $imgtype;
            } else {
                $filename = $barcode . "_" . $time . "." . $imgtype;
            }
            break;
        case "2":
            $urid = $_GET['urid'];
            $imgtype = $_GET['imgtype'];
            $tmp = md5($urid);
            $subname = "user_avatar";
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $filename = $urid . "." . $imgtype;
            break;
        case "3":
            $urid = $_GET['urid'];
            $imgtype = $_GET['imgtype'];
            $tmp = md5($urid);
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $subname = "qr_images";
            $filename = $urid . "_" . time() . "." . $imgtype;
            break;
        case "6":
            $urid = $_GET['urid'];
            $imgtype = $_GET['imgtype'];
            $tmp = md5($urid);
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $subname = "broke_images";
            $md5 = $_GET['md5'];
            $filename = $urid . "_" . time() . $md5 . "." . $imgtype;
            break;
        case "7":
            $urid = $_GET['urid'];
            $imgtype = $_GET['imgtype'];
            $tmp = md5($urid);
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $subname = "card_images";
            $md5 = $_GET['md5'];
            $filename = $urid . "_" . time() . $md5 . "." . $imgtype;
            break;
        case "8":
            $urid = $_GET['urid'];
            $imgtype = $_GET['imgtype'];
            $tmp = md5($urid);
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $subname = "broke_images";
            $filename2 = $_GET['filename'];
            $filename = $filename2 . "." . $imgtype;
            break;
        case "9":
            $udid = $_GET['udid'];
            $imgtype = $_GET['imgtype'];
            $os = $_GET['os'];
            $time = $_GET['time'];
            $tmp = md5($udid);
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $subname = "fruit_images";
            $filename = $udid . "_" . $time . "_" . $os . "." . $imgtype;
            break;
        case "10":
            $udid = $_GET['udid'];
            $imgtype = $_GET['imgtype'];
            $os = $_GET['os'];
            $time = $_GET['time'];
            $tmp = md5($udid);
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $subname = "service_images";
            $filename = $udid . "_" . $time . "_" . $os . "." . $imgtype;
            break;
        case "11":
            $urid = $_GET['urid'];
            $imgtype = $_GET['imgtype'];
            $tmp = md5($urid);
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $subname = "fake_images";
            $md5 = $_GET['md5'];
            $filename = $urid . "_" . time() . $md5 . "." . $imgtype;
            break;
        case "12":
            $udid = $_GET['udid'];
            $imgtype = $_GET['imgtype'];
            $os = $_GET['os'];
            $time = $_GET['time'];
            $tmp = md5($udid);
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $subname = "bill_images";
            $filename = $udid . "_" . $time . "_" . $os . "." . $imgtype;
            break;
        case "13":
            $udid = $_GET['udid'];
            $imgtype = $_GET['imgtype'];
            $tmp = md5($udid);
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $subname = "order_complain_images";    // 商城 - 订单申诉
            $md5 = $_GET['md5'];
            $filename = $udid . "_" . time() . $md5 . "." . $imgtype;
            break;
        case "14":
            $udid = $_GET['udid'];
            $imgtype = $_GET['imgtype'];
            $tmp = md5($udid);
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $subname = "order_return_images";    // 商城 - 退货
            $md5 = $_GET['md5'];
            $filename = $udid . "_" . time() . $md5 . "." . $imgtype;
            break;
        case "15":
            $udid = $_GET['udid'];
            $imgtype = $_GET['imgtype'];
            $tmp = md5($udid);
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $subname = "return_order_images";    // 商城 - 退货单
            $md5 = $_GET['md5'];
            $filename = $udid . "_" . time() . $md5 . "." . $imgtype;
            break;
        case "16":
            $udid = $_GET['udid'];
            $imgtype = $_GET['imgtype'];
            $tmp = md5($udid);
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $subname = "return_comment_images";    // 商城 - 退款详情留言图片
            $md5 = $_GET['md5'];
            $filename = $udid . "_" . time() . $md5 . "." . $imgtype;
            break;
        case "17":
            $udid = $_GET['udid'];
            $imgtype = $_GET['imgtype'];
            $os = $_GET['os'];
            $tmp = md5($udid);
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $subname = "user_feedback_images";    // 用户反馈、回复图片
            $md5 = $_GET['md5'];
            $filename = $udid . "_" . time() . $md5 . "_" . $os . "." . $imgtype;
            break;
        case "18":
            $sign = $_GET['sign'];
            $params = $_GET;
            $imgtype = $_GET['imgtype'];
            $md5 = $_GET['md5'];
            $tmp = md5($md5);
            $first = substr($tmp, 0, 1);
            $second = substr($tmp, 1, 1);
            $subname = "bkdemo_uploadresources";    // bkdemo后台上传资源图片
            $filename = time().'_'.$md5.'.'.$imgtype;
            $signresult = getSignature($params,'wochacha147852');
            if($signresult != $sign){//签名不过的直接退出
                $out['errno'] = '18';
                JsonOut($out);
                exit;
            }
            break;
        default :
            $out['errno'] = "5";
            JsonOut($out);
            return;
    }
    $image = file_get_contents('php://input') ? file_get_contents('php://input') : gzuncompress($GLOBALS ['HTTP_RAW_POST_DATA']);

    if ($image) {
//        $image = substr($image, 128);//TODO??
        $targetFolder = $_SERVER['DOCUMENT_ROOT'] . '/' .$subname . "/$first/$second";
        if (!is_dir($targetFolder)) {    //建立存放路径
            $dirs = explode('/', $targetFolder);
            $num = count($dirs);
            for ($i = 1, $dir = $dirs[0]; $i < $num; $i++) {
                $dir = $dir . '/' . $dirs[$i];
                if (!is_dir($dir)) {
                    mkdir($dir);
                    chmod($dir, 0777);
                }
            }
        }
        if (file_exists($subname . "/$first/$second/$filename")) {
            unlink($subname . "/$first/$second/$filename");
        }
        $file = fopen($subname . "/$first/$second/$filename", "w+");
        fwrite($file, $image);
        fclose($file);
        $len = abs(filesize($subname . "/$first/$second/$filename"));
        $out['errno'] = "0";
        $out['url'] = $_SERVER['HTTP_HOST'] . "/" . $subname . "/$first/$second/$filename";
        $out['length'] = $len;
        JsonOut($out);
        return;
    } else {
        $out['errno'] = "255";
        JsonOut($out);
        return;
    }
}

/**
 * 签名生成算法
 * @param  array  $params API调用的请求参数集合的关联数组，不包含sign参数
 * @param  string $secret 签名的密钥即获取access token时返回的session secret
 * @return string 返回参数签名值
 * @author luochao
 */
function getSignature($params, $secret)
{
    unset($params['sign']);
    $str = '';  //待签名字符串
    //先将参数以其参数名的字典序升序进行排序
    ksort($params);
    //遍历排序后的参数数组中的每一个key/value对
    foreach ($params as $k => $v) {
        //为key/value对生成一个key=value格式的字符串，并拼接到待签名字符串后面
        $str .= "$k=$v";
    }
    //将签名密钥拼接到签名字符串最后面
    $str .= $secret;
    //通过md5算法为签名字符串生成一个md5签名，该签名就是我们要追加的sign参数值
    return md5($str);
}

?>
