jQuery(document).ready(function ($) {
    setTimeout(function () {
        $("#invoice + a").remove();
        let pdfLink = $("#invoice").val();
        let anchor = document.createElement("a");
        anchor.href = pdfLink;
        anchor.textContent = "Åbn renting agreement";
        anchor.target = "_blank";
        document.getElementById("invoice").after(anchor);
        $("#invoice").hide();
    });
});