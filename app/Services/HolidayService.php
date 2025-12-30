<?php

namespace App\Services;

use Carbon\Carbon;

class HolidayService
{
    public static function getHolidaysForYear($year)
    {
        // 1. Fixed Date Holidays (PH & UN)
        $holidays = [
            // --- JANUARY ---
            "{$year}-01-01" => ['title' => "New Year's Day", 'type' => 'PH Regular'],
            "{$year}-01-24" => ['title' => "Int'l Day of Education (UN)", 'type' => 'UN Observance'],
            "{$year}-01-29" => ['title' => "Chinese New Year", 'type' => 'PH Special'],

            // --- FEBRUARY ---
            "{$year}-02-25" => ['title' => "EDSA People Power Anniv.", 'type' => 'PH Special'],

            // --- MARCH ---
            "{$year}-03-08" => ['title' => "Int'l Women's Day (UN)", 'type' => 'UN Observance'],

            // --- APRIL ---
            "{$year}-04-09" => ['title' => "Araw ng Kagitingan", 'type' => 'PH Regular'],
            "{$year}-04-17" => ['title' => "Maundy Thursday", 'type' => 'PH Regular'], // Note: Changes yearly
            "{$year}-04-18" => ['title' => "Good Friday", 'type' => 'PH Regular'],     // Note: Changes yearly
            "{$year}-04-19" => ['title' => "Black Saturday", 'type' => 'PH Special'],  // Note: Changes yearly
            "{$year}-04-22" => ['title' => "Earth Day (UN)", 'type' => 'UN Observance'],

            // --- MAY ---
            "{$year}-05-01" => ['title' => "Labor Day", 'type' => 'PH Regular'],

            // --- JUNE ---
            "{$year}-06-12" => ['title' => "Independence Day", 'type' => 'PH Regular'],

            // --- AUGUST ---
            "{$year}-08-12" => ['title' => "Int'l Youth Day (UN)", 'type' => 'UN Observance'],
            "{$year}-08-21" => ['title' => "Ninoy Aquino Day", 'type' => 'PH Special'],
            "{$year}-08-25" => ['title' => "National Heroes Day", 'type' => 'PH Regular'],

            // --- OCTOBER ---
            "{$year}-10-24" => ['title' => "United Nations Day", 'type' => 'UN Observance'],

            // --- NOVEMBER ---
            "{$year}-11-01" => ['title' => "All Saints' Day", 'type' => 'PH Special'],
            "{$year}-11-02" => ['title' => "All Souls' Day", 'type' => 'PH Special'],
            "{$year}-11-30" => ['title' => "Bonifacio Day", 'type' => 'PH Regular'],

            // --- DECEMBER ---
            "{$year}-12-08" => ['title' => "Immaculate Conception", 'type' => 'PH Special'],
            "{$year}-12-10" => ['title' => "Human Rights Day (UN)", 'type' => 'UN Observance'],
            "{$year}-12-25" => ['title' => "Christmas Day", 'type' => 'PH Regular'],
            "{$year}-12-30" => ['title' => "Rizal Day", 'type' => 'PH Regular'],
            "{$year}-12-31" => ['title' => "Last Day of the Year", 'type' => 'PH Special'],
        ];

        return $holidays;
    }
}