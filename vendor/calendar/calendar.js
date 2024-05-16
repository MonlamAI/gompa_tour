$(document).ready(function() {
  var currentDate = new Date();
  var monthYearLabel = $('#month-year');
  
  function generateCalendar(date) {
      var daysInMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();
      var firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDay();
      var calendar = $('#calendar');
      
      var months = ["ཕྱི་ཟླ་དང་པོ།", "ཕྱི་ཟླ་གཉིས་པ།", "ཕྱི་ཟླ་གསུམ་པ།", "ཕྱི་ཟླ་བཞི་པ།", "ཕྱི་ཟླ་ལྔ་པ།", "ཕྱི་ཟླ་དྲུག་པ།", "ཕྱི་ཟླ་བདུན་པ།", "ཕྱི་ཟླ་བརྒྱད་པ།", "ཕྱི་ཟླ་དགུ་པ།", "ཕྱི་ཟླ་བཅུ་པ།", "ཕྱི་ཟླ་བཅུ་གཅིག་པ།", "ཕྱི་ཟླ་བཅུ་གཉིས་པ།"];

      calendar.empty();
      monthYearLabel.text(`${months[date.getMonth()]} ${date.getFullYear()}`);
      

      // Adjust for starting day of the week
      for (let i = 0; i < firstDay; i++) {
          calendar.append('<div class="day"></div>');
      }

      // Fill in the days
      for (let day = 1; day <= daysInMonth; day++) {
          calendar.append(`<div  class="day" data-date="${date.getFullYear()}-${date.getMonth() + 1}-${day}"><span class="day-number">${day}</span></div>`);
      }

     
      fetchCalendar();
      fetchFestival();
      
  }

  function fetchCalendar() {
    $.ajax({
        url: './vendor/calendar/fetch-calendar.php', // Adjust if necessary
        method: 'GET',
        dataType: 'json',
        success: function(calendar) {
            const today = new Date();
            
            const todayFormatted = `${today.getFullYear()}-${today.getMonth() + 1}-${today.getDate()}`;

            calendar.forEach(function(calendarItem) {
                
                // Manually parse the date
                var parts = calendarItem['﻿en_date'].split('-'); // Split DD-MM-YYYY
                // Note: months are 0-based
                var startDate = new Date(parts[2], parts[1] - 1, parts[0]);
                var formattedDate = `${startDate.getFullYear()}-${startDate.getMonth() + 1}-${startDate.getDate()}`;
                var tibetanMonth = calendarItem['tb_month'];
            
                const showButton = (formattedDate && calendarItem.sojong_duchen.trim() !== "");

                if (formattedDate === todayFormatted) {
                    $(`div[data-date='${formattedDate}']`).css('background', 'rgb(242 234 248)'); // Example: change to yellow background
                }
                // Correctly targeting the elements
                
                
                
                $(`div[data-date='${formattedDate}']`).append(`
             
                <span style=" font-size: 14px;">${calendarItem.tb_month} </span><span style="font-size: 14px; color: #6a5959;">ཚེས། </span><span style="min-width: 22px;  color: #8d0505; padding-right: 2px;font-size: 14px; padding: 4px; line-height: 28px; border-radius: 3px; background-color: rgb(185, 221, 246);">${calendarItem.tb_date}</span>
                <div class="event">
                    <div>
                        <span style="font-size: 12px !important;padding: 4px;margin: 0px;line-height: 20px;float: left;">${calendarItem.week}</span>
                        <span style="font-size: 12px !important;padding: 4px;margin: 0px;line-height: 20px;float: right;">${calendarItem.jongtod}</span>
                    </div>
                    <div style="float: left;">                     
                        <span style="font-size: 12px !important;padding: 4px;margin: 0px;line-height: 20px;float: left;">${calendarItem.namgo}</span>
                        <span style="font-size: 12px !important;padding: 4px;margin: 0px;line-height: 20px;float: right;">${calendarItem.ninag}</span>                       
                    </div>
                    ${calendarItem.sojong_duchen ? `
                        <div style="text-align: center; margin-top: 30px;">
                            <button style="border: none;background: none;" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal_${formattedDate}">
                           
                            <img style="width: 32px;" src="vendor/img/map-piont.png">
                            </button>
                            </div>
                            <div class="modal fade" id="exampleModal_${formattedDate}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">དུས་ཆེན།</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            ${calendarItem.sojong_duchen}
                                        </div>
                                    </div>
                                </div>
                            </div>

                           
                        ` : ''
                    }
                </div>
            `);
            
            });
        },
        error: function(error) {
            console.log("Error fetching events:", error);
        }
    });
};

function fetchFestival() {
    $.ajax({
        url: './vendor/calendar/fetch-events.php',
        method: 'GET',
        dataType: 'json',
        success: function(events) {
            events.forEach(function(event) {
                var parts = event['start_date'].split('-');
                var startDate = new Date(parts[0], parts[1] - 1, parts[2]); // Corrected date parsing
                var formattedDate = `${startDate.getFullYear()}-${startDate.getMonth() + 1}-${startDate.getDate()}`;
                
                var modalId = `day_${formattedDate}`;
                var modalBody = $(`.${modalId} .modal-body`);
                modalBody.append(`<div>${event.event_tbname}</div>`);
            });
        },
        error: function(error) {
            console.log("Error fetching events:", error);
        }
    });
}


  $('#prev-month').click(function() {
      currentDate.setMonth(currentDate.getMonth() - 1);
      generateCalendar(currentDate);
  });

  $('#next-month').click(function() {
      currentDate.setMonth(currentDate.getMonth() + 1);
      generateCalendar(currentDate);
  });

  generateCalendar(currentDate);



});


