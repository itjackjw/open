<?php

class dirConfiggModel
{

    /**
     * @description: 图片地址
     * @param {type} 
     * @return: 
     */
    public static function img()
    {
        $res = LaiketuiDB::select("select * from lkt_config where 1=1");
        $uploadImg_domain = $res[0]->uploadimg_domain; // 图片上传域名
        $uploadImg = $res[0]->uploadimg; // 图片上传位置
        if (strpos($uploadImg, '../') === false) { // 判断字符串是否存在 ../
            $img = $uploadImg_domain . $uploadImg; // 图片路径
        } else { // 不存在
            $img = $uploadImg_domain . substr($uploadImg, 2); // 图片路径
        }
        return $img;
    }

    /**
     * @description: 分类
     * @param {type} 
     * @return: 
     */
    public static function product_class($product_class, $type)
    { //产品类别
        $res = '';
        switch ($type) {
            case "select";
                $res = LaiketuiDB::select("select pname from lkt_product_class where cid =? and recycle = 0", array($product_class));
                break;
            default: //拼团规则
                if (!empty($product_class)) {
                    //获取产品类别
                    $r = LaiketuiDB::select("select cid,pname from lkt_product_class where sid = 0 and recycle =0");
                    foreach ($r as $key => $value) {
                        $c = '-' . $value->cid . '-';
                        if ($c == $product_class) {
                            $res .= '<option selected  value="-' . $value->cid . '-" >' . $value->pname . '</option>';
                        } else {
                            $res .= '<option  value="-' . $value->cid . '-">' . $value->pname . '</option>';
                        }
                        //循环第一层
                        $r_e = LaiketuiDB::select("select cid,pname from lkt_product_class where sid =? and recycle =0", array($value->cid));
                        if ($r_e) {
                            $hx = '-----';
                            foreach ($r_e as $ke => $ve) {
                                $cone = $c . $ve->cid . '-';
                                if ($cone == $product_class) {
                                    $res .= '<option selected  value="' . $cone . '">' . $hx . $ve->pname . '</option>';
                                } else {
                                    $res .= '<option  value="' . $cone . '">' . $hx . $ve->pname . '</option>';
                                }

                                //循环第二层
                                $r_t = LaiketuiDB::select("select cid,pname from lkt_product_class where sid =? and recycle =0", array($ve->cid));
                                if ($r_t) {
                                    $hxe = $hx . '-----';
                                    foreach ($r_t as $k => $v) {
                                        $ctow = $cone . $v->cid . '-';

                                        if ($ctow == $product_class) {
                                            $res .= '<option selected value="' . $ctow . '">' . $hxe . $v->pname . '</option>';
                                        } else {
                                            $res .= '<option  value="' . $ctow . '">' . $hxe . $v->pname . '</option>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    //获取产品类别
                    $r = LaiketuiDB::select("select cid,pname from lkt_product_class where sid = 0 and recycle =0");
                    foreach ($r as $key => $value) {
                        $c = '-' . $value->cid . '-';
                        $res .= '<option  value="-' . $value->cid . '-">' . $value->pname . '</option>';
                        //循环第一层
                        $r_e = LaiketuiDB::select("select cid,pname from lkt_product_class where sid =? and recycle =0", array($value->cid));
                        if ($r_e) {
                            $hx = '-----';
                            foreach ($r_e as $ke => $ve) {
                                $cone = $c . $ve->cid . '-';
                                $res .= '<option  value="' . $cone . '">' . $hx . $ve->pname . '</option>';
                                //循环第二层
                                $r_t = LaiketuiDB::select("select cid,pname from lkt_product_class where sid =? and recycle =0", array($ve->cid));
                                if ($r_t) {
                                    $hxe = $hx . '-----';
                                    foreach ($r_t as $k => $v) {
                                        $ctow = $cone . $v->cid . '-';
                                        $res .= '<option  value="' . $ctow . '">' . $hxe . $v->pname . '</option>';
                                    }
                                }
                            }
                        }
                    }
                }
                break;
        }

        return $res;
    }

    /**
     * @description: 查询品牌
     * @param {type} 
     * @return: 
     */
    public static function brand($brand_id)
    { //品牌
        $r01 = LaiketuiDB::select("select brand_id ,brand_name from lkt_brand_class where status = 0 and recycle = 0");
        $brand = '';
        $brand_num = 0;
        if ($r01) {
            if ($brand_id) {
                foreach ($r01 as $k01 => $v01) {
                    if ($v01->brand_id == $brand_id) {
                        $brand .= '<option selected value="' . $v01->brand_id . '">' . $v01->brand_name . '</option>';
                    } else {
                        $brand .= '<option  value="' . $v01->brand_id . '">' . $v01->brand_name . '</option>';
                    }
                }
            } else {
                foreach ($r01 as $k2 => $v2) {
                    $brand .= '<option  value="' . $v2->brand_id . '">' . $v2->brand_name . '</option>';
                }
            }
        }
        return $brand;
    }

    /**
     * @description: 查询正价商品及属性
     * @param {type} 
     * @return: 
     */
    public static function pro($data, $type)
    { //正价商品
        $dd = [];
        $condition = ' 1=1 ';
        if (isset($data['product_class'])) {
            $dd[] = $data['product_class'];
            $condition .= " and a.product_class like ? ";
        }

        if (isset($data['product_title'])) {
            $dd[] = $data['product_title'];
            $condition .= " and a.product_title like ? ";
        }
        if (isset($data['brand_id'])) {
            $dd[] = $data['brand_id'];
            $condition .= " and a.brand_id = ? ";
        }
        if (isset($data['pagesize'])) {
            $pagesize = $data['pagesize'];
        }

        if (isset($data['start'])) {
            $start = $data['start'];
        }
        if ($type == 'limit') { //查询正价商品分页
            $res = LaiketuiDB::select("select  a.id,a.product_title,a.imgurl,product_class,a.num from lkt_product_list as a where $condition and recycle =0 order by status asc,a.add_date desc,a.sort desc limit $start,$pagesize", $dd);
        } elseif ($type == 'configure') { //查询正价商品的属性
            $res = LaiketuiDB::select("select min(b.num) as num,min(a.attribute) attribute,min(a.price) price,min(a.id) AS attr_id,min(b.id) id,min(b.product_title) product_title,min(b.imgurl) imgurl
                from lkt_configure as a 
                left join lkt_product_list as b on a.pid = b.id 
                where b.recycle = 0 and b.num >0 and a.num > 0 and b.status = 0 group by a.pid ");
        } elseif ($type == 'pro') { //查询正价商品对应的价格
            $res = LaiketuiDB::select("select min(a.num) as num,min(c.attribute) attribute,min(c.price) price,min(c.id) AS attr_id,min(a.id) id,min(a.product_title) product_title,min(a.imgurl) imgurl
                from lkt_configure as c
                left join lkt_product_list as a on c.pid = a.id 
                where  $condition  and  a.recycle = 0 and a.num >0 and c.num > 0 and a.status = 0  group by c.pid ", $dd);
        } elseif ($type == 'pro_configure') { //查询正价商品对应的全部属性
            $res = LaiketuiDB::select("select a.num,a.attribute,a.price,a.id as attr_id,b.id,b.product_title,b.imgurl from lkt_configure as a  left join lkt_product_list as b on a.pid = b.id where b.recycle = 0 and b.num >0 and a.num > 0 and b.id = ? and a.recycle = 0 ", $data);
        } else { //查询正价商品
            $res = LaiketuiDB::select("select  a.id,a.product_title,a.imgurl,product_class,a.num from lkt_product_list as a where $condition and recycle =0 order by status asc,a.add_date desc,a.sort desc ", $dd);
        }


        return $res;
    }
}
