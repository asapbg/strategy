<?php

namespace App\Console\Commands;

use App\Library\Facebook;
use App\Models\Setting;
use Illuminate\Console\Command;

class FacebookNewTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'facebook:tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get new long live tokens for facebook';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $activeFB = Setting::where('section', '=', Setting::FACEBOOK_SECTION)
            ->where('name', '=', Setting::FACEBOOK_IS_ACTIVE)
            ->get()->first();
        if($activeFB->value){
            $facebookApi = new Facebook();
            //get and save userLongLiveToken
            $userLongLiveToken = $facebookApi->getUserLongLivedToken();
            if(!isset($userLongLiveToken['error'])){
                Setting::where('name', '=', Setting::FACEBOOK_USER_LONG_LIVE_TOKEN)
                    ->where('section', '=', Setting::FACEBOOK_SECTION)
                    ->update(['value' => $userLongLiveToken['access_token']]);
            } else{
                \Log::error('Cron Refresh User Long Live Token error: '.json_encode($userLongLiveToken));
                $this->info('Error');
                return self::FAILURE;
            }

            $facebookApi->initTokens();
            $pageLongLiveToken = $facebookApi->getPageLongLivedToken();
            if(!isset($pageLongLiveToken['error'])){
                Setting::where('name', '=', Setting::FACEBOOK_PAGE_LONG_LIVE_TOKEN)
                    ->where('section', '=', Setting::FACEBOOK_SECTION)
                    ->update(['value' => $pageLongLiveToken['access_token']]);
            } else{
                \Log::error('Cron Refresh Page Long Live Token error: '.json_encode($pageLongLiveToken));
                $this->info('Error');
                return self::FAILURE;
            }
        }

        $this->info('Success');
        return self::SUCCESS;
    }
}
