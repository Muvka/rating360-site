<?php

namespace App\Http\Controllers\Client\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shared\FaqQuestionSendRequest;
use App\Models\Shared\Faq;
use App\Models\Company\Employee;
use App\Notifications\Shared\NewFaqQuestionNotification;
use App\Settings\Shared\GeneralSettings;
use Illuminate\Support\Facades\Notification;
use Inertia\Inertia;

class FaqController extends Controller
{
    public function index(GeneralSettings $generalSettings)
    {
        return Inertia::render('Shared/FaqsOverviewPage', [
            'title' => __('pages.shared.faqs.index.title'),
            'faqs' => Faq::where('is_published', true)
                ->orderBy('sort')
                ->get(),
            'showQuestionForm' => (bool) $generalSettings->faq_notification_email,
        ]);
    }

    public function send(FaqQuestionSendRequest $request, GeneralSettings $generalSettings)
    {
        if (! $generalSettings->faq_notification_email) {
            abort(500, __('messages.shared.faq.error.notification_email_not_set'));
        }

        $employee = Employee::findOrFail(auth()?->id());

        Notification::route('mail', $generalSettings->faq_notification_email)
            ->notify(new NewFaqQuestionNotification(fullName: $employee->full_name, question: $request->get('question')));

        return redirect()->back();
    }
}
