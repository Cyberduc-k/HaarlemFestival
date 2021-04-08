<?php
set_include_path(__DIR__);
require_once ("services/HistoricTourService.php");
require_once ("models/HistoricSchedule.php");
require_once ("models/Act.php");
require_once ("models/Musician.php");
require_once ("services/ActService.php");

class EventSchedule
{
    public function getHistoricSchedule()
    {

        echo  "<table border='1'>
            <tr>
                <th>Date</th>
                <th>No of English tours</th>
                <th>No of Dutch tours</th>
                <th>No of Chinese tours</th>
            </tr>";

        $hts = new HistoricTourService();
        $schedule = array();

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

        }
        else{
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

    public function musicEvent($eventId, $day)
    {
        $date = "";

        switch($day){
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

        echo $day."</br>";

        $this->getMusicEventSchedule($eventId, $date);
    }

    public function getMusicEventSchedule($eventId, $date)
    {
        $as = new ActService();

        $schedule = $as->getScheduleForEvent($eventId, $date);

        if (is_array($schedule)){
            foreach($schedule as $mus=>$mus_value)
            {
                echo "<table border='1'>
                    <tr>
                    <td>".$mus."</td>
                    <td>".$mus_value->getStartTime()->format("H:i:s")." - ".$mus_value->getEndTime()->format("H:i:s")."</td>
                    </tr>
                    <tr>
                    <td></td>
                    <td>".$mus_value->getLocation()."</td>
                  </tr>
                  </table>";
            }

            //

//            if (!is_null($schedule) && !empty($schedule)) {
//                foreach ($schedule as $timeSlot) {
//
//                    echo "<tr>
//                <td>".$timeSlot->getDate()->format("d-m-Y")."</td>
//                <td>".$timeSlot->getNDutchTours()."</td>
//                <td>".$timeSlot->getNEnglishTours()."</td>
//                <td>".$timeSlot->getNChineseTours()."</td>
//            <tr>";
//                }
        }
        else{
            echo "No schedule found or this day";
        }

    }

}
