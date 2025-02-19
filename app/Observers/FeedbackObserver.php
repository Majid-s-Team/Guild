<?php

namespace App\Observers;

use App\Contracts\LogServiceInterface;
use App\Models\FeedBack;
use App\Services\LogService;

class FeedbackObserver
{
    private LogServiceInterface $logServiceInterface;

    public function __construct(LogServiceInterface $logServiceInterface)
    {
        $this->logServiceInterface = $logServiceInterface;
    }
    /**
     * Handle the FeedBack "created" event.
     */
    public function created(FeedBack $feedBack): void
    {
        $this->logServiceInterface->createLog('feedback_created', 'new feedback created', $feedBack);
    }

    /**
     * Handle the FeedBack "updated" event.
     */
    public function updated(FeedBack $feedBack): void
    {
        //
    }

    /**
     * Handle the FeedBack "deleted" event.
     */
    public function deleted(FeedBack $feedBack): void
    {
        //
    }

    /**
     * Handle the FeedBack "restored" event.
     */
    public function restored(FeedBack $feedBack): void
    {
        //
    }

    /**
     * Handle the FeedBack "force deleted" event.
     */
    public function forceDeleted(FeedBack $feedBack): void
    {
        //
    }
}
