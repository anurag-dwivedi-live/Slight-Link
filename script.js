$(document).ready(function () {
    $('#submitBtn').click(function (e) {
        e.preventDefault();
        var url = $('#url').val();

        $.ajax({
            type: 'POST',
            url: 'short-link',
            data: { 
                url: url 
            },
            success: function (response) {
                if (response == "200") {
                    Swal.fire({
                        title: "Done",
                        text: "Your Link is Ready...",
                        icon: "success"
                    });                
                    $("#shortedLinks").load(location.href + " #shortedLinks");
                    $('#url').val('');
                } else {
                    Swal.fire({
                        title: "Oops!",
                        text: "Please enter a valid link...",
                        icon: "info"
                    });
                    $("#shortedLinks").load(location.href + " #shortedLinks");
                }
            }
        });
    });
});

var clipboard = new ClipboardJS('.copyLink', {
    target: function(trigger) {
        return trigger.parentElement.querySelector('.shorted-link');
    }
});

clipboard.on('success', function(e) {
    var copySvg = e.trigger.querySelector('.copy-svg');
    var checkmark = e.trigger.querySelector('.checkmark');
    copySvg.style.display = 'none';
    checkmark.style.display = 'inline-block';
    setTimeout(function() {
        copySvg.style.display = 'inline-block';
        checkmark.style.display = 'none';
    }, 3000);
});

clipboard.on('error', function(e) {
    console.error('Error copying text to clipboard:', e.action);
    alert('Error copying text to clipboard. Please try again.');
});