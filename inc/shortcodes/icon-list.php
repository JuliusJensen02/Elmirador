<?php
add_shortcode('apartmentIconList', 'apartmentIconList');
function apartmentIconList(): string {
	ob_start();
	?>
	<div class="icon-list">
		<div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Wifi-orange.svg") ?>
			<p><?php _e("Wifi", "Hefa_theme") ?></p>
		</div>
        <div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2024/08/washer.svg") ?>
            <p><?php _e("Vaskemaskine", "Hefa_theme") ?></p>
        </div>

		<div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Aircondition-orange.svg") ?>
			<p><?php _e("Aircondition", "Hefa_theme") ?></p>
		</div>
        <div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2024/08/soap-alt.svg") ?>
            <p><?php _e("Opvaskemaskine", "Hefa_theme") ?></p>
        </div>

		<div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Gulvvarme-orange.svg") ?>
			<p><?php _e("Gulvvarme", "Hefa_theme") ?></p>
		</div>
        <div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Smart-home-mobilstyring-orange.svg") ?>
            <p><?php _e("Smart home mobilstyring", "Hefa_theme") ?></p>
        </div>

        <div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2024/08/screen.svg") ?>
            <p><?php _e("TV", "Hefa_theme") ?></p>
        </div>
        <div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2024/08/terrace.svg") ?>
            <p><?php _e("Terrasser", "Hefa_theme") ?></p>
        </div>

        <div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Nordisk-kokken-orange.svg") ?>
            <p><?php _e("Nordisk køkken", "Hefa_theme") ?></p>
        </div>
        <div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Parkering-orange.svg") ?>
            <p><?php _e("Parkering", "Hefa_theme") ?></p>
        </div>

        <div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Rent-drikkevand-orange.svg") ?>
            <p><?php _e("Rent drikkevand", "Hefa_theme") ?></p>
        </div>
        <div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Middelhavsudsigt-orange.svg") ?>
            <p><?php _e("Middelhavsudsigt", "Hefa_theme") ?></p>
        </div>



		<?php
		$icons = get_post_meta(get_the_ID(), 'inkluderet_i_boligen_yderligere', true);
		foreach($icons as $icon) {
            if($icon["icon"] == "" || $icon["tekst"] == ""){
                continue;
            }
			?>
			<div class="icon">
				<?= file_get_contents(wp_get_original_image_path($icon["icon"])) ?>
				<p><?= $icon['tekst'] ?></p>
			</div>
			<?php
		}
        ?>
	</div>
	<div class="icon-list mobile">
		<div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Wifi-orange.svg") ?>
			<p><?php _e("Wifi", "Hefa_theme") ?></p>
		</div>
		<div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Aircondition-orange.svg") ?>
			<p><?php _e("Aircondition", "Hefa_theme") ?></p>
		</div>
		<div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Gulvvarme-orange.svg") ?>
			<p><?php _e("Gulvvarme", "Hefa_theme") ?></p>
		</div>
        <div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2024/08/screen.svg") ?>
            <p><?php _e("TV", "Hefa_theme") ?></p>
        </div>
        <div class="icon">
	        <?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Nordisk-kokken-orange.svg") ?>
            <p><?php _e("Nordisk køkken", "Hefa_theme") ?></p>
        </div>
		<div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Rent-drikkevand-orange.svg") ?>
			<p><?php _e("Rent drikkevand", "Hefa_theme") ?></p>
		</div>
        <div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2024/08/washer.svg") ?>
            <p><?php _e("Vaskemaskine", "Hefa_theme") ?></p>
        </div>
        <div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2024/08/soap-alt.svg") ?>
            <p><?php _e("Opvaskemaskine", "Hefa_theme") ?></p>
        </div>
		<div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Smart-home-mobilstyring-orange.svg") ?>
			<p><?php _e("Smart home mobilstyring", "Hefa_theme") ?></p>
		</div>
        <div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2024/08/terrace.svg") ?>
            <p><?php _e("Terrasser", "Hefa_theme") ?></p>
        </div>
		<div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Parkering-orange.svg") ?>
			<p><?php _e("Parkering", "Hefa_theme") ?></p>
		</div>
		<div class="icon">
			<?= file_get_contents(WP_CONTENT_DIR."/uploads/2022/07/Middelhavsudsigt-orange.svg") ?>
			<p><?php _e("Middelhavsudsigt", "Hefa_theme") ?></p>
		</div>
		<?php
		foreach($icons as $icon) {
			?>
			<div class="icon">
				<?= file_get_contents(wp_get_original_image_path($icon["icon"])) ?>
				<p><?= $icon['tekst'] ?></p>
			</div>
			<?php
		}
		?>
	</div>
	<?php
	return ob_get_clean();
}