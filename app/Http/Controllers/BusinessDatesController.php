<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ResponseHelper;
use App\Traits\BusinessDateHelper;

class BusinessDatesController extends Controller 
{
    use ResponseHelper, BusinessDateHelper;

    /**
     * Return the business date for settlemen given an initial date and delay
     * 
     * @param Request                       $request    The Http request
     * @return Illuminate\Http\Response
     */
    public function getBusinessDateWithDelay(Request $request) {

        // validate the request input
        $validator = \Validator::make($request->all(), [
            'initialDate' => 'required|date',
            'delay' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return $this->respond(false, [ 'errors' => $validator->errors() ], $request);
        }

        try {

            // create a DateTime object from the initial date
            $initialDate = new \DateTime($request->json('initialDate'));
            $delay = $request->json('delay');

            // track weekends and holidays
            $weekendDays = 0;
            $holidayDays = 0;
            $totalDates = [];

            // 1 day interval
            $diff1Day = new \DateInterval('P1D');

            // decrement the delay if the date is a business day
            while ($delay > 0) {

                // isHoliday
                if ($this->isHolidayDay($initialDate)) {
                    $holidayDays++;
                    if (!in_array($initialDate, $totalDates)) {
                        array_push($totalDates, $initialDate->format('Y-m-d'));
                    }
                }

                // isWeekend
                else if ($this->isWeekendDay($initialDate)) {
                    $weekendDays++;
                    if (!in_array($initialDate, $totalDates)) {
                        array_push($totalDates, $initialDate->format('Y-m-d'));
                    }
                }

                // isWeekDay
                else if ($this->isWeekDay($initialDate)) {
                    $delay--;
                }

                if ($delay != 0) {
                    $initialDate->add($diff1Day);
                }
            }

            // response payload
            $results = [
                'businessDate' => $initialDate->format('Y-m-d\TH:i:s\Z'),
                'totalDays' => count($totalDates),
                'holidayDays' => $holidayDays,
                'weekendDays' => $weekendDays,
            ];
            return $this->respond(true, $results, $request);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->respond(false, [ 'errors' => $e->getMessage() ], $request);
        }
    }

}
