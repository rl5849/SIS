$(document).foundation();

function showMessage(messageType, message) {
  var callout = document.getElementById(messageType + "-message");
  callout.innerHTML = "<p>" + message + "</p>" + callout.innerHTML;
  callout.style.display = ""; 
}

function makeNav(){
  $("#nav-placeholder").load("nav.php");
}

function makeCallouts() {
  $("#callouts-placeholder").load("callouts.html");
}

// form thing
$(function () {
    var showClass = 'show';

    $('input').on('checkval', function () {
        var label = $(this).prev('label');
        if(this.value !== '') {
            label.addClass(showClass);
        } else {
            label.removeClass(showClass);
        }
    }).on('keyup', function () {
        $(this).trigger('checkval');
    });
});


function instantiateFilter() {
    var filter = document.getElementById('filter');
    if( filter == null ) {
        return;
    }

    filter.onchange = function() {filter_list();};

    function filter_list() {
        var list_entries = document.getElementsByName('class_listing');
        var filter_text = filter.value;
        for (var x = 0; x < list_entries.length; x++) {
            if (filter_text === '' || list_entries[x].innerHTML.indexOf(filter_text) > -1) {
                list_entries[x].removeAttribute('hidden');
            }
            else {
                list_entries[x].setAttribute('hidden', 'true');
            }
        }
        console.log("Filtered");
    }
}