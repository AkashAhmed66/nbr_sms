<?php

namespace Modules\API\App\Repositories;

interface CustomerApiRepositoryInterface
{
    public function sendMessage($request, $userInfo);
    public function getBalance();
    public function getDLR();
    public function getKey();
    public function getUnreadReplies();
}

