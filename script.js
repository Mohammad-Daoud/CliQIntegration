// Declare the amount variable outside the AJAX call
var amount;

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
        $('#amount').attr('value', amount);
        $('#amountText').html(amount + " دينار اردني ")
        // Now, you can use the "amount" variable elsewhere in your code
        console.log(amount);
    },
    error: function (xhr, status, error) {
        Swal.fire({
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
            html: '<p>جار إرسال الطلب </p> <div class="loading-dots">' +
                '<p style="font-size: 18px">الرجاء الذهاب الى تطبيق البنك الخاص بك لقبول طلب </p>' +
                '<p style="color: red; font-size: 16px">الرجاء عدم اغلاق التطبيق</p>' +
                '        <div></div>\n' +
                '        <div></div>\n' +
                '        <div></div>\n' +
                '    </div>'
        });

        // Get form data
        const formData = {
            RAliasType: $("#RAliasType").val(),
            RAliasValue: $("#RAliasValue").val(),
            amount: $("#amount").val(),
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
                    console.log(jsonResponse)
                    console.log(jsonResponse.errorCode)
                    console.log(jsonResponse.description)

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
