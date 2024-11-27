let dateTimeNow = new Date();
let dateString = dateTimeNow.getFullYear() + "-" + (dateTimeNow.getMonth() + 1) + "-" + dateTimeNow.getDate() + " " + dateTimeNow.getHours() + ":" + dateTimeNow.getMinutes();
document.getElementById("location").value = "Ikke angivet";
document.getElementById("datetime").value = dateString;

document.querySelector("#settings_top>div[data-control-name='location']").style.display = "none";
document.querySelector("#settings_top>div[data-control-name='fastdelivery']").style.display = "none";
document.querySelector("#settings_top>div[data-control-name='datetime']").style.display = "none";

document.getElementById("user").value = ajax_object.userid;