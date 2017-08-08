function stuff() {
    $.ajax({
        type: "POST",
        url: "register.php",
        data: {
            name: "mike",
            username: "mike_johnson",
            password: "THISisaLongPassword99",
            email: "mike_johnson@gmail.com",
            question1: 1,
            answer1: "BradPitt",
            submit: true
        },
        success: function(data) {
                if (data === "Yes") {
                    window.location = "protected_page.php";
                } else {
                    window.location = "register.html";
                }
            }
    });
}
stuff();
