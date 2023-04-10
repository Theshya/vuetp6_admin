<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\Request;
use think\facade\Db;
use Firebase\JWT\JWT as JWTUtil;
use Firebase\JWT\ExpiredException; 
use Firebase\JWT\Key;
use think\captcha\facade\Captcha;
class LoginController
{
    private $expireTime = 60*60*24;
    private $jwt_secret_key = 'theshy123';
    public function test(){
        return ajaxReturn('','',1112222);
    }
    public function Login (Request $request) {
        $params = $request->param();
        $username = $params['username'];
        $password = $params['password'];
        //使用用户名和密码对数据表进行查询
        $arr = [
            'username'=>$username,
            'password'=>$password
        ];
        $usr = Db::table('user')->where($arr)->find();
        //判断返回结果
        if($usr!=NULL){
            //返回正确的数据
            $arr2 = [
                'name'=>$usr['username'],
                'is_superManager'=>$usr['is_superManager']
            ];
            $token = $this->createToken($arr2,$this->expireTime);
            return ajaxReturn('aaa','',$token);
        }
       
    }

    public function checkToken(Request $request){
        $token = $request->param('token');
        $res = $this->verifyToken($token);
        return ajaxReturn('','',[
            'token'=>$token,
            'res'=>$res
        ]);
    }

    public function createCaptcha(){
        return Captcha::create();
    }
 
    //生成token (使用jwt扩展库)

    private function createToken($user=[],$expiretime=0){
         $time = time();//签发时间
         $expire = $time + $expiretime; //过期时间
         $token = array(
            'data'=>$user,
            "iss"=>"",//签发组织  可选
            "aud"=>"",//签发作者 接收该jwt的一方  可选
            "iat"=>$time, //签发时间
            "nbf"=>$time,  //某个时间点后才能访问
            "exp"=>$expire  //token过期s时间
         );
         $jwt = JWTUtil::encode($token,$this->jwt_secret_key,"HS256");
         return $jwt;
    }
   
    //检验token
    private function verifyToken($token){
        $res['status'] = false;
        try{
           $decoded = JWTUtil::decode($token,new Key($this->jwt_secret_key,"HS256"));
           $arr = (array)$decoded;
           $res['status'] = 200;
           $res['data'] = (array)$arr['data'];
           return $res;
        }catch(\Firebase\JWT\SignatureInvalidException $e) { //签名不正确
            $res['info']    = "签名不正确";
            return $res;
        }catch(\Firebase\JWT\BeforeValidException $e) { // 签名在某个时间点之后才能用
            $res['info']    = "token失效";
            return $res;
        }catch(\Firebase\JWT\ExpiredException $e) { // token过期
            $res['info']    = "token过期";
            return $res;
        }catch(Exception $e) { //其他错误
            $res['info']    = "未知错误";
            return $res;
        }

    }

}