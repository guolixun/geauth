<?php
namespace Bennent\Geauth\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class AdminBaseController extends Controller
{
    public function __construct()
    {
        $this->_webSite();
    }

    /**
     * @notes:获取站点信息
     * @user: Bennent_G
     */
    private function _webSite()
    {
        /*Cache::remember('siteName', 7200, function () {
            return sysConf('site_name');
        });
        Cache::remember('siteTitle', 7200, function () {
            return sysConf('site_title');
        });*/
    }
}
