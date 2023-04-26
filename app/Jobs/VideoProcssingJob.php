<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Filters\WatermarkFactory;
class VideoProcssingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $filename;
    protected $filename1;

    public function __construct($filenamewithEx,$filename)
    {
        $this->filename=$filenamewithEx;
        $this->filename1=$filename;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        FFMpeg::fromDisk('Temperary')->open($this->filename)
        ->export()
        ->toDisk('watermarks')
        ->inFormat(new \FFMpeg\Format\Video\X264)
        ->addWatermark(function(WatermarkFactory $watermark) {
                $watermark->fromDisk('local')
                    ->open('logo.png')
                    ->top(5)
                    ->left(5);
            })
            ->save($this->filename);


        $lowBitrate = (new \FFMpeg\Format\Video\X264)->setKiloBitrate(250);
        FFMpeg::fromDisk('watermarks')->open($this->filename)
        ->exportForHLS()
        ->toDisk('videos')
        ->setSegmentLength(10) // optional
        ->setKeyFrameInterval(48) // optional
        ->addFormat($lowBitrate)
        ->save($this->filename1.'.m3u8');

        Storage::disk('Temperary')->delete($this->filename);
        Storage::disk('watermarks')->delete($this->filename);
    }
}
