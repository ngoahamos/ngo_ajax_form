$("#submit-btn").on("click", function () {
    const email = $("#email").val();

    if (!email) return alert("Email is Required");

    if (!isValidEmail(email)) return alert("Invalid Email");
    $("#submit-btn").text("Please Wait ...");
    $("#submit-btn").prop('disabled', true);
   

    const url = '/wp-content/plugins/ngo_ajax_form/process/';
    const fd = new FormData();
    fd.append('email', email);
    fd.append('submit_id', '1');
    $.ajax({
        url,
        type: 'post',
        data: fd,
        contentType: false,
        processData: false,
        success: function (response) {
            $("#submit-btn").prop('disabled', false);
            $("#submit-btn").text("SUBMIT");

            if (response) {

                const res = JSON.parse(response);
              
                let messagebox = '';

                if (res.status) {
                    const cardFound = res.cards.length > 0;

                    let serials = "";
                    res.cards.forEach(card => {
                        serials += `<p class="mb-2">${card.serial}</p>`
                    });

                    messagebox = `<div class="bg-${cardFound ? 'success' : 'warning'} p-1" >
                    <h4 class="alert-heading text-white">${res.message}!</h4>
                    <p class="text-white">${cardFound ? 'Please Find Card Details Below' : 'No Card Detail Found for ' + email}</p>
                    <hr>
                    ${serials}
                  </div>`
           
                } else {
                    messagebox = `<div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Error!</h4>
                    <p>${res.message}</p>
                  </div>`
                }

                $("#message-box").html(messagebox);

                return;
            }

        },
        error: function () {
            $("#submit-btn").prop('disabled', false);
            $("#submit-btn").text("SUBMIT");
            alert('Error Occured')
        }
    })


});


function isValidEmail(email) {
    // Regular expression pattern for a basic email validation
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;

    // Test the email against the pattern
    return emailPattern.test(email);
}