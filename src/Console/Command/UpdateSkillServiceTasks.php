<?php

namespace Geecko\Skills\Console\Command;

use Geecko\Skills\SkillsService;
use Illuminate\Console\Command;

class UpdateSkillServiceTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skillservice:update:tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Загружает с удаленного сервера все задачи';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $service = new SkillsService();
        $service->getAllTasks();
    }
}
