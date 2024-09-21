// 'use strict';
    function appointmentcalendar(record){ 
        
        $(document).ready(function () {

            var date = new Date();
            
            var array = JSON.parse(record);
            var events = [];
            for(var i=0; i<array.length; i++) 
            { 
               let event = {
                            date:  array[i].date,
                            slot: array[i].slot, 
                            status: array[i].status,
                            patient_mro: array[i].patient_mro,
                            patient_name: array[i].patient_name,
                            docname: array[i].name,
                        }
                
               events.push(event);
            }
        
            $('#external-events .fc-event').each(function () {

                // store data so the calendar knows to render an event upon drop
                $(this).data('event', {
                    patient_mro: $.trim($(this).text()), // use the element's text as the event title
                    stick: true, // maintain when user navigates (see docs on the renderEvent method),
                    color: $(this).find('i').css("color"),
                    icon: $(this).find('i').data('icon')
                });

                // make the event draggable using jQuery UI
                $(this).draggable({
                    zIndex: 999,
                    revert: true,      // will cause the event to go back to its
                    revertDuration: 0,  //  original position after the drag
                    start: function () {
                        $('.app-block .app-sidebar').css("overflow", "visible")
                    },
                    stop: function () {
                        $('.app-block .app-sidebar').css("overflow", "hidden")
                    }
                });

            });

            $('#calendar-demo').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listMonth'
                },
                editable: true,
                droppable: true,
                drop: function () {
                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                    }
                },
                weekNumbers: true,
                eventLimit: true, // allow "more" link when too many events
                events: events,
                eventRender: function (event, element) {
                    if (event.icon) {
                        element.find(".fc-title").prepend("<i class='mr-1 fa fa-" + event.icon + "'></i>");
                    }
                },
                dayClick: function () {
                    $('#createEventModal').modal();
                },
                eventClick: function (event, jsEvent, view) {
                    var modal = $('#viewEventModal');
                    modal.find('.event-icon').html("<i class='fa fa-" + event.icon + "'></i>");
                    var date = new Date(event.date).toISOString().slice(0,10);      
                    modal.find('.slot').html(event.slot);
                    modal.find('.status').html(event.status);
                    modal.find('.patient_mro').html(event.patient_mro);
                    modal.find('.patient_name').html(event.patient_name);
                    modal.find('.docname').html(event.docname);
                    modal.modal();
                },
            });
        });
    }
