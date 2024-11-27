jQuery(document).ready(function ($) {
    let ajaxurl = ajax_object.ajaxurl
    if(document.querySelector("[data-control-name=user]")) {
        let userBlock = document.querySelector("[data-control-name=user]");
        let userInput = userBlock.querySelector("input");
        let userLink = document.createElement("a");
        userInput.parentNode.append(userLink);
        let data = {
            action: 'get_user',
            userID: parseInt(userInput.value)
        };

        $.post(ajaxurl, data, (response) => {
            let user = response.data;
            userLink.href = user.url;
            userLink.textContent = user.name;
        });
    }
    if(document.getElementById("manglende-betalinger")){
        let table = document.getElementById("manglende-betalinger");
        let sendInvoices = table.querySelectorAll(".sendInvoice");
        sendInvoices.forEach((sendInvoice) => {
            sendInvoice.addEventListener("click", (e) => {
                let data = {
                    action: 'send_invoice',
                    order_ids: sendInvoice.value
                };
                $.post(ajaxurl, data, function (response) {
                    if(response.success){
                        console.log(response);
                    }
                    else{
                        console.log(response);
                    }
                });
            });
        });
        let createInvoices = table.querySelectorAll(".createInvoice");
        createInvoices.forEach((createInvoice) => {
            createInvoice.addEventListener("click", (e) => {
                let data = {
                    action: 'create_invoice',
                    order_ids: createInvoice.value
                };
                $.post(ajaxurl, data, function (response) {
                    if(response.success){
                        console.log(response);
                        let date = new Date();
                        window.open(response.data+"?qt="+date.getTime(), "_blank");
                    }
                    else{
                        console.log(response);
                    }
                });
            });
        });
    }
});
