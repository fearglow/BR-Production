jQuery(document).ready(function($) {
    console.log(adminMap);

    // Function to update marker positions based on their data attributes
    function updateMarkerPosition(marker, posX, posY) {
        marker.css({left: posX + 'px', top: posY + 'px'});
    }

    // Function to make markers draggable and handle their repositioning
    function makeMarkersDraggable() {
        $('.rental-marker').draggable({
            containment: '#imageMap',
            stop: function(event, ui) {
                var $marker = $(this);
                var posX = ui.position.left;
                var posY = ui.position.top;
                var rentalId = $marker.data('id');

                // AJAX call to update the marker's position
                jQuery.ajax({
                    url: adminMap.ajax_url,
                    type: 'POST',
                    data: {
                        nonce: adminMap.nonce,
                        action: 'save_rental_location',
                        posX: posX,
                        posY: posY,
                        postID: rentalId
                    },
                    success: function(response) {
                        if (response.success) {
                            console.log('Position updated successfully for rental ID: ' + rentalId);
                        } else {
                            console.log('Failed to update position for rental ID: ' + rentalId);
                        }
                    },
                    error: function(errorThrown) {
                        console.log('Error updating position for rental ID: ' + rentalId + '; Error: ' + errorThrown);
                    }
                });
            }
        });
    }

    // Initially display existing markers based on their saved positions
    adminMap.rentals.forEach(function(rental) {
        var markerHtml = '<div class="rental-marker" data-id="' + rental.post_id + '" style="position: absolute; left: ' + rental.posX + 'px; top: ' + rental.posY + 'px;">' +
                         '<img src="' + adminMap.mapIconUrl + '" alt="Marker">' +
                         '<span>' + rental.post_name + '</span></div>';
        $(markerHtml).appendTo('#imageMap');
    });

    makeMarkersDraggable();

    // Enhance the droppable area to handle new drops accurately
    $('#imageMap').droppable({
        drop: function(event, ui) {
            var rentalId = ui.draggable.data('id');
            var posX = ui.offset.left - $(this).offset().left;
            var posY = ui.offset.top - $(this).offset().top;

            // Check for an existing marker for this rental
            var existingMarker = $('.rental-marker[data-id="' + rentalId + '"]');
            if (existingMarker.length) {
                // Update position of the existing marker
                updateMarkerPosition(existingMarker, posX, posY);
            } else {
                // Create a new marker
                var newMarker = $('<div class="rental-marker" data-id="' + rentalId + '" style="position: absolute; left: ' + posX + 'px; top: ' + posY + 'px;">' +
                                    '<img src="' + adminMap.mapIconUrl + '" alt="Marker">' + 
                                    '<span>' + ui.draggable.text() + '</span></div>').appendTo(this);
                makeMarkersDraggable();
            }

            // Save the marker's position
            jQuery.ajax({
                url: adminMap.ajax_url,
                type: 'POST',
                data: {
                    nonce: adminMap.nonce,
                    action: 'save_rental_location',
                    posX: posX,
                    posY: posY,
                    postID: rentalId
                },
                success: function(response) {
                    if (response.success) {
                        console.log('Position saved or updated successfully for rental ID: ' + rentalId);
                    } else {
                        console.log('Failed to save or update position for rental ID: ' + rentalId);
                    }
                },
                error: function(errorThrown) {
                    console.log('Error saving or updating position for rental ID: ' + rentalId + '; Error: ' + errorThrown);
                }
            });
        }
    });

    $('.rental').draggable({
        helper: 'clone',
        revert: 'invalid'
    });
});
