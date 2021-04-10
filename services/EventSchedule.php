<?php

require_once __DIR__.'/../models/Act.php';
require_once __DIR__.'/../models/HistoricSchedule.php';
require_once __DIR__.'/../models/Musician.php';
require_once __DIR__.'/ActService.php';
require_once __DIR__.'/HistoricTourService.php';

class EventSchedule {
    public function getHistoricSchedule() {
        echo  "<table border='1'>
            <tr>
                <th>Date</th>
                <th>No of English tours</th>
                <th>No of Dutch tours</th>
                <th>No of Chinese tours</th>
            </tr>";

        $hts = new HistoricTourService();
        $schedule = $hts->getSchedule();

        // Show table
        if (!is_null($schedule) && !empty($schedule)) {
            foreach ($schedule as $timeSlot) {
                echo "<tr>
                    <td>".$timeSlot->getDate()->format("d-m-Y")."</td>
                    <td>".$timeSlot->getNDutchTours()."</td>
                    <td>".$timeSlot->getNEnglishTours()."</td>
                    <td>".$timeSlot->getNChineseTours()."</td>
                <tr>";
            }
        } else {
            echo "failed to import table";
        }

       echo " </table>
    
        <header>
            <h3>Prices</h3>
        </header>
    
        <table border='1'>
            <tr>
                <td>Single ticket</td>
                <td>17,50</td>
            </tr>
            <tr>
                <td>Family ticket (4 persons max.)</td>
                <td>60,&dash;</td>
            </tr>
        </table>";
    }

    public function musicEvent($eventId, $day) {
        $date = "";

        // get date of each day
        switch ($day) {
            case "Thursday":
                $date = "07-26";
                break;
            case "Friday":
                $date = "07-27";
                break;
            case "Saturday":
                $date = "07-28";
                break;
            case "Sunday":
                $date = "07-29";
                break;
        }

        $this->getMusicEventSchedule($eventId, $date);
    }

    public function getMusicEventSchedule($eventId, $date) {
        $as = new ActService();

        // get schedule from database
        $schedule = $as->getScheduleForEvent($eventId, $date);

        if (is_array($schedule)) {
            foreach($schedule as $mus=>$mus_value) {
                // show table
                echo "<table>
                    <tr>
                    <td>".$mus."</td>
                    <td>".$mus_value->getStartTime()->format("H:i")." - ".$mus_value->getEndTime()->format("H:i")."</td>
                    </tr>
                    <tr>
                    <td></td>
                    <td>".$mus_value->getLocation()."</td>
                  </tr>
                  </table>";
            }
        } else {
            // give feedback when no schedule was found
            echo "No schedule found or this day";
        }
    }
}
