(function($){
	"use strict"; 

"use strict";

jQuery(document).ready(function ($) {
    

    //switch from monthly to annual pricing tables

    function bouncy_filter(container) {
        container.each(function () {
            var pricing_table = $(this);
            var filter_list_container = pricing_table.children('.sgenix-pricing-switcher'),
                filter_radios = filter_list_container.find('input[type="radio"]'),
                filter_radio_checked = filter_list_container.find('input[type="radio"]:checked'),
                filter_switch = filter_list_container.find('.sgenix-switch'),
                pricing_table_wrapper = pricing_table.find('.sgenix-pricing-wrapper');


            //store pricing table items
            var table_elements = {};
            filter_radios.each(function () {
                var filter_type = $(this).val();
                table_elements[filter_type] = pricing_table_wrapper.find('li[data-type="' + filter_type + '"]');
            });

            // Check Radio Value Before Change also Add or Remove class form switch
            if (filter_radio_checked.val() == 'lifetime') {
                var selected_filter = filter_radio_checked.val();
                filter_switch.addClass('lifetime');
                //give higher z-index to the pricing table items selected by the radio input
                show_selected_items(table_elements[selected_filter]);

                //rotate each htmove-pricing-wrapper 
                //at the end of the animation hide the not-selected pricing tables and rotate back the .htmove-pricing-wrapper

                if (!Modernizr.cssanimations) {
                    hide_not_selected_items(table_elements, selected_filter);
                    pricing_table_wrapper.removeClass('is-switched');
                } else {
                    pricing_table_wrapper.addClass('is-switched').eq(0).one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function () {
                        hide_not_selected_items(table_elements, selected_filter);
                        pricing_table_wrapper.removeClass('is-switched');
                        //change rotation direction if .htmove-pricing-list has the .htmove-bounce-invert class
                        if (pricing_table.find('.sgenix-pricing-list').hasClass('sgenix-bounce-invert')) pricing_table_wrapper.toggleClass('reverse-animation');
                    });
                }
            } else {
                filter_switch.removeClass('lifetime');
            }

            //detect input change event
            filter_radios.on('change', function (event) {
                event.preventDefault();
                //detect which radio input item was checked
                var selected_filter = $(event.target).val();

                // Check Radio Value and Add or Remove class form switch
                if (selected_filter == 'lifetime') {
                    filter_switch.addClass('lifetime');
                } else {
                    filter_switch.removeClass('lifetime');
                }

                //give higher z-index to the pricing table items selected by the radio input
                show_selected_items(table_elements[selected_filter]);

                //rotate each htmove-pricing-wrapper 
                //at the end of the animation hide the not-selected pricing tables and rotate back the .htmove-pricing-wrapper

                if (!Modernizr.cssanimations) {
                    hide_not_selected_items(table_elements, selected_filter);
                    pricing_table_wrapper.removeClass('is-switched');
                } else {
                    pricing_table_wrapper.addClass('is-switched').eq(0).one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function () {
                        hide_not_selected_items(table_elements, selected_filter);
                        pricing_table_wrapper.removeClass('is-switched');
                        //change rotation direction if .htmove-pricing-list has the .htmove-bounce-invert class
                        if (pricing_table.find('.sgenix-pricing-list').hasClass('sgenix-bounce-invert')) pricing_table_wrapper.toggleClass('reverse-animation');
                    });
                }
            });
        });
    }
    function show_selected_items(selected_elements) {
        selected_elements.addClass('is-selected');
    }

    function hide_not_selected_items(table_containers, filter) {
        $.each(table_containers, function (key, value) {
            if (key != filter) {
                $(this).removeClass('is-visible is-selected').addClass('is-hidden');

            } else {
                $(this).addClass('is-visible').removeClass('is-hidden is-selected');
            }
        });
    }

    bouncy_filter($('.sgenix-pricing-container'));


});

})(jQuery);