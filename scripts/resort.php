<?php
add_action( 'wp_footer', function () {
	if(get_the_ID() == 2316 || get_the_ID() == 14756){?>
		<script>
            let fixedElm = document.getElementById("scrollLock");
            let lastElm = document.getElementById("lastElement");
            let steps = [];
            document.querySelectorAll(".steps").forEach((element) => {
                let step = {
                    element:element,
                    dist:window.pageYOffset + element.getBoundingClientRect().top
                }
                steps.push(step);
            });
            window.addEventListener("scroll", function(event){
                let currentStep = 0;
                steps.forEach((step, index) => {
                    if(step.dist-200 <= window.scrollY){
                        currentStep = step;
                    }
                });
                fixedElm.style.backgroundImage = window.getComputedStyle(currentStep.element).getPropertyValue("background-image");
                fixedElm.querySelector(".elementor-widget-heading h2").textContent = currentStep.element.querySelector(".elementor-widget-heading h2").textContent;
                fixedElm.querySelector(".elementor-widget-text-editor p").textContent = currentStep.element.querySelector(".elementor-widget-text-editor p").textContent;
                fixedElm.querySelector(".elementor-widget-button a").href = currentStep.element.querySelector(".elementor-widget-button a").href;
                fixedElm.querySelector(".elementor-widget-button a>span .elementor-button-text").textContent = currentStep.element.querySelector(".elementor-widget-button a>span .elementor-button-text").textContent;

                if(window.scrollY >= steps[steps.length-1].dist){
                    fixedElm.style.display = "none";
                }
                else{
                    fixedElm.style.display = "flex";
                }

                window.addEventListener("scroll", (event) => {
                    fixedElm.style.height = window.innerHeight;
                    fixedElm.style.maxHeight = window.innerHeight;
                });
            });

            let currentStep = 0;
            steps.forEach((step, index) => {
                if(step.dist-200 <= window.scrollY){
                    currentStep = step;
                }
            });
            fixedElm.style.backgroundImage = window.getComputedStyle(currentStep.element).getPropertyValue("background-image");
            fixedElm.querySelector(".elementor-widget-heading h2").textContent = currentStep.element.querySelector(".elementor-widget-heading h2").textContent;
            fixedElm.querySelector(".elementor-widget-text-editor p").textContent = currentStep.element.querySelector(".elementor-widget-text-editor p").textContent;
            fixedElm.querySelector(".elementor-widget-button a").href = currentStep.element.querySelector(".elementor-widget-button a").href;
            fixedElm.querySelector(".elementor-widget-button a>span .elementor-button-text").textContent = currentStep.element.querySelector(".elementor-widget-button a>span .elementor-button-text").textContent;

            if(window.scrollY >= steps[steps.length-1].dist){
                fixedElm.style.display = "none";
            }
            else{
                fixedElm.style.display = "flex";
            }

            fixedElm.style.height = window.innerHeight;
            fixedElm.style.maxHeight = window.innerHeight;
		</script>
	<?php }} );