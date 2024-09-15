<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use DateTime;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isNull;

class UserBooking extends Controller
{
    public function confirmDayPass (Request $req) {
       $date = $req->input('dayPassBooking');
       $dateTimeV = new DateTime($date);
       $dateTime = $dateTimeV->setTime(0,0,0);
       $formattedDate = $dateTime->format('Y-m-d H:i:s');
       $endOfDayPassV = new DateTime($date);
       $endOfDayPass = $endOfDayPassV->setTime(23,59);
       $formattedEndDate = $endOfDayPass->format('Y-m-d H:i:s');
       $formatForMessage1 = $dateTime->format('F j, Y');
       $today = date('Y-m-d');
       $todayTime = new DateTime($today);
       $todaysDate = $todayTime->format('Y-m-d H:i:s');
       $dayPassAlready = Booking::where('start_time', $formattedDate)->where('user_id', $req->user()->id)->where('type', 'day Pass')->first();
       $dateInsideWeeklyMembership = Booking::where('user_id', $req->user()->id)->where('type', 'week Pass')->where('start_time', '<=', $formattedDate)->where('end_time', '>=', $formattedEndDate)->first();
       $dateInsideMonthlyMembership = Booking::where('user_id', $req->user()->id)->where('type', 'month Pass')->where('start_time', '<=' ,$formattedDate)->where('end_time', '>=', $formattedEndDate)->first();



       if ($formattedDate < $todaysDate) {
        return redirect()->back()->with('passBooked', "Invalid selection. Cannot book a past date.");
       }
       
       if ( is_null($dayPassAlready) && is_null($dateInsideWeeklyMembership) && is_null($dateInsideMonthlyMembership) )   {
                
                $newBooking = Booking::create(
                [
                'user_id' => $req->user()->id,
                'type' => 'day Pass',
                'price' => 10,
                'start_time' => $formattedDate,
                'end_time' => $formattedEndDate
            ]
            );

            return redirect('/memberships')->with('passBooked', "You have successfully booked a day pass on {$formatForMessage1} for {$newBooking->price}$. The day pass is valid from midnight until 11:59pm.");
       }
       else if ( !is_null($dayPassAlready)){
        return redirect()->back()->with('passBooked', "You have already booked a day pass for {$formatForMessage1}. Please select another day");

       }
       else if (!is_null($dateInsideWeeklyMembership)) {
        return redirect()->back()->with('passBooked', "You already have a weekly pass between {$dateInsideWeeklyMembership->start_time} and {$dateInsideWeeklyMembership->end_time}. Please review your bookings in the \'My profile\' page if you wish to do any modifications.");

       }
       else if (!is_null($dateInsideMonthlyMembership)) {
        return redirect()->back()->with('passBooked', "You already have a monthly pass between {$dateInsideMonthlyMembership->start_time} and {$dateInsideMonthlyMembership->end_time}. Please review your bookings in the \'My profile\' page if you wish to do any modifications.");

       }

    }


    public function confirmWeekPass (Request $req){
        $firstDay = $req->input('weekPassBooking');
        $dateTimeV = new DateTime($firstDay);
        $dateTime = $dateTimeV->setTime(0,0,0);
        $seventhDay = clone $dateTime;
        $seventhDay2 = $seventhDay->modify('+6 days');
        $seventhDayReal = $seventhDay2->setTime(23,59);
        $cloned1stDay = clone $dateTime;
        $cloned7thDay = clone $seventhDayReal;
        $cloned1stDay2 = $cloned1stDay->modify('-6 days');
        $cloned1stDayReal = $cloned1stDay2->format('Y-m-d H:i:s');
        $cloned7thDay2 = $cloned7thDay->modify('+6 days');
        $cloned7thDayReal = $cloned7thDay2->format('Y-m-d H:i:s');
        $formattedFirstDay = $dateTime->format('Y-m-d H:i:s');
        $formattedSeventhDay = $seventhDayReal->format('Y-m-d H:i:s');
        $monthV = clone $dateTime;
        $monthV2 = $monthV->modify('-30 days');
        $monthV1Real = $monthV2->format('Y-m-d H:i:s');
        $monthB = clone $seventhDayReal;
        $monthB2 = $monthV->modify('+30 days');
        $monthB1Real = $monthV2->format('Y-m-d H:i:s');

        Log::info('Got to this point successfully');
        $today = date('Y-m-d');
        $todayTime = new DateTime($today);
        $todaysDate = $todayTime->format('Y-m-d H:i:s');
        $overlappingDayPass = Booking::where('user_id', $req->user()->id)->where('type', 'day Pass')->where('start_time', '>=',$formattedFirstDay)->where('end_time', '<=', $formattedSeventhDay)->get();
        $overlappingWeekPass = Booking::where('user_id', $req->user()->id)
        ->where('type', 'week Pass')
        ->where(function ($query) use ($cloned1stDayReal, $formattedFirstDay, $formattedSeventhDay, $cloned7thDayReal) {

            $query->where(function ($query) use ($cloned1stDayReal, $formattedFirstDay) {
                $query->where('start_time','>=', $cloned1stDayReal )
            ->where('start_time','<=', $formattedFirstDay ); })

            ->orWhere(function ($query) use ($formattedSeventhDay, $cloned7thDayReal)   {
                $query->where('end_time','>=', $formattedSeventhDay )
            ->where('end_time','<=', $cloned7thDayReal ); });
        })->first();
        Log::info('Overlapping week pass results:', ['results' => $overlappingWeekPass]);

        $overlappingMonthPass = Booking::where('user_id', $req->user()->id)
        ->where('type', 'month Pass')
        ->where(function ($query) use ($monthV1Real, $formattedFirstDay, $formattedSeventhDay, $monthB1Real) {

            $query->where(function ($query) use ($monthV1Real, $formattedFirstDay) {
                $query->where('start_time','>=', $monthV1Real )
            ->where('start_time','<=', $formattedFirstDay ); })

            ->orWhere(function ($query) use ($formattedSeventhDay, $monthB1Real) {
               
                $query->where('end_time','>=', $formattedSeventhDay )
            ->where('end_time','<=', $monthB1Real); });
        })->first();
        Log::info('Overlapping month pass results:', ['results' => $overlappingMonthPass]);
        
        

        if ($formattedSeventhDay < $todaysDate) {
            return redirect()->back()->with('passBooked', "Invalid selection. Cannot select a past date.");

        }


        if ($overlappingDayPass->isEmpty() && is_null($overlappingWeekPass) && is_null($overlappingMonthPass)){
            $newBooking = Booking::create(
                [
                    'user_id' => $req->user()->id,
                    'type' => 'week Pass',
                    'price' => 30,
                    'start_time' => $formattedFirstDay,
                    'end_time' => $formattedSeventhDay
                ]
                );
                Log::info('Booking created');

                return redirect('/memberships')->with('passBooked', "You have successfully booked a week pass starting from {$formattedFirstDay} until {$formattedSeventhDay} for {$newBooking->price}$.");

        }


        if (!($overlappingDayPass->isEmpty())){
            return redirect()->back()->with('passBooked', "Invalid selection as you have one or more day passes already booked on the corresponding week. You can cancel your past bookings and try again.");

        }
        if (!(is_null($overlappingMonthPass))) {
            return redirect()->back()->with('passBooked', "Invalid selection as you already have a month pass booked starting from {$overlappingMonthPass->start_time} until {$overlappingMonthPass->end_time}. Please readjust your bookings and try again.");

        }

        if (!(is_null($overlappingWeekPass))) {
            return redirect()->back()->with('passBooked', "Invalid selection as you already have a week pass booked starting from {$overlappingWeekPass->start_time} until {$overlappingWeekPass->end_time}. Please readjust your bookings and try again.");

        }

    }


    public function confirmMonthPass (Request $req){
        $firstDay = $req->input('monthPassBooking');
        $dateTimeV = new DateTime($firstDay);
        $dateTime = $dateTimeV->setTime(0,0,0);
        $tirtithDay = clone $dateTime;
        $tirtithDay2 = $tirtithDay->modify('+30 days');
        $tirtithDayReal = $tirtithDay2->setTime(23,59);
        $cloned1stDay = clone $dateTime;
        $cloned7thDay = clone  $tirtithDayReal;
        $cloned1stDay2 = $cloned1stDay->modify('-6 days');
        $cloned1stDayReal = $cloned1stDay2->format('Y-m-d H:i:s');
        $cloned7thDay2 = $cloned7thDay->modify('+6 days');
        $cloned7thDayReal = $cloned7thDay2->format('Y-m-d H:i:s');
        $formattedFirstDay = $dateTime->format('Y-m-d H:i:s');
        $formattedTirtithDay =  $tirtithDayReal->format('Y-m-d H:i:s');
        $monthV = clone $dateTime;
        $monthV2 = $monthV->modify('-30 days');
        $monthV1Real = $monthV2->format('Y-m-d H:i:s');
        $monthB = clone  $tirtithDayReal;
        $monthB2 = $monthB->modify('+30 days');
        $monthB1Real = $monthB2->format('Y-m-d H:i:s');

        Log::info('Got to this point successfully');
        $today = date('Y-m-d');
        $todayTime = new DateTime($today);
        $todaysDate = $todayTime->format('Y-m-d H:i:s');
        $overlappingDayPass = Booking::where('user_id', $req->user()->id)->where('type', 'day Pass')->where('start_time', '>=',$formattedFirstDay)->where('end_time', '<=', $formattedTirtithDay)->get();
        $overlappingWeekPass = Booking::where('user_id', $req->user()->id)
        ->where('type', 'week Pass')
        ->where(function ($query) use ($cloned1stDayReal, $formattedFirstDay, $formattedTirtithDay, $cloned7thDayReal) {

            $query->where(function ($query) use ($cloned1stDayReal, $formattedFirstDay) {
                $query->where('start_time','>=', $cloned1stDayReal )
            ->where('start_time','<=', $formattedFirstDay ); })

            ->orWhere(function ($query) use ($formattedTirtithDay, $cloned7thDayReal)   {
                $query->where('end_time','>=', $formattedTirtithDay )
            ->where('end_time','<=', $cloned7thDayReal ); });
        })->first();
        Log::info('Overlapping week pass results:', ['results' => $overlappingWeekPass]);

        $overlappingMonthPass = Booking::where('user_id', $req->user()->id)
        ->where('type', 'month Pass')
        ->where(function ($query) use ($monthV1Real, $formattedFirstDay, $formattedTirtithDay, $monthB1Real) {

            $query->where(function ($query) use ($monthV1Real, $formattedFirstDay) {
                $query->where('start_time','>=', $monthV1Real )
            ->where('start_time','<=', $formattedFirstDay ); })

            ->orWhere(function ($query) use ($formattedTirtithDay, $monthB1Real) {
               
                $query->where('end_time','>=', $formattedTirtithDay )
            ->where('end_time','<=', $monthB1Real); });
        })->first();
        Log::info('Overlapping month pass results:', ['results' => $overlappingMonthPass]);
        
        

        if ($formattedTirtithDay < $todaysDate) {
            return redirect()->back()->with('passBooked', "Invalid selection. Cannot select a past date.");

        }


        if ($overlappingDayPass->isEmpty() && is_null($overlappingWeekPass) && is_null($overlappingMonthPass)){
            $newBooking = Booking::create(
                [
                    'user_id' => $req->user()->id,
                    'type' => 'month Pass',
                    'price' => 60,
                    'start_time' => $formattedFirstDay,
                    'end_time' => $formattedTirtithDay
                ]
                );
                Log::info('Booking created');

                return redirect('/memberships')->with('passBooked', "You have successfully booked a month pass starting from {$formattedFirstDay} until {$formattedTirtithDay} for {$newBooking->price}$.");

        }


        if (!($overlappingDayPass->isEmpty())){
            return redirect()->back()->with('passBooked', "Invalid selection as you have one or more day passes already booked on the corresponding week. You can cancel your past bookings and try again.");

        }
        if (!(is_null($overlappingMonthPass))) {
            return redirect()->back()->with('passBooked', "Invalid selection as you already have a month pass booked starting from {$overlappingMonthPass->start_time} until {$overlappingMonthPass->end_time}. Please readjust your bookings and try again.");

        }

        if (!(is_null($overlappingWeekPass))) {
            return redirect()->back()->with('passBooked', "Invalid selection as you already have a week pass booked starting from {$overlappingWeekPass->start_time} until {$overlappingWeekPass->end_time}. Please readjust your bookings and try again.");

        }

    }
    

}

