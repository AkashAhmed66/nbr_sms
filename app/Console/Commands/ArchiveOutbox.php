<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ArchiveOutbox extends Command
{
  protected $signature = 'outbox:archive';
  protected $description = 'Move outbox records older than 3 months to outbox_history table and delete them';

  public function handle()
  {
    DB::statement("CALL move_outbox_to_history()");
  }
}
