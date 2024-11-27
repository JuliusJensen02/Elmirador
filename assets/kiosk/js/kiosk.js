jQuery(document).ready(function ($) {
    class Cart{
        static addProduct(id, quantity){
            let cart = JSON.parse(localStorage.getItem("cart"));
            if(cart === null) {
                cart = [];
            }
            let exists = false;
            cart.forEach(function (product) {
                if(product.product == id) {
                    product.quantity += quantity;
                    localStorage.setItem("cart", JSON.stringify(cart));
                    exists = true;
                }
            });
            if(!exists) {
                cart.push({product: id, quantity: quantity});
                localStorage.setItem("cart", JSON.stringify(cart));
            }
            Cart.updateMinicartNumber();
        }

        static numberOfItems(){
            let cart = JSON.parse(localStorage.getItem("cart"));
            let numberOfItems = 0;
            if(cart === null) {
                return numberOfItems
            }
            cart.forEach(function (product) {
                numberOfItems += product.quantity;
            });
            return numberOfItems;
        }
        
        static getProducts(callback){
            let cart = JSON.parse(localStorage.getItem("cart"));
            if(cart === null) {
                callback([]);
            }
            let data = {
                action: 'get_products',
                products: JSON.stringify(cart)
            };
            $.post(ajax_object.ajaxurl, data, (response) => callback(response.data));
        }

        /*Visual*/
        static renderCart(){
            let cartItemsPage = document.createElement("div");
            cartItemsPage.classList.add("cartItems");
            if(document.querySelector("#cart .cartItems")){
                document.querySelector("#cart .cartItems").remove();
            }
            document.getElementById("cart").append(cartItemsPage);

            Cart.getProducts(function (products) {
                if(products.length === 0) {
                    if(document.querySelector(".emptyMsg")){
                        document.querySelector(".emptyMsg").remove();
                    }
                    let p = document.createElement("p");
                    p.classList.add("emptyMsg");
                    p.innerHTML = "Kurven er tom";
                    cartItemsPage.append(p);
                    return;
                }
                products.forEach(function (product) {
                    cartItemsPage.append(Product.renderProduct(product));
                });
            });
            return cartItemsPage;
        }

        static changeQuantity(e){
            let inputElm = e.target;
            let id = inputElm.getAttribute("product");
            let cart = JSON.parse(localStorage.getItem("cart"));
            cart.forEach(function (p) {
                if(p.product == id) {
                    if(inputElm.value == "") {
                        inputElm.value = 0;
                    }
                    if(inputElm.value == 0){
                        remove.click();
                    }
                    p.quantity = parseInt(inputElm.value);
                }
            });
            localStorage.setItem("cart", JSON.stringify(cart));
            Cart.updateMinicartNumber();
            Cart.updateTotal();
        }

        static removeProduct(id, productCtn){
            let cart = JSON.parse(localStorage.getItem("cart"));
            cart.forEach(function (product, index) {
                if(product.product == id){
                    cart.splice(index, 1);
                }
            });
            localStorage.setItem("cart", JSON.stringify(cart));
            productCtn.remove();
            Cart.updateMinicartNumber();
            Cart.updateTotal();
        }

        static placeOrder(){
            let delivery = document.querySelector("#time input[name='time']:checked").value;
            let fastDelivery = true;
            let dateTime = document.getElementById("datetime").value;
            let note = document.getElementById("note").value;
            let location = document.getElementById("delivery").value;
            if(delivery != "fast"){
                fastDelivery = false;
            }

            let data = {
                action: 'create_order',
                delivery: JSON.stringify(fastDelivery),
                dateTime: JSON.stringify(dateTime),
                note: JSON.stringify(note),
                location: JSON.stringify(location),
                order_products: JSON.stringify(JSON.parse(localStorage.getItem("cart")))
            };
            $.post(ajax_object.ajaxurl, data, function (response) {
                if(response.success){
                    localStorage.removeItem("cart");
                    Cart.renderCart();
                    Cart.updateMinicartNumber();
                    Cart.updateTotal();
                    window.open("https://elmirador.dk/profil/tak-for-din-bestilling","_self")
                }
                else{
                    document.getElementById("notice").scrollIntoView();
                    document.getElementById("notice").textContent = response.data;
                }
            });
        }

        static updateTotal(){
            let total = 0;
            document.querySelector(".total").classList.add("loading");
            Cart.getProducts(function (products) {
                products.forEach(function (product) {
                    total += product.total;
                });
                total = (Math.round(total * 100) / 100).toFixed(2);
                document.getElementById("total").textContent = "€"+total;
                document.querySelector(".total").classList.remove("loading");
            });
        }

        static updateMinicartNumber(){
            let cartItems = document.querySelector("#cartButtonHeader .cartItems");
            let numberOfItems = Cart.numberOfItems();
            if(numberOfItems > 99){
                cartItems.textContent = "99+";
            }
            else{
                cartItems.textContent = Cart.numberOfItems();
            }
        }

        static displayDelivery(){
            let delivery = document.querySelector("#time input[name='time']:checked").value;
            if(delivery == "fast"){
                document.getElementById("chooseTime").style.display = "none";
            }
            else{
                document.getElementById("chooseTime").style.display = "flex";
            }
        }
    }

    class Product{
        static renderProduct(product){
            let productCtn = document.createElement("div");
            productCtn.classList.add("productCtn");
            productCtn.append(Product.img(product.img));
            productCtn.append(Product.removeBtn(product.id, productCtn));
            productCtn.append(Product.name(product.name));
            productCtn.append(Product.price(product.price, product.unit));
            productCtn.append(Product.qty(product.quantity, product.id));
            return productCtn;
        }

        static img(url){
            let img = document.createElement("img");
            img.src = url;
            return img;
        }

        static name(name){
            let nameElm = document.createElement("p");
            nameElm.textContent = name;
            nameElm.classList.add("name");
            return nameElm;
        }

        static price(price, unit){
            let priceElm = document.createElement("p");
            priceElm.textContent = "€"+price + " / "+unit;
            priceElm.classList.add("price");
            return priceElm;
        }

        static removeBtn(id, productCtn){
            let remove = document.createElement("button");
            remove.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="15.49" height="15.49" viewBox="0 0 15.49 15.49">\n' +
                '  <g id="plus_1_" data-name="plus (1)" transform="translate(-6.367 7.745) rotate(-45)">\n' +
                '    <g id="_03_Login" data-name="03 Login" transform="translate(0 0)">\n' +
                '      <path id="Path_74" data-name="Path 74" d="M.974,19.957A.974.974,0,0,1,0,18.983V.974a.974.974,0,1,1,1.949,0V18.983A.974.974,0,0,1,.974,19.957Z" transform="translate(9.004 0)" fill="#ff945b"/>\n' +
                '      <path id="Path_75" data-name="Path 75" d="M18.983,1.949H.974A.974.974,0,1,1,.974,0H18.983a.974.974,0,1,1,0,1.949Z" transform="translate(0 9.004)" fill="#ff945b"/>\n' +
                '    </g>\n' +
                '  </g>\n' +
                '</svg>\n';
            remove.classList.add("remove");

            remove.addEventListener("click", () => Cart.removeProduct(id, productCtn));
            return remove;
        }

        static qty(quantity, id){
            let qtyCtn = document.createElement("div");
            qtyCtn.classList.add("qtyCtn");

            let qtyLabel = document.createElement("label");
            qtyLabel.textContent = "Antal";
            qtyCtn.append(qtyLabel);

            let qty = document.createElement("input");
            qty.type = "number";
            qty.value = quantity;
            qty.classList.add("quantity");
            qty.setAttribute("product", id);

            qty.addEventListener("input", Cart.changeQuantity);

            qtyCtn.append(qty);

            return qtyCtn;
        }
    }

    let cartBtnHeader = document.getElementById("cartButtonHeader");
    let cartItems = document.createElement("div");
    cartItems.classList.add("cartItems");
    cartBtnHeader.append(cartItems);
    Cart.updateMinicartNumber();

    if(document.querySelector(".add-to-cart")) {
        document.querySelectorAll(".add-to-cart").forEach(function (element) {
            element.addEventListener("click", function () {
                element.textContent = "Tilføjet!";
                setTimeout(function () {
                    element.textContent = "Tilføj til kurv";
                }, 1000);
                let id = element.value;
                let quantity = parseInt(element.parentNode.querySelector(".quantity").value);
                Cart.addProduct(id, quantity);
            });
        });
    }

    if(document.getElementById("cart")) {
        Cart.renderCart();
        Cart.updateTotal();

        Cart.displayDelivery();
        document.getElementById("time").addEventListener("change", Cart.displayDelivery);

        document.getElementById("checkoutBtn").addEventListener("click", Cart.placeOrder);
    }
});













