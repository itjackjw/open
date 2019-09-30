<?php
require_once(MO_LIB_DIR . '/DBAction.class.php');
require_once(MO_LIB_DIR . '/ShowPager.class.php');
require_once(MO_LIB_DIR . '/Tools.class.php');
require_once(MO_LIB_DIR . '/version.php');
class IndexAction extends Action {
// getContext() 检索当前应用程序上下文。
// getRequest() 检索请求。
// getStorage() 检索存储。
// setAttribute() 设置一个属性。
    // 获取用户名
    public function getDefaultView() {
        $db=DBAction::getInstance();
        $request = $this->getContext()->getRequest();   
        $admin_id = $this->getContext()->getStorage()->read('admin_id');

        $version = LKT_VERSION;

        $authorization = $_SESSION['LAI_KE'];

        $upgrade= $request->getParameter('upgrade');//升级
        if($upgrade){
            $this->upgrade();
        }

        $sql = " select login_num,login_ip,login_time from ntb_admin where admin_id = '$admin_id' limit 1 ";
        $r = $db -> select($sql);
        //状态查询
        $mon = date("Y-m");//当前月份
         //得到系统的年月  
        $tmp_date=date("Ym");  
        //切割出年份  
        $tmp_year=substr($tmp_date,0,4);  
        //切割出月份  
        $tmp_mon =substr($tmp_date,4,2);  
        $tmp_forwardmonth=mktime(0,0,0,$tmp_mon-1,1,$tmp_year);  
        //得到当前月的上一个月   
        $lastmon=date("Y-m",$tmp_forwardmonth);
        //今天
        $today = date("Y-m-d");
        //昨天
        $yesterday= date("Y-m-d",strtotime("-1 day"));
        //qiantian
        $qiantian= date("Y-m-d",strtotime("-2 day"));
        $sitian= date("Y-m-d",strtotime("-3 day"));
        $wutian= date("Y-m-d",strtotime("-4 day"));
        //liutian
        $liutian= date("Y-m-d",strtotime("-5 day"));
        //qitian
        $qitian= date("Y-m-d",strtotime("-6 day"));

         $today1 = date("m-d");
         //昨天
        $yesterday1= date("m-d",strtotime("-1 day"));
        //qiantian
        $qiantian1= date("m-d",strtotime("-2 day"));
        $sitian1= date("m-d",strtotime("-3 day"));
        $wutian1= date("m-d",strtotime("-4 day"));
        //liutian
        $liutian1= date("m-d",strtotime("-5 day"));
        //qitian
        $qitian1= date("m-d",strtotime("-6 day"));

        //--待付款
        $dfk_sql = "select num from lkt_order where status = 0";
        $dfk = $db -> selectrow($dfk_sql);
        //--待发货
        $dfh_sql = "select num from lkt_order where status = 1";
        $dp = $db -> selectrow($dfh_sql);
        //--待收货
        $dsh_sql = "select num from lkt_order where status = 2";
        $yth = $db -> selectrow($dsh_sql);
        //待评价订单 
        $pj_sql = "select num from lkt_order where status = 3";
        $pj = $db -> selectrow($pj_sql);

        //退货订单 
        $th_sql = "select num from lkt_order where status = 4";
        $th = $db -> selectrow($th_sql);
         //已完成订单 
        $wc_sql = "select num from lkt_order where status = 5";
        $wc = $db -> selectrow($wc_sql);

        //当日的营业额
        $day_sql = "select sum(z_price) as z_price from lkt_order where add_time like '$today%' and status > 0 and status <> 4 ";//除退款的总金额，包含运费
        $day01 = $db -> select($day_sql);
        $day_yy01 = $day01[0] -> z_price ;
        $day_yy =$day_yy01?$day_yy01:0;

        $day001 = $db -> select("select sum(re_money) as re_money from lkt_order_details where add_time like '$today%' and r_status = 6 and re_type > 0 ");
        $day_yy001 = $day001[0] -> re_money ;
        $day_yy001 =$day_yy001?$day_yy001:0;
        $day_yy = $day_yy - $day_yy001;
        //昨天的营业额
        $yessql = "select sum(z_price) as z_price from lkt_order where add_time like '$yesterday%' and status > 0 and status <> 4 ";
        $yes01 = $db -> select($yessql);
        $yes_yyy = $yes01[0] -> z_price ;
        $yes_yy =$yes_yyy?$yes_yyy:0;

        $yes_yyy001 = $db -> select("select sum(re_money) as re_money from lkt_order_details where add_time like '$yesterday%' and r_status = 6 and re_type > 0 ");
        $yes_yyy0001 = $yes_yyy001[0] -> re_money ;
        $yes_yyy0001 =$yes_yyy0001?$yes_yyy0001:0;
        $yes_yy = $yes_yy - $yes_yyy0001;

        //营业额百分比(当日减去前一天的值除以前一日的营业额)
        if($yes_yy > 0){
            $yingye_day = round(($day_yy-$yes_yy)/$yes_yy *100 , 2)."%";
        }else{
             $yingye_day = '0';
        }
    
        //当日的总订单
        $day_dd = "select num from lkt_order where add_time like '$today%' and status > 0";
        $daydd = $db -> selectrow($day_dd);

        //昨天的总订单
        $yes_dd = "select num from lkt_order where add_time like '$yesterday%' and status > 0";
        $yesdd = $db -> selectrow($yes_dd);

        //订单百分比(当日减去前一天的值除以前一日的订单)
        if($yesdd > 0){
            $dingdan_day = round(($daydd-$yesdd)/$yesdd *100 , 2)."%";
        }else{
            $dingdan_day = '0';
        }

        //这个月的营业额
        $tyye_sql = "select sum(z_price) as z_price from lkt_order where add_time like '$mon%' and status > 0 and status <> 4 ";
        $tm001 = $db -> select($tyye_sql);
        $tm0101 = $tm001[0] -> z_price ;
        $tm01 = $tm0101?$tm0101:0;

        $tyye_sql01 = $db -> select("select sum(re_money) as re_money from lkt_order_details where add_time like '$mon%' and r_status = 6 and re_type > 0 ");
        $tm0001 = $tyye_sql01[0] -> re_money ;
        $tm0001 =$tm0001?$tm0001:0;
        // print_r("select sum(re_money) as re_money from lkt_order_details where add_time like '$mon%' and r_status = 6 and re_type > 0 ");die;
        $tm01 = $tm01 - $tm0001;



        //这个月的总订单
        $tm_sql = "select num from lkt_order where add_time like '$mon%' and status > 0";
        $tm = $db -> selectrow($tm_sql);


        //累计营业额
        $tyye_sql01 = "select sum(z_price) as z_price from lkt_order where  status > 0 and status <> 4";
        $tm002 = $db -> select($tyye_sql01);
        $tm0202 = $tm002[0] -> z_price ;
        $tm02 = $tm0202?$tm0202:0;

        $tm0002 = $db -> select("select sum(re_money) as re_money from lkt_order_details where r_status = 6 and re_type > 0 ");
        $tm02002 = $tm0002[0] -> re_money ;
        $tm002 = $tm02002?$tm02002:0;
         $tm02= $tm02- $tm002;
        //累计订单数
        $latm_sql01 = "select num from lkt_order where  status > 0";
        $leiji_dd = $db -> selectrow($latm_sql01);
         
        //会员总数
        $couhuiyuan_sql = "select id from lkt_user ";
        $couhuiyuan= $db -> selectrow($couhuiyuan_sql);
        $couhuiyuan_sql01 = "select id from lkt_user_del ";
        $couhuiyuan01= $db -> selectrow($couhuiyuan_sql01);
        $couhuiyuan= $couhuiyuan+$couhuiyuan01;

        $couhuiyuan_sql01 = "select id from lkt_user where Register_data like '$today%'";
        $couhuiyuan01= $db -> selectrow($couhuiyuan_sql01);
        $couhuiyuan_sql001 = "select id from lkt_user_del where Register_data like '$today%'";
        $couhuiyuan001= $db -> selectrow($couhuiyuan_sql001);
        $couhuiyuan01= $couhuiyuan01+$couhuiyuan001;

        $couhuiyuan_sql02 = "select id from lkt_user where Register_data like '$yesterday%'";
        $couhuiyuan02= $db -> selectrow($couhuiyuan_sql02);
        $couhuiyuan_sql002 = "select id from lkt_user_del where Register_data like '$yesterday%'";
        $couhuiyuan002= $db -> selectrow($couhuiyuan_sql002);
        $couhuiyuan02=$couhuiyuan02+$couhuiyuan002;

        $couhuiyuan_sql03 = "select id from lkt_user where Register_data like '$qiantian%'";
        $couhuiyuan03= $db -> selectrow($couhuiyuan_sql03);
        $couhuiyuan_sql003 = "select id from lkt_user_del where Register_data like '$qiantian%'";
        $couhuiyuan003= $db -> selectrow($couhuiyuan_sql003);
        $couhuiyuan03=$couhuiyuan03+$couhuiyuan003;

        $couhuiyuan_sql04 = "select id from lkt_user where Register_data like '$sitian%'";
        $couhuiyuan04= $db -> selectrow($couhuiyuan_sql04);
        $couhuiyuan_sql004 = "select id from lkt_user_del where Register_data like '$sitian%'";
        $couhuiyuan004= $db -> selectrow($couhuiyuan_sql004);
        $couhuiyuan04=$couhuiyuan04+$couhuiyuan004;

        $couhuiyuan_sql05 = "select id from lkt_user where Register_data like '$wutian%'";
        $couhuiyuan05= $db -> selectrow($couhuiyuan_sql05);
        $couhuiyuan_sql005 = "select id from lkt_user_del where Register_data like '$wutian%'";
        $couhuiyuan005= $db -> selectrow($couhuiyuan_sql005);
        $couhuiyuan05=$couhuiyuan05+$couhuiyuan005;

        $couhuiyuan_sql06 = "select id from lkt_user where Register_data like '$liutian%'";
        $couhuiyuan06= $db -> selectrow($couhuiyuan_sql06);
        $couhuiyuan_sql006 = "select id from lkt_user_del where Register_data like '$liutian%'";
        $couhuiyuan006= $db -> selectrow($couhuiyuan_sql006);
        $couhuiyuan06=$couhuiyuan06+$couhuiyuan006;

        $couhuiyuan_sql07 = "select id from lkt_user where Register_data like '$qitian%'";
        $couhuiyuan07= $db -> selectrow($couhuiyuan_sql07);
        $couhuiyuan_sql007 = "select id from lkt_user_del where Register_data like '$qitian%'";
        $couhuiyuan007= $db -> selectrow($couhuiyuan_sql007);
        $couhuiyuan07=$couhuiyuan07+$couhuiyuan007;

        $notice = "select * from lkt_set_notice order by time desc";
        $res_notice= $db -> select($notice);//公告


        //访客人数

        $fangke_sql = "select id from lkt_record where type = 0 group by user_id";
        $fangke= $db -> selectrow($fangke_sql);
    
        $fangke_sql01 = "select id from lkt_record where add_date like '$today%' and type = 0 group by user_id";
        $fangke01= $db -> selectrow($fangke_sql01);

        $fangke_sql02 = "select id from lkt_record where add_date like '$yesterday%' and type = 0 group by user_id";
        $fangke02= $db -> selectrow($fangke_sql02);

        if($fangke02 > 0){
            $fangkebizhi = round(($fangke01-$fangke02)/$fangke02 *100 , 2)."%";
        }else{
             $fangkebizhi = '0';
        }

        //本月
        $fangke_sql03 = "select id from lkt_record where add_date like '$mon%' and type = 0 group by user_id";
        $fangke03= $db -> selectrow($fangke_sql03);


        //订单统计
        $order_sql01 = "select id from lkt_order where add_time like '$today%'  and status > 0 ";
        $order01= $db -> selectrow($order_sql01);

        $order_sql02 = "select id from lkt_order where add_time like '$yesterday%' and status > 0";
        $order02= $db -> selectrow($order_sql02);

        $order_sql03 = "select id from lkt_order where add_time like '$qiantian%' and status > 0";
        $order03= $db -> selectrow($order_sql03);

        $order_sql04 = "select id from lkt_order where add_time like '$sitian%' and status > 0";
        $order04= $db -> selectrow($order_sql04);

        $order_sql05 = "select id from lkt_order where add_time like '$wutian%' and status > 0";
        $order05= $db -> selectrow($order_sql05);

        $order_sql06 = "select id from lkt_order where add_time like '$liutian%' and status > 0";
        $order06= $db -> selectrow($order_sql06);
 
        $order_sql07 = "select id from lkt_order where add_time like '$qitian%' and status > 0";
        $order07= $db -> selectrow($order_sql07);






        $sql_uploadImg = "select * from lkt_config where id = '1'";
        $r_sql_uploadImg = $db->select($sql_uploadImg);
        $uploadImg = $r_sql_uploadImg[0]->uploadImg; // 图片上传位置
        $request->setAttribute("uploadImg",$uploadImg);//--待付款
        $request->setAttribute("version",$version);
        $request->setAttribute("dfk",$dfk);//--待付款 
        $request->setAttribute("dp",$dp);//--待发货
        $request->setAttribute("authorization",$authorization);//--授权
        $request->setAttribute("yth",$yth);//--待收货
        $request->setAttribute("pj",$pj);//评价订单 
        $request->setAttribute("th",$th);//退货订单
        $request->setAttribute("wc",$wc);//完成订单 
        $request->setAttribute("day_yy",$day_yy); //当日的营业额
        $request->setAttribute("yes_yy",$yes_yy); //昨日的营业额
        $request->setAttribute("yingye_day",$yingye_day);//当日的营业额百分比
        $request->setAttribute("daydd",$daydd);//当日的总订单
        $request->setAttribute("yesdd",$yesdd);//前日的总订单
        $request->setAttribute("dingdan_day",$dingdan_day);//当日的订单百分比
        $request->setAttribute("tm01",$tm01);//这个月的营业额
        $request->setAttribute("tm",$tm);//这个月的总订单
        $request->setAttribute("tm02",$tm02);//累计营业额
        $request->setAttribute("leiji_dd",$leiji_dd);//累计订单数
        $request->setAttribute("couhuiyuan01",$couhuiyuan01);//1会员统计
        $request->setAttribute("couhuiyuan02",$couhuiyuan02);//2
        $request->setAttribute("couhuiyuan03",$couhuiyuan03);//3
        $request->setAttribute("couhuiyuan04",$couhuiyuan04);//4
        $request->setAttribute("couhuiyuan05",$couhuiyuan05);//5
        $request->setAttribute("couhuiyuan06",$couhuiyuan06);//6
        $request->setAttribute("couhuiyuan07",$couhuiyuan07);//7
        $request->setAttribute("today",$today1);//1
        $request->setAttribute("yesterday",$yesterday1);//2
        $request->setAttribute("qiantian",$qiantian1);//3
        $request->setAttribute("sitian",$sitian1);//4
        $request->setAttribute("wutian",$wutian1);//5
        $request->setAttribute("liutian",$liutian1);//6
        $request->setAttribute("qitian",$qitian1);//7
        $request->setAttribute("res_notice",$res_notice);//公告
        $request->setAttribute("couhuiyuan",$couhuiyuan);//会员总数
       

        //访客人数
        $request->setAttribute("fangke",$fangke);//fangke总数
        $request->setAttribute("fangke01",$fangke01);//
        $request->setAttribute("fangke02",$fangke02);//
        //本月
        $request->setAttribute("fangke03",$fangke03);//fangke总数
        $request->setAttribute("fangkebizhi",$fangkebizhi);//fangke比值
        
        //订单统计
        $request->setAttribute("order01",$order01);
        $request->setAttribute("order02",$order02);//
        $request->setAttribute("order03",$order03);//
        $request->setAttribute("order04",$order04);//
        $request->setAttribute("order05",$order05);//
        $request->setAttribute("order06",$order06);//
        $request->setAttribute("order07",$order07);//
        return View :: INPUT;
    }

    public function execute(){      
        
    }

    public function getRequestMethods(){
        return Request :: NONE;
    }

    function httpsRequest($url, $data=null) {
        // 1.初始化会话
        $ch = curl_init();
        // 2.设置参数: url + header + 选项
        // 设置请求的url
        curl_setopt($ch, CURLOPT_URL, $url);
        // 保证返回成功的结果是服务器的结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)"); //模拟浏览器代理
        if(!empty($data)) {
            // 发送post请求
            curl_setopt($ch, CURLOPT_POST, 1);
            // 设置发送post请求参数数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        // 3.执行会话; $result是微信服务器返回的JSON字符串
        $result = curl_exec($ch);
        // 4.关闭会话
        curl_close($ch);
        return $result;
    }

    public function upgrade()
    {
        $db=DBAction::getInstance();
        $request = $this->getContext()->getRequest();
        $version = LKT_VERSION;
        https://open.laiketui.com/a15a744a5ca77d41baa9d4f272f45dfd/LKT/index.php?module=api&action=upgrade&type=2&software_version=0&version='.$version;
        $vres = $this -> httpsRequest($url,$version);
        $aaa = json_decode($vres);
        $status = $aaa->status;
        $lkt_web_version = $aaa->version;
        
        $download = $request->getParameter('download'); // 下载更新包
        if($download){
            //校验版本
            if($version < $lkt_web_version){
                //判断是否下载过
                if(is_file('../lkt_update_'.$lkt_web_version.'.zip')){
                    echo "1";
                }else{
                    $down_url = $aaa->url;
                    $file_res = $this->curlDownFile($down_url,'../','lkt_update_'.$lkt_web_version.'.zip');
                    if($file_res){
                        echo "1";
                    }else{
                       echo "0";
                    }
                }

            }else{
                echo "0";
            } 
            
            exit;
        }

        if($version != $lkt_web_version){
           $detail = '<b style="color:#f30;font-size:16px;padding:10px 0;display: inline-block;">紧急安全问题修复,强烈推荐升级到最新版!升级前注意备份.</b><br> #### update:<br> - 安全漏洞修复：文件越权读取漏洞紧急修复，iis6配置不当导致安全问题优化<br> - 文件通用选择，支持跨域，允许第三方调用<br> - tar解压，文件名过长兼容处理(路径大于100字符处理<br> - 图片预览大图处理；生成多级缩略图<br> - 权限组开启了文件下载权限，对应开启外链功能<br> - ace更新到1.29，支持emoji；emmt扩展加载机制优化<br> - 编辑器markdown多光标编辑，支持关联工具栏快捷功能<br> - 其他优化：文件名超出部分...表示；正在上传、远程下载关闭页面提醒<br> ';
           $arrayName = array('status' => 1,'version' => $version,'lkt_web_version' => $lkt_web_version,'detail' => $detail);
           echo json_encode($arrayName);
        }else{
           $arrayName = array('status' => 0,'version' => $version);
           echo json_encode($arrayName);
        }
        exit;
    }

     /**
     * @param string $down_url 下载文件地址
     * @param string $save_path 下载文件保存目录
     * @param string $filename 下载文件保存名称
     * @return bool
    */
    function curlDownFile($down_url, $save_path = '', $filename = '') {
        if (trim($down_url) == '') {
            return false;
        }
        if (trim($save_path) == '') {
            $save_path = '../';
        }

        //创建保存目录
        if (!file_exists($save_path) && !mkdir($save_path, 0777, true)) {
            return false;
        }
        if (trim($filename) == '') {
            $img_ext = strrchr($down_url, '.');
            $img_exts = array('.zip');
            if (!in_array($img_ext, $img_exts)) {
                return false;
            }
            $filename = time() . $img_ext;
        }

        // curl下载文件
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $down_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)"); //模拟浏览器代理
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $file = curl_exec($ch);
        curl_close($ch);

        // 保存文件到制定路径
        // file_put_contents($filename, $file);
        //保存文件
        ob_start ();
        // $headfile = ob_get_contents ();
        ob_end_clean ();
        //保存文件
        $res = fopen($save_path.$filename,'w+');
        fwrite($res,$file);
        fclose($res);

        unset($file, $url);
        return true;
    }

}

?>