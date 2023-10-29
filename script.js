let amount;
let csrfToken;
console.log("sending request")
$.ajax({
    type: "GET",
    url: "CliQIntegration.php",
    success: function (response) {
        // Close the loading popup
        const jsonResponse = JSON.parse(response);
        console.log(jsonResponse);
        console.log(jsonResponse.amount);

        // Assign the value to the amount variable
        amount = jsonResponse.amount;
        csrfToken = jsonResponse.token;
        $('#amount').attr('value', amount);
        $('#amountText').html(amount + " دينار أردني ")
        $('#token').attr('value', csrfToken);
        // Now, you can use the "amount" variable elsewhere in your code
        console.log(amount);
    },
    error: function (xhr, status, error) {
        Swal.fire({
            icon: 'error',
            title: '',
            html: '<p>حدث خطأ</p>',
            confirmButtonColor: '#c72234',
            confirmButtonText: 'حسناً'
        });
        // Handle errors
        console.error(error);
    }
});

document.getElementById('RAliasType').addEventListener('change', function () {
    let RAliasType = this.value;
    let RAliasValueInput = document.getElementById('RAliasValue');

    if (RAliasType === 'MOBL') {
        RAliasValueInput.type = 'tel';
    } else {
        RAliasValueInput.type = 'text';
    }
});
$(document).ready(function () {
    // Attach a submit event handler to the form
    $("form").submit(function (event) {
        event.preventDefault(); // Prevent the default form submission

        console.log($('#amount').val())
        // Show a loading popup without the "Okay" button
        Swal.fire({
            title: '',
            showConfirmButton: false,
            html: '<p>جار إرسال الطلب</p> <div class="loading-dots">' +
                '<p style="font-size: 18px">الرجاء الذهاب الى تطبيق البنك الخاص بك لقبول طلب</p>' +
                '<p style="color: red; font-size: 16px; margin-bottom: 5px">الرجاء عدم اغلاق التطبيق</p>' +
                '<div></div>\n' +
                '<div></div>\n' +
                '<div></div>\n' +
                '<p style="font-size: 16px; margin-top: 10px; color: red" id="countdown">الوقت المتبقي: <span></span></p>' +
                '</div>',
            allowOutsideClick: false,
            showLoaderOnConfirm: false, // Disable the loader
            timer: 300000,
            timerProgressBar: false,
            didOpen: () => {
                const span = Swal.getHtmlContainer().querySelector('span');
                timerInterval = setInterval(() => {
                    const timeLeft = Swal.getTimerLeft();
                    const minutes = Math.floor(timeLeft / 60000);
                    const seconds = Math.floor((timeLeft % 60000) / 1000);
                    span.textContent = `${minutes} دقيقة و ${seconds} ثانية`;
                }, 1000);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.timer) {
                console.log('I was closed by the timer');
            }
        });


// Set the countdown timer for 5 minutes (300,000 milliseconds)


        // Get form data
        const formData = {
            RAliasType: $("#RAliasType").val(),
            RAliasValue: $("#RAliasValue").val(),
            amount: $("#amount").val(),
            token: $('#token').val(),
        };

        try {
            $.ajax({
                type: "POST",
                url: "CliQIntegration.php",
                data: formData,
                success: function (response) {
                    // Close the loading popup
                    Swal.close();

                    const jsonResponse = JSON.parse(response);

                    if (jsonResponse.errorCode === "0") {
                        Swal.fire({
                            icon: 'success',
                            title: '',
                            html: ' تمت العملية بنجاح! ',
                            confirmButtonColor: '#71BF44',
                            confirmButtonText: 'حسناً'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '',
                            html: '<p style="color: red">' + jsonResponse.description + '</p>',
                            confirmButtonColor: '#c72234',
                            confirmButtonText: 'حسناً'
                        });
                    }
                },
                error: function (xhr, status, error, response) {
                    const jsonResponse = JSON.parse(response);

                    Swal.close();
                    Swal.fire({
                        icon: 'error',
                        title: '',
                        html: '<p> ' + jsonResponse.description + '</p>',
                        confirmButtonColor: '#c72234',
                        confirmButtonText: 'حسناً'
                    });
                    // Handle errors
                    console.error(error);
                }
            });

        } catch (error) {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: '',
                html: '<p> حدث خطأ</p>',
                confirmButtonColor: '#c72234',
                confirmButtonText: 'حسناً'
            });
            // Handle errors
            console.error(error);

        }
        // Send the data to the server using AJAX

    });
});
