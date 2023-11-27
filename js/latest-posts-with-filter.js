// JavaScript Document

jQuery(document).ready(function ($) {
    // Initialize Isotope
    var $grid = $('.grid').isotope({
        itemSelector: '.grid-item',
   /*     layoutMode: 'fitRows', // Prevent gaps when resizing*/
    });
	
    // Filter items on button click
    $('.filter').on('click', 'a', function () {
        var filterValue = $(this).attr('data-filter');
        $grid.isotope({ filter: filterValue });
    });
});


// JavaScript Document

jQuery(document).ready(function ($) {
    // Add click event for filter links
    $('.filter').on('click', 'a', function (event) {
        event.preventDefault(); // Prevent default link behavior

        // Remove active class from all links
        $('.filter a').removeClass('active');

        // Add active class to the clicked link
        $(this).addClass('active');

        // Get filter value and filter Isotope grid
        var filterValue = $(this).attr('data-filter');
        $('.grid').isotope({ filter: filterValue });
    });
});


