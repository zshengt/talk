<?php
function shtmlspecialchars( $string ) {
    if ( is_array( $string ) ) {
        foreach ( $string as $key => $val ) {
            $string[$key] = shtmlspecialchars( $val );
        }
    } else {
        $string = preg_replace( '/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
            str_replace( array( '&', '"', '<', '>' ), array( '&amp;', '&quot;', '&lt;', '&gt;' ), $string ) );
    }
    return $string;
}

function safe( $text ) {
    $text = nl2br(h( shtmlspecialchars( $text ) ));
    return  $text;
}

function parse_atname( $name ) {
    $uid      = D( 'User' )->where(array('username'=>$name[1]))->getField('uid');
    $userurl  = '<a href="' .U( 'user/index', array( 'uid'=>$uid ) ).'">'. $name[0] .'</a>';
    if ( $uid ) {
        return $userurl; 
    } else {
        return $name[0];
    }
}

function convert_uid( $uid, $size) {
    $uid  = abs(intval($uid));
    $uid  = sprintf( "%09d", $uid );
    $dir1 = substr($uid, 0, 3);
    $dir2 = substr($uid, 3, 2);
    $dir3 = substr($uid, 5, 2);
    $face_path =  'Public/upload/avatar/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/';
    if ( !is_dir( $face_path ) ) mk_dir( $face_path );

    $avatar =  $face_path . substr( $uid, -2 ).'_avatar_'.$size.'.jpg';
    return $avatar;
}
function str_strlen( $str ) {
    $i = 0;
    $count = 0;
    $len = strlen( $str );
    while ( $i < $len ) {
        $chr = ord( $str[$i] );
        $count++;
        $i++;
        if ( $i >= $len ) break;
        if ( $chr & 0x80 ) {
            $chr <<= 1;
            while ( $chr & 0x80 ) {
                $i++;
                $chr <<= 1;
            }
        }
    }
    return $count;
}
/**
 * 字符串截取，支持中文和其他编码
 *
 * @static
 * @access public
 * @param string  $str     需要转换的字符串
 * @param string  $start   开始位置
 * @param string  $length  截取长度
 * @param string  $charset 编码格式
 * @param string  $suffix  截断显示字符
 * @return string
 */
function msubstr( $str, $start=0, $length, $charset="utf-8", $suffix=false ) {
    if ( function_exists( "mb_substr" ) )
        $slice = mb_substr( $str, $start, $length, $charset );
    elseif ( function_exists( 'iconv_substr' ) ) {
        $slice = iconv_substr( $str, $start, $length, $charset );
        if ( false === $slice ) {
            $slice = '';
        }
    }else {
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all( $re[$charset], $str, $match );
        $slice = join( "", array_slice( $match[0], $start, $length ) );
    }
    return $suffix ? $slice.'...' : $slice;
}

//输出安全的html
function h( $text, $tags = null ) {
    $text   =   trim( $text );
    //完全过滤注释
    $text   =   preg_replace( '/<!--?.*-->/', '', $text );
    //完全过滤动态代码
    $text   =   preg_replace( '/<\?|\?'.'>/', '', $text );
    //完全过滤js
    $text   =   preg_replace( '/<script?.*\/script>/', '', $text );

    $text   =   str_replace( '[', '&#091;', $text );
    $text   =   str_replace( ']', '&#093;', $text );
    $text   =   str_replace( '|', '&#124;', $text );
    //过滤危险的属性，如：过滤on事件lang js
    while ( preg_match( '/(<[^><]+)( lang|on|action|background|codebase|dynsrc|lowsrc)[^><]+/i', $text, $mat ) ) {
        $text=str_replace( $mat[0], $mat[1], $text );
    }
    while ( preg_match( '/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i', $text, $mat ) ) {
        $text=str_replace( $mat[0], $mat[1].$mat[3], $text );
    }
    if ( empty( $tags ) ) {
        $tags = 'table|td|th|tr|i|b|u|strong|img|p|br|div|strong|em|ul|ol|li|dl|dd|dt|a';
    }
    //允许的HTML标签
    $text   =   preg_replace( '/<('.$tags.')( [^><\[\]]*)>/i', '[\1\2]', $text );
    $text = preg_replace( '/<\/('.$tags.')>/Ui', '[/\1]', $text );
    //过滤多余html
    $text   =   preg_replace( '/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml)[^><]*>/i', '', $text );
    //过滤合法的html标签
    while ( preg_match( '/<([a-z]+)[^><\[\]]*>[^><]*<\/\1>/i', $text, $mat ) ) {
        $text=str_replace( $mat[0], str_replace( '>', ']', str_replace( '<', '[', $mat[0] ) ), $text );
    }
    //转换引号
    while ( preg_match( '/(\[[^\[\]]*=\s*)(\"|\')([^\2=\[\]]+)\2([^\[\]]*\])/i', $text, $mat ) ) {
        $text=str_replace( $mat[0], $mat[1].'|'.$mat[3].'|'.$mat[4], $text );
    }
    //过滤错误的单个引号
    while ( preg_match( '/\[[^\[\]]*(\"|\')[^\[\]]*\]/i', $text, $mat ) ) {
        $text=str_replace( $mat[0], str_replace( $mat[1], '', $mat[0] ), $text );
    }
    //转换其它所有不合法的 < >
    $text   =   str_replace( '<', '&lt;', $text );
    $text   =   str_replace( '>', '&gt;', $text );
    $text   =   str_replace( '"', '&quot;', $text );
    //反转换
    $text   =   str_replace( '[', '<', $text );
    $text   =   str_replace( ']', '>', $text );
    $text   =   str_replace( '|', '"', $text );
    //过滤多余空格
    $text   =   str_replace( '  ', ' ', $text );
    return $text;
}

function is_login() {
    $user = session( 'user_auth' );
    if ( empty( $user ) ) {
        return 0;
    } else {
        return session( 'user_auth_sign' ) == data_auth_sign( $user ) ? $user['uid'] : 0;
    }
}

function data_auth_sign( $data ) {
    if ( !is_array( $data ) ) {
        $data = (array)$data;
    }
    ksort( $data );
    $code = http_build_query( $data );
    $sign = sha1( $code );
    return $sign;
}
function page_url( $page, $url ) {

    list( $host, $query ) = explode( '?', $url );
    parse_str( $query, $params );

    $re_arr = array();
    foreach ( $params as $key=>$value ) {
        if ( $key !== 'page' ) {
            $re_arr[$key]  = $value;
        }
    }
    $query = http_build_query( $re_arr ).'&'.'page='.$page;
    $url   = $host.'?'.$query;
    return $url;
}
function get_username( $uid ) {
    $username = D( 'User' )->where( array( 'uid'=>$uid ) )->getField( 'username' );
    if ( !empty( $username ) ) {
        return  $username;
    }
}

function filter_chars( $string ) {
    if ( is_array( $string ) ) {
        foreach ( $string as $key => $val ) {
            $string[$key] = filter_chars( $val, $flags );
        }
    } else {
        $string = str_replace( array( '&', '"', '<', '>' ), array( '&amp;', '&quot;', '&lt;', '&gt;' ), $string );
        if ( strpos( $string, '&amp;#' ) !== false ) {
            $string = preg_replace( '/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string );
        }

    }
    return $string;
}

function sub_array( $arr, $key="", $condition="" ) {
    $result = array();
    if ( is_array( $arr ) ) {
        foreach ( $arr as $temp_array ) {
            if ( is_object( $temp_array ) ) {
                $temp_array = (array) $temp_array;
            }
            if ( ( ""!=$condition && $temp_array[$condition[0]]==$condition[1] ) || ""==$condition ) {
                $result[] = ( ""==$key ) ? $temp_array : isset( $temp_array[$key] ) ? $temp_array[$key] : "";
            }
        }
        return $result;
    }else {
        return false;
    }
}

function friendly_date( $date ) {
    $sec  =  time() - $date;
    if ( $sec==0 ) {
        return '刚刚';
    } elseif ( $sec < 60 ) {
        return $sec .'秒前';
    } elseif ( $sec < 3600 ) {
        return round( $sec/60 ) . '分钟前';
    } elseif ( $sec <  86400 ) {
        return round( $sec/3600 ) .' 小时前';
    } elseif ( $sec < ( 86400*7 ) ) {
        return round( $sec/86400 ) . '天前';
    } elseif ( $sec< ( 86400*7*4 ) ) {
        return round( $sec/( 86400*7 ) ) .' 周前';
    }else {
        return date( "Y年n月d日", $date );
    }
}

function uavatar( $uid, $size='middle' ) {

    $size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
    $avatar = convert_uid( $uid, $size);

    if ( file_exists($avatar) ) {
        $talk_avatar = $avatar;
    } else {
        $talk_avatar = 'Public/img/no_avatar_'. $size . '.png';
    }
    return TALK_URL . '/' . $talk_avatar;
}


function mk_dir( $dir, $mode = 0777 ) {
    if ( is_dir( $dir ) || @mkdir( $dir, $mode ) )
        return true;
    if ( !mk_dir( dirname( $dir ), $mode ) )
        return false;
    return @mkdir( $dir, $mode );
}
function rand_string( $length ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    $size = strlen( $chars );
    for ( $i = 0; $i < $length; $i++ ) {
        $str .= $chars[ mt_rand( 0, $size - 1 ) ];
    }
    return $str;
}

function send_email( $to, $name, $subject = '', $body = '', $attachment = null ) {

    vendor( 'phpmailer.PHPMailerAutoload' );
   
    $mail = new PHPMailer();
    $mail->CharSet = "utf-8";
    $mail->isSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';

    $mail->Host       = C('smtp_host');
    $mail->Port       = C('smtp_port');
    $mail->Username   = C('smtp_user');
    $mail->Password   = C('smtp_pwd');
    $mail->SetFrom( C('from_email'), C('from_name'));

    $mail->isHTML( true );
    $mail->Subject    = $subject;
    $mail->MsgHTML( $body );
    $mail->AddAddress( $to, $name );
    if ( is_array( $attachment ) ) {
        foreach ( $attachment as $file ) {
            is_file( $file ) && $mail->AddAttachment( $file );
        }
    }
    return $mail->Send() ? true : $mail->ErrorInfo;
}
/**
 * Discuz 加密/解密
 *
 * @param [type]  $string    [description]
 * @param string  $operation [description]
 * @param string  $key       [description]
 * @param integer $expiry    [description]
 * @return [type]             [description]
 */
function authcode( $string, $operation = 'DECODE', $key = '', $expiry = 0 ) {
    $ckey_length = 4;
    $key = md5( $key != '' ? $key : C( 'AUTH_CODE' ) );
    $keya = md5( substr( $key, 0, 16 ) );
    $keyb = md5( substr( $key, 16, 16 ) );
    $keyc = $ckey_length ? ( $operation == 'DECODE' ? substr( $string, 0, $ckey_length ): substr( md5( microtime() ), -$ckey_length ) ) : '';

    $cryptkey = $keya.md5( $keya.$keyc );
    $key_length = strlen( $cryptkey );

    $string = $operation == 'DECODE' ? base64_decode( substr( $string, $ckey_length ) ) : sprintf( '%010d', $expiry ? $expiry + time() : 0 ).substr( md5( $string.$keyb ), 0, 16 ).$string;
    $string_length = strlen( $string );

    $result = '';
    $box = range( 0, 255 );

    $rndkey = array();
    for ( $i = 0; $i <= 255; $i++ ) {
        $rndkey[$i] = ord( $cryptkey[$i % $key_length] );
    }

    for ( $j = $i = 0; $i < 256; $i++ ) {
        $j = ( $j + $box[$i] + $rndkey[$i] ) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for ( $a = $j = $i = 0; $i < $string_length; $i++ ) {
        $a = ( $a + 1 ) % 256;
        $j = ( $j + $box[$a] ) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr( ord( $string[$i] ) ^ ( $box[( $box[$a] + $box[$j] ) % 256] ) );
    }

    if ( $operation == 'DECODE' ) {
        if ( ( substr( $result, 0, 10 ) == 0 || substr( $result, 0, 10 ) - time() > 0 ) && substr( $result, 10, 16 ) == substr( md5( substr( $result, 26 ).$keyb ), 0, 16 ) ) {
            return substr( $result, 26 );
        } else {
            return '';
        }
    } else {
        return $keyc.str_replace( '=', '', base64_encode( $result ) );
    }
}

?>
