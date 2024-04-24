<?php

namespace App\Providers;

use App\Models\AdvisoryBoard;
use App\Models\AdvisoryBoardMeeting;
use App\Models\Comments;
use App\Models\Consultations\LegislativeProgram;
use App\Models\Consultations\OperationalProgram;
use App\Models\Consultations\PublicConsultation;
use App\Models\Consultations\PublicConsultationTranslation;
use App\Models\LegislativeInitiative;
use App\Models\LegislativeInitiativeComment;
use App\Models\OgpPlan;
use App\Models\OgpPlanAreaOfferVote;
use App\Models\Poll;
use App\Models\Pris;
use App\Models\Publication;
use App\Models\StrategicDocument;
use App\Models\StrategicDocumentChildren;
use App\Models\StrategicDocumentChildrenTranslation;
use App\Models\StrategicDocumentTranslation;
use App\Observers\AdvisoryBoardMeetingObserver;
use App\Observers\AdvisoryBoardObserver;
use App\Observers\CommentsObserver;
use App\Observers\LegislativeInitiativeCommentObserver;
use App\Observers\LegislativeInitiativeObserver;
use App\Observers\LegislativeProgramObserver;
use App\Observers\OgpPlanAreaOfferVoteObserver;
use App\Observers\OgpPlanObserver;
use App\Observers\OperationalProgramObserver;
use App\Observers\PollObserver;
use App\Observers\PrisObserver;
use App\Observers\PublicationObserver;
use App\Observers\PublicConsultationObserver;
use App\Observers\PublicConsultationTranslationObserver;
use App\Observers\StrategicDocumentChildObserver;
use App\Observers\StrategicDocumentChildTranslationObserver;
use App\Observers\StrategicDocumentObserver;
use App\Observers\StrategicDocumentTranslationObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ]
    ];

    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        PublicConsultation::class => [PublicConsultationObserver::class],
        Comments::class => [CommentsObserver::class],
        OgpPlanAreaOfferVote::class => [OgpPlanAreaOfferVoteObserver::class],
        StrategicDocument::class => [StrategicDocumentObserver::class],
        StrategicDocumentTranslation::class => [StrategicDocumentTranslationObserver::class],
        StrategicDocumentChildren::class => [StrategicDocumentChildObserver::class],
        StrategicDocumentChildrenTranslation::class => [StrategicDocumentChildTranslationObserver::class],
        LegislativeInitiative::class => [LegislativeInitiativeObserver::class],
        LegislativeInitiativeComment::class => [LegislativeInitiativeCommentObserver::class],
        AdvisoryBoard::class => [AdvisoryBoardObserver::class],
        OgpPlan::class => [OgpPlanObserver::class],
        PublicConsultationTranslation::class => [PublicConsultationTranslationObserver::class],
        Publication::class => [PublicationObserver::class],
        AdvisoryBoardMeeting::class => [AdvisoryBoardMeetingObserver::class],
        Pris::class => [PrisObserver::class],
        OperationalProgram::class => [OperationalProgramObserver::class],
        LegislativeProgram::class => [LegislativeProgramObserver::class],
        Poll::class => [PollObserver::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
