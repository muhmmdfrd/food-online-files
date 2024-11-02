<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessRabbitMQMessage implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels, InteractsWithQueue;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function handle()
    {
        try {
            echo $message;
        } catch (Exception $ex) {
            $this->failed($e);
        }
    }

    public function failed($exception)
    {
        echo $exception->getMessage();
    }
}
