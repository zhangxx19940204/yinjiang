<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;

class MessageController extends Controller
{
    //
    public  function create_message(Request $request)
    {
        header('Access-Control-Allow-Origin:*');
        $param = $request->all();
//        var_dump($param);
//        die();
        $last_message = DB::table('yj_message')->where('phone', '=', trim($param['phone']))
            ->where('delete_time', '<=', '0')
            ->first();
//        var_dump($last_message);
//        die();
        if (empty($last_message)) {
            // 可以执行插入操作
//            var_dump('可以执行插入操作');
//            die();
            return $this->deal_message_info($param);
        } else {
            // 判断时间差
            $t1 = strtotime($last_message->update_time);
            $t2 = time();
            $t = $t2 - $t1;
            // 判断是否大于1天
            $diffTime = (int)($t / 86400);
            if ($diffTime >= 1) {
                //允许投票
                return $this->deal_message_info($param);
            } else {
                //拒绝
                if(isset($param['jsonpcallback'])){//jsonp方式请求

                    return $param['jsonpcallback'] . '(' . json_encode(['code'=>'200','status'=>'0','message'=>'您今天已经提交过']) . ')';

                }else{//post的普通请求

                    return ['code'=>'200','status'=>'0','message'=>'您今天已经提交过'];
                }
            }
        }
        //判断完毕

    }

    public function deal_message_info($param){

        $name = (isset($param['name'])) ? $param['name'] : '';
        $phone = $param['phone'];
        $address = (isset($param['address'])) ? $param['address'] : '';
        $content = (isset($param['content'])) ? $param['content'] : '';
        $description = (isset($param['description'])) ? $param['description'] : '';
        $is_food = (isset($param['is_food'])) ? $param['is_food'] : '';
        $budget = (isset($param['budget'])) ? $param['budget'] : '';
        $area = (isset($param['area'])) ? $param['area'] : '';

        $from = null;
        $ip = $this->getUserIp();

        try {
//            $data = json_decode(file_get_contents(''));
            $arrContextOptions=array(
                "ssl"=>array(
                    "verify_peer"=>false,
                    "verify_peer_name"=>false,
                ),
            );

            $data = json_decode(file_get_contents('https://apis.map.qq.com/ws/location/v1/ip?ip='.$ip.'&key=HEJBZ-3XDKU-7YNVU-2TCIG-673MZ-VLBVL', false, stream_context_create($arrContextOptions)),true);
            if($data['status'] != '0'){
                $city = $data['message'];
            }else{
                $city = $data['result']['ad_info']['province'].'-'.$data['result']['ad_info']['city'];
            }

        } catch (Exception $e) {
            $city = '未知';
        }

        $fromPage = isset(parse_url($param['origin'])['path']) ? parse_url($param['origin'])['path'] : parse_url($param['origin'])['host'];
        $from = $this->judge_form_page($param);
        $msg = [
            'name' => $name,
            'phone' => trim($phone),
            'address' => $address,
            'content' => $content,
            'origin' => $from,
            'is_food' => $is_food,
            'budget' => $budget,
            'area' => $area,
            'ip' => $ip,
            'ip_city' => $city,
            'description' => $description,
            'delete_time'=>'0',
            'path' => $fromPage,
            'update_time' => date('Y-m-d H:i:s', time()),
        ];
        $send_result = '0';
        try {
            $mailer = new PHPMailer();
            $title =$name.'隐匠新增了一条消息，请注意查收！';
            $receive_user = 'zhangxx19940204@163.com';//1015255064
            $content = "
                <p align='center'>
                姓名：$name
                电话：$phone
                留言内容：$content
                地址：$address
                来源：$from
                描述：$description
                </p>";
            if($name =='测试'){
                $send_result ='0';
            }else{
                $send_result = $this->send_mail($mailer,$title,$content,$receive_user);
            }


        } catch (Exception $e) {
            $send_result = '0';
        }
        //执行了邮件，进行插入留言表
        $msg['is_send_mail'] = $send_result;

        //在这里执行事务控制
        $insert_status = false;
        // 启动事务
        DB::beginTransaction();
        $today_message = Db::table('yj_message')->where('phone', '=', trim($param['phone']))->where('delete_time', '<=', '0')->where('update_time', '>=', date("Y-m-d 00:00:00"))->first();
        if(count((array)$today_message) <= 0){
            //数据不存在
            $insert_status = Db::table('yj_message')->insert($msg);
            // 提交事务
            DB::commit();
        }else{
            // 回滚事务
            DB::rollBack();
        }


        if(isset($param['jsonpcallback'])){//jsonp方式请求

            if ($insert_status){
                return $param['jsonpcallback'] . '(' . json_encode(['code'=>'200','status'=>'1','message'=>'提交成功,']) . ')';
            }else{
                //数据库插入失败，存入文件
                file_put_contents('/message.txt', json_encode($msg).PHP_EOL);
                return $param['jsonpcallback'] . '(' . json_encode(['code'=>'200','status'=>'1','message'=>'已收到你的信息']) . ')';
            }

        }else{//post的普通请求
            if ($insert_status){
                return ['code'=>'200','status'=>'1','message'=>'提交成功,请您注意接听杭州的来电'];
            }else{
                //数据库插入失败，存入文件
                file_put_contents('/message.txt', json_encode($msg).PHP_EOL);
                return ['code'=>'200','status'=>'1','message'=>'已收到,请您注意接听杭州的来电'];
            }
        }
    }

    public function judge_form_page($param){
        if (isset($param['origin'])) {
            //$from = isset(parse_url($param['origin'])['query']) ? parse_url($param['origin'])['query'] : parse_url($param['origin'])['host'];
            $urlarr=parse_url($param['origin']);

            if(!array_key_exists('query', $urlarr)){
                $from = '';
            }else{
                parse_str($urlarr['query'],$parr);
                //判断是否有f字段
                if (array_key_exists('f', $parr)){
                    $from = 'f='.trim($parr['f']);
                }else{
                    $from = '';
                }


            }

            if (trim($from) == 'f=bdds-hy') {
                $from = "百度大搜hy";
            } else if (trim($from) == 'f=bdxxl') {
                $from = "百度信息流";
            } else if(trim($from) == 'f=360xxl'){
                $from = "360信息流";
            } else if (trim($from) == 'f=360ds') {
                $from = "360大搜";
            } else if (trim($from) == 'f=sgds') {
                $from = "搜狗大搜";
            } else if (trim($from) == 'f=smds') {
                $from = "神马大搜";
            } else if (trim($from) == 'f=bdgwxxl') {
                $from = "百度官网信息流";


            } else if (trim($from) == 'f=bdgw-ppdh') {
                $from = "加盟电话";
            } else if (trim($from) == 'f=bdgw-pp') {
                $from = "品牌词";
            } else if (trim($from) == 'f=f=bdgw-ppjm') {
                $from = "品牌加盟词";
            } else if (trim($from) == 'f=bdgw-ppf') {
                $from = "品牌加盟费词";
            } else if (trim($from) == 'f=bdgw-ppyw') {
                $from = "品牌疑问词";
            } else if (trim($from) == 'f=bdgw-sb') {
                $from = "三不牛腩加盟";
            } else if (trim($from) == 'f=bdgw-hmn') {
                $from = "花样美腩加盟";
            } else if (trim($from) == 'f=bdgw-qz') {
                $from = "青转火锅加盟";


            } else if (trim($from) == 'f=bdds-ss') {
                $from = "搜索词";
            } else if (trim($from) == 'f=bdds-ppf') {
                $from = "品牌加盟费词";

            } else if (trim($from) == 'f=bdds-ppjm') {
                $from = "品牌加盟词";
            } else if (trim($from) == 'f=bdds-ppyw') {
                $from = "品牌疑问词";
            } else if (trim($from) == 'f=bdds-ppdh') {
                $from = "加盟电话";
            } else if (trim($from) == 'f=bdds-pp') {
                $from = "品牌词";
            } else if (trim($from) == 'f=bdds-hyjmf') {
                $from = "火锅店加盟多少钱";
            } else if (trim($from) == 'f=bdds-hyyw') {
                $from = "火锅店加盟哪家好";
            } else if (trim($from) == 'f=bdds-hyjm') {
                $from = "火锅店加盟";
            } else if (trim($from) == 'f=bdds-qz') {
                $from = "青转火锅加盟";
            } else if (trim($from) == 'f=bdds-hmn') {
                $from = "花样美腩加盟";
            } else if (trim($from) == 'f=bdds-sb') {
                $from = "三不牛腩盟";
            } else if (trim($from) == 'f=bdds-dpp') {
                $from = "多品牌竞品词";
            }else if(trim($from) == 'f=bdgw'){
                $from = "百度官网";
            }else if(trim($from) == 'f=bdds'){
                $from = "百度大搜";
            } else {
                //判断是否为快手来源
                if($urlarr['path'] == '/ks'){
                    $from = '快手';
                }else{
                    $from = $param['origin'];
                }

            }

        } else {
            $from = "未知来源网站";
        }

        return $from;
    }

    public function getUserIp()
    {
        $realIp = '';
        if (isset($_SERVER)) {
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $realIp = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $realIp = $_SERVER["HTTP_CLIENT_IP"];
            } else {
                $realIp = $_SERVER["REMOTE_ADDR"];
            }
        } else {
            if (getenv("HTTP_X_FORWARDED_FOR")) {
                $realIp = getenv("HTTP_X_FORWARDED_FOR");
            } else if (getenv("HTTP_CLIENT_IP")) {
                $realIp = getenv("HTTP_CLIENT_IP");
            } else {
                $realIp = getenv("REMOTE_ADDR");
            }
        }
        return $realIp;
    }

    public function send_mail($mail,$title,$content,$receive_user){

        try {
            date_default_timezone_set('Asia/Shanghai');//设定时区东八区
            $mail->CharSet= 'UTF-8';
            $mail->SMTPDebug = false;                               // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.qq.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = '2162750756';                 // SMTP username
            $mail->Password = 'gsebctmzludieafb';                           // SMTP password
            $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 465;
            //echo $mail->Host;                                  // TCP port to connect to
            $mail->setFrom('2162750756@qq.com', '用户留言');//发件人
            // $mail->addAddress('714080794@qq.com', 'Joe User');     // 设置收件人邮箱和姓名
            $mail->addAddress($receive_user);               // 收件人
            $mail->addAddress('1852786950@qq.com');               // 收件人
            $mail->addReplyTo($receive_user, '回复');//设置回复的收件人
            // $mail->addCC('714034323@qq.com');
            // $mail->addBCC('714034323@qq.com');

            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = $title;//标题
            $mail->Body    = $content;//内容
            $mail->AltBody = $content;

            if(!$mail->send()) {
                return '0';//$mail->ErrorInfo;
            } else {
                return '1';
            }

        } catch (Exception $e) {
            return '0';
        }
    }


    public function get_message_count(){
        header('Access-Control-Allow-Origin: *');
        $count = DB::table('yj_message')->count();
        return $count+5034;
    }
}
