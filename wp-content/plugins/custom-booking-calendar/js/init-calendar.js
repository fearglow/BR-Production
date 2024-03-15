jQuery(document).ready(function($) {
    $('#calendar').fullCalendar({
        events: function(start, end, timezone, callback) {
            var events = bookingData.bookings.map(function(event) {
                return {
                    ...event,
                    allDay: true // Set all events to all-day
                };
            });
            callback(events);
        },
        defaultDate: bookingData.earliestBookingDate,
        height: 'auto',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,listWeek,listMonth'
        },
        showNonCurrentDates: false, 
        eventRender: function(event, element, view) {
            element.find('.fc-time').remove();
            element.attr('title', event.title + ": " + event.start.format("MMM D") + " - " + event.end.format("MMM D"));
            element.addClass('custom-booking-styles');
            var statusColor = getStatusColor(event.status);
            element.css('background-color', statusColor);
            element.css('border-color', statusColor);
            

            // Set data-event-id attribute for each event element
            element.attr('data-event-id', event.id);

            // Customize for list views to show "stay" information
            if (view.type === 'listWeek' || view.type === 'listMonth') {
                var stayInfo = $('<div class="custom-stay-info"></div>');
                stayInfo.text(event.status);
                element.find('.fc-list-item-time').empty().append(stayInfo); 
            }
        },
        views: {
            month: { 
                eventLimit: 5,
                buttonText: 'Month Overview'
            },
            listDay: {
                buttonText: 'Day'
            },
            listWeek: {
                buttonText: 'Week List'
            },
            listMonth: {
                buttonText: 'Month List'
            }
        },
        eventClick: function(calEvent, jsEvent, view) {
            var modal = $('#bookingDetailsModal');
            var statusColor = getStatusColor(calEvent.status);
            
            modal.find('.modal-header').css('background-color', statusColor);
            modal.find('.modal-header').css('color', calEvent.status === 'Cash Payment Due' ? '#fff' : '#333');
            
            modal.find('.modal-body').html('');
            
            // Dynamically create the content - add additional fields from bookings query
            var content = '<p><strong>Site:</strong> ' + calEvent.site + '</p>' +
                           '<p><strong>Customer:</strong> ' + calEvent.customer + '</p>' +
                           '<p><strong>Stay:</strong> ' + calEvent.stay + '</p>' +
                           '<p><strong>Adults:</strong> ' + calEvent.adult_number + '</p>' +
                           '<p><strong>Children:</strong> ' + calEvent.child_number + '</p>' +
                           '<p><strong>Status:</strong> <span style="color: ' + statusColor + ';">' + getStatusText(calEvent.status) + '</span></p>';

            modal.find('.modal-body').append(content);

            // Conditionally add the "Mark as Paid" button if it's a cash payment due
            if (calEvent.status === "Cash Payment Due") {
                var cashPaymentButton = $('<button class="btn btn-secondary btn-sm">Mark as Paid</button>');
                cashPaymentButton.on('click', function(e) {
                    e.stopPropagation();
                    markAsPaid(calEvent.id); 
                    modal.modal('hide'); 
                });
                modal.find('.modal-body').append(cashPaymentButton);
            }

            modal.modal('show');
        }
    });
	
	
	function refreshCalendarEvents() {
		$.ajax({
			url: bookingData.ajax_url,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'fetch_updated_bookings',
				nonce: bookingData.fetch_updated_bookings_nonce
			},
			success: function(response) {
				if (response.success) {
					// Assuming your calendar events are directly tied to bookingData.bookings
					bookingData.bookings = response.data;

					// Now refetch the calendar events to reflect the updated bookings
					$('#calendar').fullCalendar('refetchEvents');
				} else {
					alert('Failed to fetch updated bookings.');
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Failed to communicate with the server. Please try again.');
			}
		});
	}

	// Function to mark a booking as paid
    function markAsPaid(bookingId) {
        $.ajax({
            url: bookingData.ajax_url, 
            type: 'POST',
            data: {
                action: 'mark_as_paid', // The action hook for the backend
                booking_id: bookingId,
                nonce: bookingData.nonce 
            },
			
            success: function(response) {
				console.log("AJAX call succeeded.", response); // Add this line for debugging
				if(response.success) {
					refreshCalendarEvents();
				} else {
					alert('There was an error marking the booking as paid.');
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Failed to communicate with the server. Please try again.');
			}
        });
    }
	
    function getStatusText(status) {
		switch (status) {
			case "pending":
				return 'Pending';
			case "complete":
			case "wc-completed":
				return 'Paid';
			case "incomplete":
				return 'NOT PAID';
			case "cancelled":
			case "wc-cancelled":
				return 'Cancelled';
			case "Cash Payment Due":
				return 'Cash Payment Due';
			case "Paid Cash":
				return 'Paid Cash';
			default:
				return 'Unknown';
		}
	}


    function getStatusColor(status) {
		switch (status) {
			case "pending":
				return '#E02020'; // Red
			case "complete":
			case "wc-completed":
				return '#10CD78'; // Green
			case "incomplete":
				return '#FFAD19'; // Orange
			case "cancelled":
			case "wc-cancelled":
				return '#7A7A7A'; // Grey
			case "Cash Payment Due":
				return '#E02020'; // Red - for cash payments due
			case "Paid Cash":
				return '#10CD78'; // Green	
			default:
				return '#000000'; // Black for unknown status
		}
	}

$(document).ready(function() {
    // Use mouseenter and mouseleave for custom hover effect
    $(document).on('mouseenter', '.fc-event', function() {
        var eventId = $(this).attr('data-event-id');
        $('.fc-event[data-event-id="' + eventId + '"]').addClass('event-hover');
    }).on('mouseleave', '.fc-event', function() {
        var eventId = $(this).attr('data-event-id');
        $('.fc-event[data-event-id="' + eventId + '"]').removeClass('event-hover');
    });
});
	
});

