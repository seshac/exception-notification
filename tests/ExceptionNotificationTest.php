<?php

namespace Javelin\ExceptionNotification\Tests;

use Illuminate\Support\Facades\Mail;
use Javelin\ExceptionNotification\ExceptionMailer;
use Javelin\ExceptionNotification\ExceptionNotification;
use Javelin\ExceptionNotification\Exceptions\ShouldntReportableException;
use Javelin\ExceptionNotification\Exceptions\ShouldReportableException;

class ExceptionNotificationTest extends TestCase
{
    /** @test */
    public function it_wont_send_a_notification_when_its_disabled()
    {
        config(['exception-notification.enabled' => false]);

        Mail::fake();

        app(ExceptionNotification::class)->reportException(new ShouldReportableException());

        Mail::assertNotSent(ExceptionMailer::class);

        config(['exception-notification.enabled' => true]);
    }

    /** @test */
    public function it_will_send_a_notification_when_an_exception_occurs()
    {
        Mail::fake();

        app(ExceptionNotification::class)->reportException(new ShouldReportableException());

        Mail::assertQueued(ExceptionMailer::class, function ($mail) {
            return strpos($mail->subject, 'The reportable exception.')
            && $mail->hasTo('bar@example.com');
        });
    }

    /** @test */
    public function it_will_send_a_notification_when_an_exception_occurs_without_queue()
    {
        config(['exception-notification.queueOptions.enabled' => false]);

        Mail::fake();

        app(ExceptionNotification::class)->reportException(new ShouldReportableException());

        Mail::assertSent(ExceptionMailer::class);

        config(['exception-notification.queueOptions.enabled' => true]);
    }

    /** @test */
    public function it_will_not_send_a_notification_when_an_dont_report_exception_thrown()
    {
        Mail::fake();

        app(ExceptionNotification::class)->reportException(new ShouldntReportableException());

        Mail::assertNotQueued(ExceptionMailer::class);
    }
}
