<?php

namespace App\Observers;

use App\Models\SiteVisit;
use App\Models\Enquiry;

class SiteVisitObserver
{
    /**
     * Handle the SiteVisit "saved" event.
     */
    public function saved(SiteVisit $siteVisit): void
    {
        $enquiry = $siteVisit->enquiry;
        if (!$enquiry) {
            return;
        }

        // 1. Auto-update Enquiry Status to site_visit if visited
        if ($siteVisit->status === 'visited' && $enquiry->status !== 'won') {
            $enquiry->status = 'site_visit';
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
