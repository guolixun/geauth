<?php

if (!function_exists('verify_pass')) {
    /**
     * Notes:密码验证校验
     * User: Bennent_G
     * Date: 2020/3/11
     * @param $pass string 原始密码
     * @param $hash string 存储的密码
     * @return bool
     */
    function verify_pass($pass, $hash)
    {
        return password_verify($pass, $hash) ? true : false;
    }
}

if (!function_exists('encrypt_pass')) {
    /**
     * Notes:密码加密
     * User: Bennent_G
     * Date: 2020/3/11
     * @param string pass 原始密码
     * @return bool|string
     */
    function encrypt_pass($pass)
    {
        // 通过增加cost,增加破解密码的复杂度
        $options = [
            'cost' => 8
        ];
        return password_hash($pass, PASSWORD_BCRYPT, $options);
    }
}

if (!function_exists('ge_scan_dir')) {
    /**
     * 文件检索
     * @param string $pattern 检索模式 搜索模式 *.txt,*.doc; (同glog方法)
     * @param int $flags
     * @param        $pattern
     * @return array
     */
    function ge_scan_dir($pattern, $flags = null)
    {
        $files = glob($pattern, $flags);
        if (empty($files)) {
            $files = [];
        } else {
            $files = array_map('basename', $files);
        }
        return $files;
    }
}

