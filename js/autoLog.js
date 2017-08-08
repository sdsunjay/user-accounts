function stuff() {
    $.ajax({
        type: "POST",
        url: "check.php",
        data: {
            username: "mike_johnson",
            password: "THISisaLongPassword99",
            submit: true,
        },
        success: function(data) {
                if (data === "Yes") {
                    window.location = "protected_page.php";
                } else {
                    window.location = "index.php";
                }
            }
            //dataType: dataType
    });
}
stuff();
