$(document).foundation();

function showMessage(messageType, message) {
  var callout = document.getElementById(messageType + "-message");
  callout.innerHTML = "<div>" + message + "</div>" + callout.innerHTML;
  callout.style.display = ""; 
}

/* Replaced with PHP Includes
function makeNav(){
  $("#nav-placeholder").load("nav.php");
}

function makeCallouts() {
  $("#callouts-placeholder").load("callouts.html");
}
*/
function emptyCallout(messageType) {
    var callout = document.getElementById(messageType + "-message");
    var noChildren = callout.childNodes.length;
    // Remove all but last child (which is the close button)
    // The last child is surrounded by 2 text elements from the Foundation framework
    // so 1 + 2 = 3 below
    for( var i = 0; i < noChildren - 3; i++ ) {
        MotionUI.animateOut(callout.firstChild, 'fade-out', function() {
            callout.removeChild(callout.firstChild);
        });
    }
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


function instantiateFilter(filter_id, listing_names, hide_by_default) {
    // Get the filter if it's on the page, leave function if it isn't
    var filter = document.getElementById(filter_id);

    if( filter == null ) {
        return;
    }

    $(filter).on( "input", function() {filter_list();});

    function filter_list() {
        var list_entries = document.getElementsByName(listing_names);
        var filter_text = filter.value;
        // Sanitize inputs
        filter_text = filter_text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
        for (var x = 0; x < list_entries.length; x++) {
            if (list_entries[x].innerHTML.toLowerCase().indexOf(filter_text.toLowerCase()) > -1) {
                if (filter_text === '' && hide_by_default) {
                    list_entries[x].setAttribute('hidden', 'true');
                } else {
                    list_entries[x].removeAttribute('hidden');
                }

            }
            else {
                list_entries[x].setAttribute('hidden', 'true');
            }
        }
    }
    // Filters the list if the page was generated with a search_parameter in the filter box
    window.ready = function() {filter_list();};
}
/*
// Transitions the registration pages from the Account creation to the Account information pages
function transitionRegisterPages() {
    var register = document.getElementById("register");
    var accountInfo = document.getElementById("account-info");

    MotionUI.animateOut(register,"slide-out-left", function () {
        MotionUI.animateIn(accountInfo, "slide-in-right")
    })
}
*/
// Display profile pic load failure
function showLoadFail() {
    $("#load-fail-descriptor").show();
}

function hideLoadFail() {
    $("#load-fail-descriptor").hide();
}