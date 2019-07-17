<?php
namespace App\Handlers;

use GuzzleHttp\Client;
use Overtrue\Pinyin\Pinyin;

class SlugTranslateHandler
{
    // 参考文档  http://api.fanyi.baidu.com/api/trans/product/apidoc

    private $api = 'http://api.fanyi.baidu.com/api/trans/vip/translate?';   // 初始化配置信息   后面加个  ？ 号
    private $appid;
    private $key;
    private $text;

    function __construct($text)
    {
        $this->text = $text;
        $this->appid = config('services.baidu_translate.appid');
        $this->key = config('services.baidu_translate.key');
    }

    public function translate()
    {
        // 如果没有配置百度翻译，则自动使用拼音翻译
        if(empty($this->appid) || empty($this->key)) {
            return $this->pinyin();
        }
        // 实例化客户端
        $http = new Client();
        // 发送请求
        $response = $http->get($this->query());
        // https://www.php.net/manual/zh/function.json-decode.php
        $result = json_decode($response->getBody(), true);
        /**
        获取结果，如果请求成功，dd($result) 结果如下：

        array:3 [▼
            "from" => "zh"
            "to" => "en"
            "trans_result" => array:1 [▼
                0 => array:2 [▼
                "src" => "XSS 安全漏洞"
                "dst" => "XSS security vulnerability"
                ]
            ]
        ]
         **/

        // 尝试获取翻译结果
        if(isset($result['trans_result'][0]['dst'])) {
            // 翻译成功
            return \Str::slug($result['trans_result'][0]['dst']);
        } else {
            // 如果百度翻译没结果， 则使用拼音
            return $this->pinyin($text);
        }
    }

    // 拼音翻译 备用
    public function pinyin($text)
    {
        return \Str::slug(app(Pinyin::class)->permalink($text));
    }
    // 构建请求参数
    public function query()
    {
        $salt = time();
        $sign = md5($this->appid.$this->text.$salt.$this->key);
        // 构建请求参数  http_build_query — 生成 URL-encode 之后的请求字符串
        $query = http_build_query([
            'q' => $this->text,
            'from' => 'zh',
            'to' => 'en',
            'appid' => $this->appid,
            'salt' => $salt,
            'sign' => $sign
        ]);

        return $this->api.$query;
    }
}
