<?php

namespace App\Observers;

use App\Models\FollowUp;
use App\Models\Enquiry;

class FollowUpObserver
{
    /**
     * Handle the FollowUp "saved" event.
     */
    public function saved(FollowUp $followUp): void
    {
        $enquiry = $followUp->enquiry;
        if (!$enquiry) {
            return;
        }

        // Sync action metadata on Enquiry
        if (!empty($followUp->call_notes)) {
            $enquiry->note = $followUp->call_notes;
        }
        $enquiry->action_by = $followUp->created_by ?: auth()->id();
        $enquiry->performed_at = now();

        // 1. Auto-update Enquiry Status
        if ($followUp->status === 'completed' && !in_array($enquiry->status, ['site_visit', 'won', 'lost'])) {
            if ($followUp->outcome === 'interested') {
                $enquiry->status = 'interested';
            } else {
                $enquiry->status = 'follow_up';
            }
        }

        // 2. Recalculate lead score
        $this->recalculateLeadScore($enquiry);
    }

    private function recalculateLeadScore(Enquiry $enquiry): void
    {
        $score = 10; // Base score

        // Site visits contribution
        $visits = $enquiry->siteVisits()->get();
        foreach ($visits as $visit) {
            if ($visit->status === 'visited') {
                $score += 30;
            } elseif ($visit->status === 'scheduled') {
                $score += 10;
            }
        }

        // Follow ups contribution
        $followups = $enquiry->followUps()->get();
        foreach ($followups as $followUp) {
            if ($followUp->status === 'completed') {
                $score += 15;
                if ($followUp->outcome === 'interested') {
                    $score += 20;
                } elseif ($followUp->outcome === 'need_more_time') {
                    $score += 5;
                } elseif ($followUp->outcome === 'not_interested') {
                    $score -= 30;
                } elseif ($followUp->outcome === 'no_response') {
                    $score -= 10;
                }
            }
        }

        $enquiry->lead_score = max(0, min(100, $score));
        $enquiry->saveQuietly();
    }
}
