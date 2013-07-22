<?php namespace Radmen\Lassy\Command;

use Radmen\Lassy\Cleaner;
use Illuminate\Console\Command;

class ClearFiles extends Command {

  /**
   * The console command name.
   *
   * @var string
   */
  protected $name = 'lassy:clear';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Clear generated static files';

  /**
   * @var Radmen\Lassy\Cleaner
   */
  protected $cleaner;

  public function __construct(Cleaner $cleaner) {
    parent::__construct();

    $this->cleaner = $cleaner;
  }

  /**
   * Execute the console command.
   *
   * @return void
   */
  public function fire() {
    $this->cleaner->clear();
    $this->info('Static files were deleted');
  }

  /**
   * Get the console command options.
   *
   * @return array
   */
  protected function getOptions() {
    return array();
  }

}
