document.getElementById('RAliasType').addEventListener('change', function () {
    var RAliasType = this.value;
    var RAliasValueInput = document.getElementById('RAliasValue');

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
            showConfirmButton: false, // Hide the "Okay" button
            html: '<p>جار إرسال الطلب</p> <div class="loading-dots">' +
                '<p style="font-size: 18px">الرجاء الذهاب الى تطبيق البنك الخاص بك لقبول طلب</p>' +
                '<p style="color: red; font-size: 16px; margin-bottom: 5px">الرجاء عدم اغلاق التطبيق</p>' +
                '<div></div>\n' +
                '<div></div>\n' +
                '<div></div>\n' +
                '<p style="font-size: 16px; margin-top: 10px; color: red" id="countdown"></p>' +
                '</div>',
            allowOutsideClick: false
        });

// Set the countdown timer for 5 minutes (300,000 milliseconds)
        let countdownTime = 5 * 60 * 1000; // 5 minutes in milliseconds
        const countdownElement = document.getElementById("countdown");

// Function to update the countdown and close the dialog when it reaches zero
        function updateCountdown() {
            const minutes = Math.floor(countdownTime / 60000);
            const seconds = Math.floor((countdownTime % 60000) / 1000);

            // Display the remaining time
            countdownElement.textContent = `الوقت المتبقي لديك:  ${minutes}:${seconds}`;

            if (countdownTime <= 0) {
                // Close the Swal dialog when the countdown reaches zero
                Swal.close();
            } else {
                countdownTime -= 1000; // Update the countdown every second
            }
        }

// Update the countdown every second
        const countdownInterval = setInterval(updateCountdown, 1000);

// Clear the interval and close the dialog when the countdown is done
        setTimeout(() => {
            clearInterval(countdownInterval);
            Swal.close();
        }, countdownTime);

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
                            title: '',
                            html: '<p> تمت العملية بنجاح! ',
                            confirmButtonColor: '#71BF44',
                            confirmButtonText: 'حسناً'
                        });
                    } else {
                        Swal.fire({
                            title: '',
                            html: '<p style="color: red">' + jsonResponse.description + '</p>',
                            confirmButtonColor: '#c72234',
                            confirmButtonText: 'حسناً'
                        });
                    }
                },
                error: function (xhr, status, error) {
                    Swal.close();
                    Swal.fire({
                        title: '',
                        html: '<p> حدث خطأ</p>',
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
